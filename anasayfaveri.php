<?php
// Anasayfadaki tüm şarkıları JSON döndürür (kullanıcı girdisi yok).
require_once __DIR__ . '/db.php';

header('Content-Type: application/json; charset=utf-8');

$result = $baglan->query('SELECT id, sarki_adi, sarkici, yol, kapak FROM muzik ORDER BY id');

$rows = [];
while ($row = $result->fetch_assoc()) {
    $rows[] = $row;
}
echo json_encode($rows);

$baglan->close();
