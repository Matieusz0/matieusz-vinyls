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
        const sortOption = sortFilter.value;

        let sortedAlbums = Array.from(albums);

        switch (sortOption) {
            case "cena_asc":
                sortedAlbums.sort((a, b) => parseFloat(a.getAttribute("data-cena")) - parseFloat(b.getAttribute("data-cena")));
                break;
            case "cena_desc":
                sortedAlbums.sort((a, b) => parseFloat(b.getAttribute("data-cena")) - parseFloat(a.getAttribute("data-cena")));
                break;
            case "wykonawca_asc":
                sortedAlbums.sort((a, b) => a.querySelector(".album-text h3").textContent.localeCompare(b.querySelector(".album-text h3").textContent));
                break;
            case "tytul_asc":
                sortedAlbums.sort((a, b) => a.querySelector(".album-text h2").textContent.localeCompare(b.querySelector(".album-text h2").textContent));
                break;
        }

        sortedAlbums.forEach(album => {
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

            document.querySelector(".albums").appendChild(album);
        });
    }

    gatunekFilter.addEventListener("change", filterAlbums);
    cenaFilter.addEventListener("input", filterAlbums);
    sortFilter.addEventListener("change", filterAlbums);
    searchInput.addEventListener("input", filterAlbums);

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
            deleteAlbums();
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
        albumsToDeleteInput.value = JSON.stringify(albumIds);

        fetch('delete_albums.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ albums_to_delete: albumIds })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                selectedAlbums.forEach(album => album.remove());
            } else {
                alert('Wystąpił błąd podczas usuwania albumów.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }
});