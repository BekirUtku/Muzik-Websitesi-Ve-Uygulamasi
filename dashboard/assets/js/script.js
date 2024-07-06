document.addEventListener("DOMContentLoaded", function () {
  const addMusicBtn = document.getElementById("add-music-btn");
  const addMusicForm = document.getElementById("add-music-form");

  if (addMusicBtn && addMusicForm) {
    addMusicBtn.addEventListener("click", function () {
      addMusicForm.classList.toggle("hidden");
    });
  }
});
