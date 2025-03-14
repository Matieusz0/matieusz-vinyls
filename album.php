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
</head>
<body>

    <button onclick="window.location.href='index.php'">🔙 Powrót</button>

    <div class="album-container">
        <h1><?= $album['wykonawca'] ?> - <?= $album['tytuł'] ?></h1>

        <div class="content">
            <div class="cover">
                <?php if (!empty($album['zdjecie'])): ?>
                    <img src="<?= $album['zdjecie'] ?>" alt="<?= $album['tytuł'] ?>" class="album-img">
                <?php endif; ?>
                <?php if (!empty($album['zdjecie2'])): ?>
                    <img src="<?= $album['zdjecie2'] ?>" alt="<?= $album['tytuł'] ?>" class="album-img-hover">
                <?php endif; ?>
            </div>
            
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

                <?php if (!empty($album['piosenki'])): ?>
                    <p><span class="label">Piosenki:</span></p>
                    <ul>
                        <?php 
                        $piosenki = explode("\n", $album['piosenki']);
                        foreach ($piosenki as $piosenka) {
                            echo "<li>$piosenka</li>";
                        }
                        ?>
                    </ul>
                <?php endif; ?>

                <?php if (!empty($album['cena'])): ?>
                    <p><span class="label">Cena:</span> <?= number_format($album['cena'], 2) ?> PLN</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

</body>
</html>
