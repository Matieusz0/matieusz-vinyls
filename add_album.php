<?php
require 'php/db.php';

session_start();
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    header("Location: access_denied.php");
    exit();
}

// 🔹 POBIERANIE GATUNKOW
$gatunki = $pdo->query("SELECT * FROM gatunki")->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $wykonawca = $_POST['wykonawca'] ?? null;
    $tytuł = $_POST['tytuł'];
    $opis = $_POST['opis'] ?? null;
    $gatunek_id = $_POST['gatunek_id'] ?? null;
    $nowy_gatunek = $_POST['nowy_gatunek'] ?? null; 
    $data_wydania = $_POST['data_wydania'] ?? null;
    $ilosc_plyt = $_POST['ilosc_plyt'] ?? null;
    $piosenki = $_POST['piosenki'] ?? null;
    $cena = $_POST['cena'] ?? null;
    $spotify_link = $_POST['spotify_link'] ?? null;
    
    // 🔹 ZDJECIE
    $zdjecie = null;
    if (!empty($_FILES['zdjecie']['name'])) {
        $zdjecie_nazwa = time() . "_" . $_FILES['zdjecie']['name'];
        $target_path = "uploads/" . $zdjecie_nazwa;
        move_uploaded_file($_FILES['zdjecie']['tmp_name'], $target_path);
        $zdjecie = $target_path;
    }

    // 🔹 ZDJECIE2
    $zdjecie2 = null;
    if (!empty($_FILES['zdjecie2']['name'])) {
        $zdjecie2_nazwa = time() . "_" . $_FILES['zdjecie2']['name'];
        $target_path2 = "uploads/" . $zdjecie2_nazwa;
        move_uploaded_file($_FILES['zdjecie2']['tmp_name'], $target_path2);
        $zdjecie2 = $target_path2;
    }

    if (!empty($nowy_gatunek)) {
        try {
            $nowy_gatunek = trim($nowy_gatunek);
            $nowy_gatunek = ucfirst(strtolower($nowy_gatunek));
    
            $stmt = $pdo->prepare("SELECT id FROM gatunki WHERE nazwa = ?");
            $stmt->execute([$nowy_gatunek]);
            $existing_gatunek = $stmt->fetch(PDO::FETCH_ASSOC);
    
            if ($existing_gatunek) {
                $gatunek_id = $existing_gatunek['id'];
            } else {
                $stmt = $pdo->prepare("INSERT INTO gatunki (nazwa) VALUES (?)");
                $stmt->execute([$nowy_gatunek]);
    
                $gatunek_id = $pdo->lastInsertId();
                error_log("Nowy gatunek dodany: $nowy_gatunek, ID: $gatunek_id");

                if (empty($gatunek_id)) {
                    die("BŁĄD: lastInsertId() zwrócił NULL! Nowy gatunek nie zapisany.");
                }
            }
        } catch (PDOException $e) {
            die("Błąd dodawania gatunku: " . $e->getMessage());
        }
    } else if ($gatunek_id === null) {
        $gatunek_id = NULL;
    }

    // 🔹 DODAWANIE ALBUMU
    $stmt = $pdo->prepare("INSERT INTO albumy (wykonawca, tytuł, opis, gatunek_id, data_wydania, ilosc_plyt, piosenki, cena, zdjecie, zdjecie2, spotify_link) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$wykonawca, $tytuł, $opis, $gatunek_id, $data_wydania, $ilosc_plyt, $piosenki, $cena, $zdjecie, $zdjecie2, $spotify_link]);

    header("Location: index.php");
    exit();
}
?>


<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Dodaj Album</title>
    <link rel="stylesheet" href="css/add_album.css">
    <script src="js/add_album.js" defer></script>
</head>
<body>
    <div>
        <h1>Dodaj nową płytę</h1>
    </div>
    <div class="przyciski">  
        <button type="button" class="back-btn" onclick="window.location.href='index.php'">🔙 Powrót</button>
    </div>
    <div>
        <form action="" method="POST" enctype="multipart/form-data">
            <div class="form-step">
                <label for="wykonawca">Wykonawca:</label>
                <input type="text" name="wykonawca">
                <button type="button" class="next-btn">Dalej</button>
            </div>

            <div class="form-step">
                <label for="tytuł">Tytuł:</label>
                <input type="text" name="tytuł" required>
                <button type="button" class="prev-btn">Powrót</button>
                <button type="button" class="next-btn">Dalej</button>
            </div>

            <div class="form-step">
                <label for="opis">Opis:</label>
                <textarea name="opis"></textarea>
                <button type="button" class="prev-btn">Powrót</button>
                <button type="button" class="next-btn">Dalej</button>
            </div>

            <div class="form-step">
                <label for="gatunek">Gatunek:</label>
                <select name="gatunek_id">
                    <option value="">Wybierz gatunek</option>
                    <?php foreach ($gatunki as $gatunek): ?>
                    <option value="<?= $gatunek['id'] ?>"><?= $gatunek['nazwa'] ?></option>
                    <?php endforeach; ?>
                </select>
                <label for="nowy_gatunek">Jeśli gatunek nie istnieje, wpisz nowy:</label>
                <input type="text" name="nowy_gatunek" id="nowy_gatunek" placeholder="Wpisz nowy gatunek">
                <button type="button" class="prev-btn">Powrót</button>
                <button type="button" class="next-btn">Dalej</button>
            </div>

            <div class="form-step">
                <label for="data_wydania">Data wydania:</label>
                <input type="number" name="data_wydania">
                <button type="button" class="prev-btn">Powrót</button>
                <button type="button" class="next-btn">Dalej</button>
            </div>

            <div class="form-step">
                <label for="ilosc_plyt">Ilość płyt:</label>
                <input type="number" name="ilosc_plyt">
                <button type="button" class="prev-btn">Powrót</button>
                <button type="button" class="next-btn">Dalej</button>
            </div>

            <div class="form-step">
                <label for="piosenki">Piosenki:</label>
                <textarea name="piosenki"></textarea>
                <button type="button" class="prev-btn">Powrót</button>
                <button type="button" class="next-btn">Dalej</button>
            </div>

            <div class="form-step">
                <label for="cena">Cena:</label>
                <input type="number" step="0.01" name="cena">
                <button type="button" class="prev-btn">Powrót</button>
                <button type="button" class="next-btn">Dalej</button>
            </div>

            <div class="form-step">
                <label for="spotify_link">Spotify Link:</label>
                <input type="text" name="spotify_link" placeholder="Wklej link do Spotify">
                <button type="button" class="prev-btn">Powrót</button>
                <button type="button" class="next-btn">Dalej</button>
            </div>

            <div class="form-step">
                <label for="zdjecie">Wybierz zdjęcie:</label>
                <div class="zdjecie-upload-container">
                    <input type="file" id="zdjecie" name="zdjecie" accept="image/*" onchange="previewZdjecie(event)">
                    <div id="zdjeciePreview" class="zdjecie-preview"></div>
                </div>
                <label for="zdjecie2">Wybierz drugie zdjęcie:</label>
                <div class="zdjecie-upload-container">
                    <input type="file" id="zdjecie2" name="zdjecie2" accept="image/*" onchange="previewZdjecie2(event)">
                    <div id="zdjeciePreview2" class="zdjecie-preview"></div>
                </div>
                <button type="button" class="prev-btn">Powrót</button>
                <button type="submit">Dodaj</button>
            </div>
        </form>
    </div>
</body>
</html>
