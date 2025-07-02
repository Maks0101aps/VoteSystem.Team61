# VoteSystem.Team61

A voting system application built with Laravel and Inertia.js.

## Installation / Встановлення

### All Platforms / Всі платформи

Clone the repo locally:

```sh
git clone https://github.com/Maks0101aps/VoteSystem.Team61.git votesystem
cd votesystem
```

### Windows

1. Install PHP (8.1+ recommended) from [windows.php.net](https://windows.php.net/download/)
2. Install Composer from [getcomposer.org](https://getcomposer.org/)
3. Install Node.js from [nodejs.org](https://nodejs.org/)
4. Run in PowerShell:
```powershell
composer install
npm ci
copy .env.example .env
php artisan key:generate
# For SQLite:
New-Item -Type File database/database.sqlite
php artisan migrate
php artisan db:seed
php -S 127.0.0.1:8000 -t public
```

### Mac/Linux

Install PHP dependencies:

```sh
composer install
```

Install NPM dependencies:

```sh
npm ci
```

Build assets:

```sh
npm run dev
```

Setup configuration:

```sh
cp .env.example .env
```

Generate application key:

```sh
php artisan key:generate
```

Create database (SQLite example):

```sh
touch database/database.sqlite
```

Run database migrations:

```sh
php artisan migrate
```

Run database seeder:

```sh
php artisan db:seed
```

Run the dev server:

```sh
php artisan serve
```

Default admin credentials:
- **Username:** admin@votesystem.com
- **Password:** secret

Тестові облікові дані адміністратора:
- **Логін:** johndoe@example.com
- **Пароль:** secret

## Running Tests / Запуск тестів

```sh
php artisan test
```
