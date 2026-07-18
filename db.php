<?php
/**
 * Merkezi veritabanı bağlantısı.
 * Tüm dosyalar bağlantı için SADECE bunu include etmeli:
 *     require_once __DIR__ . '/db.php';
 *
 * NOT: Bu dosya hiçbir şey EKRANA YAZMAZ (echo yok). Aksi halde
 * JSON dönen uçlar ve header() yönlendirmeleri bozulur.
 */

// Hataları kullanıcıya gösterme; istisna olarak fırlat ve log'a yaz.
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$DB_HOST = 'localhost';
$DB_USER = 'root';
$DB_PASS = '';
$DB_NAME = 'spotify';

try {
    $baglan = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
    $baglan->set_charset('utf8mb4');
} catch (mysqli_sql_exception $e) {
    http_response_code(500);
    error_log('DB connection failed: ' . $e->getMessage());
    die('Veritabanına bağlanılamadı.');
}
