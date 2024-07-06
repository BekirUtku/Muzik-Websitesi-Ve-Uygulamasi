<?php
include '../config/database.php';
include '../includes/header.php';

$result = $db->query("SELECT id, sarki_adi, turu, yol, album, sarkici, kapak FROM muzik");
?>

<body>
    <div class="container mx-auto p-4">
        <h1 class="text-2xl font-bold mb-4">Müzik Listesi</h1>
        <div class="grid grid-cols-3 gap-4">
            <?php while ($row = $result->fetch_assoc()) : ?>
                <div class="bg-white p-4 rounded-lg shadow-md">
                    <img src="/spotifyodev<?php echo $row['kapak']; ?>" alt="<?php echo $row['sarki_adi']; ?>" class="w-full h-48 object-cover mb-4 rounded">
                    <h2 class="text-xl font-bold"><?php echo $row['sarki_adi']; ?></h2>
                    <p class="text-gray-700"><strong>Sanatçı:</strong> <?php echo $row['sarkici']; ?></p>
                    <p class="text-gray-700"><strong>Albüm:</strong> <?php echo $row['album']; ?></p>
                    <p class="text-gray-700"><strong>Tür:</strong> <?php echo $row['turu']; ?></p>
                    <div class="flex justify-between mt-4">
                        <form action="../delete_music.php" method="POST">
                            <input type="hidden" name="music_id" value="<?php echo $row['id']; ?>">
                            <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded">Sil</button>
                        </form>
                        <form action="./music_update.php?id=<?php echo $row['id']; ?>" method="POST">
                            <input type="hidden" name="music_id" value="<?php echo $row['id']; ?>">
                            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Güncelle</button>
                        </form>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</body>

</html>

<?php
$db->close();
?>