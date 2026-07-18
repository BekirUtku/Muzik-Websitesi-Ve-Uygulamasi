<?php $id = isset($_GET['id']) ? (int) $_GET['id'] : 0; ?>
<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Çalma Listesi — AUBE MUSIC</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="aube.css">
</head>
<body data-playlist-id="<?= $id ?>">

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
      <a href="profil.php">Profil</a>
      <a href="cikis.php">Çıkış Yap</a>
    </div>
    <form class="search" id="search-form" action="#" method="get">
      <span id="search-icon"></span>
      <input type="text" name="search" placeholder="Şarkı veya sanatçı ara...">
    </form>
  </nav>

  <section class="hero" style="display:flex;align-items:center;gap:20px;">
    <div class="pic" style="width:96px;height:96px;border-radius:16px;background:linear-gradient(135deg,var(--accent),#37d67a);display:grid;place-items:center;color:#04120a;flex:0 0 auto;">
      <svg width="42" height="42" viewBox="0 0 24 24" fill="currentColor"><path d="M3 6h13v2H3V6zm0 5h13v2H3v-2zm0 5h9v2H3v-2zm15-5v6.6a2.4 2.4 0 1 1-2-2.36V9l4-1v2z"/></svg>
    </div>
    <div>
      <p style="color:var(--muted);font-size:13px;letter-spacing:1px;text-transform:uppercase;">Çalma Listesi</p>
      <h1 id="pl-name">Yükleniyor…</h1>
      <a href="listelerim.php" style="color:var(--muted);font-size:13px;">&larr; Listelerim</a>
    </div>
  </section>

  <section class="section">
    <div class="section-head"><h2>Şarkılar</h2><span class="count" id="trend-count"></span></div>
    <div class="grid" id="trend-grid"></div>
  </section>

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
