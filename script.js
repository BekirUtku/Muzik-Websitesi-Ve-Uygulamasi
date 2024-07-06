document.addEventListener('DOMContentLoaded', (event) => {
  const songTitleElement = document.getElementById('song-title');
  const artistElement = document.getElementById('artist');
  const playButton = document.getElementById('play-button');
  const prevButton = document.getElementById('prev-button');
  const nextButton = document.getElementById('next-button');
  const audioPlayer = document.getElementById('audio-player');
  const albumCoverElement = document.getElementById('album-cover');
  const searchForm = document.getElementById('search-form');

  let currentSongIndex = parseInt(localStorage.getItem('currentSongIndex')) || 1;
  let currentTime = parseFloat(localStorage.getItem('currentTime')) || 0;
  let isPlaying = localStorage.getItem('isPlaying') === 'true';
  let isShuffle = localStorage.getItem('isShuffle') === 'true'; 

  function updateSongInfo(sarki_adi, sarkici, yol, kapak) {
    songTitleElement.textContent = sarki_adi;
    artistElement.textContent = sarkici;
    audioPlayer.src = yol;
    albumCoverElement.src = kapak;

    audioPlayer.addEventListener('loadedmetadata', () => {
      audioPlayer.currentTime = currentTime;
      if (isPlaying) {
        audioPlayer.play();
      }
    }, { once: true });
  }


  //Müzik çalara bilgi çekme fonksiyonu
  function fetchSongInfo(index) {
    fetch('deneme.php?index=' + index)
      .then(response => response.json())
      .then(data => {
        if (data.error) {
          console.error(data.error);
          currentSongIndex = 0;
        } else {
          updateSongInfo(data.sarki_adi, data.sarkici, data.yol, data.kapak);
          addToRecentlyPlayed(data); 
          localStorage.setItem('currentSongIndex', currentSongIndex);
        }
      })
      .catch(error => console.error('Hata:', error));
  }


//Birdahaki şarkıya geçme fonksiyonu
  function playNextSong() {
    if (isShuffle) {
      currentSongIndex = getRandomIndex(); 
    } else {
      currentSongIndex++;
    }
    currentTime = 0; 
    fetchSongInfo(currentSongIndex);
  }

  //Önceki Şarkıya geçme fonksiyonu
  function playPreviousSong() {
    currentSongIndex = (currentSongIndex > 1) ? currentSongIndex - 1 : 1;
    currentTime = 0; 
    fetchSongInfo(currentSongIndex);
  }


  //Duraklat ve oynat butonlarının işlevi
  function togglePlay() {
    if (audioPlayer.paused) {
      audioPlayer.play();
      playButton.textContent = 'Duraklat';
      playButton.style.backgroundColor = '#bd3200';
      isPlaying = true;
    } else {
      audioPlayer.pause();
      playButton.textContent = 'Oynat';
      playButton.style.backgroundColor = '#1db954';
      isPlaying = false;
    }
    localStorage.setItem('isPlaying', isPlaying);
  }

  // Karışık çalma durumunu değiştirme fonksiyonu
  function toggleShuffle() {
    isShuffle = !isShuffle;
    localStorage.setItem('isShuffle', isShuffle);
    if (isShuffle) {
      document.getElementById('shuffle-button').style.backgroundColor = '#1db954';
    } else {
      document.getElementById('shuffle-button').style.backgroundColor = '#282828';
    }
  }

  // Rastgele bir şarkı indeksi döndüren fonksiyon
  function getRandomIndex() {
    const maxIndex = 12; 
    let randomIndex = currentSongIndex;
    while (randomIndex === currentSongIndex) {
      randomIndex = Math.floor(Math.random() * maxIndex) + 1;
    }
    return randomIndex;
  }

  setInterval(() => {
    if (!audioPlayer.paused) {
      localStorage.setItem('currentTime', audioPlayer.currentTime);
    }
  }, 1000);

  playButton.addEventListener('click', togglePlay);
  prevButton.addEventListener('click', playPreviousSong);
  nextButton.addEventListener('click', playNextSong);
  document.getElementById('shuffle-button').addEventListener('click', toggleShuffle);

  fetchSongInfo(currentSongIndex);

  if (isPlaying) {
    playButton.textContent = 'Duraklat';
    playButton.style.backgroundColor = '#bd3200';
  } else {
    playButton.textContent = 'Oynat';
    playButton.style.backgroundColor = '#1db954';
  }


  //Geçmiş kısmına şarkı eklemek için
  function addToRecentlyPlayed(song) {
    let recentlyPlayed = JSON.parse(localStorage.getItem('recentlyPlayed')) || [];
    recentlyPlayed = recentlyPlayed.filter(s => s.yol !== song.yol); // 
    recentlyPlayed.unshift(song); 
    if (recentlyPlayed.length > 10) recentlyPlayed.pop(); 
    localStorage.setItem('recentlyPlayed', JSON.stringify(recentlyPlayed));
    updateRecentlyPlayed();
  }

  //Her şarkıda geçmişi güncellemek için
  function updateRecentlyPlayed() {
    const recentlyPlayedContainer = document.querySelector('.spotify-playlists.recently-played .list');
    recentlyPlayedContainer.innerHTML = '';
    let recentlyPlayed = JSON.parse(localStorage.getItem('recentlyPlayed')) || [];

    recentlyPlayed.forEach(song => {
      const item = document.createElement('div');
      item.classList.add('item');
      item.innerHTML = `
        <img src="${song.kapak}" />
        <div class="play" onclick="playMusic('${song.kapak}', '${song.sarki_adi}', '${song.sarkici}', ${song.index})">
          <span class="fa fa-play"></span>
        </div>
        <h4>${song.sarki_adi}</h4>
        <p>${song.sarkici}</p>
      `;
      recentlyPlayedContainer.appendChild(item);
    });
  }

  updateRecentlyPlayed();

  // Arama formunun gönderilmesini dinleyin
  searchForm.addEventListener('submit', function(event) {
    event.preventDefault();
    const formData = new FormData(searchForm);
    const searchQuery = formData.get('search');

    fetch('arama.php?search=' + encodeURIComponent(searchQuery))
      .then(response => response.json())
      .then(data => {
        if (data.error) {
          console.error(data.error);
        } else {
          currentSongIndex = data.index; 
          updateSongInfo(data.sarki_adi, data.sarkici, data.yol, data.kapak);
          addToRecentlyPlayed(data); 
          localStorage.setItem('currentSongIndex', currentSongIndex);
        }
      })
      .catch(error => console.error('Hata:', error));
  });
});



