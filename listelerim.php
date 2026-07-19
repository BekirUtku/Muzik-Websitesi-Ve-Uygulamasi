<?php session_start(); $girisli = isset($_SESSION['kullanici_id']); ?>
<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Listelerim — AUBE MUSIC</title>
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
      <a href="profil.php">Profil</a>
      <a href="cikis.php">Çıkış Yap</a>
    </div>
    <form class="search" id="search-form" action="#" method="get">
      <span id="search-icon"></span>
      <input type="text" name="search" placeholder="Şarkı veya sanatçı ara...">
    </form>
  </nav>

  <section class="hero">
    <h1>Çalma Listelerim</h1>
    <p>Kendi listelerini oluştur, şarkı ekleyip dilediğin gibi dinle.</p>
  </section>

  <?php if (!$girisli): ?>
    <section class="section">
      <div class="notice">Çalma listeleri için <a href="login.php">giriş yapın</a>.</div>
    </section>
  <?php else: ?>
    <section class="section">
      <div class="section-head"><h2>Yeni Liste</h2></div>
      <div style="display:flex;gap:10px;max-width:460px;">
        <input id="new-name" type="text" placeholder="Liste adı (ör. Yolculuk)"
               style="flex:1;background:var(--panel);border:1px solid var(--border);color:var(--text);padding:12px 14px;border-radius:11px;outline:none;">
        <button class="btn-primary" id="create-btn" style="margin-top:0;width:auto;padding:12px 20px;">Oluştur</button>
      </div>
      <div class="form-msg" id="lm-msg" style="max-width:460px;margin-top:12px;"></div>
    </section>

    <section class="section">
      <div class="section-head"><h2>Listelerim</h2><span class="count" id="mine-count"></span></div>
      <div class="artist-grid" id="my-lists"></div>
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

  <?php if ($girisli): ?>
  <script>
  (function () {
    var mine = document.getElementById('my-lists');
    var msg = document.getElementById('lm-msg');
    function esc(s){ return String(s==null?'':s).replace(/[&<>"]/g,function(c){return {'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;'}[c];}); }

    function load() {
      fetch('playlist.php?action=benim').then(function(r){return r.json();}).then(function(d){
        mine.innerHTML = '';
        if (!d.ok) { mine.innerHTML = '<div class="empty">'+esc(d.error||'Hata')+'</div>'; return; }
        document.getElementById('mine-count').textContent = d.listeler.length + ' liste';
        if (!d.listeler.length) { mine.innerHTML = '<div class="empty">Henüz listen yok. Yukarıdan oluştur.</div>'; return; }
        d.listeler.forEach(function(l){
          var card = document.createElement('a');
          card.className = 'artist-card';
          card.href = 'liste.php?id=' + l.id;   // SPA gezinmesi yakalar, müzik kesilmez
          card.style.cursor = 'pointer';
          card.innerHTML =
            '<div class="avatar" style="display:grid;place-items:center;background:var(--panel-2);color:var(--accent);">' +
              '<svg width="42" height="42" viewBox="0 0 24 24" fill="currentColor"><path d="M3 6h13v2H3V6zm0 5h13v2H3v-2zm0 5h9v2H3v-2zm15-5v6.6a2.4 2.4 0 1 1-2-2.36V9l4-1v2z"/></svg>' +
            '</div>' +
            '<div class="name">'+esc(l.ad)+'</div>' +
            '<div class="role">'+l.adet+' şarkı</div>' +
            '<button class="btn-ghost del" style="margin-top:10px;padding:6px 14px;">Sil</button>';
          card.querySelector('.del').addEventListener('click', function(e){
            e.preventDefault(); e.stopPropagation();
            if (!confirm('"'+l.ad+'" listesini silmek istiyor musun?')) return;
            var fd = new FormData(); fd.append('action','sil'); fd.append('id', l.id);
            fetch('playlist.php', {method:'POST', body:fd}).then(function(r){return r.json();}).then(function(){ load(); });
          });
          mine.appendChild(card);
        });
      });
    }

    document.getElementById('create-btn').addEventListener('click', function(){
      var ad = document.getElementById('new-name').value.trim();
      msg.className = 'form-msg';
      if (!ad) { msg.className='form-msg err'; msg.textContent='Liste adı gir.'; return; }
      var fd = new FormData(); fd.append('action','olustur'); fd.append('ad', ad);
      fetch('playlist.php', {method:'POST', body:fd}).then(function(r){return r.json();}).then(function(d){
        if (!d.ok) { msg.className='form-msg err'; msg.textContent=d.error||'Hata'; return; }
        document.getElementById('new-name').value='';
        msg.className='form-msg ok'; msg.textContent='"'+d.ad+'" oluşturuldu.';
        load();
      });
    });
    document.getElementById('new-name').addEventListener('keydown', function(e){ if (e.key==='Enter') document.getElementById('create-btn').click(); });

    load();
  })();
  </script>
  <?php endif; ?>

</body>
</html>
