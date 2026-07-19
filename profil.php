<?php
session_start();
require_once __DIR__ . '/db.php';

$girisli = isset($_SESSION['kullanici_id']);
$uye_tarih = '—';
$ad = $soyad = $email = $kullanici_adi = '';

if ($girisli) {
    // Taze veriyi veritabanından çek (düzenlemeden sonra da güncel kalsın)
    $stmt = $baglan->prepare('SELECT ad, soyad, email, kullanici_adi, olusturma FROM kullanicilar WHERE id = ?');
    $stmt->bind_param('i', $_SESSION['kullanici_id']);
    $stmt->execute();
    $u = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    if ($u) {
        $ad = $u['ad']; $soyad = $u['soyad']; $email = $u['email']; $kullanici_adi = $u['kullanici_adi'];
        if (!empty($u['olusturma'])) {
            $uye_tarih = date('d.m.Y', strtotime($u['olusturma']));
        }
    }
}
$bashar = $girisli && $kullanici_adi !== '' ? mb_strtoupper(mb_substr($kullanici_adi, 0, 1, 'UTF-8'), 'UTF-8') : '?';
function g($s){ return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }
?>
<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Profil — AUBE MUSIC</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="aube.css">
</head>
<body>

  <nav class="navbar">
    <a class="brand" href="anasayfaa.html">
      <span class="logo">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M12 3v10.6A4 4 0 1 0 14 17V7h4V3z"/></svg>
      </span>
      AUBE MUSIC
    </a>
    <div class="nav-links">
      <a href="anasayfaa.html">Anasayfa</a>
      <a href="sanatcilar.html">Sanatçılar</a>
      <a href="begenilenler.html">Beğenilenler</a>
      <a href="profil.php" class="active">Profil</a>
      <a href="cikis.php">Çıkış Yap</a>
    </div>
    <form class="search" id="search-form" action="#" method="get">
      <span id="search-icon"></span>
      <input type="text" name="search" placeholder="Şarkı veya sanatçı ara...">
    </form>
  </nav>

  <section class="section" style="margin-top:26px">
  <?php if ($girisli): ?>
    <div class="profile-card">
      <div class="pic"><?= g($bashar) ?></div>
      <div class="who profile-head" style="flex:1">
        <div>
          <div class="uname" id="pc-name"><?= g(trim($ad.' '.$soyad)) ?></div>
          <div class="mail">@<span id="pc-uname"><?= g($kullanici_adi) ?></span> · <span id="pc-mail"><?= g($email) ?></span></div>
        </div>
        <button class="btn-ghost" id="btn-edit">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4z"/></svg>
          Profili Düzenle
        </button>
      </div>
    </div>

    <!-- İstatistikler -->
    <div class="stats">
      <div class="stat">
        <div class="ic"><svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M12 21s-7.5-4.6-10-9.2C.6 8.5 2.2 5 5.5 5 7.7 5 9 6.3 12 9c3-2.7 4.3-4 6.5-4C21.8 5 23.4 8.5 22 11.8 19.5 16.4 12 21 12 21z"/></svg></div>
        <div><div class="num" id="stat-likes">0</div><div class="lbl">Beğenilen şarkı</div></div>
      </div>
      <div class="stat">
        <div class="ic"><svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M8 5v14l11-7z"/></svg></div>
        <div><div class="num" id="stat-recent">0</div><div class="lbl">Son dinlenen</div></div>
      </div>
      <div class="stat">
        <div class="ic"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg></div>
        <div><div class="num" style="font-size:18px"><?= g($uye_tarih) ?></div><div class="lbl">Üyelik tarihi</div></div>
      </div>
    </div>
  <?php else: ?>
    <div class="notice">Oturum açmadınız. Lütfen <a href="login.php">giriş yapın</a>.</div>
  <?php endif; ?>
  </section>

  <?php if ($girisli): ?>
  <section class="section">
    <div class="section-head"><h2>Beğendiklerin</h2><span class="count" id="liked-count"></span></div>
    <div class="grid" id="liked-grid"></div>
  </section>
  <section class="section">
    <div class="section-head"><h2>Son Dinlediklerin</h2></div>
    <div class="grid" id="recent-grid"></div>
  </section>

  <!-- Düzenleme modalı -->
  <div class="modal-overlay" id="edit-modal">
    <div class="modal">
      <div class="modal-head">
        <h3>Profili Düzenle</h3>
        <button class="x" id="modal-close" title="Kapat">&times;</button>
      </div>
      <p class="sub">Bilgilerini güncelle. Şifreni değiştirmek istemiyorsan şifre alanlarını boş bırak.</p>
      <div class="form-msg" id="form-msg"></div>
      <form id="edit-form">
        <div class="field"><label>Ad</label><input type="text" name="ad" value="<?= g($ad) ?>" required></div>
        <div class="field"><label>Soyad</label><input type="text" name="soyad" value="<?= g($soyad) ?>" required></div>
        <div class="field"><label>E-Posta</label><input type="email" name="email" value="<?= g($email) ?>" required></div>
        <div class="field"><label>Kullanıcı Adı</label><input type="text" name="k_adi" value="<?= g($kullanici_adi) ?>" required></div>
        <div class="divider"></div>
        <p class="small">Şifre değiştir (opsiyonel)</p>
        <div class="field"><label>Mevcut Şifre</label><input type="password" name="mevcut_sifre" placeholder="••••••••" autocomplete="current-password"></div>
        <div class="field"><label>Yeni Şifre</label><input type="password" name="yeni_sifre" placeholder="en az 6 karakter" autocomplete="new-password"></div>
        <div class="btn-row">
          <button type="button" class="btn-ghost" id="modal-cancel">Vazgeç</button>
          <button type="submit" class="btn-primary" id="save-btn">Kaydet</button>
        </div>
      </form>
    </div>
  </div>
  <?php endif; ?>

  <!-- Çalar -->
  <div class="player">
    <div class="now">
      <img id="p-cover" src="kapaklar/Ayrı_Gitme.jpg" alt="">
      <div style="min-width:0">
        <div class="t" id="p-title">Bir şarkı seç</div>
        <div class="a" id="p-artist">AUBE MUSIC</div>
      </div>
      <button class="plike" id="p-like" title="Beğen"></button>
    </div>
    <div class="center">
      <div class="buttons">
        <button id="p-shuffle" class="sh" title="Karışık"></button>
        <button id="p-prev" title="Önceki"></button>
        <button id="p-play" class="play" title="Oynat/Duraklat"></button>
        <button id="p-next" title="Sonraki"></button>
      </div>
      <div class="progress">
        <span class="time" id="p-cur">0:00</span>
        <div class="bar" id="p-bar"><div class="fill" id="p-fill"></div></div>
        <span class="time" id="p-dur">0:00</span>
      </div>
    </div>
    <div class="right"></div>
  </div>

  <audio id="audio-player" preload="metadata"></audio>
  <script src="app.js"></script>

  <?php if ($girisli): ?>
  <script>
  (function () {
    // İstatistikleri tarayıcı hafızasından doldur
    function stats() {
      var likes = 0, recent = 0;
      try { likes = (JSON.parse(localStorage.getItem('aube_likes')) || []).length; } catch (e) {}
      try { recent = (JSON.parse(localStorage.getItem('aube_recent')) || []).length; } catch (e) {}
      document.getElementById('stat-likes').textContent = likes;
      document.getElementById('stat-recent').textContent = recent;
    }
    stats();
    // Beğeni değişince (aynı sekmede) güncellensin diye periyodik tazele.
    // Sayfa içi gezinmede tekrar çalıştığı için önceki zamanlayıcıyı temizle.
    if (window.__profilStatsTimer) clearInterval(window.__profilStatsTimer);
    window.__profilStatsTimer = setInterval(function () {
      if (!document.getElementById('stat-likes')) { clearInterval(window.__profilStatsTimer); return; }
      stats();
    }, 1500);

    var modal = document.getElementById('edit-modal');
    var msg = document.getElementById('form-msg');
    function openM() { msg.className = 'form-msg'; msg.textContent = ''; modal.classList.add('open'); }
    function closeM() { modal.classList.remove('open'); }

    document.getElementById('btn-edit').addEventListener('click', openM);
    document.getElementById('modal-close').addEventListener('click', closeM);
    document.getElementById('modal-cancel').addEventListener('click', closeM);
    modal.addEventListener('click', function (e) { if (e.target === modal) closeM(); });

    var form = document.getElementById('edit-form');
    form.addEventListener('submit', function (e) {
      e.preventDefault();
      var btn = document.getElementById('save-btn');
      btn.disabled = true; btn.textContent = 'Kaydediliyor...';
      msg.className = 'form-msg';

      fetch('profil_guncelle.php', { method: 'POST', body: new FormData(form) })
        .then(function (r) { return r.json(); })
        .then(function (d) {
          btn.disabled = false; btn.textContent = 'Kaydet';
          if (!d.ok) { msg.className = 'form-msg err'; msg.textContent = d.error || 'Hata oluştu.'; return; }
          var u = d.user;
          document.getElementById('pc-name').textContent = (u.ad + ' ' + u.soyad).trim();
          document.getElementById('pc-uname').textContent = u.kullanici_adi;
          document.getElementById('pc-mail').textContent = u.email;
          document.querySelector('.profile-card .pic').textContent = (u.kullanici_adi.charAt(0) || '?').toUpperCase();
          msg.className = 'form-msg ok';
          msg.textContent = d.sifre_degisti ? 'Kaydedildi. Şifren de güncellendi.' : 'Bilgilerin kaydedildi.';
          form.querySelector('[name=mevcut_sifre]').value = '';
          form.querySelector('[name=yeni_sifre]').value = '';
          setTimeout(closeM, 1100);
        })
        .catch(function () {
          btn.disabled = false; btn.textContent = 'Kaydet';
          msg.className = 'form-msg err'; msg.textContent = 'Sunucuya ulaşılamadı.';
        });
    });
  })();
  </script>
  <?php endif; ?>

</body>
</html>
