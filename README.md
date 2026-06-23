# 👁️ Vision App

Vision App is a Laravel-based image scanning and product enrichment system. It allows authenticated users to upload images, processes them asynchronously using Google Vision, and extracts structured data such as OCR text, labels, and image properties.

The system is designed for scalability using queued background jobs, Redis, Sanctum authentication, and MySQL, with an admin dashboard powered by Backpack.

---

## 🚀 Features

- 🔐 Secure authentication using Laravel Sanctum
- 📤 Image upload via REST API
- ⚙️ Asynchronous OCR and image analysis using queues
- 🧠 Google Vision API integration (OCR, labels, colors)
- 📊 Scan lifecycle tracking (queued → processing → done/failed)
- 🗃️ Store scan results and derived product data
- 🧑‍💼 Admin panel powered by Backpack CRUD
- 📡 Redis queue system
- 📈 Queue monitoring via Laravel Horizon

---

## 🧱 Tech Stack

- Laravel 13
- PHP 8.3+
- MySQL 8
- Redis
- Laravel Sanctum
- Laravel Horizon
- Backpack Admin + Permission Manager
- Google Vision API
- Vite + Tailwind CSS

---

## 📁 Project Structure

### Core
- `app/Models` – Database models (ScanRequest, ScanResult, UserProduct)
- `app/Http/Controllers` – API + admin controllers
- `app/Jobs` – Queue jobs (OCR processing)
- `app/Services` – External services (Google Vision integration)

### Database
- `database/migrations` – Schema definitions
- `database/seeders` – Seed data

### Routes
- `routes/api.php` – API endpoints
- `routes/web.php` – Web + admin redirect

---

## 🔄 System Architecture

```text
Client
  ↓
API (Sanctum Auth)
  ↓
ScanRequest Created
  ↓
Redis Queue Job
  ↓
Google Vision API
  ↓
ScanResult Stored
  ↓
UserProduct (optional)
  ↓
API / Admin Dashboard
