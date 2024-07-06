<?php
include '../includes/header.php';
include '../config/database.php';

// Müzik ID'sini al
$music_id = $_GET['id']; // Örnek olarak URL'den ID'yi alıyoruz, gerçek kullanıma göre değiştirebilirsiniz.

// Veritabanından ilgili müziği çek
$query = "SELECT * FROM muzik WHERE id = $music_id";
$result = $db->query($query);
$row = $result->fetch_assoc(); // Verileri bir dizi olarak alıyoruz
?>

<body>
    <div class="container mx-auto p-4">
        <h1 class="text-2xl font-bold mb-4">Müzik Güncelle</h1>
        <form action="../update_music.php" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 gap-4">
            <input type="hidden" name="music_id" value="<?php echo $row['id']; ?>">

            <div class="flex flex-col">
                <label for="song_title">Şarkı Adı:</label>
                <input type="text" name="sarki_adi" value="<?php echo $row['sarki_adi']; ?>" required class="p-2 border border-gray-300 rounded">
            </div>

            <div class="flex flex-col">
                <label for="artist">Sanatçı:</label>
                <input type="text" name="sarkici" value="<?php echo $row['sarkici']; ?>" required class="p-2 border border-gray-300 rounded">
            </div>

            <div class="flex flex-col">
                <label for="album">Albüm:</label>
                <input type="text" name="album" value="<?php echo $row['album']; ?>" class="p-2 border border-gray-300 rounded">
            </div>

            <div class="flex flex-col">
                <label for="genre">Tür:</label>
                <input type="text" name="turu" value="<?php echo $row['turu']; ?>" class="p-2 border border-gray-300 rounded">
            </div>

            <div class="flex flex-col">
                <label for="picture">Resim:</label>
                <input type="file" name="kapak" accept="image/*" class="p-2 border border-gray-300 rounded">
            </div>

            <div class="flex flex-col">
                <label for="file_path">Dosya:</label>
                <input type="file" name="yol" accept="audio/*" class="p-2 border border-gray-300 rounded">
            </div>

            <div class="flex justify-end">
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Güncelle</button>
            </div>
        </form>
    </div>
</body>

</html>

<?php
$db->close();
?>