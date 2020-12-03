<?php
$servername = "localhost";
$username = "s190217m_mytechbus";
$password = "x^B,wzY;X1On";
$dbname = "s190217m_mytechbus";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 
?>