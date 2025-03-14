<?php
require 'php/db.php';
include 'php/admin_check.php';

// 🔹 POBIERANIE ALBUMOW
$stmt = $pdo->query("SELECT albumy.*, gatunki.nazwa AS gatunek 
    FROM albumy 
    LEFT JOIN gatunki ON albumy.gatunek_id = gatunki.id
    ORDER BY albumy.data_wydania DESC
");
$albums = $stmt->fetchAll(PDO::FETCH_ASSOC);

// 🔹 POBIERANIE GATUNKOW
$gatunki = $pdo->query("SELECT * FROM gatunki ORDER BY nazwa ASC")->fetchAll(PDO::FETCH_ASSOC);

// 🔹 OSTATNIO DODANY ALBUM
$recentAlbumStmt = $pdo->query("SELECT albumy.*, gatunki.nazwa AS gatunek 
    FROM albumy 
    LEFT JOIN gatunki ON albumy.gatunek_id = gatunki.id
    ORDER BY albumy.created_at DESC
    LIMIT 1
");
$recentAlbum = $recentAlbumStmt->fetch(PDO::FETCH_ASSOC);

// 🔹 OBLICZANIE LACZNEJ WARTOSCI WSZYSTKICH ALBUMOW
$totalValueStmt = $pdo->query("SELECT SUM(cena) AS total_value FROM albumy");
$totalValue = $totalValueStmt->fetch(PDO::FETCH_ASSOC)['total_value'];
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>matieusz vinyls</title>
    <link rel="stylesheet" href="css/index.css">
    <script src="js/index.js" defer></script>
</head>
<body>
<!-- 🔹 PRZYCISKI W PRAWYM GORNYM ROGU -->
<div class="top-right-buttons">
    <?php if ($isAdmin): ?>
        <button onclick="window.location.href='add_album.php'" class="add-btn">➕ Dodaj album</button>
        <button id="delete-toggle-btn" class="delete-btn">Usuń album</button>
        <button onclick="window.location.href='logout.php'" class="login-btn">Wyloguj</button>
    <?php else: ?>
        <button onclick="window.location.href='login.php'" class="login-btn">Zaloguj</button>
    <?php endif; ?>
    <button onclick="window.location.href='settings.php'" class="settings-btn">Ustawienia</button>
</div>

    <!-- 🔹 TYTUŁ STRONY -->
    <h1>Moja Kolekcja Winylowa</h1>

    <!-- 🔹 PRZYCISKI DO ZMIANY WIDOKU -->
    <div class="view-toggle">
        <button id="largeView">⬆️</button>
        <button id="mediumView">⬇️</button>
        <button id="listView">📝</button>
    </div>

    <!-- 🔹 WYSZUKIWARKA -->
    <div class="search-bar">
        <input type="text" id="search-input" placeholder="Szukaj albumów...">
    </div>

    <div class="main-container">  
        <!-- 🔹 PANEL FILTRÓW PO LEWEJ STRONIE -->
<div class="filters-container">
    <h2>Filtry</h2>

    <label for="gatunek-filter">Gatunek:</label>
    <select id="gatunek-filter">
        <option value="all">Wszystkie</option>
        <?php foreach ($gatunki as $gatunek): ?>
            <option value="<?= $gatunek['nazwa'] ?>"><?= $gatunek['nazwa'] ?></option>
        <?php endforeach; ?>
    </select>

    <label for="cena-filter">Cena do:</label>
    <input type="number" id="cena-filter" placeholder="Maks. cena">

    <label for="sort-filter">Sortuj według:</label>
    <select id="sort-filter">
        <option value="cena_desc">Cena od najwyższej</option>
        <option value="cena_asc">Cena od najniższej</option>
        <option value="wykonawca_asc">Alfabetycznie wykonawca</option>
        <option value="tytul_asc">Alfabetycznie tytuł</option>
    </select>

    <button id="reset-filters">Resetuj</button>
</div>
 <!-- 🔹 FORMULARZ DO USUWANIA ALBUMOW -->
<form id="delete-albums-form" method="POST">
    <input type="hidden" name="albums_to_delete" id="albums-to-delete">
    <div class="albums-main-container">  
        <!-- 🔹 KONTENER NA ALBUMY -->
        <div class="albums-container">
            <!-- 🔹 LISTA ALBUMOW -->
            <div class="albums">
                <?php foreach ($albums as $album): ?>
                <div class="album" data-gatunek="<?= $album['gatunek'] ?>" data-cena="<?= $album['cena'] ?>" data-id="<?= $album['id'] ?>">
                    <a href="album.php?id=<?= $album['id'] ?>">
                        <div class="album-content">
                            <img src="<?= $album['zdjecie'] ?>" alt="<?= $album['tytuł'] ?>" class="album-img">
                            <img src="<?= $album['zdjecie2'] ?>" alt="<?= $album['tytuł'] ?>" class="album-img-hover">
                            <div class="album-text">
                                <h3><?= $album['wykonawca'] ?></h3>
                                <h2><?= $album['tytuł'] ?></h2>
                                <?php if (!empty($album['cena'])): ?>
                                    <p><strong>Cena:</strong> <?= number_format($album['cena'], 2) ?> PLN</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </a>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</form>
        <!-- 🔹 DANA ILOSC PIENIEDZY I OSTATNI ALBUM -->
        <div class="right-column-container">
            <div class="total-value">
                <h2>Łączna wartość kolekcji</h2>
                <p><?= number_format($totalValue, 2) ?> PLN</p>
            </div>

            <div class="recent-album">
                <h2>Ostatnio dodany album</h2>
                <?php if ($recentAlbum): ?>
                    <div class="album" data-gatunek="<?= $recentAlbum['gatunek'] ?>" data-cena="<?= $recentAlbum['cena'] ?>">
                        <a href="album.php?id=<?= $recentAlbum['id'] ?>">
                            <div class="album-content">
                                <img src="<?= $recentAlbum['zdjecie'] ?>" alt="<?= $recentAlbum['tytuł'] ?>">
                                <div class="album-text">
                                    <h3><?= $recentAlbum['wykonawca'] ?></h3>
                                    <h2><?= $recentAlbum['tytuł'] ?></h2>
                                    <?php if (!empty($recentAlbum['cena'])): ?>
                                        <p><strong>Cena:</strong> <?= number_format($recentAlbum['cena'], 2) ?> PLN</p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>
