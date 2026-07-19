<?php

namespace App\Http\Controllers;

use App\Services\MjGuide\ChatService;
use Illuminate\Http\Request;

class MjGuideController extends Controller
{
    public function message(Request $request, ChatService $chat)
    {
        if (! config('services.mj_guide.enabled', true)) {
            return response()->json([
                'reply' => ChatService::FALLBACK_REPLY,
                'provider' => 'none',
                'products' => [],
            ]);
        }

        $data = $request->validate([
            'message' => 'required|string|max:1000',
            'context' => 'nullable|array|max:10',
            'context.*.role' => 'required|string|in:user,assistant',
            'context.*.text' => 'required|string|max:3000',
        ]);

        $context = array_map(fn ($m) => [
            'role' => $m['role'],
            'text' => $m['text'],
        ], $data['context'] ?? []);

        return response()->json($chat->reply($context, $data['message']));
    }
}
