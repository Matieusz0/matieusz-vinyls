// 🔹 PODGLĄD ZDJĘCIA PRZED WYSŁANIEM
function previewZdjecie(event) {
    const file = event.target.files[0];
    const reader = new FileReader();

    reader.onload = function () {
        const zdjeciePreview = document.getElementById('zdjeciePreview');
        zdjeciePreview.style.display = 'flex';
        zdjeciePreview.innerHTML = `<img src="${reader.result}" alt="Preview">`;
    }

    if (file) {
        reader.readAsDataURL(file);
    }
}

// 🔹 PODGLĄD DRUGIEGO ZDJĘCIA PRZED WYSŁANIEM
function previewZdjecie2(event) {
    const file = event.target.files[0];
    const reader = new FileReader();

    reader.onload = function () {
        const zdjeciePreview2 = document.getElementById('zdjeciePreview2');
        zdjeciePreview2.style.display = 'flex';
        zdjeciePreview2.innerHTML = `<img src="${reader.result}" alt="Preview">`;
    }

    if (file) {
        reader.readAsDataURL(file);
    }
}

// 🔹 WALIDACJA FORMULARZA
document.addEventListener('DOMContentLoaded', function () {
    const form = document.querySelector('form');
    const gatunekSelect = document.querySelector('select[name="gatunek_id"]');
    const nowyGatunekInput = document.querySelector('input[name="nowy_gatunek"]');
    const errorMessage = document.createElement('p');
    errorMessage.style.color = 'red';
    errorMessage.style.textAlign = 'center';
    errorMessage.style.display = 'none';
    errorMessage.textContent = 'Proszę wybrać gatunek lub wpisać nowy gatunek.';
    form.appendChild(errorMessage);

    form.addEventListener('submit', function (event) {
        if (!gatunekSelect.value && !nowyGatunekInput.value.trim()) {
            event.preventDefault();
            errorMessage.style.display = 'block';
        } else {
            errorMessage.style.display = 'none';
        }
    });
});