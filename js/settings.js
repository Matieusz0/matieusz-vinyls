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