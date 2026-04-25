<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use App\Events\MessageSent;
use Illuminate\Http\Request;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

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

    public function ask(Request $request)
    {
        $request->validate(['body' => 'required|string|max:1000']);

        $apiKey = trim((string) env('GEMINI_API_KEY', ''));
        if ($apiKey === '') {
            return response()->json([
                'success' => false,
                'message' => 'AI service is not configured (missing GEMINI_API_KEY).',
            ], 500);
        }

        $url = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent';

        try {
            $response = Http::timeout(20)
                ->withoutVerifying()
                ->withQueryParameters(['key' => $apiKey])
                ->post($url, [
                    'contents' => [
                        [
                            'parts' => [
                                ['text' => $request->body],
                            ],
                        ],
                    ],
                ]);

            if ($response->successful()) {
                $data = $response->json();
                $aiResponse = $data['candidates'][0]['content']['parts'][0]['text'] ?? 'Sorry, I could not generate a response.';
                
                return response()->json([
                    'success' => true,
                    'ai_response' => [
                        'body' => $aiResponse,
                        'sender' => 'AI Assistant',
                        'created_at' => now()->format('H:i'),
                    ]
                ]);
            } else {
                Log::warning('Gemini API returned non-success response.', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);

                return response()->json([
                    'success' => false,
                    'message' => config('app.debug')
                        ? ($response->json('error.message') ?? ('AI service returned an error (HTTP ' . $response->status() . ').'))
                        : 'Failed to connect to the AI service. Please try again later.',
                ], 502);
            }
        } catch (ConnectionException $e) {
            Log::warning('Gemini API connection failed.', [
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => config('app.debug')
                    ? ('Connection error: ' . $e->getMessage())
                    : 'An error occurred while connecting to the AI service.',
            ], 502);
        } catch (\Throwable $e) {
            Log::error('Gemini API request failed unexpectedly.', [
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => config('app.debug')
                    ? ('Unexpected error: ' . $e->getMessage())
                    : 'An error occurred while connecting to the AI service.',
            ], 500);
        }
    }

    public function unreadCount()
    {
        $count = Message::whereHas('conversation', function ($query) {
            $query->forUser(Auth::id());
        })
        ->where('sender_id', '!=', Auth::id())
        ->whereNull('read_at')
        ->count();

        return response()->json(['count' => $count]);
    }
}
