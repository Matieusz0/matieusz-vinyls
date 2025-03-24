document.addEventListener('DOMContentLoaded', () => {
    const savedTheme = localStorage.getItem('theme') || 'dark';
    document.body.classList.add(savedTheme === 'dark' ? 'dark-mode' : 'light-mode');

    const modal = document.getElementById('songs-modal');
    const showSongsBtn = document.getElementById('show-songs-btn');
    const closeBtn = document.querySelector('.close-btn');

    // Show modal
    showSongsBtn.addEventListener('click', () => {
        modal.classList.add('show');
    });

    // Close modal
    closeBtn.addEventListener('click', () => {
        modal.classList.remove('show');
    });

    // Close modal when clicking outside
    window.addEventListener('click', (event) => {
        if (event.target === modal) {
            modal.classList.remove('show');
        }
    });

    // Adjust modal height dynamically
    const adjustModalHeight = () => {
        const modalContent = document.querySelector('.modal-content');
        const windowHeight = window.innerHeight;
        modalContent.style.maxHeight = `${windowHeight * 0.9}px`;
    };

    window.addEventListener('resize', adjustModalHeight);
    adjustModalHeight();
});
