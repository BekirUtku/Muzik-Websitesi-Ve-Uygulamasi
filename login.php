<?php 
include "databaseconnection.php";

if(isset($_POST["Giris"])){ 
    $k_adi = $_POST["k_adi"];
    $sifre = $_POST["sifre"];
    
    // Güvenli bir şekilde kullanıcı adı ve şifreyi al
    $k_adi = mysqli_real_escape_string($baglan, $k_adi);
    $sifre = mysqli_real_escape_string($baglan, $sifre);
    
    // Veritabanında kullanıcıyı sorgula
    $sql = "SELECT * FROM kullanicilar WHERE kullanici_adi='$k_adi' ";
    $sonuc = mysqli_query($baglan, $sql);
    $kullanici = mysqli_fetch_assoc($sonuc);

    // Kullanıcı varsa ve şifre doğruysa giriş yap
    if($kullanici || password_verify($sifre, $kullanici['sifre'])){
        // Oturumu başlat ve kullanıcı bilgilerini sakla
        session_start();
        $_SESSION['kullanici_id'] = $kullanici['id'];
        $_SESSION['kullanici_adi'] = $kullanici['kullanici_adi'];
        $_SESSION['ad'] = $kullanici['ad'];
        $_SESSION['soyad'] = $kullanici['soyad'];
        $_SESSION['email'] = $kullanici['email'];
        
        // Giriş yapıldıktan sonra yönlendir
        header("Location: anasayfaa.html");
        exit();
    } else {
        echo "Kullanıcı adı veya şifre hatalı!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
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
        
        <div class="form">
         <form method="post" action="">
          <div class="inputBox" >
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
