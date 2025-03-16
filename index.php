<?php
require 'php/db.php';
include 'php/admin_check.php';

// 🔹 POBIERANIE ALBUMOW
$stmt = $pdo->prepare("SELECT albumy.*, gatunki.nazwa AS gatunek 
    FROM albumy 
    LEFT JOIN gatunki ON albumy.gatunek_id = gatunki.id
    ORDER BY albumy.data_wydania DESC
");
$stmt->execute();
$albums = $stmt->fetchAll(PDO::FETCH_ASSOC);

// 🔹 POBIERANIE GATUNKOW
$gatunkiStmt = $pdo->prepare("SELECT * FROM gatunki ORDER BY nazwa ASC");
$gatunkiStmt->execute();
$gatunki = $gatunkiStmt->fetchAll(PDO::FETCH_ASSOC);

// 🔹 OSTATNIO DODANY ALBUM
$recentAlbumStmt = $pdo->prepare("SELECT albumy.*, gatunki.nazwa AS gatunek 
    FROM albumy 
    LEFT JOIN gatunki ON albumy.gatunek_id = gatunki.id
    ORDER BY albumy.created_at DESC
    LIMIT 1
");
$recentAlbumStmt->execute();
$recentAlbum = $recentAlbumStmt->fetch(PDO::FETCH_ASSOC);

// 🔹 OBLICZANIE LACZNEJ WARTOSCI WSZYSTKICH ALBUMOW
$totalValueStmt = $pdo->prepare("SELECT SUM(cena) AS total_value FROM albumy");
$totalValueStmt->execute();
$totalValue = $totalValueStmt->fetch(PDO::FETCH_ASSOC)['total_value'];

// 🔹 ILOŚĆ ALBUMÓW W KOLEKCJI
$totalAlbumsStmt = $pdo->prepare("SELECT COUNT(*) AS total_albums FROM albumy");
$totalAlbumsStmt->execute();
$totalAlbums = $totalAlbumsStmt->fetch(PDO::FETCH_ASSOC)['total_albums'];

// 🔹 WARTOŚĆ KOLEKCJI WEDŁUG GATUNKÓW
$genreValuesStmt = $pdo->prepare("SELECT gatunki.nazwa AS genre, SUM(albumy.cena) AS value 
    FROM albumy 
    LEFT JOIN gatunki ON albumy.gatunek_id = gatunki.id 
    GROUP BY gatunki.nazwa
");
$genreValuesStmt->execute();
$genreValues = [];
while ($row = $genreValuesStmt->fetch(PDO::FETCH_ASSOC)) {
    $genreValues[$row['genre']] = $row['value'];
}

// 🔹 WARTOŚĆ KOLEKCJI WEDŁUG GATUNKÓW
$genreCountsStmt = $pdo->prepare("SELECT gatunki.nazwa AS genre, COUNT(albumy.id) AS count 
    FROM albumy 
    LEFT JOIN gatunki ON albumy.gatunek_id = gatunki.id 
    GROUP BY gatunki.nazwa
");
$genreCountsStmt->execute();
$genreCounts = [];
while ($row = $genreCountsStmt->fetch(PDO::FETCH_ASSOC)) {
    $genreCounts[$row['genre']] = $row['count'];
}

// 🔹 NAJCZĘŚCIEJ WYSTĘPUJĄCY ARTYSTA
$mostFrequentArtistStmt = $pdo->prepare("SELECT wykonawca, COUNT(*) AS count 
    FROM albumy 
    GROUP BY wykonawca 
    ORDER BY count DESC 
    LIMIT 1
");
$mostFrequentArtistStmt->execute();
$mostFrequentArtistData = $mostFrequentArtistStmt->fetch(PDO::FETCH_ASSOC);
$mostFrequentArtist = $mostFrequentArtistData['wykonawca'];
$mostFrequentArtistCount = $mostFrequentArtistData['count'];

// 🔹 NAJDROŻSZY ALBUM
$mostExpensiveAlbumStmt = $pdo->prepare("SELECT albumy.*, gatunki.nazwa AS gatunek 
    FROM albumy 
    LEFT JOIN gatunki ON albumy.gatunek_id = gatunki.id
    ORDER BY albumy.cena DESC
    LIMIT 1
");
$mostExpensiveAlbumStmt->execute();
$mostExpensiveAlbum = $mostExpensiveAlbumStmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>matieusz vinyls</title>
    <link rel="stylesheet" href="css/index.css">
    <script src="js/index.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
                                        <p><strong>Cena:</strong> <?= number_format($album['cena']) ?> PLN</p>
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
    <div class="stats-slideshow">
        <div class="stat-slide">
            <h2 id="stat-title">Łączna wartość:</h2>
            <p id="stat-value"><?= number_format($totalValue) ?> PLN</p>
        </div>
    </div>

    <div class="recent-album">
        <h2>Ostatnio dodany album</h2>
        <div class="album">
            <div class="album-content">
                <img src="<?= $recentAlbum['zdjecie'] ?>" alt="<?= $recentAlbum['tytuł'] ?>">
                <div class="album-text">
                    <h3><?= $recentAlbum['wykonawca'] ?></h3>
                    <h2><?= $recentAlbum['tytuł'] ?></h2>
                    <?php if (!empty($recentAlbum['cena'])): ?>
                        <p><strong>Cena:</strong> <?= number_format($recentAlbum['cena']) ?> PLN</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- 🔹 CHART CONTAINER -->
    <div class="chart-container">
        <h2>Ilość albumów według gatunku</h2>
        <canvas id="genreChart"></canvas>
    </div>
</div>
    <script>
document.addEventListener('DOMContentLoaded', function () {
    // Slide show for statistics
    const stats = [
        { title: 'Łączna wartość:', value: '<?= number_format($totalValue) ?> PLN' },
        { title: 'Ilość albumów:', value: '<?= $totalAlbums ?> albumów' },
        <?php foreach ($genreValues as $genre => $value): ?>
        { title: 'Wartość gatunku:', value: '<?= $genre ?><br><?= number_format($value) ?> PLN' },
        <?php endforeach; ?>
        { title: 'Najwięcej albumów:', value: '<?= $mostFrequentArtist ?> <br><?= $mostFrequentArtistCount ?> albumów' }
    ];
    let currentStat = 0;

    function showStat(index) {
        const statTitle = document.getElementById('stat-title');
        const statValue = document.getElementById('stat-value');
        statTitle.innerHTML = stats[index].title;
        statValue.innerHTML = stats[index].value;
    }

    function nextStat() {
        currentStat = (currentStat + 1) % stats.length;
        showStat(currentStat);
    }

    showStat(currentStat);
    setInterval(nextStat, 4000);

    // Apply the correct box shadow and background based on the current theme
    const statSlide = document.querySelector('.stat-slide');
    const savedTheme = localStorage.getItem('theme') || 'dark';
    let backgroundColor, borderColor;

    if (savedTheme === 'dark') {
        statSlide.style.background = 'rgba(20, 20, 20, 0.8)';
        statSlide.style.boxShadow = '0 0 15px rgba(161, 0, 255, 0.8)';
        backgroundColor = 'rgba(161, 0, 255, 0.8)';
        borderColor = 'rgba(161, 0, 255, 1)';
    } else {
        statSlide.style.background = 'rgba(230, 230, 230, 0.8)';
        statSlide.style.boxShadow = '0 0 10px rgba(128, 192, 255, 1)';
        backgroundColor = 'rgba(128, 192, 255, 0.8)';
        borderColor = 'rgba(128, 192, 255, 1)';
    }

    // Create the chart
    const ctx = document.getElementById('genreChart').getContext('2d');
    const genreChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: [<?php foreach ($genreCounts as $genre => $count) { echo "'$genre',"; } ?>],
            datasets: [{
                label: 'Ilość albumów',
                data: [<?php foreach ($genreCounts as $genre => $count) { echo "$count,"; } ?>],
                backgroundColor: backgroundColor,
                borderColor: borderColor,
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            },
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });

    // Slide show for recent album
    const recentAlbums = [
        {
            title: 'Ostatnio dodany',
            album: <?= json_encode($recentAlbum) ?>
        },
        {
            title: 'Najdroższy',
            album: <?= json_encode($mostExpensiveAlbum) ?>
        }
    ];
    let currentAlbum = 0;

    function showAlbum(index) {
        const albumTitle = document.querySelector('.recent-album h2');
        const albumContent = document.querySelector('.recent-album .album-content');
        const album = recentAlbums[index].album;

        albumTitle.textContent = recentAlbums[index].title;
        albumContent.innerHTML = `
            <img src="${album.zdjecie}" alt="${album.tytuł}">
            <div class="album-text">
                <h3>${album.wykonawca}</h3>
                <h2>${album.tytuł}</h2>
                ${album.cena ? `<p><strong>Cena:</strong> ${new Intl.NumberFormat().format(album.cena)} PLN</p>` : ''}
            </div>
        `;
    }

    function nextAlbum() {
        currentAlbum = (currentAlbum + 1) % recentAlbums.length;
        showAlbum(currentAlbum);
    }

    showAlbum(currentAlbum);
    setInterval(nextAlbum, 5000); // Change album every 5 seconds
});
    </script>
    </div>
</div>
</body>
</html>
