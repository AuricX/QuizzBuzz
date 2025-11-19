<?php

try {
    $database = new PDO("mysql:host=127.0.0.1;port=3306;dbname=quizzbuzz", "root", ""); 
    $database->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

?>