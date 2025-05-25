<?php

try {
    $database = new PDO("mysql:host=localhost;unix_socket=/Applications/XAMPP/xamppfiles/var/mysql/mysql.sock;dbname=quizzbuzz", "root", ""); 
    $database->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

?>