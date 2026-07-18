<?php
// Şarkı arama. Prepared statement + LIKE -> SQL injection'a kapalı.
require_once __DIR__ . '/db.php';

header('Content-Type: application/json; charset=utf-8');

$search = trim($_GET['search'] ?? '');

if ($search === '') {
    echo json_encode(['error' => 'Arama terimi boş.']);
    $baglan->close();
    exit;
}

// Kısmi eşleşme için LIKE. %/_ karakterlerini kaçır.
$like = '%' . str_replace(['\\', '%', '_'], ['\\\\', '\\%', '\\_'], $search) . '%';

$stmt = $baglan->prepare(
    'SELECT id, sarki_adi, sarkici, yol, kapak
     FROM muzik
     WHERE sarki_adi LIKE ? OR sarkici LIKE ?
     ORDER BY id
     LIMIT 1'
);
$stmt->bind_param('ss', $like, $like);
$stmt->execute();
$row = $stmt->get_result()->fetch_assoc();
$stmt->close();

if ($row) {
    echo json_encode([
        'index'     => $row['id'],
        'sarki_adi' => $row['sarki_adi'],
        'sarkici'   => $row['sarkici'],
        'yol'       => $row['yol'],
        'kapak'     => $row['kapak'],
    ]);
} else {
    echo json_encode(['error' => 'Arama sonucu bulunamadı.']);
}

$baglan->close();
