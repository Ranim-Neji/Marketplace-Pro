<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NotificationController extends Controller
{
    public function index(Request $request): View
    {
        $notifications = $request->user()
            ->notifications()
            ->paginate(15);

        return view('pages.notifications.index', compact('notifications'));
    }

    public function markAsRead(Request $request, string $id): RedirectResponse|JsonResponse
    {
        $notification = $request->user()->notifications()->findOrFail($id);
        $notification->markAsRead();

        $message = 'Notification marked as read.';

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'ok' => true,
                'message' => $message,
                'id' => $notification->id,
            ]);
        }

        return back()->with('success', $message);
    }

    public function markAllAsRead(Request $request): RedirectResponse|JsonResponse
    {
        $unreadCount = $request->user()->unreadNotifications()->count();
        $request->user()->unreadNotifications->markAsRead();

        $message = 'All notifications marked as read.';

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'ok' => true,
                'message' => $message,
                'updated' => $unreadCount,
            ]);
        }

        return back()->with('success', $message);
    }

    public function unread(Request $request): JsonResponse
    {
        $notifications = $request->user()
            ->unreadNotifications()
            ->latest()
            ->take(10)
            ->get();

        return response()->json([
            'count' => $notifications->count(),
            'notifications' => $notifications->map(fn($n) => [
                'id' => $n->id,
                'message' => $n->data['message'] ?? 'You have a new notification.',
                'created_at_human' => $n->created_at->diffForHumans(),
                'data' => $n->data,
                'created_at' => $n->created_at->toDateTimeString(),
            ]),
        ]);
    }
}
