document.addEventListener("DOMContentLoaded", function() {
    // PHP dosyasından müzik verilerini almak için AJAX kullan
    fetch('anasayfaveri.php')
        .then(response => response.json())
        .then(data => {
            // Müzik listesini güncelle
            const playlistContainer = document.querySelector('.spotify-playlists .list');
            data.forEach(song => {
                const item = document.createElement('div');
                item.classList.add('item');
                item.innerHTML = `
                    <img src="${song.kapak}" />
                    <div class="play">
                        <span class="fa fa-play"></span>
                    </div>
                    <h4>${song.sarki_adi}</h4>
                    <p>${song.sarkici}</p>
                `;
                playlistContainer.appendChild(item);
            });
        })
        .catch(error => {
            console.error('Hata:', error);
        });
});
