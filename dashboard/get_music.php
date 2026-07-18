<?php
// dashboard/get_music.php
// DÜZELTME: eski hali "music" tablosunu İngilizce kolonlarla (artist,
// song_title, file_path) sorguluyordu -> uygulamanın geri kalanı "muzik"
// tablosunu Türkçe kolonlarla kullanıyor. Şema ile uyumlu hale getirildi.

require_once __DIR__ . '/config/database.php';   // $db değişkenini sağlar

header('Content-Type: application/json; charset=utf-8');

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $music_id = (int) $_GET['id'];

    $stmt = $db->prepare(
        'SELECT id, sarkici, sarki_adi, album, turu, kapak, yol
         FROM muzik
         WHERE id = ?'
    );
    $stmt->bind_param('i', $music_id);
    $stmt->execute();
    $music = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if ($music) {
        echo json_encode($music);
    } else {
        echo json_encode(['error' => 'Müzik bulunamadı']);
    }
} else {
    echo json_encode(['error' => 'Geçersiz istek']);
}

$db->close();
