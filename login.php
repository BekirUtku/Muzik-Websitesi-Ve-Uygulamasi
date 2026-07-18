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
        // Oturum sabitlemesine (session fixation) karşı ID yenile.
        session_regenerate_id(true);

        $_SESSION['kullanici_id']  = $kullanici['id'];
        $_SESSION['kullanici_adi'] = $kullanici['kullanici_adi'];
        $_SESSION['ad']            = $kullanici['ad'];
        $_SESSION['soyad']         = $kullanici['soyad'];
        $_SESSION['email']         = $kullanici['email'];

        header('Location: anasayfaa.html');
        exit();
    }

    // Kullanıcı adı ve şifreyi ayrı ayrı ele vermemek için tek mesaj.
    $hata = 'Kullanıcı adı veya şifre hatalı!';
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Giriş</title>
  <link rel="stylesheet" href="./login.css">
</head>
<body>
  <section>
    <div class="signin">
      <div class="content">
        <h2>Giriş</h2>

        <?php if ($hata !== ''): ?>
          <p style="color:#e33;text-align:center;"><?= htmlspecialchars($hata) ?></p>
        <?php endif; ?>

        <div class="form">
          <form method="post" action="">
            <div class="inputBox">
              <input type="text" name="k_adi" required> <i>Kullanıcı Adı</i>
            </div>
            <div class="inputBox" style="margin-top: 40px; margin-bottom: 20px;">
              <input type="password" name="sifre" required> <i>Şifre</i>
            </div>
            <div class="links" style="margin-bottom: 20px;">
              <a href="#">Şifremi Unuttum</a>
              <a href="register.php">Kayıt Ol</a>
            </div>
            <div class="inputBox">
              <input type="submit" name="Giris" value="Giriş">
            </div>
          </form>
        </div>
      </div>
    </div>
  </section>
</body>
</html>
