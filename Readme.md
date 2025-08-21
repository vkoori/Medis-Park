# 🚀 Medis park Application

This is a modular Laravel application designed for high performance, security, and flexibility. It leverages Docker for local development, uses a stateless token-based authentication system, and follows best practices for modular design and service separation.

## 📦 Features

- **🧱 Modular Architecture**: Built using *nWidart/laravel-modules* for a clean and maintainable module-based structure.
- **🔐 Stateless Authentication**: Powered by *Koorosh* for JWT-based auth with per-scope access and refresh tokens.
- **✅ Authorization**: Powered by *Spatie Laravel Permission* for role and permission management.
- **🧼 XSS Protection**: All HTML input sanitized using *mews/purifier*.
- **⚡ High Performance**: Enhanced with *Laravel Octane* for blazing-fast performance.
- **🧵 Worker Services**: Runs three separate services:
  - HTTP Worker
  - Queue Consumer
  - Scheduler
- **🗓️ Jalali Date Support**: Integrated with *morilog/jalali* to handle Persian (Jalali) calendar dates across the application.

## 🐳 Getting Started

**Start the Application**

Run following command and access to service at `http://localhost`

```
docker compose -f docker-compose.yaml -f docker-compose-local.yml --profile infra --profile gui up -d
```

Run following commands:

```bash
php artisan generate:jwt-keys RS256
php artisan migrate
php artisan db:seed
php artisan module:seed User Notification Reward
php artisan queue:work --queue=default,user,post,reward,crm,sms_product,sms_advertising 
```
