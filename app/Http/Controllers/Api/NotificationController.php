<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class NotificationController extends Controller
{
    /**
     * Store a new notification from the React Native app.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'package_name' => 'required|string',
            'app_name' => 'required|string',
            'title' => 'nullable|string',
            'text' => 'nullable|string',
            'post_time' => 'required|integer',
        ]);

        $notification = $request->user()->notifications()->create([
            'package_name' => $validated['package_name'],
            'app_name' => $validated['app_name'],
            'title' => $validated['title'],
            'text' => $validated['text'],
            'post_time' => date('Y-m-d H:i:s', $validated['post_time'] / 1000),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Notification saved',
            'data' => $notification,
        ], 201);
    }

    /**
     * Get all notifications for the authenticated user.
     */
    public function index(Request $request): JsonResponse
    {
        $notifications = $request->user()
            ->notifications()
            ->orderBy('post_time', 'desc')
            ->paginate(100);

        return response()->json([
            'success' => true,
            'data' => $notifications->items(),
        ]);
    }
}
