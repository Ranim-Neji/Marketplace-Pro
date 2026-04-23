import './bootstrap';
import Alpine from 'alpinejs';

window.Alpine = Alpine;
Alpine.start();

// Theme Engine
const themeToggle = document.getElementById('themeToggle');
if (themeToggle) {
    themeToggle.addEventListener('click', () => {
        if (document.documentElement.classList.contains('dark')) {
            document.documentElement.classList.remove('dark');
            localStorage.theme = 'light';
        } else {
            document.documentElement.classList.add('dark');
            localStorage.theme = 'dark';
        }
    });
}

// Live Search Engine
const searchInput = document.getElementById('liveSearch');
const searchResults = document.getElementById('searchResults');

if (searchInput && searchResults) {
    let timeout = null;
    searchInput.addEventListener('input', (e) => {
        clearTimeout(timeout);
        const query = e.target.value;
        
        if (query.length < 2) {
            searchResults.classList.add('hidden');
            return;
        }

        timeout = setTimeout(async () => {
            try {
                const response = await fetch(`/api/v1/products/search?q=${query}`);
                const data = await response.json();
                
                searchResults.innerHTML = '';
                if (data.length > 0) {
                    data.forEach(product => {
                        const div = document.createElement('div');
                        div.className = 'p-4 hover:bg-muted dark:hover:bg-dark transition-colors cursor-pointer border-b border-border dark:border-border last:border-0';
                        div.innerHTML = `
                            <a href="/products/${product.slug}" class="flex items-center gap-4">
                                <img src="${product.image_url}" class="h-10 w-10 rounded-lg object-cover">
                                <div>
                                    <div class="text-[10px] font-black dark:text-white uppercase tracking-tighter">${product.title}</div>
                                    <div class="text-[8px] font-bold text-indigo-600 font-mono mt-0.5">$${product.price}</div>
                                </div>
                            </a>
                        `;
                        searchResults.appendChild(div);
                    });
                    searchResults.classList.remove('hidden');
                } else {
                    searchResults.classList.add('hidden');
                }
            } catch (error) {
                console.error('Search failed:', error);
            }
        }, 300);
    });

    document.addEventListener('click', (e) => {
        if (!searchInput.contains(e.target) && !searchResults.contains(e.target)) {
            searchResults.classList.add('hidden');
        }
    });
}

// Scroll Animation Controller
if ('IntersectionObserver' in window) {
    const observerOptions = { threshold: 0.1 };
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate-fade-in');
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);
    document.querySelectorAll('.scroll-reveal').forEach(el => observer.observe(el));
}

function dispatchToast(type, message) {
    window.dispatchEvent(new CustomEvent('toast', { detail: { type, message } }));
}

const notificationRuntime = {
    initialized: false,
    knownUnreadIds: new Set(),
};

function renderNotifications(notifications) {
    const list = document.getElementById('notificationList');
    if (!list) return;

    list.innerHTML = '';

    if (!Array.isArray(notifications) || notifications.length === 0) {
        list.innerHTML = `
            <div class="p-12 text-center flex flex-col items-center gap-3 opacity-40">
                <i class="fa-solid fa-bell-slash text-2xl"></i>
                <p class="text-[10px] font-bold uppercase tracking-[0.2em]">Silence is golden</p>
            </div>
        `;
        return;
    }

    notifications.forEach((notification) => {
        const message = notification.message || notification?.data?.message || 'New notification';
        const createdAt = notification.created_at_human || notification.created_at || '';

        const item = document.createElement('div');
        item.className = 'p-5 hover:bg-primary/[0.03] transition-all cursor-pointer group relative overflow-hidden';
        item.innerHTML = `
            <div class="absolute left-0 top-0 bottom-0 w-1 bg-primary scale-y-0 group-hover:scale-y-100 transition-transform origin-top"></div>
            <p class="text-[11px] font-medium text-foreground leading-relaxed italic line-clamp-2">${message}</p>
            <p class="text-[8px] font-black text-muted-foreground mt-2 uppercase tracking-widest font-mono opacity-60 group-hover:opacity-100 transition-opacity">${createdAt}</p>
        `;

        list.appendChild(item);
    });
}

function animateBadge(badge) {
    badge.classList.remove('animate-pop');
    void badge.offsetWidth; // Trigger reflow
    badge.classList.add('animate-pop');
}

function updateNotificationCounter(count) {
    const badge = document.getElementById('notificationBadge');
    const counter = document.getElementById('notificationCount');
    if (!badge || !counter) return;

    const currentCount = parseInt(counter.textContent) || 0;
    counter.textContent = String(count);

    if (count > 0) {
        badge.classList.remove('hidden');
        if (count > currentCount) animateBadge(badge);
    } else {
        badge.classList.add('hidden');
    }
}

async function fetchNotifications() {
    const endpoint = document.querySelector('meta[name="notifications-fetch-url"]')?.getAttribute('content');
    if (!endpoint) return;

    try {
        const response = await fetch(endpoint, {
            method: 'GET',
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
        });
        if (!response.ok) return;

        const data = await response.json();
        const unreadCount = Number(data.count ?? 0);
        const notifications = Array.isArray(data.notifications) ? data.notifications : [];
        const latestUnreadIds = new Set(notifications.map(n => n.id));

        if (notificationRuntime.initialized) {
            notifications
                .filter(n => !notificationRuntime.knownUnreadIds.has(n.id))
                .forEach(n => dispatchToast('info', n.message || n?.data?.message || 'New notification'));
        }

        notificationRuntime.initialized = true;
        notificationRuntime.knownUnreadIds = latestUnreadIds;

        updateNotificationCounter(unreadCount);
        renderNotifications(notifications);
    } catch (error) {
        console.error('Notifications polling error:', error);
    }
}

// Global Chat Counter
async function fetchChatCount() {
    const isAuthenticated = document.querySelector('meta[name="app-authenticated"]')?.getAttribute('content') === '1';
    if (!isAuthenticated) return;

    try {
        const response = await fetch('/chat/unread-count', {
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
        });
        if (!response.ok) return;

        const data = await response.json();
        const badge = document.getElementById('unreadMessagesBadge');
        const counter = document.getElementById('unreadMessagesCount');
        
        if (badge && counter) {
            const currentCount = parseInt(counter.textContent) || 0;
            const newCount = Number(data.count || 0);
            counter.textContent = String(newCount);
            
            if (newCount > 0) {
                badge.classList.remove('hidden');
                if (newCount > currentCount) animateBadge(badge);
            } else {
                badge.classList.add('hidden');
            }
        }
    } catch (error) {
        console.error('Chat count polling error:', error);
    }
}

// Real-time Chat Notifications (Echo)
function initChatNotifications() {
    const isAuthenticated = document.querySelector('meta[name="app-authenticated"]')?.getAttribute('content') === '1';
    const userId = document.querySelector('meta[name="user-id"]')?.getAttribute('content');

    if (!isAuthenticated || !userId || !window.Echo) {
        console.warn('[Echo] Real-time chat skipped (Unauthenticated or Echo missing)');
        return;
    }

    console.log(`[Echo] Active: Subscribing to private channel: App.Models.User.${userId}`);

    window.Echo.private(`App.Models.User.${userId}`)
        .listen('.message.sent', (e) => {
            console.log('[Echo] Event Received: message.sent', e);
            
            // Show premium toast notification
            const shortMessage = e.body.length > 60 ? e.body.substring(0, 57) + '...' : e.body;
            dispatchToast('info', `Message from ${e.sender}: "${shortMessage}"`);
            
            // Instant refresh of counters
            fetchChatCount();
            
            // Trigger badge animation if we are on the messages page
            const globalBadge = document.getElementById('unreadMessagesBadge');
            if (globalBadge) animateBadge(globalBadge);
        })
        .error((error) => {
            console.error('[Echo] Subscription error:', error);
        });
}

window.markAllAsRead = async function() {
    try {
        const response = await fetch('/notifications/read-all', {
            method: 'PATCH',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        });
        if (response.ok) {
            fetchNotifications();
            dispatchToast('success', 'All notifications marked as read');
        }
    } catch (error) {
        console.error('Mark all as read failed:', error);
    }
};

function startPolling() {
    const isAuthenticated = document.querySelector('meta[name="app-authenticated"]')?.getAttribute('content') === '1';
    if (!isAuthenticated) return;

    fetchNotifications();
    fetchChatCount();
    initChatNotifications();

    setInterval(fetchNotifications, 5000);
    setInterval(fetchChatCount, 6000);
}

startPolling();
