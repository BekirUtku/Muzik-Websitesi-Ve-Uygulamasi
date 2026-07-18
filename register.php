<?php
session_start();
require_once __DIR__ . '/db.php';

$hata = '';

if (isset($_POST['Kayit'])) {
    $ad    = trim($_POST['ad'] ?? '');
    $soyad = trim($_POST['soyad'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $k_adi = trim($_POST['k_adi'] ?? '');
    $sifre = $_POST['sifre'] ?? '';

    if ($ad === '' || $soyad === '' || $email === '' || $k_adi === '' || $sifre === '') {
        $hata = 'Lütfen tüm alanları doldurun.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $hata = 'Geçerli bir e-posta adresi girin.';
    } elseif (strlen($sifre) < 6) {
        $hata = 'Şifre en az 6 karakter olmalıdır.';
    } else {
        $stmt = $baglan->prepare(
            'SELECT id FROM kullanicilar WHERE kullanici_adi = ? OR email = ? LIMIT 1'
        );
        $stmt->bind_param('ss', $k_adi, $email);
        $stmt->execute();
        $mevcut = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if ($mevcut) {
            $hata = 'Bu kullanıcı adı veya e-posta zaten kayıtlı.';
        } else {
            $sifre_hash = password_hash($sifre, PASSWORD_DEFAULT);
            $stmt = $baglan->prepare(
                'INSERT INTO kullanicilar (email, sifre, kullanici_adi, ad, soyad)
                 VALUES (?, ?, ?, ?, ?)'
            );
            $stmt->bind_param('sssss', $email, $sifre_hash, $k_adi, $ad, $soyad);
            if ($stmt->execute()) {
                $stmt->close();
                header('Location: login.php');
                exit();
            }
            $hata = 'Kayıt işlemi sırasında bir hata oluştu!';
            $stmt->close();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Kayıt Ol — AUBE MUSIC</title>
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
      <h2>Kayıt Ol</h2>
      <p class="sub">Ücretsiz hesap oluştur, müziğe başla.</p>

      <?php if ($hata !== ''): ?>
        <div class="auth-error"><?= htmlspecialchars($hata) ?></div>
      <?php endif; ?>

      <form method="post" action="">
        <div class="field">
          <label>Ad</label>
          <input type="text" name="ad" placeholder="Adın" required>
        </div>
        <div class="field">
          <label>Soyad</label>
          <input type="text" name="soyad" placeholder="Soyadın" required>
        </div>
        <div class="field">
          <label>E-Posta</label>
          <input type="email" name="email" placeholder="ornek@mail.com" required>
        </div>
        <div class="field">
          <label>Kullanıcı Adı</label>
          <input type="text" name="k_adi" placeholder="kullanıcı adın" required>
        </div>
        <div class="field">
          <label>Şifre</label>
          <input type="password" name="sifre" placeholder="en az 6 karakter" required>
        </div>
        <button class="btn-primary" type="submit" name="Kayit">Kayıt Ol</button>
        <div class="auth-links">
          <a href="#">Şifremi Unuttum</a>
          <a href="login.php">Zaten hesabın var mı? Giriş Yap</a>
        </div>
      </form>
    </div>
  </div>
</body>
</html>
