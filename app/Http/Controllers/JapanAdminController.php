<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

/**
 * Minimal standalone admin panel for the `japan_products` table (list/add/edit/delete only).
 * Reuses the existing `admin_users` credentials but keeps its own session namespace
 * (japanadmin_*) so it never touches the main /admin login state.
 */
class JapanAdminController extends Controller
{
    private function guard()
    {
        if (!Session::get('japanadmin_logged_in')) {
            abort(response()->json(['success' => false, 'message' => 'Not authorized'], 401));
        }
    }

    private function guardPage()
    {
        if (!Session::get('japanadmin_logged_in')) {
            return redirect('/japanadmin/login');
        }
        return null;
    }

    public function loginForm()
    {
        if (Session::get('japanadmin_logged_in')) {
            return redirect('/japanadmin/products');
        }
        return view('JapanAdmin.login');
    }

    public function login(Request $request)
    {
        $admin = DB::table('admin_users')->where('username', $request->username)->first();
        if (!$admin) {
            return response()->json(['success' => false, 'message' => 'Invalid credentials'], 401);
        }

        $given = (string) $request->password;
        $stored = (string) $admin->password_hash;
        $isHashed = str_starts_with($stored, '$2y$') || str_starts_with($stored, '$argon2');
        $ok = $isHashed ? Hash::check($given, $stored) : hash_equals($stored, $given);

        if ($ok && !$isHashed) {
            DB::table('admin_users')->where('id', $admin->id)->update(['password_hash' => Hash::make($given)]);
        }

        if (!$ok) {
            return response()->json(['success' => false, 'message' => 'Invalid credentials'], 401);
        }

        $request->session()->regenerate();
        Session::put('japanadmin_logged_in', true);
        Session::put('japanadmin_id', $admin->id);
        Session::put('japanadmin_username', $admin->username);

        return response()->json(['success' => true, 'message' => 'Login successful']);
    }

    public function logout()
    {
        Session::forget('japanadmin_logged_in');
        Session::forget('japanadmin_id');
        Session::forget('japanadmin_username');
        return redirect('/japanadmin/login');
    }

    public function index()
    {
        if ($r = $this->guardPage()) return $r;

        $products = DB::table('japan_products')->orderBy('created_at', 'desc')->get();

        return view('JapanAdmin.products', compact('products'));
    }

    public function store(Request $request)
    {
        $this->guard();

        $data = $request->validate([
            'product_name'  => 'required|string|max:190',
            'brand'         => 'nullable|string|max:120',
            'model'         => 'nullable|string|max:120',
            'made_in'       => 'nullable|string|max:120',
            'conditionp'    => 'nullable|in:New,Used,Refurbished',
            'selling_price' => 'required|numeric|min:0',
            'mrp'           => 'nullable|numeric|min:0',
            'quantity'      => 'required|integer|min:0',
            'description'   => 'nullable|string',
            'image'         => 'required|image|max:5120',
        ]);

        $data['vendor_id'] = 0; // admin-added, no vendor owner
        $data['image'] = $this->storeImage($request);
        $data['status'] = 'approved';
        $data['created_at'] = now();
        $data['updated_at'] = now();

        $id = DB::table('japan_products')->insertGetId($data);

        return response()->json(['success' => true, 'id' => $id, 'message' => 'Product added.']);
    }

    public function update(Request $request, $id)
    {
        $this->guard();

        $product = DB::table('japan_products')->where('id', $id)->first();
        if (!$product) {
            return response()->json(['success' => false, 'message' => 'Not found.'], 404);
        }

        $data = $request->validate([
            'product_name'  => 'required|string|max:190',
            'brand'         => 'nullable|string|max:120',
            'model'         => 'nullable|string|max:120',
            'made_in'       => 'nullable|string|max:120',
            'conditionp'    => 'nullable|in:New,Used,Refurbished',
            'selling_price' => 'required|numeric|min:0',
            'mrp'           => 'nullable|numeric|min:0',
            'quantity'      => 'required|integer|min:0',
            'description'   => 'nullable|string',
            'image'         => 'nullable|image|max:5120',
        ]);

        if ($request->hasFile('image')) {
            $this->deleteImageFile($product->image);
            $data['image'] = $this->storeImage($request);
        }
        $data['updated_at'] = now();

        DB::table('japan_products')->where('id', $id)->update($data);

        return response()->json(['success' => true, 'message' => 'Product updated.']);
    }

    public function destroy($id)
    {
        $this->guard();

        $product = DB::table('japan_products')->where('id', $id)->first();
        if (!$product) {
            return response()->json(['success' => false, 'message' => 'Not found.'], 404);
        }

        $this->deleteImageFile($product->image);
        DB::table('japan_products')->where('id', $id)->delete();

        return response()->json(['success' => true, 'message' => 'Product deleted.']);
    }

    /** Stores an uploaded image under japan/products/images (mirrors VendorController's double-write pattern), returns the public URL. */
    private function storeImage(Request $request): string
    {
        $image = $request->file('image');
        $extension = $image->getClientOriginalExtension();
        $filename = uniqid() . '.' . $extension;

        $image->storeAs('japan/products/images', $filename, 'public');
        $destinationPath = public_path('storage/japan/products/images');
        $image->move($destinationPath, $filename);

        return asset('storage/japan/products/images/' . $filename);
    }

    /** Only deletes files this panel uploaded (local storage paths) — leaves external/placeholder URLs (e.g. placehold.co) alone. */
    private function deleteImageFile(?string $imageUrl): void
    {
        if (!$imageUrl || !str_contains($imageUrl, '/storage/japan/products/images/')) {
            return;
        }
        $filename = basename(parse_url($imageUrl, PHP_URL_PATH));

        \Illuminate\Support\Facades\Storage::disk('public')->delete('japan/products/images/' . $filename);
        $publicCopy = public_path('storage/japan/products/images/' . $filename);
        if (is_file($publicCopy)) {
            @unlink($publicCopy);
        }
    }
}
