<?php
require 'php/db.php';
session_start();
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    header("Location: access_denied.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ustawienia</title>
    <link rel="stylesheet" href="css/settings.css">
    <script src="js/settings.js" defer></script>
</head>
<body>
    <div class="top-right-buttons">
        <button onclick="window.location.href='index.php'" class="back-btn">Strona główna</button>
    </div>

    <h1>Ustawienia</h1>

    <div class="settings-container">
        <h2>Opcje ustawień</h2>
        <div>
            <label for="rngRed">Red</label>
            <input type="range" id="rngRed" name="rngRed" min="0" max="255" step="1" value="161" />
            <input type="number" id="txtRed" name="txtRed" value="161" />
        </div>
        <div>
            <label for="rngGreen">Green</label>
            <input type="range" id="rngGreen" name="rngGreen" min="0" max="255" step="1" value="0" />
            <input type="number" id="txtGreen" name="txtGreen" value="0" />
        </div>
        <div>
            <label for="rngBlue">Blue</label>
            <input type="range" id="rngBlue" name="rngBlue" min="0" max="255" step="1" value="255" />
            <input type="number" id="txtBlue" name="txtBlue" value="255" />
        </div>
    </div>
</body>
</html>