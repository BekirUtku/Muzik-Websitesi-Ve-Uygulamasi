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
    search: '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="7"/><path d="M21 21l-4.3-4.3"/></svg>'
  };

  const LIKES_KEY = 'aube_likes';
  const RECENT_KEY = 'aube_recent';

  let SONGS = [];
  let curId = null;
  let shuffle = false;

  const $ = (s, r = document) => r.querySelector(s);
  const esc = (s) => String(s == null ? '' : s).replace(/[&<>"]/g, c => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;' }[c]));
  const byId = (id) => SONGS.find(s => String(s.id) === String(id));

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
        '<button class="play-ov" title="Çal">' + ICON.play + '</button>' +
      '</div>' +
      '<div class="meta"><div class="t">' + esc(song.sarki_adi) + '</div><div class="a">' + esc(song.sarkici) + '</div></div>';

    card.querySelector('.like-btn').addEventListener('click', (e) => { e.stopPropagation(); toggleLike(song.id); });
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

  function renderTrend(list) { fillGrid($('#trend-grid'), list || SONGS, 'Şarkı bulunamadı.'); updateCounts(); }
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
    if (!SONGS.length) return;
    let i;
    if (shuffle) { do { i = Math.floor(Math.random() * SONGS.length); } while (SONGS.length > 1 && i === currentIndex()); }
    else { i = currentIndex(); i = (i < 0 || i >= SONGS.length - 1) ? 0 : i + 1; }
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

  // ---- Başlat ----
  document.addEventListener('DOMContentLoaded', function () {
    // İkonları yerleştir
    const set = (id, html) => { const el = $('#' + id); if (el) el.innerHTML = html; };
    set('p-prev', ICON.prev); set('p-next', ICON.next); set('p-shuffle', ICON.shuffle);
    set('p-play', ICON.play); set('p-like', ICON.heart);
    const si = $('#search-icon'); if (si) si.innerHTML = ICON.search;

    // Çalar olayları
    if ($('#p-play')) $('#p-play').addEventListener('click', togglePlay);
    if ($('#p-next')) $('#p-next').addEventListener('click', playNext);
    if ($('#p-prev')) $('#p-prev').addEventListener('click', playPrev);
    if ($('#p-shuffle')) $('#p-shuffle').addEventListener('click', function () {
      shuffle = !shuffle; this.classList.toggle('on', shuffle);
    });
    if ($('#p-like')) $('#p-like').addEventListener('click', function () { if (curId != null) toggleLike(curId); });

    if (audio) {
      audio.addEventListener('ended', playNext);
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

    // Arama
    const form = $('#search-form');
    if (form) form.addEventListener('submit', function (e) {
      e.preventDefault();
      const q = (new FormData(form).get('search') || '').toString().trim().toLowerCase();
      if (!q) { renderTrend(SONGS); return; }
      const filtered = SONGS.filter(s =>
        (s.sarki_adi || '').toLowerCase().includes(q) || (s.sarkici || '').toLowerCase().includes(q));
      renderTrend(filtered);
      if (filtered.length) playSong(filtered[0].id);
    });

    const cta = $('#hero-cta');
    if (cta) cta.addEventListener('click', function () { if (SONGS.length) { shuffle = true; const sb = $('#p-shuffle'); if (sb) sb.classList.add('on'); playNext(); } });

    // Verileri çek
    fetch('anasayfaveri.php')
      .then(r => r.json())
      .then(data => {
        SONGS = Array.isArray(data) ? data : [];
        renderTrend(SONGS);
        renderRecent();
        renderLiked();
      })
      .catch(err => {
        console.error('Veri alınamadı:', err);
        const t = $('#trend-grid'); if (t) t.innerHTML = '<div class="empty">Veri alınamadı. Sunucu/veritabanı çalışıyor mu?</div>';
      });
  });
})();
