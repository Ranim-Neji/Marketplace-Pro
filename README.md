🛒 MarketPlace Pro

Laravel 11 Multi-Vendor E-Commerce Platform

MarketPlace Pro is a full-featured, production-ready e-commerce platform built with Laravel 11.
It follows clean MVC architecture, uses Eloquent ORM, and provides a modern shopping experience with advanced features like recommendations, real-time chat, and role-based access control.

🚀 Features
🧩 Core Features
User authentication (login, register, email verification, reset password)
Product catalog with filtering, sorting, and search
Shopping cart system
Order management (place, track, cancel)
Product reviews & ratings
⚡ Advanced Features
Multi-vendor system (any user can become a seller)
Wishlist system
Real-time chat (with fallback polling)
Notification system (database + mail)
Recommendation engine (based on user behavior)
REST API (/api/v1/...)
AJAX live search
🔐 Security
CSRF protection
XSS prevention (Blade escaping + headers)
SQL injection protection (Eloquent ORM)
Role-based authorization (Spatie)
Rate limiting (auth system)
🏗️ Tech Stack
Backend: Laravel 11 (PHP 8.2+)
Frontend: Blade + Tailwind CSS + Vite
Database: MySQL
Auth: Laravel Breeze
RBAC: Spatie Laravel Permission
Real-time: Broadcasting / Events
⚙️ Installation Guide
📌 Requirements
PHP 8.2+
Composer
Node.js + npm
MySQL
WAMP / XAMPP / Laravel Sail
🧑‍💻 Setup Instructions
1. Clone the project
git clone <your-repo-url>
cd Marketplace-Pro
2. Install PHP dependencies
composer install
3. Setup environment
copy .env.example .env

4. Generate application key
php artisan key:generate
5. Run migrations
php artisan migrate

6. Install frontend dependencies
npm install
7. Build assets
npm run build
8. Start the server
php artisan serve

App will run at:

http://127.0.0.1:8000
🗄️ Database Structure

Main entities:

Users (buyers + vendors + admins)
Roles & permissions
Categories (hierarchical)
Products + images
Cart & cart items
Orders & order items
Reviews
Wishlist
Conversations & messages
User behaviors (recommendation system)
🧠 Architecture
MVC pattern (Models, Views, Controllers)
Clean separation of concerns
RESTful API design
Middleware-based access control
🔑 Roles & Access
Admin
Full system control
Manage users, products, orders
Vendor
Manage own products
View orders
User (Buyer)
Browse, buy, review, wishlist
🔌 API Usage
Public Endpoints
GET /api/v1/products
GET /api/v1/products/{slug}
Protected Endpoints
GET    /api/v1/orders
POST   /api/v1/orders
GET    /api/v1/orders/{id}
PUT    /api/v1/orders/{id}
DELETE /api/v1/orders/{id}
Generate API Token
php artisan tinker
$user = App\Models\User::first();
$user->createToken('api')->plainTextToken;
👤 Demo Accounts
Admin
admin@marketplace.com
password123
Vendors
vendor1@marketplace.com → vendor10@marketplace.com
password123
Buyers
buyer1@marketplace.com → buyer20@marketplace.com
password123
✅ Quality Checks
php artisan test
npm run build
📌 Notes
Run php artisan storage:link if images are not displaying
Use migrate:fresh --seed to reset demo data
Ensure WAMP/XAMPP services are running
