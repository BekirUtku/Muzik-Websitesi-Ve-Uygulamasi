<?php
include '../config/database.php';

// Her zaman JSON döndüreceğimiz için Content-Type başlığını ayarla
header('Content-Type: application/json');

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $music_id = (int) $_GET['id'];

    $stmt = $db->prepare("SELECT id, artist, song_title, album, genre, picture, file_path FROM music WHERE id = ?");
    $stmt->bind_param("i", $music_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $music = $result->fetch_assoc();

    if ($music) {
        echo json_encode($music);
    } else {
        echo json_encode(['error' => 'Müzik bulunamadı']);
    }

    $stmt->close();
} else {
    echo json_encode(['error' => 'Geçersiz istek']);
}

$db->close();
