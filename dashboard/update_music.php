<!-- update_music.php -->

<?php
include './config/database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $music_id = $_POST['music_id'];
    $sarki_adi = $_POST['sarki_adi'];
    $sarkici = $_POST['sarkici'];
    $album = $_POST['album'];
    $turu = $_POST['turu'];

    // Resim dosyasını yükle
    if ($_FILES['kapak']['error'] === UPLOAD_ERR_OK) {
        $picture_tmp_name = $_FILES['kapak']['tmp_name'];
        $picture_name = $_FILES['kapak']['name'];
        move_uploaded_file($picture_tmp_name, "../kapaklar/$picture_name");
        $picture_path = "./kapaklar/$picture_name";
    } else {
        // Eğer yeni bir resim yüklenmemişse, mevcut resmin yolu kullanılır
        $picture_path = $_POST['old_picture_path'];
    }

    // Müzik dosyasını yükle
    if ($_FILES['yol']['error'] === UPLOAD_ERR_OK) {
        $file_tmp_name = $_FILES['yol']['tmp_name'];
        $file_name = $_FILES['yol']['name'];
        move_uploaded_file($file_tmp_name, "../muzikler/$file_name");
        $file_path = "./muzikler/$file_name";
    } else {
        // Eğer yeni bir müzik dosyası yüklenmemişse, mevcut dosyanın yolu kullanılır
        $file_path = $_POST['old_file_path'];
    }

    // Veritabanında müziği güncelle
    $update_query = "UPDATE muzik SET sarki_adi='$sarki_adi', sarkici='$sarkici', album='$album', turu='$turu', kapak='$picture_path', yol='$file_path' WHERE id=$music_id";
    $db->query($update_query);

    // Başka bir sayfaya yönlendir
    header("Location: ./pages/music_list.php");
    exit();
}
?>