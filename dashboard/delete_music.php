<?php
include './config/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $music_id = $_POST['music_id'];

    // Müzik bilgilerini veritabanından çek
    $stmt = $db->prepare("SELECT album, yol FROM muzik WHERE id = ?");
    $stmt->bind_param("i", $music_id);
    $stmt->execute();
    $stmt->bind_result($picture_path, $file_music_path);
    $stmt->fetch();
    $stmt->close();

    // Dosyaları sunucudan sil
    if (file_exists($picture_path)) {
        unlink($picture_path);
    }

    if (file_exists($file_music_path)) {
        unlink($file_music_path);
    }

    // Veritabanından müziği sil
    $stmt = $db->prepare("DELETE FROM muzik WHERE id = ?");
    $stmt->bind_param("i", $music_id);

    if ($stmt->execute()) {
        header('Location: ./pages/music_list.php');
        exit;
    } else {
        echo "Bir hata oluştu: " . $stmt->error;
    }

    $stmt->close();
    $db->close();
} else {
    echo "Geçersiz istek.";
    exit;
}
