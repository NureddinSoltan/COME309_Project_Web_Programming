# COME309_Project_Web_Programming

# Library Management System

## Setup Instructions
1. Clone the repository:
   ```bash
   git clone https://github.com/NureddinSoltan/COME309_Project_Web_Programming.git
   ```
2. Navigate to the project directory:
   ```bash
   cd library-management-system
   ```
3. Import the `data.sql` file into your MySQL database:
   - Open **phpMyAdmin** or use the MySQL CLI:
     ```bash
     mysql -u root -p library_db < data.sql
     ```
4. Update the database configuration:
   - Edit `includes/db.php` with your local MySQL credentials:
     ```php
     define('DB_HOST', 'localhost');
     define('DB_NAME', 'library_db');
     define('DB_USER', 'root');
     define('DB_PASS', 'your_password');
     ```

5. Start your local server (e.g., XAMPP or WAMP) and access the project at:
   ```
   http://localhost/library-management-system
   ```

## Default Users
- **Admin:**
  - Email: `admin@library.com`
  - Password: `admin123`
- **User 1:**
  - Email: `noureldien@gmail.com`
  - Password: `noureldien123`
- **User 2:**
  - Email: `soltan@gmail.com`
  - Password: `soltan123`
