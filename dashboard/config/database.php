<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "spotify";

// Veritabanı bağlantısını oluştur
$db = new mysqli($servername, $username, $password, $dbname);

// Bağlantı hatası kontrolü
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}
