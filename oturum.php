<?php
// Oturum durumunu JSON olarak döndürür (statik HTML sayfaları için).
session_start();
header('Content-Type: application/json; charset=utf-8');

$girisli = isset($_SESSION['kullanici_id']);
$yonetici = false;

if ($girisli) {
    // Yönetici mi? (yonetici sütunu yoksa hata vermeden false döner)
    try {
        require_once __DIR__ . '/db.php';   // $baglan
        $st = $baglan->prepare('SELECT yonetici FROM kullanicilar WHERE id = ?');
        $st->bind_param('i', $_SESSION['kullanici_id']);
        $st->execute();
        $u = $st->get_result()->fetch_assoc();
        $st->close();
        $yonetici = $u && (int) $u['yonetici'] === 1;
    } catch (Throwable $e) {
        $yonetici = false;
    }
}

echo json_encode([
    'girisli'       => $girisli,
    'kullanici_adi' => $girisli ? $_SESSION['kullanici_adi'] : '',
    'ad'            => $girisli ? $_SESSION['ad'] : '',
    'yonetici'      => $yonetici,
]);
