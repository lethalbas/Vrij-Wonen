# Vrij Wonen - Modern Real Estate Platform

A comprehensive, modern real estate platform built with PHP 8.2, featuring a clean MVC architecture with Service Layer and Repository Pattern implementation.

## 🏠 Features

- **Property Management**: Complete CRUD operations for real estate listings
- **Advanced Search**: Filter properties by city, features, and price range
- **User Management**: Admin panel with staff management
- **Inquiry System**: Contact requests for properties
- **REST API**: Full API with comprehensive documentation
- **Responsive Design**: Modern Bootstrap-based UI
- **Security**: CSRF protection, input validation, SQL injection prevention

## 🚀 Technology Stack

### Backend
- **PHP 8.2** - Modern PHP with latest features
- **MariaDB 10.9** - Reliable database system
- **Apache 2.4** - Web server with mod_rewrite
- **Docker** - Containerized development environment

### Architecture
- **MVC Pattern** - Model-View-Controller separation
- **Service Layer** - Business logic abstraction
- **Repository Pattern** - Data access abstraction
- **Dependency Injection** - Improved testability

### Frontend
- **Bootstrap 5.2** - Responsive UI framework
- **jQuery 3.6** - JavaScript library
- **Select2** - Enhanced select boxes
- **Font Awesome** - Icon library

## 🛠️ Installation & Setup

### Prerequisites
- Docker Desktop
- Git

### Quick Start

1. **Clone the repository**
   ```bash
   git clone https://github.com/yourusername/Vrij-Wonen.git
   cd Vrij-Wonen
   ```

2. **Start the application**
   ```bash
   docker-compose up -d
   ```

3. **Access the application**
   - **Website**: http://localhost:8080
   - **phpMyAdmin**: http://localhost:8081
   - **API Documentation**: http://localhost:8080/beheerder/api-documentatie (admin only)

### Default Credentials
- **Admin**: `admin` / `password`
- **Database**: `root` / `password`

## 📁 Project Structure

```
Vrij-Wonen/
├── app/
│   ├── api/                 # REST API endpoints
│   ├── controller/          # MVC Controllers
│   ├── model/              # Legacy Models (deprecated)
│   ├── repository/          # Data Access Layer
│   ├── service/            # Business Logic Layer
│   ├── util/               # Utility classes
│   └── view/               # Templates and views
├── docker/                 # Docker configuration
├── htdocs/                 # Web root
│   ├── cdn/               # Static assets
│   └── index.php          # Application entry point
├── docker-compose.yml      # Docker services
├── schema.sql             # Database schema
└── mock_data.sql          # Sample data
```

## 🔧 Development

### Database Reset
```bash
# Windows
reset-database.bat

# Linux/Mac
./reset-database.sh
```

### API Endpoints

#### Objects
- `GET /api/objects` - List all properties
- `GET /api/objects?city=17` - Filter by city
- `GET /api/objects?properties=1,2` - Filter by features
- `GET /api/objects/{id}` - Get specific property

#### Cities
- `GET /api/cities` - List all cities
- `GET /api/cities?used=true` - Cities with properties
- `GET /api/cities/{id}` - Get specific city

#### Properties
- `GET /api/properties` - List all features
- `GET /api/properties/{id}` - Get specific feature

#### Staff
- `GET /api/staff` - List all staff members
- `GET /api/staff/{id}` - Get specific staff member

#### Inquiries
- `GET /api/inquiries` - List all inquiries
- `GET /api/inquiries/{id}` - Get specific inquiry

## 🔒 Security Features

- **CSRF Protection**: All forms protected against CSRF attacks
- **Input Validation**: Comprehensive input sanitization
- **SQL Injection Prevention**: Parameterized queries throughout
- **Session Management**: Secure user authentication
- **Password Hashing**: bcrypt password encryption

## 🎨 UI/UX Features

- **Responsive Design**: Works on all device sizes
- **Modern Interface**: Clean, professional design
- **Interactive Elements**: Dynamic filtering and search
- **User Feedback**: Toast notifications and confirmations
- **Print Support**: Property details printable format

## 📊 Database Schema

The application uses a normalized database schema with the following main tables:

- **objects**: Property listings
- **cities**: Dutch municipalities
- **properties**: Property features/amenities
- **connectprop**: Many-to-many relationship between objects and properties
- **inquiries**: Contact requests
- **staff**: User accounts

## 🚀 Deployment

### Production Setup

1. **Environment Configuration**
   - Update database credentials in `app/util/db_connection_util.php`
   - Configure web server document root to `htdocs/`
   - Enable Apache mod_rewrite module

2. **Database Setup**
   - Import `schema.sql` for database structure
   - Import `mock_data.sql` for sample data (optional)

3. **File Permissions**
   - Ensure write permissions for `app/system_logs/`
   - Ensure write permissions for `htdocs/cdn/img/user_image_uploads/`

## 🤝 Contributing

This project is proprietary software. All rights reserved.

## 📄 License

**Proprietary License** - All rights reserved.

This software is proprietary and confidential. No part of this software may be reproduced, distributed, or transmitted in any form or by any means, including photocopying, recording, or other electronic or mechanical methods, without the prior written permission of the copyright owner.

**Commercial Use**: Prohibited without explicit written permission.
**Modification**: Prohibited without explicit written permission.
**Distribution**: Prohibited without explicit written permission.

## 📞 Support

For support and licensing inquiries, please contact the project owner.

---

**Vrij Wonen** - Modern Real Estate Platform  
Built with ❤️ using modern PHP architecture