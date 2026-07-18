<?php
// Çalma listeleri API'si. Tüm işlemler oturum + prepared statement ile güvenli.
// action: benim | olustur | sil | ekle | cikar | sarkilar
session_start();
require_once __DIR__ . '/db.php';

header('Content-Type: application/json; charset=utf-8');

function bitir($ok, $data = []) { echo json_encode(array_merge(['ok' => $ok], $data)); exit; }

if (!isset($_SESSION['kullanici_id'])) {
    http_response_code(401);
    bitir(false, ['error' => 'Oturum açmadınız.']);
}
$uid = (int) $_SESSION['kullanici_id'];
$action = $_GET['action'] ?? $_POST['action'] ?? '';

// Bir listenin bu kullanıcıya ait olduğunu doğrula
function listeSahibiMi($baglan, $liste_id, $uid) {
    $stmt = $baglan->prepare('SELECT id FROM calma_listeleri WHERE id = ? AND kullanici_id = ?');
    $stmt->bind_param('ii', $liste_id, $uid);
    $stmt->execute();
    $var = (bool) $stmt->get_result()->fetch_assoc();
    $stmt->close();
    return $var;
}

switch ($action) {

    case 'benim': // kullanıcının listeleri (şarkı sayısıyla)
        $stmt = $baglan->prepare(
            'SELECT cl.id, cl.ad, COUNT(ls.id) AS adet
             FROM calma_listeleri cl
             LEFT JOIN liste_sarkilari ls ON ls.liste_id = cl.id
             WHERE cl.kullanici_id = ?
             GROUP BY cl.id, cl.ad
             ORDER BY cl.olusturma DESC'
        );
        $stmt->bind_param('i', $uid);
        $stmt->execute();
        $res = $stmt->get_result();
        $rows = [];
        while ($r = $res->fetch_assoc()) {
            $rows[] = ['id' => (int)$r['id'], 'ad' => $r['ad'], 'adet' => (int)$r['adet']];
        }
        $stmt->close();
        bitir(true, ['listeler' => $rows]);

    case 'olustur':
        $ad = trim($_POST['ad'] ?? '');
        if ($ad === '') bitir(false, ['error' => 'Liste adı boş olamaz.']);
        if (mb_strlen($ad) > 120) $ad = mb_substr($ad, 0, 120);
        $stmt = $baglan->prepare('INSERT INTO calma_listeleri (kullanici_id, ad) VALUES (?, ?)');
        $stmt->bind_param('is', $uid, $ad);
        $stmt->execute();
        $yeni = $stmt->insert_id;
        $stmt->close();
        bitir(true, ['id' => (int)$yeni, 'ad' => $ad, 'adet' => 0]);

    case 'sil':
        $id = (int) ($_POST['id'] ?? 0);
        if (!listeSahibiMi($baglan, $id, $uid)) bitir(false, ['error' => 'Liste bulunamadı.']);
        $stmt = $baglan->prepare('DELETE FROM calma_listeleri WHERE id = ? AND kullanici_id = ?');
        $stmt->bind_param('ii', $id, $uid);
        $stmt->execute();
        $stmt->close();
        bitir(true);

    case 'ekle':
        $liste_id = (int) ($_POST['liste_id'] ?? 0);
        $muzik_id = (int) ($_POST['muzik_id'] ?? 0);
        if (!listeSahibiMi($baglan, $liste_id, $uid)) bitir(false, ['error' => 'Liste bulunamadı.']);
        // INSERT IGNORE -> zaten varsa çift eklemez (UNIQUE kısıt)
        $stmt = $baglan->prepare('INSERT IGNORE INTO liste_sarkilari (liste_id, muzik_id) VALUES (?, ?)');
        $stmt->bind_param('ii', $liste_id, $muzik_id);
        $stmt->execute();
        $eklendi = $stmt->affected_rows > 0;
        $stmt->close();
        bitir(true, ['eklendi' => $eklendi]);

    case 'cikar':
        $liste_id = (int) ($_POST['liste_id'] ?? 0);
        $muzik_id = (int) ($_POST['muzik_id'] ?? 0);
        if (!listeSahibiMi($baglan, $liste_id, $uid)) bitir(false, ['error' => 'Liste bulunamadı.']);
        $stmt = $baglan->prepare('DELETE FROM liste_sarkilari WHERE liste_id = ? AND muzik_id = ?');
        $stmt->bind_param('ii', $liste_id, $muzik_id);
        $stmt->execute();
        $stmt->close();
        bitir(true);

    case 'sarkilar':
        $id = (int) ($_GET['id'] ?? 0);
        if (!listeSahibiMi($baglan, $id, $uid)) bitir(false, ['error' => 'Liste bulunamadı.']);
        // liste adı
        $stmt = $baglan->prepare('SELECT ad FROM calma_listeleri WHERE id = ?');
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $liste = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        // şarkılar
        $stmt = $baglan->prepare(
            'SELECT m.id, m.sarki_adi, m.sarkici, m.yol, m.kapak
             FROM liste_sarkilari ls
             JOIN muzik m ON m.id = ls.muzik_id
             WHERE ls.liste_id = ?
             ORDER BY ls.eklenme'
        );
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $res = $stmt->get_result();
        $rows = [];
        while ($r = $res->fetch_assoc()) $rows[] = $r;
        $stmt->close();
        bitir(true, ['ad' => $liste['ad'] ?? '', 'sarkilar' => $rows]);

    default:
        bitir(false, ['error' => 'Geçersiz işlem.']);
}
