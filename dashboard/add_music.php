<?php
include './config/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $sarkici = $_POST['sarkici'];
    $sarki_adi = $_POST['sarki_adi'];
    $album = $_POST['album'];
    $turu = $_POST['turu'];

    $kapak = $_FILES['kapak'];
    $yol = $_FILES['yol'];

    $picture_path = './kapaklar/' . basename($kapak['name']);
    $file_music_path = './muzikler/' . basename($yol['name']);

    // Hedef dizinin var olduğundan ve yazılabilir olduğundan emin olun
    if (!is_dir('./muzikler/')) {
        mkdir('./muzikler/', 0777, true);
    }

    if (!is_writable('./muzikler/')) {
        echo 'Hedef dizin yazılabilir değil.';
        exit;
    }

    // Maksimum dosya boyutunu belirleyin
    $maxFileSize = 10485760; // 10 MB

    // Dosya boyutlarını kontrol edin
    if ($kapak['size'] > $maxFileSize || $yol['size'] > $maxFileSize) {
        echo "Dosya boyutu çok büyük. Maksimum boyut 10 MB olmalıdır.";
        exit;
    }

    // Hata ayıklama için dosya yükleme hatalarını kontrol edelim
    if ($kapak['error'] !== UPLOAD_ERR_OK) {
        echo "Picture upload error: " . $kapak['error'];
        exit;
    }

    if ($yol['error'] !== UPLOAD_ERR_OK) {
        echo "File upload error: " . $yol['error'];
        exit;
    }

    // Geçici dosyanın mevcut olup olmadığını kontrol edin
    if (!file_exists($kapak['tmp_name'])) {
        echo 'Geçici resim dosyası mevcut değil.';
        exit;
    }

    if (!file_exists($yol['tmp_name'])) {
        echo 'Geçici müzik dosyası mevcut değil.';
        exit;
    }

   
    // Veritabanına ekleme işlemi
    $stmt = $db->prepare("INSERT INTO muzik (sarkici, sarki_adi, album, turu, kapak, yol) VALUES (?, ?, ?, ?, ?, ?)");
    if ($stmt === false) {
        echo "Prepare failed: (" . $db->errno . ") " . $db->error;
        exit;
    }
    $stmt->bind_param("ssssss", $sarkici, $sarki_adi, $album, $turu, $picture_path, $file_music_path);

    // Sorguyu çalıştır
    if ($stmt->execute()) {
        header('Location: ./pages/music_list.php');
        exit;
    } else {
        echo "Bir hata oluştu: " . $stmt->error;
    }

    $stmt->close();
    $db->close();
}
