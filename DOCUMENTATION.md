# VoteSystem.Team61 - Installation Documentation

This document provides instructions for setting up and running the VoteSystem.Team61 application on both Linux and Windows operating systems.

## System Requirements

### Common Requirements
- PHP 8.2 or higher
- Composer
- Node.js (16.x or higher)
- npm
- Git
- Database (MySQL, PostgreSQL, or SQLite)
- PHP Extensions: exif, gd

### Windows-specific Requirements
- Windows 10 or higher
- [XAMPP](https://www.apachefriends.org/download.html), [Laragon](https://laragon.org/download/index.html), or [WampServer](https://www.wampserver.com/en/) (recommended for easy PHP setup)
- [Git for Windows](https://gitforwindows.org/)

### Linux-specific Requirements
- Ubuntu 20.04/22.04 or other modern Linux distribution
- Apache or Nginx web server

## Installation Instructions

### Step 1: Clone the Repository

**Windows:**
```powershell
git clone <repository-url> VoteSystem.Team61
cd VoteSystem.Team61
```

**Linux:**
```bash
git clone <repository-url> VoteSystem.Team61
cd VoteSystem.Team61
```

### Step 2: Install PHP Dependencies

**Windows:**
```powershell
composer install
```

**Linux:**
```bash
composer install
```

### Step 3: Install JavaScript Dependencies

**Windows:**
```powershell
npm ci
```

**Linux:**
```bash
npm ci
```

### Step 4: Environment Setup

**Windows:**
```powershell
copy .env.example .env
php artisan key:generate
```

**Linux:**
```bash
cp .env.example .env
php artisan key:generate
```

Edit the `.env` file to configure your database connection:

**SQLite:**
```
DB_CONNECTION=sqlite
DB_DATABASE=/absolute/path/to/database/database.sqlite
```

**MySQL/PostgreSQL:**
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=votesystem
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### Step 5: Database Setup

**SQLite (Both Windows & Linux):**

Create an SQLite database file:

**Windows:**
```powershell
New-Item -ItemType file -Path database/database.sqlite
```

**Linux:**
```bash
touch database/database.sqlite
```

**Run Migrations:**

**Windows/Linux:**
```
php artisan migrate
php artisan db:seed
```

### Step 6: Build Assets

**Windows/Linux:**
```
npm run dev
```

For production:
```
npm run build
```

### Step 7: Start the Development Server

**Windows/Linux:**
```
php artisan serve
```

The application should now be accessible at http://localhost:8000

## Running in Production

### Using Apache

#### Windows (with XAMPP/WampServer/Laragon)

1. Configure your virtual host to point to the `public` directory
2. Ensure mod_rewrite is enabled
3. Make sure the storage and bootstrap/cache directories are writable

#### Linux (Apache)

1. Configure a virtual host:
```apache
<VirtualHost *:80>
    ServerName votesystem.local
    DocumentRoot /path/to/VoteSystem.Team61/public
    
    <Directory "/path/to/VoteSystem.Team61/public">
        AllowOverride All
        Require all granted
    </Directory>
    
    ErrorLog ${APACHE_LOG_DIR}/votesystem-error.log
    CustomLog ${APACHE_LOG_DIR}/votesystem-access.log combined
</VirtualHost>
```

2. Enable the site and restart Apache:
```bash
sudo a2ensite votesystem.conf
sudo systemctl restart apache2
```

### Using Nginx

#### Linux (Nginx)

1. Configure a server block:
```nginx
server {
    listen 80;
    server_name votesystem.local;
    root /path/to/VoteSystem.Team61/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-XSS-Protection "1; mode=block";
    add_header X-Content-Type-Options "nosniff";

    index index.php;
    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

2. Enable the site and restart Nginx:
```bash
sudo ln -s /etc/nginx/sites-available/votesystem /etc/nginx/sites-enabled/
sudo systemctl restart nginx
```

## Common Issues and Troubleshooting

### Windows-specific Issues

1. **File permissions**: 
   - Make sure the PHP process has write access to the `storage` and `bootstrap/cache` directories
   - You may need to run commands as Administrator

2. **SSL issues with Composer**:
   - If you encounter SSL certificate errors when running composer commands, you can try:
   ```powershell
   composer config -g disable-tls true
   ```

3. **Path issues**:
   - Ensure PHP, Composer, and Node.js are in your system PATH

### Linux-specific Issues

1. **File permissions**:
   - Set proper permissions for the storage and bootstrap/cache directories:
   ```bash
   sudo chown -R $USER:www-data storage bootstrap/cache
   sudo chmod -R 775 storage bootstrap/cache
   ```

2. **Missing PHP extensions**:
   - Install required PHP extensions:
   ```bash
   sudo apt update
   sudo apt install php8.2-common php8.2-cli php8.2-gd php8.2-mysql php8.2-pgsql php8.2-sqlite3 php8.2-xml php8.2-zip php8.2-mbstring php8.2-curl
   ```

## User Access

After setup, you can access the system using the following default credentials:

- **Email:** johndoe@example.com
- **Password:** secret

## Additional Resources

- [Laravel Documentation](https://laravel.com/docs)
- [Inertia.js Documentation](https://inertiajs.com/)
- [Vue 3 Documentation](https://vuejs.org/guide/introduction.html) 