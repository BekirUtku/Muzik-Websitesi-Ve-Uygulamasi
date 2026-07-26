<?php
/**
 * Yönetici koruması. Panel dosyalarının EN ÜSTÜNDE include edilir.
 * Sadece giriş yapmış VE yonetici=1 olan kullanıcılar geçebilir.
 * Ayrıca $db bağlantısını sağlar (config/database.php).
 */
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/config/database.php';   // $db

// Proje kök yolunu URL'den hesapla (hem pages/ hem dashboard/ kökünden çalışır)
$__sn = $_SERVER['SCRIPT_NAME'] ?? '';
$__pos = strpos($__sn, '/dashboard/');
$__root = $__pos !== false ? substr($__sn, 0, $__pos) : '';

if (!isset($_SESSION['kullanici_id'])) {
    header('Location: ' . $__root . '/login.php');
    exit;
}

$__stmt = $db->prepare('SELECT yonetici FROM kullanicilar WHERE id = ?');
$__stmt->bind_param('i', $_SESSION['kullanici_id']);
$__stmt->execute();
$__u = $__stmt->get_result()->fetch_assoc();
$__stmt->close();

if (!$__u || (int) $__u['yonetici'] !== 1) {
    // Giriş var ama yönetici değil -> siteye gönder
    header('Location: ' . $__root . '/anasayfaa.html');
    exit;
}
// Buradan sonrası: kullanıcı yöneticidir, $db kullanılabilir.
