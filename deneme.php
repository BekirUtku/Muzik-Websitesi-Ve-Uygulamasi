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

// Index değerini al
$index = isset($_GET['index']) ? intval($_GET['index']) : 1;

$sql = "SELECT sarki_adi, sarkici, yol, kapak FROM muzik WHERE id=$index"; 
$result = $conn->query($sql);


if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $response = [
        'sarki_adi' => $row["sarki_adi"],
        'sarkici' => $row["sarkici"],
        'yol' => $row["yol"],
        'kapak' => $row["kapak"]
        
    ];
 // Şarkı bilgilerini "gecmis" tablosuna ekleme
 $sarki_adi = $row["sarki_adi"];
 $sarkici = $row["sarkici"];
 $yol = $row["yol"];
 $kapak = $row["kapak"];

 $insert_sql = "INSERT INTO gecmis (sarki_adi, sarkici, yol, kapak) VALUES ('$sarki_adi', '$sarkici', '$yol', '$kapak')";
 if ($conn->query($insert_sql) === TRUE) {
    // Kayıt başarılı
} else {
    // Kayıt başarısız
    echo "Error: " . $insert_sql . "<br>" . $conn->error;
}
    echo json_encode($response);
} else {
    echo json_encode(['error' => "Veritabanında müzik bulunamadı."]);
}



$conn->close();
?>
