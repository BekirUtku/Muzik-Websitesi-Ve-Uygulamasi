<?php
// Tek şarkıyı id'ye göre döndürür ve "gecmis" tablosuna ekler.
// Tüm sorgular prepared statement.
require_once __DIR__ . '/db.php';

header('Content-Type: application/json; charset=utf-8');

$index = isset($_GET['index']) ? (int) $_GET['index'] : 1;

$stmt = $baglan->prepare(
    'SELECT sarki_adi, sarkici, yol, kapak FROM muzik WHERE id = ?'
);
$stmt->bind_param('i', $index);
$stmt->execute();
$row = $stmt->get_result()->fetch_assoc();
$stmt->close();

if ($row) {
    // Dinleme geçmişine ekle.
    $ins = $baglan->prepare(
        'INSERT INTO gecmis (sarki_adi, sarkici, yol, kapak) VALUES (?, ?, ?, ?)'
    );
    $ins->bind_param('ssss', $row['sarki_adi'], $row['sarkici'], $row['yol'], $row['kapak']);
    $ins->execute();
    $ins->close();

    echo json_encode($row);
} else {
    echo json_encode(['error' => 'Veritabanında müzik bulunamadı.']);
}

$baglan->close();
