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

#### Authentication
- `GET /api/auth` - Check authentication status
- `POST /api/auth` - Login with username/password

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

#### Staff (Admin Only)
- `GET /api/staff` - List all staff members
- `GET /api/staff?manageable=true` - List manageable users
- `GET /api/staff/{id}` - Get specific staff member

#### Inquiries (Admin Only)
- `GET /api/inquiries` - List all inquiries
- `GET /api/inquiries/{id}` - Get specific inquiry

#### Roles Management (Admin Only)
- `GET /api/roles` - List all roles
- `GET /api/roles?assignable=true` - List assignable roles
- `GET /api/roles/{id}` - Get specific role
- `GET /api/user-roles/{user_id}` - Get user roles
- `POST /api/user-roles` - Assign role to user
- `DELETE /api/user-roles/{user_id}` - Remove role from user

#### Archive Management (Admin Only)
- `POST /api/archive` - Archive user
- `DELETE /api/archive/{user_id}` - Unarchive user

## 🔒 Security Features

- **CSRF Protection**: All forms protected against CSRF attacks
- **Input Validation**: Comprehensive input sanitization
- **SQL Injection Prevention**: Parameterized queries throughout
- **Session Management**: Secure user authentication
- **Password Hashing**: bcrypt password encryption
- **Role-Based Access Control (RBAC)**: Granular permission system
- **API Authentication**: HTTP Basic Auth and JSON-based login
- **Archived User Management**: Secure user deactivation system

## 🔐 Authentication & Authorization System

### Role-Based Access Control (RBAC)

The application implements a comprehensive role-based access control system with hierarchical permissions:

#### **Role Hierarchy (Priority System)**
| Role | Priority | Access Level | Description |
|------|----------|--------------|-------------|
| **api_admin** | 1 | Highest | Full system access, API management, can manage all users |
| **system_admin** | 2 | High | System administration, user management, can manage admin and below |
| **admin** | 3 | Medium | Administrative functions, can manage medewerker and archived |
| **medewerker** | 4 | Low | Basic staff access, limited management functions |
| **archived** | 5 | None | No access, deactivated users |

#### **Permission Inheritance**
- Users with higher priority (lower number) automatically inherit all permissions of lower priority roles
- Example: `api_admin` (priority 1) has access to all functions available to `system_admin`, `admin`, and `medewerker`

### Database Schema

#### **Roles Table**
```sql
CREATE TABLE roles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL UNIQUE,
    priority INT NOT NULL UNIQUE,
    description TEXT
);
```

#### **User Roles Table**
```sql
CREATE TABLE user_roles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    role_id INT NOT NULL,
    assigned_by INT,
    assigned_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES staff(id) ON DELETE CASCADE,
    FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE CASCADE,
    FOREIGN KEY (assigned_by) REFERENCES staff(id) ON DELETE SET NULL,
    UNIQUE KEY unique_user_role (user_id, role_id)
);
```

### Frontend Access Control

#### **Page Access Control**
| Page | Required Access | Description |
|------|----------------|-------------|
| `/beheerder` | `has_management_access()` | Admin dashboard |
| `/beheerder/medewerkers-overzicht` | `admin` or higher | Staff overview |
| `/beheerder/medewerker-aanmaken` | `admin` or higher | Create staff |
| `/beheerder/rollen-beheer` | `admin` or higher | Role management |
| `/beheerder/api-documentatie` | `api_admin` only | API documentation |
| `/beheerder/objecten-beheer` | `admin` or higher | Object management |
| `/beheerder/aanvragen-overzicht` | `admin` or higher | Inquiries overview |

#### **Button Visibility Control**
| Button/Feature | Visibility Condition | Description |
|----------------|---------------------|-------------|
| **Beheerdersdashboard** (Header) | `has_management_access()` | Navigation link |
| **API Documentatie** | `has_role('api_admin')` | Admin home button |
| **Rollen Beheer** | `admin` or higher | Admin home button |
| **Object Aanmaken** | `has_management_access()` | Object overview |
| **Edit Object** | `has_management_access()` | Object cards |
| **Verwijder Object** | `has_management_access()` | Object details |
| **Rollen Beheren** | `can_manage_user()` | Staff overview |
| **Archiveren/Uit Archief** | `can_archive_user()` | Staff overview |
| **Verwijder** | `isArchived` | Only for archived users |

### API Authentication

#### **Authentication Methods**
1. **HTTP Basic Authentication**
   - Username/password in `Authorization` header
   - Format: `Basic base64(username:password)`

2. **JSON-based Login**
   - POST to `/api/auth` with credentials
   - Returns session token for subsequent requests

#### **API Access Control**
| Endpoint | Authentication | Required Role |
|----------|----------------|---------------|
| `/api/auth` | None | Public |
| `/api/objects` | Optional | Public (filtered) |
| `/api/cities` | Optional | Public |
| `/api/properties` | Optional | Public |
| `/api/staff` | Required | `api_admin` |
| `/api/inquiries` | Required | `api_admin` |
| `/api/roles` | Required | `api_admin` |
| `/api/user-roles` | Required | `api_admin` |
| `/api/archive` | Required | `api_admin` |

#### **API Authentication Examples**

**Basic Authentication:**
```bash
curl -u "admin:password" http://localhost:8080/api/staff
```

**JSON Login:**
```bash
curl -X POST http://localhost:8080/api/auth \
  -H "Content-Type: application/json" \
  -d '{"username":"admin","password":"password"}'
```

### User Management Features

#### **Role Assignment Rules**
- **api_admin**: Can assign any role to any user
- **system_admin**: Can assign `admin`, `medewerker`, `archived` roles
- **admin**: Can assign `medewerker`, `archived` roles
- **medewerker**: Cannot assign roles
- **archived**: Cannot assign roles

#### **User Management Rules**
- **api_admin**: Can manage all users (including other api_admins)
- **system_admin**: Can manage `admin`, `medewerker`, `archived` users
- **admin**: Can manage `medewerker`, `archived` users
- **medewerker**: Cannot manage users
- **archived**: Cannot manage users

#### **Archiving System**
- **Archive User**: Removes all roles, assigns `archived` role
- **Unarchive User**: Removes `archived` role, assigns `medewerker` role
- **Archived Users**: No access to management functions, redirected to login

### Security Utilities

#### **user_login_session_util.php**
- `has_management_access()`: Checks if user has management permissions
- `has_role($roleName)`: Checks for specific role
- `has_role_or_higher($roleName)`: Checks for role or higher priority
- `is_archived()`: Checks if user is archived
- `get_highest_priority_role()`: Returns user's highest priority role

#### **api_auth_util.php**
- `authenticateRequest()`: Validates API authentication
- `hasApiAccess()`: Checks API access permissions
- `getAuthenticatedUser()`: Returns authenticated user data

### Session Management

#### **Login Process**
1. User submits credentials
2. System validates against database
3. Session created with user data
4. User roles loaded and cached
5. Redirect to appropriate dashboard

#### **Logout Process**
1. Session destroyed
2. User redirected to login page
3. All authentication tokens invalidated

### Default User Accounts

| Username | Password | Role | Access Level | Description |
|----------|----------|------|--------------|-------------|
| `api_admin` | `password` | `api_admin` | Highest | Full system access, API management |
| `system_admin` | `password` | `system_admin` | High | System administration, user management |
| `admin` | `password` | `admin` | Medium | Administrative functions |
| `medewerker` | `password` | `medewerker` | Low | Basic staff access |
| `archived` | `password` | `archived` | None | No access, deactivated user |

## 🎨 UI/UX Features

- **Responsive Design**: Works on all device sizes
- **Modern Interface**: Clean, professional design
- **Interactive Elements**: Dynamic filtering and search
- **User Feedback**: Toast notifications and confirmations
- **Print Support**: Property details printable format

## 📊 Database Schema

The application uses a normalized database schema with the following main tables:

### Core Tables
- **objects**: Property listings
- **cities**: Dutch municipalities
- **properties**: Property features/amenities
- **connectprop**: Many-to-many relationship between objects and properties
- **inquiries**: Contact requests
- **staff**: User accounts

### Authentication Tables
- **roles**: User roles with priority system
- **user_roles**: Many-to-many relationship between users and roles

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