<?php
require 'php/db.php';
$id = $_GET['id'] ?? null;

if ($id === null || !is_numeric($id)) {
    header("Location: access_denied.php");
    exit();
}

$stmt = $pdo->prepare("SELECT albumy.*, gatunki.nazwa AS gatunek 
    FROM albumy 
    LEFT JOIN gatunki ON albumy.gatunek_id = gatunki.id
    WHERE albumy.id = ?
");
$stmt->execute([$id]);
$album = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$album) {
    error_log("Brak albumu o id: $id");
    header("Location: access_denied.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title><?= $album['wykonawca']?> - <?= $album['tytuł'] ?></title>
    <link rel="stylesheet" href="css/album.css">
    <script src="js/album.js" defer></script>
    <?php if (!empty($album['zdjecie'])): ?>
        <link rel="icon" type="image/png" href="<?= $album['zdjecie'] ?>"> <!-- Set album cover as favicon -->
    <?php endif; ?>
</head>
<body>

    <button class="back-button" onclick="window.location.href='index.php'">🔙 Powrót</button> <!-- Updated class -->

    <?php if (!empty($album['edycja_limitowana_opis'])): ?>
        <div class="limited-edition-banner">
            <h2 class="limited-title">Edycja Limitowana</h2>
            <p class="limited-desc"><?= htmlspecialchars($album['edycja_limitowana_opis']) ?></p>
        </div>
    <?php endif; ?>

    <div class="album-container">
        <h1><?= $album['wykonawca'] ?> - <?= $album['tytuł'] ?></h1>
        <div class="content">
            <div class="cover">
                <?php if (!empty($album['zdjecie'])): ?>
                    <img src="<?= $album['zdjecie'] ?>" alt="<?= $album['tytuł'] ?>" class="album-img">
                <?php endif; ?>
            </div>
            
            <script>
                setTimeout(() => {
                    document.querySelector('.album-img').classList.add('spin');
                }, 1000);
            </script>
            
            <div class="album-details">
                <?php if (!empty($album['opis'])): ?>
                    <p><span class="label">Opis:</span> <?= $album['opis'] ?></p>
                <?php endif; ?>

                <?php if (!empty($album['gatunek'])): ?>
                    <p><span class="label">Gatunek:</span> <?= $album['gatunek'] ?></p>
                <?php endif; ?>

                <?php if (!empty($album['data_wydania'])): ?>
                    <p><span class="label">Rok wydania:</span> <?= $album['data_wydania'] ?></p>
                <?php endif; ?>

                <?php if (!empty($album['ilosc_plyt'])): ?>
                    <p><span class="label">Ilość płyt:</span> <?= $album['ilosc_plyt'] ?></p>
                <?php endif; ?>

                <?php if (!empty($album['cena'])): ?>
                    <p><span class="label">Cena:</span> <?= number_format($album['cena'], 2) ?> PLN</p>
                <?php endif; ?>

                <button class="songs-button" id="show-songs-btn">Pokaż piosenki</button> <!-- Updated class -->

                <?php if (!empty($album['spotify_link'])): ?>
                    <div class="spotify-player">
                        <iframe src="<?= $album['spotify_link'] ?>&theme=0" width="100%" height="158" frameborder="0" allowtransparency="true" allow="encrypted-media"></iframe>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Modal for displaying songs -->
    <div id="songs-modal" class="modal">
        <div class="modal-content">
            <span class="close-btn">&times;</span>
            <h2>Lista Piosenek</h2>
            <div class="songs-columns">
                <?php 
                if (!empty($album['piosenki'])) {
                    $piosenki = explode("\n", $album['piosenki']);
                    $groupedSongs = [];
                    $currentSide = '';

                    foreach ($piosenki as $piosenka) {
                        if (preg_match('/^([A-Z])\d+/', $piosenka, $matches)) {
                            $currentSide = "STRONA " . $matches[1];
                            $groupedSongs[$currentSide] = [];
                        } 
                        if (!empty($currentSide)) {
                            $groupedSongs[$currentSide][] = preg_replace('/^[A-Z]\d+\s*/', '', $piosenka);
                        }
                    }

                    foreach ($groupedSongs as $side => $songs) {
                        echo "<div class='songs-column'>";
                        echo "<h3>$side</h3>";
                        echo "<ul>";
                        foreach ($songs as $song) {
                            echo "<li>$song</li>";
                        }
                        echo "</ul>";
                        echo "</div>";
                    }
                }
                ?>
            </div>
        </div>
    </div>

</body>
</html>
