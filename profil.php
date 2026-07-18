<?php
session_start();
$girisli = isset($_SESSION['kullanici_id']);
$kullanici_adi = $girisli ? $_SESSION['kullanici_adi'] : '';
$ad     = $girisli ? $_SESSION['ad']    : '';
$soyad  = $girisli ? $_SESSION['soyad'] : '';
$email  = $girisli ? $_SESSION['email'] : '';
$bashar = $girisli ? mb_strtoupper(mb_substr($kullanici_adi, 0, 1, 'UTF-8'), 'UTF-8') : '?';
function g($s){ return htmlspecialchars($s, ENT_QUOTES, 'UTF-8'); }
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
      <div class="who">
        <div class="uname"><?= g($ad . ' ' . $soyad) ?></div>
        <div class="mail">@<?= g($kullanici_adi) ?> · <?= g($email) ?></div>
      </div>
    </div>
    <div class="profile-rows">
      <div class="row"><div class="k">Kullanıcı Adı</div><div class="v"><?= g($kullanici_adi) ?></div></div>
      <div class="row"><div class="k">Ad</div><div class="v"><?= g($ad) ?></div></div>
      <div class="row"><div class="k">Soyad</div><div class="v"><?= g($soyad) ?></div></div>
      <div class="row"><div class="k">E-Posta</div><div class="v"><?= g($email) ?></div></div>
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
</body>
</html>
