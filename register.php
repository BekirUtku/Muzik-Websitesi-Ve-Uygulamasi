<?php 
include "databaseconnection.php";

if(isset($_POST["Kayit"])){ 
    $ad = $_POST["ad"];
    $soyad = $_POST["soyad"];
    $email = $_POST["email"];
    $k_adi = $_POST["k_adi"];
    $sifre = $_POST["sifre"];
    
    
    $sql = "INSERT INTO kullanicilar (email, sifre, kullanici_adi, ad, soyad) VALUES ('$email', '$sifre', '$k_adi', '$ad', '$soyad')";
    $sonuc = mysqli_query($baglan, $sql);

    if($sonuc){
        header("Location: login.php");
        echo "Kayıt başarılı!";
    } else {
        echo "Kayıt işlemi sırasında bir hata oluştu!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
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
