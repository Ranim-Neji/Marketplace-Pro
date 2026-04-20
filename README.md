# MarketPlace Pro (Laravel E-Commerce)

Production-grade Laravel 11 e-commerce platform with MVC architecture, Eloquent ORM, Blade UI, REST API, role-based access control, recommendations, multi-vendor support, and real-time chat.

## Step 1: WAMP + Laravel Setup (Windows)

1. Install and start WAMP services (Apache + MySQL).
2. Use PHP 8.2+ (project targets PHP `^8.2`).
3. From project root (`ecommerce`):

```powershell
composer install
npm.cmd install
```

4. Create `.env` (or copy `.env.example`) and set database credentials for WAMP MySQL:

```env
APP_NAME="MarketPlace Pro"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://127.0.0.1:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ecommerce_db
DB_USERNAME=root
DB_PASSWORD=

BROADCAST_CONNECTION=log
QUEUE_CONNECTION=database
SESSION_DRIVER=file
CACHE_STORE=file
```

5. Create the database in MySQL (example):

```sql
CREATE DATABASE ecommerce_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

6. Generate app key and run migrations + seeders:

```powershell
php artisan key:generate
php artisan migrate:fresh --seed
php artisan storage:link
```

7. Run app:

```powershell
php artisan serve --host=127.0.0.1 --port=8000
npm.cmd run dev
```

## Step 2: Database Schema

Implemented tables with relations:
- Users + profile/vendor fields
- Roles/permissions (Spatie)
- Categories (hierarchical)
- Products + product_images + product_category pivot
- Carts + cart_items
- Orders + order_items
- Reviews
- Wishlists
- Conversations + messages
- User behaviors (recommendation engine)
- Notifications (database)

## Step 3: Models + Relationships

All domain models implemented with relationships and helper accessors/scopes:
- `User`, `Category`, `Product`, `ProductImage`, `Cart`, `CartItem`, `Order`, `OrderItem`, `Review`, `Wishlist`, `Conversation`, `Message`, `UserBehavior`.

## Step 4: Controllers

Implemented clean MVC controllers for:
- Catalog, Product CRUD, Cart, Orders, Reviews, Wishlist, Chat, Recommendations
- Auth/profile flow (Breeze + email verification + password reset)
- Admin modules: users, categories, products, orders, dashboard
- API controllers: products + orders
- Notifications controller (UI actions)

## Step 5: Routes (Web + API)

- Web routes in `routes/web.php`
- API routes in `routes/api.php` (`/api/v1/...`)
- Broadcast channel auth in `routes/channels.php`
- API + channels loaded in `bootstrap/app.php`

## Step 6: Middleware + RBAC

Custom middleware:
- `admin` (admin-only areas)
- `vendor` (vendor-only product management)
- `active` (blocks inactive accounts)
- `security` headers globally applied

Role package: `spatie/laravel-permission`.

## Step 7: Blade UI

Implemented full Blade UI for:
- Catalogue with advanced filtering/sorting
- Product details + reviews
- Vendor product CRUD pages
- Cart and checkout/order history pages
- Wishlist, notifications, recommendations
- Chat inbox + thread
- Admin dashboards and management pages

## Step 8: Core Features

- Authentication + email verification + password reset + profile update
- Product CRUD with image upload and multi-category assignment
- Public catalogue with live search, pagination, filters, sort
- Cart (add/update/remove/clear)
- Order placement + history + cancellation + status display
- Ratings/reviews workflow

## Step 9: Advanced Features

- Wishlist
- Database + mail notifications
- REST API (`/api/v1/products`, `/api/v1/orders`)
- AJAX live search
- Real-time chat event broadcasting + polling fallback
- Recommendation engine (`user_behaviors` + category/collaborative logic)
- Multi-vendor setup (all registered users can sell)
- Role-based access for admin/vendor/user

## Step 10: Security

Implemented protections:
- SQL injection prevention via Eloquent/query builder + validation
- XSS mitigation via escaped Blade output + CSP/security headers
- CSRF protection on forms
- Auth rate limiting (`LoginRequest`)
- Authorization via policies + middleware

## Step 11: Demo Accounts

Seeders create realistic test data for the marketplace, including roles, vendors, and products.

### Admin
Email: `admin@marketplace.com`  
Password: `password123`

### Vendors
Email: `vendor1@marketplace.com` → `vendor10@marketplace.com`  
Password: `password123`

### Buyers
Email: `buyer1@marketplace.com` → `buyer20@marketplace.com`  
Password: `password123`

> **Note:** All accounts use the same password (`password123`) for demo purposes. Run `php artisan migrate:fresh --seed` to reset the environment with this dataset.

## API Quick Start

1. Create token (Tinker example):

```powershell
php artisan tinker
$user = App\Models\User::first();
$user->createToken('api')->plainTextToken;
```

2. Use token in `Authorization: Bearer <token>` for protected endpoints.

Public endpoints:
- `GET /api/v1/products`
- `GET /api/v1/products/{slug}`

Protected endpoints:
- `GET /api/v1/orders`
- `POST /api/v1/orders`
- `GET /api/v1/orders/{id}`
- `PUT /api/v1/orders/{id}`
- `DELETE /api/v1/orders/{id}`

## Validation + Quality Checks

```powershell
php artisan migrate:fresh --seed
php artisan test
npm.cmd run build
```

