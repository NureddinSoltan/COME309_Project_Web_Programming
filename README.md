# Library Management System

## Browser the website live via:
http://lms-come309.wuaze.com/index.php?i=1

## Prerequisites

Ensure the following tools are installed on your system:
- **XAMPP** (or any LAMP/WAMP stack): Used to run PHP and MySQL locally.
- **Web Browser** (e.g., Chrome, Firefox).

---

## Setup Instructions

### 1. Clone or Download the Repository

1. Clone the repository using Git:
   ```bash
   git clone https://github.com/NureddinSoltan/COME309_Project_Web_Programming.git
   ```
2. Alternatively, download the project as a ZIP file from the repository and extract it.

---

### 2. Move the Project to `htdocs`

1. Navigate to your XAMPP installation directory.
2. Move the extracted project folder to the `htdocs` directory:
   ```
   C:/xampp/htdocs/library-management-system
   ```

---

### 3. Set Up the Database

1. Open **phpMyAdmin** in your browser:
   ```
   http://localhost/phpmyadmin
   ```
2. Create a new database:
   - Name the database: `library_db`.
3. Import the database schema and preloaded data:
   - Select the `library_db` database.
   - Click **Import** and upload the `schema.sql` file to create the database structure.
   - Repeat the process for the `data.sql` file to populate the database with sample data.

---

### 4. Configure the Database Connection

1. Open the `includes/db.php` file in your text editor.
2. Replace the contents with the following code:
   ```php
   $host = "localhost"; // Hostname
   $user = "root"; // Username
   $password = ""; // Password (leave empty for XAMPP)
   $dbname = "library_db"; // Database name

   try {
       $conn = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
       $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
   } catch (PDOException $e) {
       die("Connection failed: " . $e->getMessage());
   }
   ```
3. Ensure the `$dbname` matches the database name you created in phpMyAdmin.

---

### 5. Run the Project

1. Start the XAMPP Control Panel.
   - Start **Apache** and **MySQL**.
2. Open your web browser and navigate to:
   ```
   http://localhost/library-management-system
   ```

---

### 6. Login to the Application

1. Use the following default credentials to log in:
   - **Admin:**
     - Email: `admin@library.com`
     - Password: `admin123`
   - **User 1:**
     - Email: `noureldien@gmail.com`
     - Password: `user123`
   - **User 2:**
     - Email: `soltan@gmail.com`
     - Password: `user123`
2. Alternatively, create a new account using the **Sign Up** page.

---
