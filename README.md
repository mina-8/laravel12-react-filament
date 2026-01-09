# 🚀 Laravel & Filament Guide

## 📑 Table of Contents
1. [Packages with Filament](#-packages-with-filament)  
2. [Storage Link](#-storage-link)  
3. [Rollback Specific Migration](#-rollback-specific-migration)  
4. [Upload File More Than 50MB](#-upload-file-more-than-50mb)  
5. [Add Column to Table Migration](#-add-column-to-table-migration)  
6. [Create Admin Authentication](#-create-admin-authentication)  
   - [Step 1: Create Admin Model](#step-1-create-admin-model)  
   - [Step 2: Edit configauthphp](#step-2-edit-configauthphp)  
   - [Step 3: Admin Model](#step-3-admin-model)  
   - [Step 4 (Optional): Admin Routes](#step-4-optional-admin-routes)  
7. [Trait for Handling Guards](#-trait-for-handling-guards)  
8. [Middleware for Lang & Route Group](#-middleware-for-lang--route-group)  
9. [Run on Mobile](#-run-on-mobile)  
10. [Install on Server](#-install-on-server)  
11. [Filament Notifications & Queue](#-filament-notifications--queue)  

---

## 📦 Packages with Filament
- `pixelpeter/filament-language-tabs`  
- `spatie/*`  
- `tomatophp/filament-language-switcher`  

---

## 📂 Storage Link
```bash
php artisan storage:link
# or
cd public
unlink storage
ln -s ../storage/app/public storage
```

---

## 🔄 Rollback Specific Migration
```bash
php artisan migrate:rollback --path=/database/migrations/2025_05_13_155620_create_about_us_table.php
```

---

## 📤 Upload File More Than 50MB

1. **Edit `AppServiceProvider` boot function**  
2. **Update `php.ini`**:
   ```ini
   upload_max_filesize = 50M
   post_max_size = 60M
   ```
3. **Config Livewire**:
   ```php
   Config::set('livewire.temporary_file_upload.rules', ['file', 'max:51200']);
   ```

---

## 🗄️ Add Column to Table Migration
```php
$table->foreignId('solve_brands_id')
      ->nullable()
      ->after('id') // 👈 Adds the column after 'id'
      ->constrained('solve_brands')
      ->cascadeOnDelete()
      ->cascadeOnUpdate();
```

---

## 👨‍💻 Create Admin Authentication

### Step 1: Create Admin Model
```bash
php artisan make:model Admin -m
```

### Step 2: Edit `config/auth.php`
```php
'guards' => [
    'web' => [
        'driver' => 'session',
        'provider' => 'users',
    ],
    'admin' => [
        'driver' => 'session',
        'provider' => 'admins',
    ],
],

'providers' => [
    'users' => [
        'driver' => 'eloquent',
        'model' => env('AUTH_MODEL', App\Models\User::class),
    ],
    'admins' => [
        'driver' => 'eloquent',
        'model' => App\Models\Admin::class,
    ],
],
```

### Step 3: Admin Model
```php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Admin extends Authenticatable
{
    use Notifiable;
    use \TomatoPHP\FilamentLanguageSwitcher\Traits\InteractsWithLanguages; // if using filament

    protected $guard = 'admin';

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];
}
```

### Step 4 (Optional): Admin Routes
```php
return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        admin: __DIR__ . '/../routes/admin.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    );
```

---

## 🛠️ Trait for Handling Guards
**`app/Traits/GuardUsers.php`**
```php
namespace App\Traits;

trait GuardUsers
{
    protected $lang;

    public function __construct()
    {
        $this->lang  = request()->segment(1);
    }

    private function isAgent()
    {
        return request()->is("{$this->lang}/agent/*");
    }

    public function Guardusers()
    {
        if ($this->isAgent()) {
            return "agent";
        }

        return "web";
    }
}
```

👉 Use in:
- `AuthenticatedSessionController` (create, store, destroy)  
- `RegisteredUserController`  

---

## 🌐 Middleware for Lang & Route Group

**Routes**
```php
Route::group(
    [
        'prefix' => '{lang?}',
        'where' => [
            // Exclude 'livewire' and 'admin' from being matched as language
            'lang' => '(?!livewire|admin)[a-zA-Z]{2}(-[a-zA-Z]{2})?'
        ],
        'middleware' => 'lang'
    ],
    function () {
        // all routes
    }
);
```

**Register Middleware in `app.php`**
```php
->withMiddleware(function (Middleware $middleware) {
    $middleware->web(append: [
        \App\Http\Middleware\Lang::class,
    ]);

    $middleware->alias([
        'lang' => \App\Http\Middleware\Lang::class,
    ]);
});
```

---

## 📱 Run on Mobile

**Vite Config**
```js
server:{
    host : '0.0.0.0',
    port : 5173,
    strictPort: true,
    cors : true,
    hmr : {
        host : '192.168.1.8',
    }
}
```

**Run Commands**
```bash
php artisan serve --host=0.0.0.0 --port=8000
npm run dev -- --host=0.0.0.0 --port=5173
```

---

## 🚀 Install on Server
```bash
composer2 install --no-dev --optimize-autoloader

APP_ENV=production
APP_DEBUG=false
LOG_LEVEL=debug
```
---
### run on mobile developent to check api

php artisan serve --host=0.0.0.0 --port=8000

---

## 🔔 Filament Notifications & Queue

- **Local Dev**
```env
QUEUE_CONNECTION=sync
```

- **On Server (Recommended)**
```env
QUEUE_CONNECTION=database
```

Run worker:
```bash
php artisan queue:work
```


ادخل لوحة Hostinger

روح Advanced → Cron Jobs

اعمل Cron Job جديد

خلي الجدولة:

* * * * *


خلي الأمر كالتالي:

/usr/bin/php /home/uXXXXXX/public_html/artisan queue:run --tries=3

الفرق بين queue:run و queue:work

queue:work = شغال طول الوقت (مش هينفع في Hostinger Shared)

queue:run = بيشتغل دورة واحدة ويمشي (ينفع مع Cron)