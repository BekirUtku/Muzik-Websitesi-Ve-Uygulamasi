<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="anasayfa.css" />
    <style>
        /* Arka plan rengi */
        body {
            background-color: #f2f2f2;
        }

        /* Ortadaki alanın stili */
        .orta {
            margin: auto;
            width: 80%;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        /* Kullanıcı bilgilerinin stil */
        .kullanici-bilgileri {
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        /* Kullanıcı bilgileri içeriklerinin stil */
        .kullanici-bilgi {
            margin-bottom: 10px;
        }

        /* Başlık ve menü stil */
        header {
            background-color: #333;
            color: #fff;
            padding: 10px 0;
            text-align: center;
        }

        nav a {
            color: #fff;
            text-decoration: none;
            margin: 0 10px;
        }
    </style>
</head>

<body>
    <header>
        <nav>
            <h1>AUBE MUSIC</h1>
            <a href="anasayfaa.html">Anasayfa</a>
            <a href="sanatcilar.html">Sanatçılar</a>
            <a href="begenilenler.html">Begenilenler</a>
            <a href="profil.php">Profil</a>
            <a href="login.php">Çıkış Yap</a>
        </nav>
        <div class="search-bar">
            <form action="#" method="get">
                <input type="text" name="search" placeholder="Ara...">
                <input type="submit" value="Ara">
            </form>
        </div>
    </header>

    <div class="orta">
        <div class="kullanici-bilgileri">
            <?php
            session_start(); 

            // Oturumda kullanıcı bilgilerini kontrol et
            if (isset($_SESSION['kullanici_id'])) {
                $kullanici_id = $_SESSION['kullanici_id'];
                $kullanici_adi = $_SESSION['kullanici_adi'];
                $ad = $_SESSION['ad'];
                $soyad = $_SESSION['soyad'];
                $email = $_SESSION['email'];

                // Kullanıcı bilgilerini HTML içeriğini oluştur
                echo "<div class='kullanici-bilgi'>Kullanıcı Adı: $kullanici_adi</div>";
                echo "<div class='kullanici-bilgi'>Ad: $ad</div>";
                echo "<div class='kullanici-bilgi'>Soyad: $soyad</div>";
                echo "<div class='kullanici-bilgi'>E-Posta: $email</div>";
            } else {
                // Oturum açık değilse, giriş yapmamıştır, bu nedenle bir hata mesajı gösterir
                echo "<p>Oturum açmadınız. Lütfen giriş yapın.</p>";
            }
            ?>
        </div>
    </div>





    <div class="music-player">
  <div class="song-info">
    <img id="album-cover" src="./kapaklar/Ayrı_Gitme.jpg" alt="Albüm Kapağı">
    <div class="song-details">
      <p id="song-title">Şarkı Adı</p>
      <p id="artist">Madrigal</p>
    </div>
  </div>
  <div class="music">
    <audio id="audio-player" controls style="width: 1000px;"></audio>
  </div>
  <div class="controls">
    <button id="prev-button">Önceki</button>
    <button id="play-button">Oynat</button>
    <button id="next-button">Sonraki</button>
    <button id="shuffle-button">Karışık Çal</button>
  </div>
</div>


<script src="script.js"></script>

</body>

</html>
