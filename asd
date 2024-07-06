
<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "spotify";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Veritabanına bağlanılamadı: " . $conn->connect_error);
}

if(isset($_POST["Ara"])){ 
    $sarki_adi = $conn->real_escape_string($_POST["search"]);

    $sql = "SELECT sarki_adi, sarkici, yol, kapak FROM muzik WHERE sarki_adi='$sarki_adi'"; 
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $response = [
            'sarki_adi' => $row["sarki_adi"],
            'sarkici' => $row["sarkici"],
            'yol' => $row["yol"],
            'kapak' => $row["kapak"]
        ];
        echo json_encode($response);
    } else {
        echo json_encode(['error' => "Veritabanında müzik bulunamadı."]);
    }
}
$conn->close();
?>



document.addEventListener("DOMContentLoaded", function() {
    // PHP dosyasından müzik dosyasının yolunu almak için AJAX kullanılır
    fetch('anasayfaa.php')
        .then(response => response.json())
        .then(data => {
            // Müzik çaları oluştur
            var audioPlayer = document.getElementById('audio-player');
            audioPlayer.src = data.music_path;
            audioPlayer.play();
        })
        .catch(error => {
            console.error('Hata:', error);
        });
});

















<div class="spotify-playlists">

    <div class="list">
      <div class="item">
        <img src="kapaklar/Sahte.jpg" />
        <div class="play">
          <span class="fa fa-play"></span>
        </div>
        <h4>Sahte</h4>
        <p>Hande Yener</p>
      </div>

      <div class="item">
        <img src="kapaklar/Tanrım_Reva_Mı.jpg" />
        <div class="play">
          <span class="fa fa-play"></span>
        </div>
        <h4>Tanrım Reva Mı?</h4>
        <p>Semicenk</p>
      </div>

      <div class="item">
        <img src="kapaklar/Tavrına_Hayran.jpg" />
        <div class="play">
          <span class="fa fa-play"></span>
        </div>
        <h4>Tavrına Hayran</h4>
        <p>Reynmen,Bilal Sonses</p>
      </div>

      <div class="item">
        <img src="kapaklar/Tek_Başıma.jpg" />
        <div class="play">
          <span class="fa fa-play"></span>
        </div>
        <h4>Tek Başıma</h4>
        <p>Semicenk</p>
      </div>

      <div class="item">
        <img src="kapaklar/Uçurum.jpg" />
        <div class="play">
          <span class="fa fa-play"></span>
        </div>
        <h4>Uçurum</h4>
        <p>Mehmet Elmas</p>
      </div>

      <div class="item">
        <img src="kapaklar/Yakışıklı.jpg" />
        <div class="play">
          <span class="fa fa-play"></span>
        </div>
        <h4>Yakışıklı</h4>
        <p>KÖFN,Simge</p>
      </div>

      <div class="item">
        <img src="kapaklar/Yangın.jpg" />
        <div class="play">
          <span class="fa fa-play"></span>
        </div>
        <h4>Yangın</h4>
        <p>Hande Ünsal</p>
      </div>
    </div>
      
  <div class="spotify-playlists">
    <div class="list">
      <div class="item">
        <img src="kapaklar/Yansıma.jpg" />
        <div class="play">
          <span class="fa fa-play"></span>
        </div>
        <h4>Yansıma</h4>
        <p>Derya Uluğ</p>
      </div>

      <div class="item">
        <img src="kapaklar/Yatıya.jpg" />
        <div class="play">
          <span class="fa fa-play"></span>
        </div>
        <h4>Yatıya</h4>
        <p>Melis Kar</p>
      </div>

      <div class="item">
        <img src="kapaklar/Yaz_Gülü.jpg" />
        <div class="play">
          <span class="fa fa-play"></span>
        </div>
        <h4>Yaz Gülü</h4>
        <p>İrem Derici</p>
      </div>
      <div class="item">
        <img src="kapaklar/Ölü.jpg" />
        <div class="play">
          <span class="fa fa-play"></span>
        </div>
        <h4>Ölü</h4>
        <p>Contra</p>
      </div>

      <div class="item">
        <img src="kapaklar/Renklensin.jpg" />
        <div class="play">
          <span class="fa fa-play"></span>
        </div>
        <h4>Renklensin</h4>
        <p>Reynmen</p>
      </div>

      <div class="item">
        <img src="kapaklar/Rüzgar.jpg" />
        <div class="play">
          <span class="fa fa-play"></span>
        </div>
        <h4>Rüzgar</h4>
        <p>Bilal Hancı,Mustafa Ceceli</p>
      </div>

      <div class="item">
        <img src="kapaklar/Sahi.jpg" />
        <div class="play">
          <span class="fa fa-play"></span>
        </div>
        <h4>Sahi</h4>
        <p>Merve Özbey</p>
      </div>
  </div>
</div>