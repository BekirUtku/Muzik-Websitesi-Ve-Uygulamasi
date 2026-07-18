<?php
session_start();                 // Oturum her zaman EN ÜSTTE, çıktıdan önce başlar.
require_once __DIR__ . '/db.php';

$hata = '';

if (isset($_POST['Giris'])) {
    $k_adi = trim($_POST['k_adi'] ?? '');
    $sifre = $_POST['sifre'] ?? '';

    // Prepared statement -> SQL injection'a kapalı.
    $stmt = $baglan->prepare(
        'SELECT id, kullanici_adi, ad, soyad, email, sifre
         FROM kullanicilar
         WHERE kullanici_adi = ?
         LIMIT 1'
    );
    $stmt->bind_param('s', $k_adi);
    $stmt->execute();
    $kullanici = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    // Kullanıcı VAR *VE* şifre doğru (&&). Hash doğrulaması ile.
    if ($kullanici && password_verify($sifre, $kullanici['sifre'])) {
        session_regenerate_id(true);
        $_SESSION['kullanici_id']  = $kullanici['id'];
        $_SESSION['kullanici_adi'] = $kullanici['kullanici_adi'];
        $_SESSION['ad']            = $kullanici['ad'];
        $_SESSION['soyad']         = $kullanici['soyad'];
        $_SESSION['email']         = $kullanici['email'];
        header('Location: anasayfaa.html');
        exit();
    }

    $hata = 'Kullanıcı adı veya şifre hatalı!';
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Giriş — AUBE MUSIC</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="aube.css">
</head>
<body>
  <div class="auth-wrap">
    <div class="auth-card">
      <div class="brand">
        <span class="logo">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M12 3v10.6A4 4 0 1 0 14 17V7h4V3z"/></svg>
        </span>
        AUBE MUSIC
      </div>
      <h2>Giriş Yap</h2>
      <p class="sub">Hesabına giriş yaparak dinlemeye devam et.</p>

      <?php if ($hata !== ''): ?>
        <div class="auth-error"><?= htmlspecialchars($hata) ?></div>
      <?php endif; ?>

      <form method="post" action="">
        <div class="field">
          <label>Kullanıcı Adı</label>
          <input type="text" name="k_adi" placeholder="kullanıcı adın" required>
        </div>
        <div class="field">
          <label>Şifre</label>
          <input type="password" name="sifre" placeholder="••••••••" required>
        </div>
        <button class="btn-primary" type="submit" name="Giris">Giriş Yap</button>
        <div class="auth-links">
          <a href="#">Şifremi Unuttum</a>
          <a href="register.php">Hesabın yok mu? Kayıt Ol</a>
        </div>
      </form>
    </div>
  </div>
</body>
</html>
