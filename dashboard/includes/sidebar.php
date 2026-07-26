<?php $__p = basename($_SERVER['PHP_SELF'] ?? ''); ?>
<aside class="dash-sidebar">
  <div class="dash-brand">
    <span class="logo"><svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M12 3v10.6A4 4 0 1 0 14 17V7h4V3z"/></svg></span>
    <span>AUBE MUSIC</span>
  </div>
  <nav class="dash-nav">
    <a href="home.php" class="<?= $__p==='home.php'?'active':'' ?>">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 10.5L12 3l9 7.5"/><path d="M5 9.5V21h14V9.5"/></svg>
      <span>Anasayfa</span>
    </a>
    <a href="music_settings.php" class="<?= $__p==='music_settings.php'?'active':'' ?>">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M11 5h2v6h6v2h-6v6h-2v-6H5v-2h6z"/></svg>
      <span>Müzik Ekle</span>
    </a>
    <a href="music_list.php" class="<?= ($__p==='music_list.php'||$__p==='music_update.php')?'active':'' ?>">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M4 6h11v2H4V6zm0 5h11v2H4v-2zm0 5h7v2H4v-2zm13-6v6.6a2.4 2.4 0 1 1-2-2.36V8l4-1v2z"/></svg>
      <span>Müzikler</span>
    </a>
  </nav>
  <div class="dash-side-foot">
    <a href="../../anasayfaa.html">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 18l-6-6 6-6"/></svg>
      <span>Siteye Dön</span>
    </a>
  </div>
</aside>
