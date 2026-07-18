<?php
// Profil güncelleme (AJAX). Oturum + prepared statement ile güvenli.
session_start();
require_once __DIR__ . '/db.php';

header('Content-Type: application/json; charset=utf-8');

function bitir($ok, $data = []) {
    echo json_encode(array_merge(['ok' => $ok], $data));
    exit;
}

if (!isset($_SESSION['kullanici_id'])) {
    http_response_code(401);
    bitir(false, ['error' => 'Oturum açmadınız.']);
}

$id    = (int) $_SESSION['kullanici_id'];
$ad    = trim($_POST['ad'] ?? '');
$soyad = trim($_POST['soyad'] ?? '');
$email = trim($_POST['email'] ?? '');
$k_adi = trim($_POST['k_adi'] ?? '');
$mevcut_sifre = $_POST['mevcut_sifre'] ?? '';
$yeni_sifre   = $_POST['yeni_sifre'] ?? '';

// ---- Doğrulama ----
if ($ad === '' || $soyad === '' || $email === '' || $k_adi === '') {
    bitir(false, ['error' => 'Ad, soyad, e-posta ve kullanıcı adı boş olamaz.']);
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    bitir(false, ['error' => 'Geçerli bir e-posta adresi girin.']);
}

// Kullanıcı adı / e-posta başka birinde var mı?
$stmt = $baglan->prepare(
    'SELECT id FROM kullanicilar WHERE (kullanici_adi = ? OR email = ?) AND id <> ? LIMIT 1'
);
$stmt->bind_param('ssi', $k_adi, $email, $id);
$stmt->execute();
if ($stmt->get_result()->fetch_assoc()) {
    $stmt->close();
    bitir(false, ['error' => 'Bu kullanıcı adı veya e-posta başka bir hesapta kullanılıyor.']);
}
$stmt->close();

// ---- Şifre değişikliği isteniyorsa ----
$sifre_degisir = ($yeni_sifre !== '');
$yeni_hash = null;
if ($sifre_degisir) {
    if (strlen($yeni_sifre) < 6) {
        bitir(false, ['error' => 'Yeni şifre en az 6 karakter olmalıdır.']);
    }
    // Mevcut şifreyi doğrula
    $stmt = $baglan->prepare('SELECT sifre FROM kullanicilar WHERE id = ?');
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    if (!$row || !password_verify($mevcut_sifre, $row['sifre'])) {
        bitir(false, ['error' => 'Mevcut şifre hatalı.']);
    }
    $yeni_hash = password_hash($yeni_sifre, PASSWORD_DEFAULT);
}

// ---- Güncelle ----
if ($sifre_degisir) {
    $stmt = $baglan->prepare(
        'UPDATE kullanicilar SET ad=?, soyad=?, email=?, kullanici_adi=?, sifre=? WHERE id=?'
    );
    $stmt->bind_param('sssssi', $ad, $soyad, $email, $k_adi, $yeni_hash, $id);
} else {
    $stmt = $baglan->prepare(
        'UPDATE kullanicilar SET ad=?, soyad=?, email=?, kullanici_adi=? WHERE id=?'
    );
    $stmt->bind_param('ssssi', $ad, $soyad, $email, $k_adi, $id);
}

if (!$stmt->execute()) {
    $stmt->close();
    bitir(false, ['error' => 'Güncelleme sırasında bir hata oluştu.']);
}
$stmt->close();

// Oturumdaki bilgileri de güncelle
$_SESSION['ad']            = $ad;
$_SESSION['soyad']         = $soyad;
$_SESSION['email']         = $email;
$_SESSION['kullanici_adi'] = $k_adi;

bitir(true, [
    'user' => [
        'ad' => $ad, 'soyad' => $soyad, 'email' => $email, 'kullanici_adi' => $k_adi,
    ],
    'sifre_degisti' => $sifre_degisir,
]);
