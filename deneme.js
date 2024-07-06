document.addEventListener("DOMContentLoaded", function() {
    // PHP dosyasından müzik dosyasının yolunu almak için AJAX kullanılır
    fetch('deneme.php')
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

