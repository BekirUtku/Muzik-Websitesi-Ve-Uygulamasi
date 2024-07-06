<?php
$servername = "localhost"; // Sunucu adı
$username = "root"; // Veritabanı kullanıcı adı
$password = ""; // Veritabanı şifresi
$database = "spotify"; // Veritabanı adı

// Veritabanı bağlantısını oluştur
$conn = new mysqli($servername, $username, $password, $database);

// Bağlantıyı kontrol et
if ($conn->connect_error) {
    die("Veritabanına bağlanılamadı: " . $conn->connect_error);
}

// SQL sorgusuyla müzik verilerini çek
$sql = "SELECT sarki_adi, sarkici, yol, kapak FROM muzik";
$result = $conn->query($sql);

// Verileri JSON formatında dön
$rows = array();
while ($row = $result->fetch_assoc()) {
    $rows[] = $row;
}
echo json_encode($rows);

$conn->close();
?>
