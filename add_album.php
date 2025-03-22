<?php
require 'php/db.php';

session_start();
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    header("Location: access_denied.php");
    exit();
}

// 🔹 POBIERANIE GATUNKOW
$gatunkiStmt = $pdo->prepare("SELECT * FROM gatunki ORDER BY nazwa ASC");
$gatunkiStmt->execute();
$gatunki = $gatunkiStmt->fetchAll(PDO::FETCH_ASSOC);

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

    if (!empty($nowy_gatunek)) {
        try {
            $nowy_gatunek = trim($nowy_gatunek);
            $nowy_gatunek = ucfirst(strtolower($nowy_gatunek));
    
            $stmt = $pdo->prepare("SELECT id FROM gatunki WHERE nazwa = :nowy_gatunek");
            $stmt->bindParam(':nowy_gatunek', $nowy_gatunek, PDO::PARAM_STR);
            $stmt->execute();
            $existing_gatunek = $stmt->fetch(PDO::FETCH_ASSOC);
    
            if ($existing_gatunek) {
                $gatunek_id = $existing_gatunek['id'];
            } else {
                $stmt = $pdo->prepare("INSERT INTO gatunki (nazwa) VALUES (:nowy_gatunek)");
                $stmt->bindParam(':nowy_gatunek', $nowy_gatunek, PDO::PARAM_STR);
                $stmt->execute();
    
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
    $stmt = $pdo->prepare("INSERT INTO albumy 
    (wykonawca, `tytuł`, opis, gatunek_id, data_wydania, ilosc_plyt, piosenki, cena, zdjecie, spotify_link) 
    VALUES 
    (:wykonawca, :tytul, :opis, :gatunek_id, :data_wydania, :ilosc_plyt, :piosenki, :cena, :zdjecie, :spotify_link)");

$stmt->bindParam(':wykonawca', $wykonawca, PDO::PARAM_STR);
$stmt->bindParam(':tytul', $tytuł, PDO::PARAM_STR); // 🛠 Poprawiona nazwa parametru!
$stmt->bindParam(':opis', $opis, PDO::PARAM_STR);
$stmt->bindParam(':gatunek_id', $gatunek_id, PDO::PARAM_INT);
$stmt->bindParam(':data_wydania', $data_wydania, PDO::PARAM_INT);
$stmt->bindParam(':ilosc_plyt', $ilosc_plyt, PDO::PARAM_INT);
$stmt->bindParam(':piosenki', $piosenki, PDO::PARAM_STR);
$stmt->bindParam(':cena', $cena, PDO::PARAM_STR);
$stmt->bindParam(':zdjecie', $zdjecie, PDO::PARAM_STR);
$stmt->bindParam(':spotify_link', $spotify_link, PDO::PARAM_STR);

// ✅ DEBUGUJ WARTOŚCI PRZED `execute()`
error_log("🔍 Wartości przed execute: " . print_r([
    'wykonawca' => $wykonawca,
    'tytul' => $tytuł,
    'opis' => $opis,
    'gatunek_id' => $gatunek_id,
    'data_wydania' => $data_wydania,
    'ilosc_plyt' => $ilosc_plyt,
    'piosenki' => $piosenki,
    'cena' => $cena,
    'zdjecie' => $zdjecie,
    'spotify_link' => $spotify_link
], true));

$stmt->execute();
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
                <button type="button" class="prev-btn">Powrót</button>
                <button type="submit">Dodaj</button>
            </div>
        </form>
    </div>
</body>
</html>
