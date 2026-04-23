@extends('layouts.app')
@section('title', 'Notifications | MarketPlace Pro')

@section('content')
<div class="container-layout py-16">
    <div class="flex flex-col md:flex-row justify-between items-end gap-6 mb-12 border-b border-border dark:border-border pb-10">
        <div>
            <h1 class="text-3xl font-black text-foreground dark:text-white uppercase tracking-tighter italic">Notifications</h1>
            <div class="text-[10px] font-black text-muted-foreground uppercase tracking-widest mt-2">System & Order Notifications</div>
        </div>
        
        @if(auth()->user()->unreadNotifications->isNotEmpty())
            <form method="POST" action="{{ route('notifications.read-all', absolute: false) }}" data-ajax-notification data-ajax-notification-action="mark-all">
                @csrf @method('PATCH')
                <button type="submit" class="text-[10px] font-black text-primary uppercase tracking-widest hover:underline italic">
                    Mark All as Read
                </button>
            </form>
        @endif
    </div>

    @if($notifications->isEmpty())
        <div class="py-32 flex flex-col items-center justify-center bg-white dark:bg-dark rounded-[3rem] border border-border dark:border-border">
            <i class="fa-solid fa-bell-slash text-4xl text-border mb-8"></i>
            <h2 class="text-xl font-black dark:text-white uppercase tracking-tighter mb-4 italic">No Notifications</h2>
            <p class="text-xs text-muted-foreground uppercase font-bold tracking-widest">You have no new notifications at this time.</p>
        </div>
    @else
        <div class="space-y-4">
            @foreach($notifications as $notification)
                <div data-notification-item="{{ $notification->id }}" class="group p-8 rounded-[2rem] bg-white dark:bg-dark border border-border dark:border-border shadow-sm hover:border-primary/30 transition-all {{ $notification->unread() ? 'border-l-4 border-l-primary' : '' }}">
                    <div class="flex flex-col sm:flex-row justify-between items-start gap-6">
                        <div class="flex gap-6">
                            <div class="h-12 w-12 rounded-2xl bg-muted dark:bg-dark border border-border dark:border-border flex items-center justify-center text-primary shrink-0">
                                @switch($notification->data['type'] ?? '')
                                    @case('order_status')
                                        <i class="fa-solid fa-box-open"></i>
                                        @break
                                    @case('chat')
                                        <i class="fa-solid fa-comment-dots"></i>
                                        @break
                                    @default
                                        <i class="fa-solid fa-info"></i>
                                @endswitch
                            </div>
                            <div>
                                <h3 class="text-sm font-black text-foreground dark:text-white uppercase tracking-tighter italic mb-1">{{ $notification->data['title'] ?? 'System Notification' }}</h3>
                                <p class="text-xs text-muted-foreground dark:text-muted-foreground font-medium italic mb-4">{{ $notification->data['message'] ?? 'New notification received.' }}</p>
                                <div class="flex items-center gap-4">
                                    <span class="text-[8px] font-black text-muted-foreground uppercase tracking-widest italic">{{ $notification->created_at->diffForHumans() }}</span>
                                    @if(isset($notification->data['url']))
                                        <a href="{{ $notification->data['url'] }}" class="text-[8px] font-black text-primary uppercase tracking-widest hover:underline italic">View Details</a>
                                    @endif
                                </div>
                            </div>
                        </div>

                        @if($notification->unread())
                            <form method="POST" action="{{ route('notifications.read', ['id' => $notification->id], absolute: false) }}" data-ajax-notification data-ajax-notification-action="mark-read">
                                @csrf @method('PATCH')
                                <button type="submit" class="h-10 w-10 flex items-center justify-center rounded-xl bg-muted dark:bg-dark text-muted-foreground hover:text-primary transition-all">
                                    <i class="fa-solid fa-check"></i>
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-12">
            {{ $notifications->links() }}
        </div>
    @endif
</div>
@endsection
