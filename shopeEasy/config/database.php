<?php
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'ecommerce_db');

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Attempt to connect to MySQL database
$conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Create database if it doesn't exist
$sql = "CREATE DATABASE IF NOT EXISTS " . DB_NAME;
if (mysqli_query($conn, $sql)) {
    mysqli_select_db($conn, DB_NAME);
} else {
    die("Error creating database: " . mysqli_error($conn));
}

// Create users table
$sql = "CREATE TABLE IF NOT EXISTS users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
)";

if (!mysqli_query($conn, $sql)) {
    die("Error creating users table: " . mysqli_error($conn));
}

// Create products table
$sql = "CREATE TABLE IF NOT EXISTS products (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    category_id INT,
    image_url VARCHAR(255),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
)";

if (!mysqli_query($conn, $sql)) {
    die("Error creating products table: " . mysqli_error($conn));
}

// Create categories table
$sql = "CREATE TABLE IF NOT EXISTS categories (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(50) NOT NULL,
    description TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
)";

if (!mysqli_query($conn, $sql)) {
    die("Error creating categories table: " . mysqli_error($conn));
}

// Set charset to utf8mb4
mysqli_set_charset($conn, "utf8mb4");
?> 