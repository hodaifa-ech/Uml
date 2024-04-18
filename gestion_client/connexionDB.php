<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "uml";



try {
    // Connect to the database
    $conn = new PDO("mysql:host=$servername;dbname=$database", $username, "");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Database error
    $error_message = "Database Error: " . $e->getMessage();
}
