# E-Commerce Website

A modern e-commerce website built with PHP and MySQL, featuring a responsive design and essential e-commerce functionality.

## Features

- User Authentication (Login, Signup, Profile Management)
- Product Catalog with Categories
- Shopping Cart Functionality
- Order Management
- Responsive Design
- Search and Filter Products
- User Profile Management
- Order History

## Requirements

- PHP 7.4 or higher
- MySQL 5.7 or higher
- Web Server (Apache/Nginx)
- Modern Web Browser

## Installation

1. Clone the repository to your web server directory:
```bash
git clone https://github.com/yourusername/ecommerce-website.git
```

2. Create a MySQL database and import the database schema:
```sql
CREATE DATABASE ecommerce_db;
```

3. Configure the database connection:
   - Open `config/database.php`
   - Update the database credentials:
     ```php
     define('DB_SERVER', 'localhost');
     define('DB_USERNAME', 'your_username');
     define('DB_PASSWORD', 'your_password');
     define('DB_NAME', 'ecommerce_db');
     ```

4. Set up the web server:
   - For Apache, ensure mod_rewrite is enabled
   - Point the document root to the project directory
   - Ensure the web server has write permissions for the uploads directory

5. Create required directories:
```bash
mkdir -p assets/images/products
mkdir -p assets/images/categories
chmod 755 assets/images/products
chmod 755 assets/images/categories
```

## Directory Structure

```
├── assets/
│   ├── css/
│   ├── js/
│   └── images/
├── config/
├── includes/
├── uploads/
└── *.php
```

## Usage

1. Access the website through your web browser
2. Register a new account or login with existing credentials
3. Browse products, add items to cart, and complete purchases
4. Manage your profile and view order history

## Security Features

- Password Hashing
- SQL Injection Prevention
- XSS Protection
- CSRF Protection
- Input Validation
- Secure Session Management

## Contributing

1. Fork the repository
2. Create your feature branch
3. Commit your changes
4. Push to the branch
5. Create a new Pull Request

## License

This project is licensed under the MIT License - see the LICENSE file for details.

## Support

For support, please open an issue in the GitHub repository or contact the maintainers. 