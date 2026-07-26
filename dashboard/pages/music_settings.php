<?php include __DIR__ . '/../includes/header.php'; ?>
<div class="dash-head">
  <div>
    <h1>Müzik Ekle</h1>
    <p class="sub">Yeni bir şarkıyı bilgileriyle birlikte yükle</p>
  </div>
  <button id="add-music-btn" class="btn btn-ghost">Formu Aç / Kapat</button>
</div>

<div id="add-music-form" class="card" style="max-width:680px">
  <form action="../add_music.php" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="MAX_FILE_SIZE" value="10485760">
    <div class="form-grid">
      <div class="field">
        <label>Sanatçı</label>
        <input type="text" name="sarkici" placeholder="Sanatçı adı" required>
      </div>
      <div class="field">
        <label>Şarkı Adı</label>
        <input type="text" name="sarki_adi" placeholder="Şarkı adı" required>
      </div>
      <div class="field">
        <label>Albüm</label>
        <input type="text" name="album" placeholder="Albüm (opsiyonel)">
      </div>
      <div class="field">
        <label>Tür</label>
        <input type="text" name="turu" placeholder="Pop, Rap… (opsiyonel)">
      </div>
    </div>
    <div class="field">
      <label>Kapak Görseli</label>
      <input type="file" name="kapak" accept="image/*">
    </div>
    <div class="field">
      <label>Müzik Dosyası (.mp3)</label>
      <input type="file" name="yol" accept="audio/*">
    </div>
    <div class="form-actions">
      <button type="submit" class="btn btn-primary">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M11 5h2v6h6v2h-6v6h-2v-6H5v-2h6z"/></svg>
        Ekle
      </button>
    </div>
  </form>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
