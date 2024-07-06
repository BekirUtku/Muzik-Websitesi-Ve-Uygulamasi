<?php
include '../includes/header.php';
//include '../includes/sidebar.php';
?>

<div class="w-4/5 p-6">
    <div class="flex justify-between items-center">
        <h1 class="text-2xl mb-4">Müzik Ayarları</h1>
        <button id="add-music-btn" class="bg-blue-500 text-white px-4 py-2 rounded">Yeni Müzik Ekle</button>
    </div>

    <div id="add-music-form" class="hidden mt-4 p-6 bg-white shadow rounded">
        <form action="../add_music.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="MAX_FILE_SIZE" value="10485760">
            <div class="mb-4">
                <label for="sarkici" class="block text-gray-700">Sanatçı</label>
                <input type="text" name="sarkici" id="artist" class="w-full p-2 border border-gray-300 rounded">
            </div>
            <div class="mb-4">
                <label for="sarki_adi" class="block text-gray-700">Şarkı Adı</label>
                <input type="text" name="sarki_adi" id="song_title" class="w-full p-2 border border-gray-300 rounded">
            </div>
            <div class="mb-4">
                <label for="album" class="block text-gray-700">Albüm</label>
                <input type="text" name="album" id="album" class="w-full p-2 border border-gray-300 rounded">
            </div>
            <div class="mb-4">
                <label for="turu" class="block text-gray-700">Tür</label>
                <input type="text" name="turu" id="genre" class="w-full p-2 border border-gray-300 rounded">
            </div>
            <div class="mb-4">
                <label for="kapak" class="block text-gray-700">Resim</label>

                <input type="file" name="kapak" id="picture" class="w-full p-2 border border-gray-300 rounded">
            </div>
            <div class="mb-4">
                <label for="yol" class="block text-gray-700">Dosya</label>

                <input type="file" name="yol" id="file_path" class="w-full p-2 border border-gray-300 rounded">
            </div>
            <div>
                <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded">Ekle</button>
            </div>
        </form>
    </div>
</div>

<?php include '../includes/footer.php'; ?>