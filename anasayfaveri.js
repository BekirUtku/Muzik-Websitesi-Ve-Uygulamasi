document.addEventListener("DOMContentLoaded", function() {
    // PHP dosyasından müzik verilerini almak için AJAX kullan
    fetch('anasayfaveri.php')
        .then(response => response.json())
        .then(data => {
            // "Trend" listesini doldur
            const playlistContainer = document.querySelector('.spotify-playlists .list');
            if (!playlistContainer) return;
            data.forEach(song => {
                const item = document.createElement('div');
                item.classList.add('item');
                // Tıklanınca script.js bu id ile şarkıyı çalar.
                item.setAttribute('data-index', song.id);
                item.style.cursor = 'pointer';
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
