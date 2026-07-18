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
        // Kullanıcı adı / e-posta zaten var mı?
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
            // Şifreyi HASH'le (asla düz metin saklama).
            $sifre_hash = password_hash($sifre, PASSWORD_DEFAULT);

            // Prepared statement ile ekle.
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
  <title>Kayıt Ol</title>
  <link rel="stylesheet" href="./login.css">
</head>
<body>
  <section>
    <div class="signin">
      <div class="content">
        <h2>Kayıt Ol</h2>

        <?php if ($hata !== ''): ?>
          <p style="color:#e33;text-align:center;"><?= htmlspecialchars($hata) ?></p>
        <?php endif; ?>

        <div class="form">
          <form method="post" action="">
            <div class="inputBox" style="margin-bottom: 30px;">
              <input type="text" name="ad" required> <i>Ad</i>
            </div>
            <div class="inputBox" style="margin-bottom: 30px;">
              <input type="text" name="soyad" required> <i>Soyad</i>
            </div>
            <div class="inputBox" style="margin-bottom: 30px;">
              <input type="email" name="email" required> <i>E-Mail</i>
            </div>
            <div class="inputBox" style="margin-bottom: 30px;">
              <input type="text" name="k_adi" required> <i>Kullanıcı Adı</i>
            </div>
            <div class="inputBox" style="margin-bottom: 30px;">
              <input type="password" name="sifre" required> <i>Şifre</i>
            </div>
            <div class="links" style="margin-bottom: 30px;">
              <a href="#">Şifremi Unuttum</a>
              <a href="login.php">Giriş Yap</a>
            </div>
            <div class="inputBox">
              <input type="submit" name="Kayit" value="Kayıt Ol">
            </div>
          </form>
        </div>
      </div>
    </div>
  </section>
</body>
</html>
