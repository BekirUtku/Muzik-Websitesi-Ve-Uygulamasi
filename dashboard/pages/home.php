<?php
include '../config/database.php';
include '../includes/header.php';
//include '../includes/sidebar.php';

// Database queries for counts
$artist_count = $db->query("SELECT COUNT(DISTINCT sarkici) AS count FROM muzik")->fetch_assoc()['count'];
$song_count = $db->query("SELECT COUNT(id) AS count FROM muzik")->fetch_assoc()['count'];
?>

<div class="w-4/5 p-6">
    <h1 class="text-2xl mb-4">Dashboard</h1>
    <div class="grid grid-cols-3 gap-4">
        <div class="p-4 bg-white shadow rounded">
            <p class="text-lg">Sanatçı Sayısı</p>
            <p class="text-2xl"><?php echo $artist_count; ?></p>
        </div>
        <div class="p-4 bg-white shadow rounded">
            <p class="text-lg">Şarkı Sayısı</p>
            <p class="text-2xl"><?php echo $song_count; ?></p>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>