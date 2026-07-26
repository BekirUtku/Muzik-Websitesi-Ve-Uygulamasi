<?php
require __DIR__ . '/../admin_kontrol.php';   // yönetici koruması + $db

// SQL injection'a kapalı: id tam sayıya çevrilir + prepared statement
$music_id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$stmt = $db->prepare("SELECT * FROM muzik WHERE id = ?");
$stmt->bind_param('i', $music_id);
$stmt->execute();
$row = $stmt->get_result()->fetch_assoc();
$stmt->close();

include __DIR__ . '/../includes/header.php';
function h($s){ return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }
?>
<div class="dash-head">
  <div>
    <h1>Müzik Güncelle</h1>
    <p class="sub"><?= $row ? h($row['sarki_adi']) : 'Kayıt bulunamadı' ?></p>
  </div>
  <a href="music_list.php" class="btn btn-ghost">← Listeye dön</a>
</div>

<?php if (!$row): ?>
  <div class="card">Bu ID ile bir şarkı bulunamadı.</div>
<?php else: ?>
<div class="card" style="max-width:680px">
  <form action="../update_music.php" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="music_id" value="<?= (int)$row['id'] ?>">
    <div class="form-grid">
      <div class="field">
        <label>Şarkı Adı</label>
        <input type="text" name="sarki_adi" value="<?= h($row['sarki_adi']) ?>" required>
      </div>
      <div class="field">
        <label>Sanatçı</label>
        <input type="text" name="sarkici" value="<?= h($row['sarkici']) ?>" required>
      </div>
      <div class="field">
        <label>Albüm</label>
        <input type="text" name="album" value="<?= h($row['album']) ?>">
      </div>
      <div class="field">
        <label>Tür</label>
        <input type="text" name="turu" value="<?= h($row['turu']) ?>">
      </div>
    </div>
    <div class="field">
      <label>Kapak Görseli (değiştirmek istersen seç)</label>
      <input type="file" name="kapak" accept="image/*">
    </div>
    <div class="field">
      <label>Müzik Dosyası (değiştirmek istersen seç)</label>
      <input type="file" name="yol" accept="audio/*">
    </div>
    <div class="form-actions">
      <a href="music_list.php" class="btn btn-ghost">Vazgeç</a>
      <button type="submit" class="btn btn-info">Güncelle</button>
    </div>
  </form>
</div>
<?php endif; ?>

<?php include __DIR__ . '/../includes/footer.php'; $db->close(); ?>
