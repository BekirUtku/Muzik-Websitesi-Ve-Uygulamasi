<?php
require __DIR__ . '/../admin_kontrol.php';   // yönetici koruması + $db
$result = $db->query("SELECT id, sarki_adi, turu, yol, album, sarkici, kapak FROM muzik ORDER BY id");
include __DIR__ . '/../includes/header.php';
function h($s){ return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }
?>
<div class="dash-head">
  <div>
    <h1>Müzikler</h1>
    <p class="sub"><?= $result ? (int)$result->num_rows : 0 ?> şarkı</p>
  </div>
  <a href="music_settings.php" class="btn btn-primary">
    <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M11 5h2v6h6v2h-6v6h-2v-6H5v-2h6z"/></svg>
    Yeni Müzik
  </a>
</div>

<div class="music-grid">
<?php if (!$result || $result->num_rows === 0): ?>
  <div class="empty">Henüz şarkı yok. "Yeni Müzik" ile ekleyebilirsin.</div>
<?php else: while ($row = $result->fetch_assoc()): ?>
  <div class="music-card">
    <div class="cover">
      <img src="../../<?= h(ltrim($row['kapak'], './')) ?>" alt="" loading="lazy" onerror="this.style.opacity=.25">
    </div>
    <div class="body">
      <h3><?= h($row['sarki_adi']) ?></h3>
      <div class="meta">
        <b>Sanatçı:</b> <?= h($row['sarkici']) ?><br>
        <b>Albüm:</b> <?= h($row['album'] ?: '—') ?><br>
        <b>Tür:</b> <?= h($row['turu'] ?: '—') ?>
      </div>
      <div class="actions">
        <a href="music_update.php?id=<?= (int)$row['id'] ?>" class="btn btn-info">Güncelle</a>
        <form action="../delete_music.php" method="POST" style="flex:1" onsubmit="return confirm('Bu şarkı silinsin mi?');">
          <input type="hidden" name="music_id" value="<?= (int)$row['id'] ?>">
          <button type="submit" class="btn btn-danger" style="width:100%">Sil</button>
        </form>
      </div>
    </div>
  </div>
<?php endwhile; endif; ?>
</div>

<?php include __DIR__ . '/../includes/footer.php'; $db->close(); ?>
