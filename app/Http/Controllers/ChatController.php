<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use App\Events\MessageSent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified', 'active']);
    }

    public function index()
    {
        $conversations = Conversation::query()
            ->forUser(Auth::id())
            ->with(['userOne', 'userTwo', 'lastMessage'])
            ->orderByDesc('last_message_at')
            ->get();

        $users = User::query()
            ->where('id', '!=', Auth::id())
            ->where('is_active', true)
            ->orderBy('name')
            ->limit(20)
            ->get(['id', 'name', 'email']);

        return view('pages.chat.index', compact('conversations', 'users'));
    }

    public function show(Conversation $conversation)
    {
        // Security check
        abort_if(
            $conversation->user_one_id !== Auth::id() &&
            $conversation->user_two_id !== Auth::id(),
            403
        );

        $messages = $conversation->messages()->with('sender')->get();

        // Mark messages as read
        $conversation->messages()
            ->where('sender_id', '!=', Auth::id())
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        $otherUser = $conversation->getOtherUser(Auth::id());

        return view('pages.chat.show', compact('conversation', 'messages', 'otherUser'));
    }

    public function startConversation(User $user)
    {
        if ($user->id === Auth::id()) {
            return back()->with('error', "You can't chat with yourself.");
        }

        $ids = [Auth::id(), $user->id];
        sort($ids);

        $conversation = Conversation::query()
            ->where('user_one_id', $ids[0])
            ->where('user_two_id', $ids[1])
            ->first();

        if (!$conversation) {
            $conversation = Conversation::create([
                'user_one_id'     => $ids[0],
                'user_two_id'     => $ids[1],
                'last_message_at' => now(),
            ]);
        }

        return redirect()->route('chat.show', $conversation);
    }

    public function send(Request $request, Conversation $conversation)
    {
        abort_if(
            $conversation->user_one_id !== Auth::id() &&
            $conversation->user_two_id !== Auth::id(),
            403
        );

        $request->validate(['body' => 'required|string|max:1000']);

        $message = $conversation->messages()->create([
            'sender_id' => Auth::id(),
            'body'      => $request->body,
        ]);

        $conversation->update(['last_message_at' => now()]);
        $message->load('sender');

        // Broadcast event for real-time (Pusher)
        broadcast(new MessageSent($message))->toOthers();

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => [
                    'id'         => $message->id,
                    'body'       => $message->body,
                    'sender'     => $message->sender->name,
                    'created_at' => $message->created_at->format('H:i'),
                ],
            ]);
        }

        return back();
    }

    // Polling fallback — for when Pusher is not configured
    public function getMessages(Conversation $conversation, Request $request)
    {
        abort_if(
            $conversation->user_one_id !== Auth::id() &&
            $conversation->user_two_id !== Auth::id(),
            403
        );

        $since = $request->input('since', 0);

        $messages = $conversation->messages()
            ->with('sender')
            ->where('id', '>', $since)
            ->get()
            ->map(fn($m) => [
                'id'         => $m->id,
                'body'       => $m->body,
                'sender'     => $m->sender->name,
                'is_mine'    => $m->sender_id === Auth::id(),
                'created_at' => $m->created_at->format('H:i'),
            ]);

        return response()->json($messages);
    }

    public function support(Request $request)
    {
        $request->validate(['body' => 'required|string|max:1000']);

        // Find an admin to handle support
        $admin = User::role('admin')->first() ?: User::where('email', 'admin@marketplace.com')->first();
        
        if (!$admin) {
            return response()->json(['success' => false, 'message' => 'No support agents available.']);
        }

        $ids = [Auth::id(), $admin->id];
        sort($ids);

        $conversation = Conversation::firstOrCreate([
            'user_one_id' => $ids[0],
            'user_two_id' => $ids[1],
        ]);

        $message = $conversation->messages()->create([
            'sender_id' => Auth::id(),
            'body'      => $request->body,
        ]);

        $conversation->update(['last_message_at' => now()]);

        // Broadcast event for real-time
        broadcast(new MessageSent($message))->toOthers();

        return response()->json([
            'success' => true,
            'user_message' => [
                'body' => $message->body,
                'created_at' => $message->created_at->format('H:i'),
            ],
            'system_response' => [
                'body' => "Your message has been received. A support agent will be with you shortly.",
                'sender' => 'System',
                'created_at' => now()->format('H:i'),
            ]
        ]);
    }
}
