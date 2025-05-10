document.addEventListener('DOMContentLoaded', function () {
    const gatunekFilter = document.getElementById("gatunek-filter");
    const cenaFilter = document.getElementById("cena-filter");
    const sortFilter = document.getElementById("sort-filter");
    const resetFilters = document.getElementById("reset-filters");
    const searchInput = document.getElementById("search-input");
    const albums = document.querySelectorAll(".albums .album");

    function filterAlbums() {
        const selectedGatunek = gatunekFilter.value;
        const maxCena = parseFloat(cenaFilter.value) || Infinity;
        const searchQuery = searchInput.value.toLowerCase();

        albums.forEach(album => {
            const albumGatunek = album.getAttribute("data-gatunek");
            const albumCena = parseFloat(album.getAttribute("data-cena")) || 0;
            const albumWykonawca = album.querySelector(".album-text h3").textContent.toLowerCase();
            const albumTytul = album.querySelector(".album-text h2").textContent.toLowerCase();

            const matchesGatunek = selectedGatunek === "all" || albumGatunek === selectedGatunek;
            const matchesCena = albumCena <= maxCena;
            const matchesSearch = albumWykonawca.includes(searchQuery) || albumTytul.includes(searchQuery);

            if (matchesGatunek && matchesCena && matchesSearch) {
                album.classList.remove("hidden");
            } else {
                album.classList.add("hidden");
            }
        });

        // Pokaż tylko pierwsze 20 wyników
        const visibleAlbums = Array.from(albums).filter(album => !album.classList.contains("hidden"));
        visibleAlbums.forEach((album, index) => {
            album.style.display = index < 20 ? "block" : "none";
        });
    }

    // 🔹 RESETOWANIE FILTRÓW
    resetFilters.addEventListener("click", function () {
        gatunekFilter.value = "all";
        cenaFilter.value = "";
        sortFilter.value = "cena_desc";
        searchInput.value = "";
        filterAlbums();
    });

    filterAlbums();
});

document.addEventListener('DOMContentLoaded', function () {
    // 🔹 ZMIANA WIDOKU
    function setView(view) {
        const albumsContainer = document.querySelector('.albums');
        
        albumsContainer.classList.remove('large', 'medium', 'list');
        albumsContainer.classList.add(view);
    }

    // 🔹 PRZYCISKI DO ZMIANY WIDOKU
    document.getElementById('largeView').addEventListener('click', function() {
        setView('large');
    });
    document.getElementById('mediumView').addEventListener('click', function() {
        setView('medium');
    });
    document.getElementById('listView').addEventListener('click', function() {
        setView('list');
    });
});

document.addEventListener('DOMContentLoaded', function () {
    // Toggle delete button
    const deleteToggleBtn = document.getElementById('delete-toggle-btn');
    const albumsContainer = document.querySelector('.albums-container');
    const albums = document.querySelectorAll('.album');
    const deleteForm = document.getElementById('delete-albums-form');
    const albumsToDeleteInput = document.getElementById('albums-to-delete');

    deleteToggleBtn.addEventListener('click', function() {
        if (deleteToggleBtn.textContent === 'Usuń album') {
            deleteToggleBtn.textContent = 'Gotowe';
            albumsContainer.classList.add('disable-hover');
            albums.forEach(album => {
                album.addEventListener('click', toggleAlbumSelection);
            });
        } else {
            deleteToggleBtn.textContent = 'Usuń album';
            albumsContainer.classList.remove('disable-hover');
            albums.forEach(album => {
                album.removeEventListener('click', toggleAlbumSelection);
            });
            deleteAlbums(); // Trigger album deletion
        }
    });

    function toggleAlbumSelection(event) {
        event.preventDefault();
        const album = event.currentTarget;
        album.classList.toggle('selected');
    }

    function deleteAlbums() {
        const selectedAlbums = document.querySelectorAll('.album.selected');
        const albumIds = Array.from(selectedAlbums).map(album => album.getAttribute('data-id'));

        if (albumIds.length === 0) {
            alert('Nie wybrano żadnych albumów do usunięcia.');
            return;
        }

        console.log('Sending album IDs to delete:', albumIds); // Debugging log

        fetch('delete_albums.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ albums_to_delete: albumIds })
        })
        .then(response => response.json())
        .then(data => {
            console.log('Server response:', data); // Debugging log
            if (data.success) {
                selectedAlbums.forEach(album => album.remove());
                alert('Albumy zostały pomyślnie usunięte.');
            } else {
                alert(data.message || 'Wystąpił błąd podczas usuwania albumów.');
            }
        })
        .catch(error => {
            console.error('Error during fetch:', error); // Debugging log
            alert('Wystąpił błąd podczas komunikacji z serwerem.');
        });
    }
});

// Apply the saved theme on page load
document.addEventListener('DOMContentLoaded', () => {
    const savedTheme = localStorage.getItem('theme') || 'dark';
    document.body.classList.add(savedTheme === 'dark' ? 'dark-mode' : 'light-mode');
});

document.addEventListener('DOMContentLoaded', function () {
    const resetFilters = document.getElementById("reset-filters");

    resetFilters.addEventListener("click", function () {
        // Resetuj wartości filtrów
        document.getElementById("gatunek-filter").value = "all";
        document.getElementById("cena-filter").value = "";
        document.getElementById("sort-filter").value = "cena_desc";
        document.getElementById("search-input").value = "";

        // Przekierowanie na stronę bez parametrów GET
        window.location.href = window.location.pathname;
    });
});
