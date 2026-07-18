<?php
// Toplam şarkı sayısını ve en büyük id'yi döndürür (shuffle sınırları için).
require_once __DIR__ . '/db.php';

header('Content-Type: application/json; charset=utf-8');

$res = $baglan->query('SELECT COUNT(*) AS adet, COALESCE(MAX(id), 0) AS max_id FROM muzik');
$row = $res->fetch_assoc();

echo json_encode([
    'count'  => (int) $row['adet'],
    'max_id' => (int) $row['max_id'],
]);

$baglan->close();
