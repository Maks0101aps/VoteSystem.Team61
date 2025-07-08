# VoteSystem.Team61 🗳️

![Static Badge](https://img.shields.io/badge/Laravel-10-red)
![Static Badge](https://img.shields.io/badge/PHP-8.1-blue)
![Static Badge](https://img.shields.io/badge/Vue.js-3-green)
![Static Badge](https://img.shields.io/badge/Inertia.js-1.0-purple)
![Static Badge](https://img.shields.io/badge/Tests-126%20passed-success)

A complete voting and petition system for schools and educational institutions, built with Laravel and Vue.js.

Повна система голосування та петицій для шкіл та навчальних закладів, побудована з використанням Laravel та Vue.js.

## 🌟 Features / Особливості

**English:**
- Authentication with email verification and two-factor authentication
- User roles (director, teacher, parent, student)
- Create and manage petitions with signature collection
- Create and manage votings with different visibility settings
- Comment system for petitions and votings
- Dashboard with statistics and reports
- Responsive design for desktop and mobile

**Українська:**
- Аутентифікація з верифікацією електронної пошти та двофакторною аутентифікацією
- Ролі користувачів (директор, вчитель, батько, учень)
- Створення та управління петиціями зі збором підписів
- Створення та управління голосуваннями з різними налаштуваннями видимості
- Система коментарів для петицій та голосувань
- Інформаційна панель зі статистикою та звітами
- Адаптивний дизайн для комп'ютерів та мобільних пристроїв

## 🚀 Technologies / Технології

**English:**
- **Backend:** Laravel 10, PHP 8.1+
- **Frontend:** Vue.js 3, Inertia.js, Tailwind CSS
- **Database:** MySQL/SQLite
- **Testing:** PHPUnit
- **Authentication:** Laravel Sanctum
- **Email:** Mailtrap (for testing)
- **Localization:** Laravel localization with Ukrainian and English support

**Українська:**
- **Бекенд:** Laravel 10, PHP 8.1+
- **Фронтенд:** Vue.js 3, Inertia.js, Tailwind CSS
- **База даних:** MySQL/SQLite
- **Тестування:** PHPUnit
- **Аутентифікація:** Laravel Sanctum
- **Електронна пошта:** Mailtrap (для тестування)
- **Локалізація:** Laravel локалізація з підтримкою української та англійської мов

## 📋 Project Structure / Структура проекту

**English:**
```
votesystem/
├── app/                   # PHP application code
│   ├── Console/           # Console commands
│   ├── Http/              # HTTP layer (controllers, middleware, requests)
│   ├── Mail/              # Mail templates
│   ├── Models/            # Eloquent models
│   └── Providers/         # Service providers
├── bootstrap/             # Framework bootstrap files
├── config/                # Configuration files
├── database/              # Database migrations, seeders, factories
├── lang/                  # Localization files (UK, EN)
├── public/                # Publicly accessible files
├── resources/             # Views and frontend assets
│   ├── css/               # CSS files
│   ├── js/                # JavaScript files with Vue components
│   └── views/             # Blade templates
├── routes/                # Application routes
├── storage/               # Application storage
└── tests/                 # Automated tests
    ├── Feature/           # Feature tests
    └── Unit/              # Unit tests
```

**Українська:**
```
votesystem/
├── app/                   # PHP код додатку
│   ├── Console/           # Консольні команди
│   ├── Http/              # HTTP шар (контролери, посередники, запити)
│   ├── Mail/              # Шаблони листів
│   ├── Models/            # Eloquent моделі
│   └── Providers/         # Сервіс-провайдери
├── bootstrap/             # Файли ініціалізації фреймворку
├── config/                # Конфігураційні файли
├── database/              # Міграції, сідери, фабрики бази даних
├── lang/                  # Файли локалізації (UK, EN)
├── public/                # Публічні файли
├── resources/             # Шаблони та фронтенд-ресурси
│   ├── css/               # CSS файли
│   ├── js/                # JavaScript файли з Vue компонентами
│   └── views/             # Blade шаблони
├── routes/                # Маршрути додатку
├── storage/               # Сховище додатку
└── tests/                 # Автоматизовані тести
    ├── Feature/           # Функціональні тести
    └── Unit/              # Модульні тести
```

## 💻 Installation / Встановлення

### All Platforms / Всі платформи

**English:**
Clone the repo locally:

**Українська:**
Клонуйте репозиторій локально:

```sh
git clone https://github.com/Maks0101aps/VoteSystem.Team61.git votesystem
cd votesystem
```

### Windows

**English:**
1. Install PHP (8.1+ recommended) from [windows.php.net](https://windows.php.net/download/)
2. Install Composer from [getcomposer.org](https://getcomposer.org/)
3. Install Node.js from [nodejs.org](https://nodejs.org/)
4. Run in PowerShell:

**Українська:**
1. Встановіть PHP (рекомендовано 8.1+) з [windows.php.net](https://windows.php.net/download/)
2. Встановіть Composer з [getcomposer.org](https://getcomposer.org/)
3. Встановіть Node.js з [nodejs.org](https://nodejs.org/)
4. Виконайте у PowerShell:

```powershell
# Install dependencies / Встановіть залежності
composer install
npm ci

# Setup environment / Налаштуйте середовище
copy .env.example .env
php artisan key:generate

# For SQLite / Для SQLite:
New-Item -Type File database/database.sqlite
php artisan migrate
php artisan db:seed

# Start the server / Запустіть сервер
php -S 127.0.0.1:8000 -t public

# In another terminal, start Vite / В іншому терміналі запустіть Vite
npm run dev
```

### Mac/Linux

**English:**
1. Install PHP 8.1+ and required extensions
2. Install Composer and Node.js
3. Run the following commands:

**Українська:**
1. Встановіть PHP 8.1+ та необхідні розширення
2. Встановіть Composer та Node.js
3. Виконайте наступні команди:

```sh
# Install dependencies / Встановіть залежності
composer install
npm ci

# Setup environment / Налаштуйте середовище
cp .env.example .env
php artisan key:generate

# Create database (SQLite example) / Створіть базу даних (приклад для SQLite)
touch database/database.sqlite
php artisan migrate
php artisan db:seed

# Start the server / Запустіть сервер
php artisan serve

# In another terminal, start Vite / В іншому терміналі запустіть Vite
npm run dev
```

## ▶️ Running the Application / Запуск додатку

**English:**
1. Start the development server:

**Українська:**
1. Запустіть сервер розробки:

### Windows
```sh
# Start PHP server / Запустіть PHP сервер
php -S 127.0.0.1:8000 -t public

# In another terminal, start Vite / В іншому терміналі запустіть Vite
npm run dev
```

### Mac/Linux
```sh
# Start Laravel server / Запустіть сервер Laravel
php artisan serve

# In another terminal, start Vite / В іншому терміналі запустіть Vite
npm run dev
```

2. Visit [http://localhost:8000](http://localhost:8000) in your browser.

## 📧 Setting up Mailtrap / Налаштування Mailtrap

**English:**
The application uses Mailtrap for email testing in development environment:

1. Register for a free account at [Mailtrap.io](https://mailtrap.io/)
2. Create a new inbox
3. Get SMTP credentials
4. Update your `.env` file with Mailtrap settings:

**Українська:**
Додаток використовує Mailtrap для тестування електронної пошти в середовищі розробки:

1. Зареєструйтеся для безкоштовного акаунту на [Mailtrap.io](https://mailtrap.io/)
2. Створіть нову скриньку
3. Отримайте SMTP-дані
4. Оновіть ваш файл `.env` з налаштуваннями Mailtrap:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_mailtrap_username
MAIL_PASSWORD=your_mailtrap_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@votesystem.example"
MAIL_FROM_NAME="${APP_NAME}"
```

## 🧪 Testing / Тестування

**English:**
The project includes comprehensive test coverage with 126 passing tests and 1 skipped test. These tests cover:

- Authentication and authorization (login, registration, two-factor auth)
- User roles and permissions
- Petition creation, signing, and approval workflow
- Voting creation and participation
- Comment system functionality
- Director actions for petition management

Run the tests with:

**Українська:**
Проект включає комплексне покриття тестами з 126 успішними тестами та 1 пропущеним. Ці тести охоплюють:

- Аутентифікацію та авторизацію (вхід, реєстрація, двофакторна аутентифікація)
- Ролі та дозволи користувачів
- Робочий процес створення, підписання та затвердження петицій
- Створення голосувань та участь у них
- Функціональність системи коментарів
- Дії директора для управління петиціями

Запустіть тести за допомогою:

```sh
php artisan test
```

## 👥 Test Accounts / Тестові акаунти

**English:**
The system includes these test accounts (password for all is `secret`):

**Українська:**
Система включає наступні тестові акаунти (пароль для всіх - `secret`):

| Email | Role / Роль | Name / Ім'я |
|-------|-------------|-------------|
| johndoe@example.com | director / директор | John Doe |
| teacher@example.com | teacher / вчитель | Jane Smith |
| parent@example.com | parent / батько | Peter Jones |
| student@example.com | student / учень | Sam Wilson |

## 🌐 Localization / Локалізація

**English:**
The application supports both English and Ukrainian languages. The language can be changed using the language switcher in the UI.

**Українська:**
Додаток підтримує англійську та українську мови. Мову можна змінити за допомогою перемикача мови в інтерфейсі.

## 📄 License / Ліцензія

**English:**
This project is licensed under the MIT License - see the LICENSE.md file for details.

**Українська:**
Цей проект ліцензований під ліцензією MIT - дивіться файл LICENSE.md для деталей.
