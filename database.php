<?php
$servername = 'sql12.freesqldatabase.com';  // Gunakan URL atau IP langsung
$username = 'sql12725641';                 // Nama pengguna database
$password = 'G695KPGbiC';                  // Kata sandi database
$dbname = 'sql12725641';                   // Nama database

// Membuat koneksi
$conn = new mysqli($servername, $username, $password, $dbname);

// Memeriksa koneksi
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
