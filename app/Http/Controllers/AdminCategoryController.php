<?php

namespace App\Http\Controllers;

use App\Support\CategoryCatalog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

/**
 * Admin management of storefront categories/subcategories and
 * vendor-suggested ("Other") category approval.
 */
class AdminCategoryController extends Controller
{
    private function guard()
    {
        if (!Session::get('admin_logged_in')) {
            abort(response()->json(['success' => false, 'message' => 'Not authorized'], 401));
        }
    }

    private function guardPage()
    {
        if (!Session::get('admin_logged_in')) {
            return redirect('/admin/login');
        }
        return null;
    }

    public function index()
    {
        if ($r = $this->guardPage()) return $r;

        $categories = DB::table('site_categories')
            ->orderBy('sort_order')->orderBy('name')
            ->get();

        $subs = DB::table('site_subcategories')
            ->orderBy('sort_order')->orderBy('name')
            ->get()
            ->groupBy('category_id');

        // live product counts per category name (string match — products store names)
        $counts = DB::table('vendor_products')
            ->select('category', DB::raw('COUNT(*) as c'))
            ->groupBy('category')
            ->pluck('c', 'category');

        $suggestions = DB::table('category_suggestions as cs')
            ->leftJoin('users as u', 'cs.user_id', '=', 'u.user_id')
            ->select('cs.*', 'u.full_name as vendor_name')
            ->orderByDesc('cs.created_at')
            ->get();

        return view('Admin.category_manage', compact('categories', 'subs', 'counts', 'suggestions'));
    }

    public function store(Request $request)
    {
        $this->guard();
        $request->validate(['name' => 'required|string|max:120']);

        $name = trim($request->input('name'));
        if (DB::table('site_categories')->where('name', $name)->exists()) {
            return response()->json(['success' => false, 'message' => 'Category already exists.'], 422);
        }

        $id = DB::table('site_categories')->insertGetId([
            'name' => $name,
            'emoji' => trim((string) $request->input('emoji')) ?: null,
            'status' => 'active',
            'show_on_home' => $request->boolean('show_on_home'),
            'show_on_cosmetics' => $request->boolean('show_on_cosmetics'),
            'sort_order' => (int) (DB::table('site_categories')->max('sort_order') + 1),
            'source' => 'admin',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        CategoryCatalog::clearCache();
        return response()->json(['success' => true, 'id' => $id, 'message' => 'Category added.']);
    }

    public function update(Request $request, $id)
    {
        $this->guard();

        $cat = DB::table('site_categories')->where('id', $id)->first();
        if (!$cat) return response()->json(['success' => false, 'message' => 'Not found.'], 404);

        $data = [];
        if ($request->filled('name')) {
            $name = trim($request->input('name'));
            $clash = DB::table('site_categories')->where('name', $name)->where('id', '!=', $id)->exists();
            if ($clash) return response()->json(['success' => false, 'message' => 'Another category already has this name.'], 422);
            $data['name'] = $name;
        }
        if ($request->has('emoji')) $data['emoji'] = trim((string) $request->input('emoji')) ?: null;
        if ($request->has('status')) $data['status'] = $request->input('status') === 'inactive' ? 'inactive' : 'active';
        if ($request->has('show_on_home')) $data['show_on_home'] = $request->boolean('show_on_home');
        if ($request->has('show_on_cosmetics')) $data['show_on_cosmetics'] = $request->boolean('show_on_cosmetics');
        if ($request->has('sort_order')) $data['sort_order'] = (int) $request->input('sort_order');

        if ($data) {
            $data['updated_at'] = now();
            DB::table('site_categories')->where('id', $id)->update($data);

            // keep existing products' category strings in sync on rename
            if (isset($data['name']) && $data['name'] !== $cat->name) {
                DB::table('vendor_products')->where('category', $cat->name)->update(['category' => $data['name']]);
            }
        }

        CategoryCatalog::clearCache();
        return response()->json(['success' => true, 'message' => 'Category updated.']);
    }

    public function destroy($id)
    {
        $this->guard();

        $cat = DB::table('site_categories')->where('id', $id)->first();
        if (!$cat) return response()->json(['success' => false, 'message' => 'Not found.'], 404);

        // products keep their category string; they just stop being listed under a managed category
        DB::table('site_subcategories')->where('category_id', $id)->delete();
        DB::table('site_categories')->where('id', $id)->delete();

        CategoryCatalog::clearCache();
        return response()->json(['success' => true, 'message' => 'Category removed.']);
    }

    public function storeSub(Request $request, $id)
    {
        $this->guard();
        $request->validate(['name' => 'required|string|max:120']);

        if (!DB::table('site_categories')->where('id', $id)->exists()) {
            return response()->json(['success' => false, 'message' => 'Category not found.'], 404);
        }

        $name = trim($request->input('name'));
        $exists = DB::table('site_subcategories')->where('category_id', $id)->where('name', $name)->exists();
        if ($exists) return response()->json(['success' => false, 'message' => 'Subcategory already exists.'], 422);

        $subId = DB::table('site_subcategories')->insertGetId([
            'category_id' => $id,
            'name' => $name,
            'sort_order' => (int) (DB::table('site_subcategories')->where('category_id', $id)->max('sort_order') + 1),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        CategoryCatalog::clearCache();
        return response()->json(['success' => true, 'id' => $subId, 'message' => 'Subcategory added.']);
    }

    public function destroySub($subId)
    {
        $this->guard();
        DB::table('site_subcategories')->where('id', $subId)->delete();
        CategoryCatalog::clearCache();
        return response()->json(['success' => true, 'message' => 'Subcategory removed.']);
    }

    /** Approve a vendor suggestion: create the category (+subcategory) and mark it added. */
    public function approveSuggestion($id)
    {
        $this->guard();

        $s = DB::table('category_suggestions')->where('id', $id)->first();
        if (!$s) return response()->json(['success' => false, 'message' => 'Not found.'], 404);

        $cat = DB::table('site_categories')->where('name', $s->category_name)->first();
        if (!$cat) {
            $catId = DB::table('site_categories')->insertGetId([
                'name' => $s->category_name,
                'emoji' => '🆕',
                'status' => 'active',
                'show_on_home' => false,
                'show_on_cosmetics' => false,
                'sort_order' => (int) (DB::table('site_categories')->max('sort_order') + 1),
                'source' => 'vendor',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } else {
            $catId = $cat->id;
            if ($cat->status !== 'active') {
                DB::table('site_categories')->where('id', $catId)->update(['status' => 'active', 'updated_at' => now()]);
            }
        }

        if ($s->subcategory_name) {
            $subExists = DB::table('site_subcategories')->where('category_id', $catId)->where('name', $s->subcategory_name)->exists();
            if (!$subExists) {
                DB::table('site_subcategories')->insert([
                    'category_id' => $catId,
                    'name' => $s->subcategory_name,
                    'sort_order' => (int) (DB::table('site_subcategories')->where('category_id', $catId)->max('sort_order') + 1),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        DB::table('category_suggestions')->where('id', $id)->update(['status' => 'added', 'updated_at' => now()]);

        CategoryCatalog::clearCache();
        return response()->json(['success' => true, 'message' => 'Suggestion approved — category is now live.']);
    }

    public function rejectSuggestion($id)
    {
        $this->guard();
        DB::table('category_suggestions')->where('id', $id)->update(['status' => 'rejected', 'updated_at' => now()]);
        return response()->json(['success' => true, 'message' => 'Suggestion rejected.']);
    }
}
