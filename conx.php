<?php

try {
    $database = new PDO("mysql:host=localhost:4039;dbname=quizzbuzz", "root", ""); 
    $database->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

?>