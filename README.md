# 🛒 MarketPlace Pro
> **Laravel 11 Multi-Vendor E-Commerce Platform**

MarketPlace Pro is a premium, production-ready e-commerce platform built with **Laravel 11**. It features a modern SaaS-style architecture, advanced recommendation engines, real-time messaging, and a unified design system.

---

## 🚀 Key Features

### 🧩 Core Commerce
- **Advanced Auth:** Login, Registration, Social verification, and Password resets.
- **Dynamic Catalog:** High-performance filtering, sorting, and live search.
- **Inventory Management:** Full product lifecycle management for vendors.
- **Order Engine:** Seamless checkout, order tracking, and status management.

### ⚡ Premium Experience
- **Multi-Vendor:** Integrated vendor onboarding and dedicated dashboards.
- **Smart Search:** AJAX-powered fuzzy search with typo correction.
- **Real-Time Chat:** User-to-vendor messaging with fallback polling.
- **AI Recommendations:** Personalized product suggestions based on browsing behavior.
- **Notification Suite:** Real-time database alerts and automated email updates.

---

## 🏗️ Tech Stack

| Layer | Technology |
| :--- | :--- |
| **Backend** | Laravel 11 (PHP 8.2+) |
| **Frontend** | Blade + Tailwind CSS + Alpine.js |
| **Database** | MySQL 8.0+ |
| **Build Tool** | Vite |
| **Auth** | Laravel Breeze + Sanctum |
| **Design** | Custom Premium UI Kit (CSS Variables) |

---

## ⚙️ Installation Guide

### 📌 Prerequisites
- PHP 8.2 or higher
- Composer
- Node.js & npm
- MySQL

### 🧑‍💻 Setup Steps

1. **Clone & Enter**
   ```bash
   git clone <repository-url>
   cd Marketplace-Pro
   ```

2. **Backend Setup**
   ```bash
   composer install
   cp .env.example .env
   php artisan key:generate
   php artisan storage:link
   ```
   To enable the AI Assistant chat widget, set `GEMINI_API_KEY` in `.env` (avoid leading/trailing spaces).

3. **Database Configuration**
   Configure your database credentials in `.env`, then run:
   ```bash
   php artisan migrate:fresh --seed
   ```

4. **Frontend Setup**
   ```bash
   npm install
   npm run dev
   ```

5. **Launch**
   ```bash
   php artisan serve
   ```
   Visit: `http://127.0.0.1:8000`

---

## 🔑 Roles & Demo Access

| Role | Email | Password |
| :--- | :--- | :--- |
| **Admin** | `admin@marketplace.com` | `password123` |
| **Vendor** | `vendor1@marketplace.com` | `password123` |
| **Buyer** | `buyer1@marketplace.com` | `password123` |

---

## 🔌 API Reference

### Public
- `GET /api/v1/products` - List all products
- `GET /api/v1/products/{slug}` - Product details

### Protected
- `GET /api/v1/orders` - View order history
- `POST /api/v1/orders` - Place new order

---

## 🛡️ Quality & Security
- **RBAC:** Fine-grained permissions via Spatie.
- **Protection:** CSRF, XSS, and SQL Injection prevention out-of-the-box.
- **Testing:** Run `php artisan test` for the full test suite.

---
*© 2026 MarketPlace Pro Team. Built for excellence.*
