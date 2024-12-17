<?php
define('DB_HOST', 'localhost');
define('DB_USER', 'your_username');
define('DB_PASS', 'your_password');
define('DB_NAME', 'bakery_registration');

// Create connection
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create database if not exists
$sql = "CREATE DATABASE IF NOT EXISTS " . DB_NAME;
if ($conn->query($sql) === TRUE) {
    $conn->select_db(DB_NAME);
    
    // Create products table with video support
    $sql = "CREATE TABLE IF NOT EXISTS products (
        id INT(11) AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        category VARCHAR(50) NOT NULL,
        description TEXT,
        ingredients TEXT,
        allergens TEXT,
        price DECIMAL(10,2) NOT NULL,
        prep_time INT,
        image_url VARCHAR(255),
        video_url VARCHAR(255),
        video_thumbnail_url VARCHAR(255),
        availability JSON,
        special_instructions TEXT,
        seo_title VARCHAR(255),
        seo_description TEXT,
        seo_keywords VARCHAR(255),
        status ENUM('draft', 'published', 'archived') DEFAULT 'draft',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )";
    
    if (!$conn->query($sql)) {
        die("Error creating table: " . $conn->error);
    }

    // Create media table for handling multiple images and videos
    $sql = "CREATE TABLE IF NOT EXISTS product_media (
        id INT(11) AUTO_INCREMENT PRIMARY KEY,
        product_id INT(11),
        media_type ENUM('image', 'video', 'thumbnail') NOT NULL,
        file_url VARCHAR(255) NOT NULL,
        file_size INT,
        mime_type VARCHAR(100),
        duration INT,
        sort_order INT DEFAULT 0,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
    )";

    if (!$conn->query($sql)) {
        die("Error creating media table: " . $conn->error);
    }
} else {
    die("Error creating database: " . $conn->error);
}
?>
