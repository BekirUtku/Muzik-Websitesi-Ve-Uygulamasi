<?php

$servername = "localhost";
$username = "root";
$password = "";
$database = "spotify";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Veritabanına bağlanılamadı: " . $conn->connect_error);
}

$search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';

$sql = "SELECT id, sarki_adi, sarkici, yol, kapak FROM muzik WHERE sarki_adi='$search'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $response = [
        'index' => $row["id"],
        'sarki_adi' => $row["sarki_adi"],
        'sarkici' => $row["sarkici"],
        'yol' => $row["yol"],
        'kapak' => $row["kapak"]
    ];
    echo json_encode($response);
} else {
    echo json_encode(['error' => "Arama sonucu bulunamadı."]);
}

$conn->close();
?>
