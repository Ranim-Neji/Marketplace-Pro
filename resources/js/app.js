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
                        div.className = 'p-4 hover:bg-slate-50 dark:hover:bg-slate-900 transition-colors cursor-pointer border-b border-slate-100 dark:border-slate-800 last:border-0';
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
    const observerOptions = {
        threshold: 0.1
    };

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
    window.dispatchEvent(new CustomEvent('toast', {
        detail: { type, message },
    }));
}

const notificationRuntime = {
    initialized: false,
    knownUnreadIds: new Set(),
};

function renderNotifications(notifications) {
    const list = document.getElementById('notificationList');
    if (!list) {
        return;
    }

    // Clear previous notifications before rendering.
    list.innerHTML = '';

    if (!Array.isArray(notifications) || notifications.length === 0) {
        list.innerHTML = `
            <div class="p-8 text-center">
                <p class="text-xs text-muted-foreground">No unread notifications</p>
            </div>
        `;
        return;
    }

    notifications.forEach((notification) => {
        const message = notification.message || notification?.data?.message || 'New notification';
        const createdAt = notification.created_at_human || notification.created_at || '';

        const item = document.createElement('div');
        item.className = 'p-4 hover:bg-muted/50 transition-colors';
        item.innerHTML = `
            <p class="text-sm text-foreground">${message}</p>
            <p class="text-[10px] text-muted-foreground mt-1 uppercase font-mono">${createdAt}</p>
        `;

        list.appendChild(item);
    });
}

function updateNotificationCounter(count) {
    const badge = document.getElementById('notificationBadge');
    const counter = document.getElementById('notificationCount');

    if (!badge || !counter) {
        return;
    }

    counter.textContent = String(count);

    if (count > 0) {
        badge.classList.remove('hidden');
    } else {
        badge.classList.add('hidden');
    }
}

async function fetchNotifications() {
    const notificationsEndpoint =
        document.querySelector('meta[name="notifications-fetch-url"]')?.getAttribute('content');

    if (!notificationsEndpoint) {
        return;
    }

    try {
        console.log('Notifications endpoint:', notificationsEndpoint);
        const response = await fetch(notificationsEndpoint, {
            method: 'GET',
            credentials: 'same-origin',
            headers: {
                Accept: 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
            },
        });

        if (!response.ok) {
            throw new Error(`Notification request failed with status ${response.status}`);
        }

        const data = await response.json();
        console.log('Notifications response:', data);

        const unreadCount = Number(data.count ?? 0);
        const notifications = Array.isArray(data.notifications) ? data.notifications : [];
        const latestUnreadIds = new Set(notifications.map((notification) => notification.id));

        if (!notificationRuntime.initialized && unreadCount > 0) {
            dispatchToast('info', `You have ${unreadCount} unread notification${unreadCount > 1 ? 's' : ''}.`);
        }

        if (notificationRuntime.initialized) {
            notifications
                .filter((notification) => !notificationRuntime.knownUnreadIds.has(notification.id))
                .slice(0, 2)
                .forEach((notification) => {
                    const message = notification.message || notification?.data?.message || 'New notification';
                    dispatchToast('info', message);
                });
        }

        notificationRuntime.initialized = true;
        notificationRuntime.knownUnreadIds = latestUnreadIds;

        updateNotificationCounter(unreadCount);
        renderNotifications(notifications);
    } catch (error) {
        console.error('Notifications polling error:', error);
        dispatchToast('error', 'Unable to load notifications.');
    }
}

function initNotificationDropdown() {
    const widget = document.getElementById('notificationWidget');
    const bell = document.getElementById('notificationBell');
    const dropdown = document.getElementById('notificationDropdown');

    if (!widget || !bell || !dropdown) {
        return;
    }

    bell.addEventListener('click', () => {
        dropdown.classList.toggle('hidden');
        if (!dropdown.classList.contains('hidden')) {
            fetchNotifications();
        }
    });

    document.addEventListener('click', (event) => {
        if (!widget.contains(event.target)) {
            dropdown.classList.add('hidden');
        }
    });
}

function startNotificationPolling() {
    const isAuthenticated =
        document.querySelector('meta[name="app-authenticated"]')?.getAttribute('content') === '1';
    const notificationsEndpoint =
        document.querySelector('meta[name="notifications-fetch-url"]')?.getAttribute('content');

    if (!isAuthenticated || !notificationsEndpoint) {
        return;
    }

    fetchNotifications();

    // Poll every 4 seconds for near real-time updates.
    setInterval(fetchNotifications, 4000);
}

initNotificationDropdown();
startNotificationPolling();
