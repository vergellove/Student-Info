<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection parameters - adjusted for local phpMyAdmin
$servername = getenv('DB_HOST') ?: 'localhost'; // Changed from 'mysql' to 'localhost'
$username = getenv('DB_USER') ?: 'root';
$password = getenv('DB_PASSWORD') ?: ''; // You may need to set your actual password if not empty
$dbname = getenv('DB_NAME') ?: 'student_registration';

// Connection port - MySQL default is 3306
$port = getenv('DB_PORT') ?: 3306;

// Create connection with error handling
try {
    // Log connection attempt for debugging
    error_log("Attempting to connect to MySQL at $servername:$port with user $username");
    
    // Try direct connection without database first
    $conn = new mysqli($servername, $username, $password, "", $port);
    
    // Check connection
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }
    
    // Create database if not exists
    $sql = "CREATE DATABASE IF NOT EXISTS `$dbname`";
    if ($conn->query($sql) !== TRUE) {
        throw new Exception("Error creating database: " . $conn->error);
    }
    
    // Select the database
    if (!$conn->select_db($dbname)) {
        throw new Exception("Error selecting database: " . $conn->error);
    }
    
    // Create students table if not exists
    $sql = "CREATE TABLE IF NOT EXISTS `students` (
        `id` INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        `first_name` VARCHAR(30) NOT NULL,
        `last_name` VARCHAR(30) NOT NULL,
        `dob` DATE NOT NULL,
        `email` VARCHAR(100) NOT NULL,
        `lrn` VARCHAR(12) NOT NULL,
        `gender` VARCHAR(10) NOT NULL,
        `address` TEXT NOT NULL,
        `city` VARCHAR(30) NOT NULL,
        `pin_code` VARCHAR(6) NOT NULL,
        `state` VARCHAR(30) NOT NULL,
        `country` VARCHAR(30) NOT NULL DEFAULT 'Philippines',
        `hobbies` VARCHAR(255),
        `other_hobby` VARCHAR(30),
        `course` VARCHAR(10) NOT NULL,
        `reg_date` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    
    if ($conn->query($sql) !== TRUE) {
        throw new Exception("Error creating table: " . $conn->error);
    }
    
    // Log success
    error_log("Successfully connected to database and created/verified table structure");
    
} catch (Exception $e) {
    // Log error with detailed information
    error_log("Database connection error: " . $e->getMessage());
    
    // Provide detailed debugging information
    echo "<div style='background-color: #ffeeee; padding: 15px; border: 1px solid #ff0000;'>";
    echo "<h3>Database Connection Error</h3>";
    echo "<p><strong>Error:</strong> " . $e->getMessage() . "</p>";
    echo "<p><strong>Connection Details:</strong></p>";
    echo "<ul>";
    echo "<li>Host: " . $servername . "</li>";
    echo "<li>Port: " . $port . "</li>";
    echo "<li>User: " . $username . "</li>";
    echo "<li>Database: " . $dbname . "</li>";
    echo "</ul>";
    echo "<p>Please check your database configuration and ensure MySQL is running and accessible.</p>";
    echo "</div>";
    die();
}

// Function to sanitize form inputs
function sanitize_input($conn, $data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    $data = $conn->real_escape_string($data);
    return $data;
}