# Event Management Backend & Admin Dashboard

A Laravel-based event management system with a backend API and admin dashboard starter template. This project provides a solid foundation for building event management applications with user authentication, site analytics, and a modern admin interface.

## Features

- 🔐 **User Authentication** - Complete authentication system with Laravel Breeze
- 📊 **Admin Dashboard** - Pre-built admin panel for managing events and users
- 📈 **Site Analytics** - Track site views and visitor statistics
- 🔒 **API Authentication** - Sanctum-based API token authentication
- 🎨 **Modern UI** - Tailwind CSS for responsive design
- ⚡ **Vite Integration** - Fast frontend build tooling
- 🧪 **Testing Suite** - PHPUnit tests included

## Requirements

- PHP >= 8.1
- Composer
- Node.js >= 16.x
- npm or yarn
- MySQL/PostgreSQL/SQLite database
- Web server (Apache/Nginx)

## Installation

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd event-backend
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Install Node dependencies**
   ```bash
   npm install
   ```

4. **Environment setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Configure database**
   
   Edit your `.env` file with your database credentials:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=your_database_name
   DB_USERNAME=your_database_user
   DB_PASSWORD=your_database_password
   ```

6. **Run migrations**
   ```bash
   php artisan migrate
   ```

7. **Seed database (optional)**
   ```bash
   php artisan db:seed
   ```

8. **Create storage symlink**
   ```bash
   php artisan storage:link
   ```

9. **Build frontend assets**
   ```bash
   npm run build
   ```

## Development

### Start development servers

**Backend (Laravel)**
```bash
php artisan serve
```
The application will be available at `http://localhost:8000`

**Frontend (Vite)**
```bash
npm run dev
```

### Running tests

```bash
# Run all tests
php artisan test

# Run specific test suite
php artisan test --testsuite=Feature

# Run with coverage
php artisan test --coverage
```

### Code quality

```bash
# Run PHP CS Fixer (if configured)
composer run-script format

# Run PHPStan (if configured)
composer run-script analyse
```

## Project Structure

```
├── app/
│   ├── Console/          # Artisan commands
│   ├── Exceptions/       # Exception handlers
│   ├── Http/
│   │   ├── Controllers/  # Request controllers
│   │   ├── Middleware/   # HTTP middleware
│   │   └── Requests/     # Form requests
│   ├── Models/           # Eloquent models
│   └── Providers/        # Service providers
├── config/               # Configuration files
├── database/
│   ├── factories/        # Model factories
│   ├── migrations/       # Database migrations
│   └── seeders/          # Database seeders
├── public/               # Public assets
│   └── admin/           # Admin dashboard assets
├── resources/
│   ├── css/             # Stylesheets
│   ├── js/              # JavaScript files
│   └── views/           # Blade templates
├── routes/
│   ├── api.php          # API routes
│   ├── web.php          # Web routes
│   └── auth.php         # Authentication routes
├── storage/             # Generated files
└── tests/               # Test files
```

## Configuration

### API Configuration

The API uses Laravel Sanctum for authentication. Configure CORS settings in [config/cors.php](config/cors.php).

### Admin Dashboard

Access the admin dashboard at `/admin` (configure routes in [routes/web.php](routes/web.php)).

### File Storage

Configure file storage drivers in [config/filesystems.php](config/filesystems.php).



### Production build

```bash
# Install dependencies
composer install --optimize-autoloader --no-dev
npm install
npm run build

# Optimize Laravel
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Set proper permissions
chmod -R 755 storage bootstrap/cache
```

### Environment variables

Ensure these are set in production:
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com
```

## Contributing

Contributions are welcome! Please follow these steps:

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## Security

If you discover any security issues, please email [security@example.com] instead of using the issue tracker.

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## Support

For support, email support@example.com or open an issue in the repository.

## Credits

Built with:
- [Laravel](https://laravel.com) - PHP Framework
- [Tailwind CSS](https://tailwindcss.com) - CSS Framework
- [Vite](https://vitejs.dev) - Frontend Build Tool
- [Laravel Sanctum](https://laravel.com/docs/sanctum) - API Authentication

---

**Happy coding! 🚀**
