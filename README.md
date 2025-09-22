# BugTester - Professional Bug Tracking System

A modern, comprehensive bug tracking and project management application built with Laravel 11, Livewire 3, and Bootstrap 5. Inspired by Jira's clean and professional design system.

## 🚀 Features

### Core Functionality
- **Bug Management**: Create, edit, assign, and track bugs through their lifecycle
- **Project Management**: Organize bugs by projects with status tracking
- **Kanban Board**: Visual bug tracking with drag-and-drop functionality
- **User Management**: Role-based access control (Admin, Developer, Tester)
- **Real-time Updates**: Livewire-powered dynamic interface
- **Search & Filtering**: Advanced search and filter capabilities
- **Notifications**: Real-time notifications for bug updates
- **File Attachments**: Support for bug attachments and screenshots

### Advanced Features
- **AI Bug Summarization**: Intelligent bug description summarization
- **Dynamic Logo Management**: Customizable application branding
- **Responsive Design**: Mobile-first, fully responsive interface
- **Dark/Light Theme**: Modern UI with smooth animations
- **API Endpoints**: RESTful API for external integrations
- **Comprehensive Testing**: 90%+ test coverage with unit, feature, and integration tests

## 🛠️ Technology Stack

- **Backend**: Laravel 11, PHP 8.2+
- **Frontend**: Livewire 3, Alpine.js, Bootstrap 5.3.2
- **Database**: SQLite (development), MySQL/PostgreSQL (production)
- **Authentication**: Laravel Breeze with Spatie Permissions
- **Icons**: Font Awesome 6.5.1
- **Build Tools**: Vite, NPM
- **Testing**: PHPUnit with comprehensive test suite

## 📋 Requirements

- PHP 8.2 or higher
- Composer
- Node.js & NPM
- SQLite (or MySQL/PostgreSQL for production)

## 🚀 Installation

1. **Clone the repository**
   ```bash
   git clone https://github.com/appsaga-io/bugtester.git
   cd bugtester
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Install Node.js dependencies**
   ```bash
   npm install
   ```

4. **Environment setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Database setup**
   ```bash
   php artisan migrate:fresh --seed
   ```

6. **Build assets**
   ```bash
   npm run build
   ```

7. **Start the application**
   ```bash
   php artisan serve
   ```

## 👥 Default Users

The application comes with pre-configured users:

- **Admin**: `admin@example.com` / `password`
- **Developer**: `developer@example.com` / `password`
- **Tester**: `tester@example.com` / `password`

## 🧪 Testing

Run the comprehensive test suite:

```bash
# Run all tests
./run-tests.sh

# Or run specific test suites
vendor/bin/phpunit tests/Unit
vendor/bin/phpunit tests/Feature
vendor/bin/phpunit --coverage-html coverage
```

## 📁 Project Structure

```
bugtester/
├── app/
│   ├── Livewire/          # Livewire components
│   ├── Models/            # Eloquent models
│   ├── Services/          # Business logic services
│   └── Http/Controllers/  # API controllers
├── database/
│   ├── factories/         # Model factories
│   ├── migrations/        # Database migrations
│   └── seeders/          # Database seeders
├── resources/
│   ├── views/            # Blade templates
│   ├── css/              # Stylesheets
│   └── js/               # JavaScript files
├── tests/                # Comprehensive test suite
├── routes/               # Application routes
└── public/               # Public assets
```

## 🎨 UI/UX Features

- **Jira-inspired Design**: Clean, professional interface
- **Bootstrap 5**: Modern, responsive framework
- **Smooth Animations**: Enhanced user experience
- **Mobile-First**: Fully responsive design
- **Accessibility**: WCAG compliant interface
- **Custom Components**: Reusable UI components

## 🔧 Configuration

### Environment Variables

Key environment variables to configure:

```env
APP_NAME=BugTester
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=bugtester
DB_USERNAME=your_username
DB_PASSWORD=your_password

MAIL_MAILER=smtp
MAIL_HOST=your-smtp-host
MAIL_PORT=587
MAIL_USERNAME=your-email
MAIL_PASSWORD=your-password
```

### Logo Management

Upload a custom logo through the admin panel at `/admin/logo` or set the `LOGO_PATH` environment variable.

## 📊 API Documentation

The application provides RESTful API endpoints:

- `GET /api/bugs` - List all bugs
- `POST /api/bugs` - Create a new bug
- `PUT /api/bugs/{id}` - Update a bug
- `DELETE /api/bugs/{id}` - Delete a bug
- `GET /api/projects` - List all projects
- `POST /api/projects` - Create a new project
- `GET /api/statistics` - Get application statistics

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add some amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## 📝 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 🆘 Support

For support and questions:

- Create an issue on GitHub
- Check the [documentation](docs/)
- Review the [testing guide](TESTING.md)

## 🗺️ Roadmap

- [ ] Advanced reporting and analytics
- [ ] Time tracking integration
- [ ] Mobile application
- [ ] Webhook integrations
- [ ] Advanced AI features
- [ ] Multi-language support

## 🙏 Acknowledgments

- Laravel framework and community
- Livewire team for the amazing reactive components
- Bootstrap team for the UI framework
- Font Awesome for the icon library
- All contributors and testers

---

**BugTester** - Professional bug tracking made simple and efficient.

Built with ❤️ by the Appsaga team.