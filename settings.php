<?php
require 'db.php';
session_start();

if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
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
    <link rel="stylesheet" href="settings.css">
</head>
<body>
    <div class="top-right-buttons">
        <button onclick="window.location.href='index.php'" class="add-btn">Strona główna</button>
        <button onclick="window.location.href='logout.php'" class="login-btn">Wyloguj</button>
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
    <script>
        const rngRed = document.getElementById("rngRed");
const txtRed = document.getElementById("txtRed");
const rngGreen = document.getElementById("rngGreen");
const txtGreen = document.getElementById("txtGreen");
const rngBlue = document.getElementById("rngBlue");
const txtBlue = document.getElementById("txtBlue");

function updateBoxShadows() {
    const red = rngRed.value;
    const green = rngGreen.value;
    const blue = rngBlue.value;
    const boxShadowColor = `rgb(${red}, ${green}, ${blue})`;

    document.querySelectorAll('*').forEach(element => {
        const style = window.getComputedStyle(element);
        if (style.boxShadow !== 'none') {
            const newBoxShadow = style.boxShadow.replace(/rgb\(\d+, \d+, \d+\)/g, boxShadowColor);
            element.style.boxShadow = newBoxShadow;
        }
    });
}

if (rngRed && txtRed) {
    rngRed.addEventListener("input", () => {
        txtRed.value = rngRed.value;
        updateBoxShadows();
    });
    txtRed.addEventListener("input", () => {
        const valueRed = parseInt(txtRed.value);
        if (!isNaN(valueRed) && valueRed >= 0 && valueRed <= 255) {
            rngRed.value = valueRed;
            updateBoxShadows();
        }
    });
}

if (rngGreen && txtGreen) {
    rngGreen.addEventListener("input", () => {
        txtGreen.value = rngGreen.value;
        updateBoxShadows();
    });
    txtGreen.addEventListener("input", () => {
        const valueGreen = parseInt(txtGreen.value);
        if (!isNaN(valueGreen) && valueGreen >= 0 && valueGreen <= 255) {
            rngGreen.value = valueGreen;
            updateBoxShadows();
        }
    });
}

if (rngBlue && txtBlue) {
    rngBlue.addEventListener("input", () => {
        txtBlue.value = rngBlue.value;
        updateBoxShadows();
    });
    txtBlue.addEventListener("input", () => {
        const valueBlue = parseInt(txtBlue.value);
        if (!isNaN(valueBlue) && valueBlue >= 0 && valueBlue <= 255) {
            rngBlue.value = valueBlue;
            updateBoxShadows();
        }
    });
}

window.addEventListener("DOMContentLoaded", () => {
    txtRed.value = rngRed.value;
    txtGreen.value = rngGreen.value;
    txtBlue.value = rngBlue.value;
    updateBoxShadows();
});
    </script>
</body>
</html>