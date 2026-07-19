/* ===== AUBE MUSIC — anasayfa uygulaması ===== */
(function () {
  'use strict';

  // ---- İkonlar ----
  const ICON = {
    play: '<svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M8 5v14l11-7z"/></svg>',
    pause: '<svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M6 5h4v14H6zM14 5h4v14h-4z"/></svg>',
    prev: '<svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M6 6h2v12H6zm3.5 6L18 6v12z"/></svg>',
    next: '<svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M16 6h2v12h-2zM6 6l8.5 6L6 18z"/></svg>',
    shuffle: '<svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M17 3l4 4-4 4V8h-2.2l-2.6 3.3 2.6 3.3H17v-3l4 4-4 4v-3h-3.2l-2.6-3.3L8.4 8H3V6h5.6l1.8 2.3L12.8 6H17V3zM3 16h3.2l1.9-2.4 1.3 1.6L7.9 18H3v-2z"/></svg>',
    heart: '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 21s-7.5-4.6-10-9.2C.6 8.5 2.2 5 5.5 5 7.7 5 9 6.3 12 9c3-2.7 4.3-4 6.5-4C21.8 5 23.4 8.5 22 11.8 19.5 16.4 12 21 12 21z"/></svg>',
    search: '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="7"/><path d="M21 21l-4.3-4.3"/></svg>',
    repeat: '<svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M7 7h10v3l4-4-4-4v3H5v6h2V7zm10 10H7v-3l-4 4 4 4v-3h12v-6h-2v4z"/></svg>',
    queue: '<svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M3 6h13v2H3V6zm0 5h13v2H3v-2zm0 5h9v2H3v-2zm15-5v6.6a2.4 2.4 0 1 1-2-2.36V9l4-1v2z"/></svg>',
    volume: '<svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M4 9v6h4l5 5V4L8 9H4zm12 3a3 3 0 0 0-2-2.8v5.6A3 3 0 0 0 16 12zm-2-7.7v2.1a5 5 0 0 1 0 9.2v2.1a7 7 0 0 0 0-13.4z"/></svg>',
    mute: '<svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M4 9v6h4l5 5V4L8 9H4zm15.5 3l2.3-2.3-1.4-1.4L18 10.6 15.7 8.3l-1.4 1.4L16.6 12l-2.3 2.3 1.4 1.4L18 13.4l2.3 2.3 1.4-1.4L19.5 12z"/></svg>',
    more: '<svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><circle cx="12" cy="5" r="2"/><circle cx="12" cy="12" r="2"/><circle cx="12" cy="19" r="2"/></svg>',
    plusList: '<svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M3 6h12v2H3V6zm0 4h12v2H3v-2zm0 4h8v2H3v-2zm14-4h2v3h3v2h-3v3h-2v-3h-3v-2h3v-3z"/></svg>',
    plusQueue: '<svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M3 6h10v2H3V6zm0 4h10v2H3v-2zm0 4h6v2H3v-2zm12-1h3v-3h2v3h3v2h-3v3h-2v-3h-3v-2z"/></svg>',
    trash: '<svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M6 7h12l-1 14H7L6 7zm3-3h6l1 2H8l1-2z"/></svg>'
  };

  const LIKES_KEY = 'aube_likes';
  const RECENT_KEY = 'aube_recent';

  let SONGS = [];
  let curId = null;
  let shuffle = false;
  let repeatMode = localStorage.getItem('aube_repeat') || 'off'; // off | all | one
  let QUEUE = [];            // manuel kuyruk (şarkı id'leri)
  let girisli = false;       // playlist özelliği için oturum durumu

  const $ = (s, r = document) => r.querySelector(s);
  const esc = (s) => String(s == null ? '' : s).replace(/[&<>"]/g, c => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;' }[c]));
  const byId = (id) => SONGS.find(s => String(s.id) === String(id));

  // Elimizdeki gerçek sanatçı fotoğrafları (varsa kullanılır)
  const ARTIST_PHOTO = {
    'burak bulut': 'sanatcilar/Burak_Bulut.jpg',
    'melis kar': 'sanatcilar/Melis_Kar.jpg',
    'mustafa ceceli': 'sanatcilar/mustafa ceceli.jpg',
    'mavi gri': 'sanatcilar/Mavi_Gri.jpg',
    'hande yener': 'sanatcilar/Hande_Yener.jpg',
    'buray': 'sanatcilar/Buray.jpg',
    'melis fis': 'sanatcilar/Melis_Fis.jpg'
  };

  // "A & B", "A x B", "A feat. B", "A, B" -> ['A','B'] (Bilinmeyen atılır)
  function splitArtists(s) {
    if (!s) return [];
    return s.split(/\s*&\s*|\s*,\s*|\s+feat\.?\s+|\s+ft\.?\s+|\s+[xX]\s+/)
      .map(a => a.trim())
      .filter(a => a && a.toLowerCase() !== 'bilinmeyen');
  }

  // data-artist (statik) veya ?ad= (dinamik) sanatçı sayfası filtresi
  function getArtistFilter() {
    const d = document.body.getAttribute('data-artist');
    if (d) return d;
    const q = new URLSearchParams(location.search).get('ad');
    return q ? q : '';
  }

  function artistPhoto(name, fallbackCover) {
    return ARTIST_PHOTO[String(name).toLowerCase()] || fallbackCover || 'kapaklar/Ayrı_Gitme.jpg';
  }

  // Sanatçılar sayfası: veritabanındaki şarkılardan sanatçı listesini üret
  function renderArtists() {
    const cont = $('#artist-list');
    if (!cont) return;
    const map = new Map();
    SONGS.forEach(s => splitArtists(s.sarkici).forEach(name => {
      if (!map.has(name)) map.set(name, { name, cover: s.kapak, count: 0 });
      map.get(name).count++;
    }));
    const arr = [...map.values()].sort((a, b) => b.count - a.count || a.name.localeCompare(b.name, 'tr'));
    cont.innerHTML = '';
    arr.forEach(a => {
      const card = document.createElement('a');
      card.className = 'artist-card';
      card.href = 'sanatci.html?ad=' + encodeURIComponent(a.name);
      card.innerHTML =
        '<img class="avatar" src="' + esc(artistPhoto(a.name, a.cover)) + '" alt="" loading="lazy">' +
        '<div class="name">' + esc(a.name) + '</div>' +
        '<div class="role">' + a.count + ' şarkı</div>';
      cont.appendChild(card);
    });
    const c = $('#artist-count'); if (c) c.textContent = arr.length + ' sanatçı';
  }

  // ---- localStorage yardımcıları ----
  const getLikes = () => { try { return JSON.parse(localStorage.getItem(LIKES_KEY)) || []; } catch (e) { return []; } };
  const setLikes = (a) => localStorage.setItem(LIKES_KEY, JSON.stringify(a));
  const isLiked = (id) => getLikes().map(String).includes(String(id));
  function toggleLike(id) {
    let a = getLikes().map(String);
    if (a.includes(String(id))) a = a.filter(x => x !== String(id));
    else a.unshift(String(id));
    setLikes(a);
    syncHearts(id);
    renderLiked();
  }

  const getRecent = () => { try { return JSON.parse(localStorage.getItem(RECENT_KEY)) || []; } catch (e) { return []; } };
  function pushRecent(id) {
    let a = getRecent().map(String).filter(x => x !== String(id));
    a.unshift(String(id));
    a = a.slice(0, 12);
    localStorage.setItem(RECENT_KEY, JSON.stringify(a));
    renderRecent();
  }

  // ---- Kart oluşturma ----
  function makeCard(song) {
    const card = document.createElement('div');
    card.className = 'card';
    card.dataset.id = song.id;
    if (String(song.id) === String(curId)) card.classList.add('playing');
    card.innerHTML =
      '<div class="cover">' +
        '<img src="' + esc(song.kapak) + '" alt="" loading="lazy">' +
        '<button class="like-btn' + (isLiked(song.id) ? ' liked' : '') + '" title="Beğen">' + ICON.heart + '</button>' +
        '<button class="more-btn" title="Daha fazla">' + ICON.more + '</button>' +
        '<button class="play-ov" title="Çal">' + ICON.play + '</button>' +
      '</div>' +
      '<div class="meta"><div class="t">' + esc(song.sarki_adi) + '</div><div class="a">' + esc(song.sarkici) + '</div></div>';

    card.querySelector('.like-btn').addEventListener('click', (e) => { e.stopPropagation(); toggleLike(song.id); });
    card.querySelector('.more-btn').addEventListener('click', (e) => { e.stopPropagation(); openCardMenu(e.currentTarget, song.id); });
    card.addEventListener('click', () => playSong(song.id));
    return card;
  }

  function fillGrid(container, songs, emptyText) {
    if (!container) return;
    container.innerHTML = '';
    if (!songs.length) {
      const d = document.createElement('div');
      d.className = 'empty';
      d.textContent = emptyText;
      container.appendChild(d);
      return;
    }
    songs.forEach(s => container.appendChild(makeCard(s)));
  }

  function renderTrend(list) {
    const l = list || SONGS;
    fillGrid($('#trend-grid'), l, 'Bu sanatçının şu an listede şarkısı yok.');
    const t = $('#trend-count'); if (t) t.textContent = l.length + ' şarkı';
    const lc = $('#liked-count'); if (lc) lc.textContent = getLikes().length + ' şarkı';
  }
  function renderRecent() {
    const songs = getRecent().map(byId).filter(Boolean);
    fillGrid($('#recent-grid'), songs, 'Henüz şarkı dinlemedin. Bir kapağa tıkla, burada birikssin.');
    highlightPlaying();
  }
  function renderLiked() {
    const songs = getLikes().map(byId).filter(Boolean);
    fillGrid($('#liked-grid'), songs, 'Henüz beğenin yok. Kapakların üstündeki ♥ butonuna bas.');
    highlightPlaying();
    updateCounts();
  }

  function updateCounts() {
    const t = $('#trend-count'); if (t) t.textContent = SONGS.length + ' şarkı';
    const l = $('#liked-count'); if (l) l.textContent = getLikes().length + ' şarkı';
  }

  // Beğeni butonlarını tüm grid'lerde ve çalarda eşitle
  function syncHearts(id) {
    const liked = isLiked(id);
    document.querySelectorAll('.card[data-id="' + CSS.escape(String(id)) + '"] .like-btn')
      .forEach(b => b.classList.toggle('liked', liked));
    const plike = $('#p-like');
    if (plike && String(curId) === String(id)) plike.classList.toggle('liked', liked);
  }

  function highlightPlaying() {
    document.querySelectorAll('.card').forEach(c => c.classList.toggle('playing', String(c.dataset.id) === String(curId)));
  }

  // ---- Çalar ----
  const audio = $('#audio-player');

  function playSong(id) {
    const s = byId(id);
    if (!s) return;
    curId = id;
    audio.src = s.yol;
    const p = audio.play();
    if (p && p.catch) p.catch(() => {});
    $('#p-cover').src = s.kapak;
    $('#p-title').textContent = s.sarki_adi;
    $('#p-artist').textContent = s.sarkici;
    const plike = $('#p-like');
    if (plike) plike.classList.toggle('liked', isLiked(id));
    setPlayIcon(true);
    pushRecent(id);
    highlightPlaying();
    // Dinleme geçmişini sunucudaki "gecmis" tablosuna da yaz (isteğe bağlı)
    fetch('deneme.php?index=' + encodeURIComponent(id)).catch(() => {});
  }

  function currentIndex() { return SONGS.findIndex(s => String(s.id) === String(curId)); }
  function playNext() {
    // Önce manuel kuyruk
    if (QUEUE.length) { const id = QUEUE.shift(); renderQueue(); playSong(id); return; }
    if (!SONGS.length) return;
    let i;
    if (shuffle) {
      do { i = Math.floor(Math.random() * SONGS.length); } while (SONGS.length > 1 && i === currentIndex());
    } else {
      i = currentIndex();
      if (i < 0) i = 0;
      else if (i >= SONGS.length - 1) { if (repeatMode === 'all') i = 0; else return; }
      else i = i + 1;
    }
    playSong(SONGS[i].id);
  }
  function playPrev() {
    if (!SONGS.length) return;
    let i = currentIndex();
    i = (i <= 0) ? SONGS.length - 1 : i - 1;
    playSong(SONGS[i].id);
  }
  function togglePlay() {
    if (!audio.src) { if (SONGS.length) playSong(SONGS[0].id); return; }
    if (audio.paused) { audio.play(); setPlayIcon(true); }
    else { audio.pause(); setPlayIcon(false); }
  }
  function setPlayIcon(playing) {
    const b = $('#p-play'); if (b) b.innerHTML = playing ? ICON.pause : ICON.play;
  }

  const fmt = (s) => { s = Math.floor(s || 0); return Math.floor(s / 60) + ':' + String(s % 60).padStart(2, '0'); };

  // ===== Ek özellikler: tekrar, volume, kuyruk, kart menüsü, playlist =====
  function toast(msg) {
    let t = $('#aube-toast');
    if (!t) { t = document.createElement('div'); t.id = 'aube-toast'; t.className = 'toast'; document.body.appendChild(t); }
    t.textContent = msg; t.classList.add('show');
    clearTimeout(t._to); t._to = setTimeout(() => t.classList.remove('show'), 1800);
  }

  // ---- Kuyruk ----
  function addToQueue(id) { QUEUE.push(String(id)); renderQueue(); const s = byId(id); toast((s ? s.sarki_adi : 'Şarkı') + ' kuyruğa eklendi'); }
  function renderQueue() {
    const c = $('#queue-list'); if (!c) return;
    c.innerHTML = '';
    if (!QUEUE.length) { c.innerHTML = '<div class="q-empty">Kuyruk boş. Bir kartın ⋮ menüsünden "Sıraya ekle" de.</div>'; return; }
    QUEUE.forEach((id, idx) => {
      const s = byId(id); if (!s) return;
      const el = document.createElement('div');
      el.className = 'q-item';
      el.innerHTML = '<img src="' + esc(s.kapak) + '"><div style="min-width:0"><div class="qi-t">' + esc(s.sarki_adi) + '</div><div class="qi-a">' + esc(s.sarkici) + '</div></div><button class="qi-x" title="Kaldır">' + ICON.trash + '</button>';
      el.querySelector('.qi-x').addEventListener('click', (e) => { e.stopPropagation(); QUEUE.splice(idx, 1); renderQueue(); });
      el.addEventListener('click', () => { QUEUE.splice(idx, 1); renderQueue(); playSong(id); });
      c.appendChild(el);
    });
  }
  function toggleQueue() { const p = $('#queue-panel'); if (p) { p.classList.toggle('open'); renderQueue(); } }

  // ---- Kart menüsü (⋮) ----
  function closeMenus() { document.querySelectorAll('.popup.open').forEach(p => p.classList.remove('open')); }
  function openCardMenu(btn, songId) {
    closeMenus();
    let menu = $('#card-menu');
    if (!menu) { menu = document.createElement('div'); menu.id = 'card-menu'; menu.className = 'popup'; document.body.appendChild(menu); }
    menu.innerHTML = '';
    const add = (label, icon, fn) => {
      const b = document.createElement('button');
      b.innerHTML = icon + '<span style="margin-left:8px">' + label + '</span>';
      b.style.display = 'flex'; b.style.alignItems = 'center';
      b.addEventListener('click', () => { closeMenus(); fn(); });
      menu.appendChild(b);
    };
    add('Sıraya ekle', ICON.plusQueue, () => addToQueue(songId));
    add('Çalma listesine ekle', ICON.plusList, () => openPlaylistModal(songId));
    const r = btn.getBoundingClientRect();
    menu.style.top = (window.scrollY + r.bottom + 6) + 'px';
    menu.style.left = (window.scrollX + Math.min(r.left, window.innerWidth - 210)) + 'px';
    menu.classList.add('open');
  }
  document.addEventListener('click', (e) => { if (!e.target.closest('#card-menu') && !e.target.closest('.more-btn')) closeMenus(); });

  // ---- Çalma listesine ekle modalı ----
  function openPlaylistModal(songId) {
    let ov = $('#pl-modal');
    if (!ov) {
      ov = document.createElement('div'); ov.id = 'pl-modal'; ov.className = 'modal-overlay';
      ov.innerHTML =
        '<div class="modal"><div class="modal-head"><h3>Çalma listesine ekle</h3><button class="x" id="pl-x">&times;</button></div>' +
        '<div class="form-msg" id="pl-msg"></div>' +
        '<div id="pl-list" style="margin-bottom:14px"></div>' +
        '<div class="divider"></div>' +
        '<div class="field"><label>Yeni liste oluştur</label><input type="text" id="pl-new" placeholder="Liste adı"></div>' +
        '<button class="btn-primary" id="pl-create">Oluştur ve ekle</button></div>';
      document.body.appendChild(ov);
      ov.addEventListener('click', (e) => { if (e.target === ov) ov.classList.remove('open'); });
      ov.querySelector('#pl-x').addEventListener('click', () => ov.classList.remove('open'));
    }
    ov._songId = songId;
    $('#pl-msg').className = 'form-msg'; $('#pl-new').value = '';
    ov.classList.add('open');

    fetch('playlist.php?action=benim').then(r => r.json()).then(d => {
      const box = $('#pl-list'); box.innerHTML = '';
      if (!d.ok) { box.innerHTML = '<div class="q-empty">' + esc(d.error || 'Hata') + ' <a href="login.php" style="color:var(--accent)">Giriş</a></div>'; return; }
      if (!d.listeler.length) box.innerHTML = '<div class="q-empty">Henüz listen yok. Aşağıdan oluştur.</div>';
      d.listeler.forEach(l => {
        const b = document.createElement('button'); b.className = 'btn-ghost';
        b.style.width = '100%'; b.style.justifyContent = 'space-between'; b.style.marginBottom = '8px';
        b.innerHTML = '<span>' + esc(l.ad) + '</span><span style="color:var(--muted);font-size:12px">' + l.adet + ' şarkı</span>';
        b.addEventListener('click', () => addSongToPlaylist(l.id, ov._songId));
        box.appendChild(b);
      });
    });
    $('#pl-create').onclick = function () {
      const ad = $('#pl-new').value.trim();
      if (!ad) { $('#pl-msg').className = 'form-msg err'; $('#pl-msg').textContent = 'Liste adı gir.'; return; }
      const fd = new FormData(); fd.append('action', 'olustur'); fd.append('ad', ad);
      fetch('playlist.php', { method: 'POST', body: fd }).then(r => r.json()).then(d => {
        if (!d.ok) { $('#pl-msg').className = 'form-msg err'; $('#pl-msg').textContent = d.error || 'Hata'; return; }
        addSongToPlaylist(d.id, ov._songId);
      });
    };
  }
  function addSongToPlaylist(listeId, songId) {
    const fd = new FormData(); fd.append('action', 'ekle'); fd.append('liste_id', listeId); fd.append('muzik_id', songId);
    fetch('playlist.php', { method: 'POST', body: fd }).then(r => r.json()).then(d => {
      const ov = $('#pl-modal');
      if (!d.ok) { if ($('#pl-msg')) { $('#pl-msg').className = 'form-msg err'; $('#pl-msg').textContent = d.error || 'Hata'; } return; }
      if (ov) ov.classList.remove('open');
      toast(d.eklendi ? 'Listeye eklendi' : 'Şarkı zaten listede');
    });
  }

  // ---- Player'a ek kontrolleri enjekte et ----
  function applyRepeatUI() {
    const rep = $('#p-repeat'); if (!rep) return;
    rep.classList.toggle('on', repeatMode !== 'off');
    rep.classList.toggle('one', repeatMode === 'one');
  }
  function injectControls() {
    const buttons = document.querySelector('.player .buttons');
    if (buttons) {
      const rep = document.createElement('button');
      rep.id = 'p-repeat'; rep.className = 'rep'; rep.title = 'Tekrar';
      rep.innerHTML = ICON.repeat + '<span class="one">1</span>';
      buttons.insertBefore(rep, buttons.firstChild);
      applyRepeatUI();
      rep.addEventListener('click', () => {
        repeatMode = repeatMode === 'off' ? 'all' : repeatMode === 'all' ? 'one' : 'off';
        localStorage.setItem('aube_repeat', repeatMode); applyRepeatUI();
        toast(repeatMode === 'off' ? 'Tekrar kapalı' : repeatMode === 'all' ? 'Listeyi tekrarla' : 'Şarkıyı tekrarla');
      });
      const q = document.createElement('button');
      q.id = 'p-queue'; q.className = 'q'; q.title = 'Kuyruk'; q.innerHTML = ICON.queue;
      buttons.appendChild(q);
      q.addEventListener('click', toggleQueue);
    }
    const right = document.querySelector('.player .right');
    const audioEl = $('#audio-player');
    if (right && audioEl) {
      const vol = document.createElement('div'); vol.className = 'vol';
      vol.innerHTML = '<button id="p-mute" title="Sessize al">' + ICON.volume + '</button><input id="p-vol" type="range" min="0" max="1" step="0.01">';
      right.appendChild(vol);
      let v = parseFloat(localStorage.getItem('aube_vol')); if (isNaN(v)) v = 1;
      audioEl.volume = v; $('#p-vol').value = v;
      $('#p-vol').addEventListener('input', function () {
        audioEl.volume = parseFloat(this.value); localStorage.setItem('aube_vol', this.value);
        $('#p-mute').innerHTML = audioEl.volume === 0 ? ICON.mute : ICON.volume;
      });
      $('#p-mute').addEventListener('click', function () {
        if (audioEl.volume > 0) { audioEl._prev = audioEl.volume; audioEl.volume = 0; $('#p-vol').value = 0; }
        else { audioEl.volume = audioEl._prev || 1; $('#p-vol').value = audioEl.volume; }
        localStorage.setItem('aube_vol', audioEl.volume);
        this.innerHTML = audioEl.volume === 0 ? ICON.mute : ICON.volume;
      });
    }
    if (!$('#queue-panel')) {
      const p = document.createElement('div'); p.id = 'queue-panel'; p.className = 'queue-panel';
      p.innerHTML = '<div class="qh"><h3>Sıradaki Kuyruk</h3><button class="x" id="queue-close">&times;</button></div><div id="queue-list"></div>';
      document.body.appendChild(p);
      $('#queue-close').addEventListener('click', toggleQueue);
    }
  }
  // Navbar her sayfa geçişinde yenilendiği için ayrı tutuldu
  function ensureNavLink() {
    const nav = document.querySelector('.nav-links');
    if (nav && !nav.querySelector('[data-listelerim]')) {
      const a = document.createElement('a'); a.href = 'listelerim.php'; a.textContent = 'Listelerim'; a.setAttribute('data-listelerim', '1');
      const prof = nav.querySelector('a[href="profil.php"]');
      nav.insertBefore(a, prof || null);
    }
  }

  // ---- Çalma listesi sayfası (liste.php) ----
  function loadPlaylistPage(id) {
    fetch('playlist.php?action=sarkilar&id=' + encodeURIComponent(id))
      .then(r => r.json())
      .then(d => {
        const nm = $('#pl-name');
        if (!d.ok) { const t = $('#trend-grid'); if (t) t.innerHTML = '<div class="empty">' + esc(d.error || 'Liste bulunamadı') + '</div>'; if (nm) nm.textContent = 'Liste'; return; }
        SONGS = d.sarkilar || [];
        if (nm) nm.textContent = d.ad || 'Liste';
        document.title = (d.ad || 'Liste') + ' — AUBE MUSIC';
        renderTrend(SONGS);
      });
  }

  // ===== Sayfa içi (SPA) gezinme — sayfa geçişinde müzik kesilmesin =====
  // Bu ögeler sayfa değişse de KALIR (çalar, ses, paneller):
  const KALICI = ['.player', '#audio-player', '#queue-panel', '#card-menu', '#pl-modal', '#aube-toast', '#spa-root'];
  const kaliciMi = (el) => KALICI.some(sel => el.matches && el.matches(sel));

  function spaRoot() {
    let root = $('#spa-root');
    if (!root) {
      root = document.createElement('div');
      root.id = 'spa-root';
      const tasinacak = Array.prototype.slice.call(document.body.children)
        .filter(el => !kaliciMi(el) && el.tagName !== 'SCRIPT');
      document.body.insertBefore(root, document.body.firstChild);
      tasinacak.forEach(el => root.appendChild(el));
    }
    return root;
  }

  let geziyor = false;
  function navigate(url, push) {
    if (geziyor) return;
    geziyor = true;
    fetch(url, { credentials: 'same-origin' })
      .then(r => r.text())
      .then(html => {
        const doc = new DOMParser().parseFromString(html, 'text/html');
        const root = spaRoot();
        root.innerHTML = '';
        Array.prototype.slice.call(doc.body.children)
          .filter(el => !kaliciMi(el) && el.tagName !== 'SCRIPT')
          .forEach(el => root.appendChild(document.importNode(el, true)));

        // body veri nitelikleri (sanatçı / çalma listesi sayfaları)
        ['data-artist', 'data-playlist-id'].forEach(a => {
          const v = doc.body.getAttribute(a);
          if (v === null) document.body.removeAttribute(a); else document.body.setAttribute(a, v);
        });
        if (doc.title) document.title = doc.title;
        if (push) history.pushState({}, '', url);
        window.scrollTo(0, 0);

        // Sayfaya özel inline script'leri çalıştır (profil, listelerim)
        Array.prototype.slice.call(doc.body.querySelectorAll('script:not([src])')).forEach(s => {
          const yeni = document.createElement('script');
          yeni.textContent = s.textContent;
          root.appendChild(yeni);
        });

        initPage();
      })
      .catch(() => { location.href = url; })  // sorun olursa normal gezinmeye düş
      .then(() => { geziyor = false; });
  }

  function icLinkMi(a) {
    if (!a) return false;
    const href = a.getAttribute('href');
    if (!href || href.charAt(0) === '#') return false;
    if (/^(https?:|mailto:|tel:)/i.test(href)) return false;
    if (a.target === '_blank' || a.hasAttribute('download')) return false;
    // Oturum sayfaları tam yüklenmeli (çıkışta müzik de dursun)
    if (/^(cikis|login|register)\.php/i.test(href)) return false;
    return /\.(html|php)(\?.*)?$/i.test(href);
  }

  document.addEventListener('click', function (e) {
    const a = e.target.closest ? e.target.closest('a[href]') : null;
    if (!icLinkMi(a)) return;
    e.preventDefault();
    navigate(a.getAttribute('href'), true);
  });
  window.addEventListener('popstate', function () {
    navigate(location.pathname.split('/').pop() + location.search, false);
  });

  // ---- Sayfaya özel bağlamalar (her geçişte yeniden) ----
  function bindPageUI() {
    const si = $('#search-icon'); if (si) si.innerHTML = ICON.search;
    ensureNavLink();

    const form = $('#search-form');
    if (form && !form._bound) {
      form._bound = 1;
      form.addEventListener('submit', function (e) {
        e.preventDefault();
        const q = (new FormData(form).get('search') || '').toString().trim().toLowerCase();
        if (!q) { renderTrend(SONGS); return; }
        const filtered = SONGS.filter(s =>
          (s.sarki_adi || '').toLowerCase().includes(q) || (s.sarkici || '').toLowerCase().includes(q));
        renderTrend(filtered);
        if (filtered.length) playSong(filtered[0].id);
      });
    }

    const cta = $('#hero-cta');
    if (cta && !cta._bound) {
      cta._bound = 1;
      cta.addEventListener('click', function () {
        if (SONGS.length) { shuffle = true; const sb = $('#p-shuffle'); if (sb) sb.classList.add('on'); playNext(); }
      });
    }

    // Navbar aktif link
    const sayfa = location.pathname.split('/').pop() || 'anasayfaa.html';
    document.querySelectorAll('.nav-links a').forEach(a => {
      a.classList.toggle('active', a.getAttribute('href') === sayfa);
    });
  }

  // ---- Sayfa verisini yükle ----
  function loadPageData() {
    const plId = document.body.getAttribute('data-playlist-id');
    if (plId) { loadPlaylistPage(plId); return; }

    fetch('anasayfaveri.php')
      .then(r => r.json())
      .then(data => {
        SONGS = Array.isArray(data) ? data : [];
        const af = getArtistFilter();
        const list = af
          ? SONGS.filter(s => (s.sarkici || '').toLowerCase().includes(af.toLowerCase()))
          : SONGS;

        if (af) {
          const nm = $('#artist-name'); if (nm) nm.textContent = af;
          const av = $('#artist-avatar'); if (av) av.src = artistPhoto(af, list[0] && list[0].kapak);
          document.title = af + ' — AUBE MUSIC';
        }

        renderArtists();
        renderTrend(list);
        renderRecent();
        renderLiked();
        highlightPlaying();
      })
      .catch(err => {
        console.error('Veri alınamadı:', err);
        const t = $('#trend-grid'); if (t) t.innerHTML = '<div class="empty">Veri alınamadı. Sunucu/veritabanı çalışıyor mu?</div>';
      });
  }

  function initPage() { bindPageUI(); loadPageData(); }

  // ---- Bir kez çalışır: çalar kalıcı olduğu için olayları tek sefer bağla ----
  function bootOnce() {
    const set = (id, html) => { const el = $('#' + id); if (el) el.innerHTML = html; };
    set('p-prev', ICON.prev); set('p-next', ICON.next); set('p-shuffle', ICON.shuffle);
    set('p-play', ICON.play); set('p-like', ICON.heart);

    injectControls(); // tekrar, volume, kuyruk

    if ($('#p-play')) $('#p-play').addEventListener('click', togglePlay);
    if ($('#p-next')) $('#p-next').addEventListener('click', playNext);
    if ($('#p-prev')) $('#p-prev').addEventListener('click', playPrev);
    if ($('#p-shuffle')) $('#p-shuffle').addEventListener('click', function () {
      shuffle = !shuffle; this.classList.toggle('on', shuffle);
    });
    if ($('#p-like')) $('#p-like').addEventListener('click', function () { if (curId != null) toggleLike(curId); });

    if (audio) {
      audio.addEventListener('ended', function () {
        if (repeatMode === 'one') { audio.currentTime = 0; audio.play(); return; }
        playNext();
      });
      audio.addEventListener('timeupdate', function () {
        const fill = $('#p-fill'), cur = $('#p-cur'), dur = $('#p-dur');
        if (audio.duration) {
          if (fill) fill.style.width = (audio.currentTime / audio.duration * 100) + '%';
          if (cur) cur.textContent = fmt(audio.currentTime);
          if (dur) dur.textContent = fmt(audio.duration);
        }
      });
      const bar = $('#p-bar');
      if (bar) bar.addEventListener('click', function (e) {
        const r = bar.getBoundingClientRect();
        if (audio.duration) audio.currentTime = (e.clientX - r.left) / r.width * audio.duration;
      });
    }
  }

  // ---- Başlat ----
  document.addEventListener('DOMContentLoaded', function () {
    spaRoot();   // içeriği kalıcı kabuktan ayır
    bootOnce();
    initPage();
  });
})();
