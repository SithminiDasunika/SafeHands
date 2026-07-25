<?php

$servername = "localhost";
$username = "root";
$password = "root";
$database = "safehands_db";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection Failed: " . $conn->connect_error);
}
$conn->set_charset("utf8mb4");
?>