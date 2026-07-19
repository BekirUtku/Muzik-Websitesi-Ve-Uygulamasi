<?php
// Oturum durumunu JSON olarak döndürür.
// Statik HTML sayfalarının (anasayfaa.html gibi) kullanıcıyı tanıması için.
session_start();
header('Content-Type: application/json; charset=utf-8');

$girisli = isset($_SESSION['kullanici_id']);

echo json_encode([
    'girisli'       => $girisli,
    'kullanici_adi' => $girisli ? $_SESSION['kullanici_adi'] : '',
    'ad'            => $girisli ? $_SESSION['ad'] : '',
]);
