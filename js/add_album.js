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

document.addEventListener('DOMContentLoaded', function () {
    const formSteps = document.querySelectorAll('.form-step');
    const nextBtns = document.querySelectorAll('.next-btn');
    const prevBtns = document.querySelectorAll('.prev-btn');
    let currentStep = 0;

    formSteps[currentStep].classList.add('active');

    nextBtns.forEach((btn, index) => {
        btn.addEventListener('click', () => {
            formSteps[currentStep].classList.remove('active');
            currentStep++;
            formSteps[currentStep].classList.add('active');
        });
    });

    prevBtns.forEach((btn, index) => {
        btn.addEventListener('click', () => {
            formSteps[currentStep].classList.remove('active');
            currentStep--;
            formSteps[currentStep].classList.add('active');
        });
    });
});

// Apply the saved theme on page load
document.addEventListener('DOMContentLoaded', () => {
    const savedTheme = localStorage.getItem('theme') || 'dark';
    document.body.classList.add(savedTheme === 'dark' ? 'dark-mode' : 'light-mode');
});
