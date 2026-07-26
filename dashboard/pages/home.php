<?php
include __DIR__ . '/../config/database.php';

$artist_count = $db->query("SELECT COUNT(DISTINCT sarkici) AS c FROM muzik")->fetch_assoc()['c'];
$song_count   = $db->query("SELECT COUNT(id) AS c FROM muzik")->fetch_assoc()['c'];
$album_count  = $db->query("SELECT COUNT(DISTINCT album) AS c FROM muzik WHERE album IS NOT NULL AND album<>''")->fetch_assoc()['c'];

include __DIR__ . '/../includes/header.php';
?>
<div class="dash-head">
  <div>
    <h1>Genel Bakış</h1>
    <p class="sub">AUBE MUSIC yönetim paneli</p>
  </div>
  <a href="music_settings.php" class="btn btn-primary">
    <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M11 5h2v6h6v2h-6v6h-2v-6H5v-2h6z"/></svg>
    Yeni Müzik Ekle
  </a>
</div>

<div class="stats">
  <div class="stat">
    <div class="ic"><svg width="22" height="22" viewBox="0 0 24 24" fill="currentColor"><path d="M12 12a5 5 0 1 0-5-5 5 5 0 0 0 5 5zm0 2c-4 0-8 2-8 5v1h16v-1c0-3-4-5-8-5z"/></svg></div>
    <div><div class="num"><?= (int)$artist_count ?></div><div class="lbl">Sanatçı</div></div>
  </div>
  <div class="stat">
    <div class="ic"><svg width="22" height="22" viewBox="0 0 24 24" fill="currentColor"><path d="M12 3v10.6A4 4 0 1 0 14 17V7h4V3z"/></svg></div>
    <div><div class="num"><?= (int)$song_count ?></div><div class="lbl">Şarkı</div></div>
  </div>
  <div class="stat">
    <div class="ic"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="9"/><circle cx="12" cy="12" r="2.5"/></svg></div>
    <div><div class="num"><?= (int)$album_count ?></div><div class="lbl">Albüm</div></div>
  </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; $db->close(); ?>
