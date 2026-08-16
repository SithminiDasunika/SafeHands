document.addEventListener('DOMContentLoaded', () => {
    // Interactive search filter logic
    const searchInput = document.getElementById('searchInput');
    const reportGrid = document.getElementById('reportGrid');

    if (searchInput && reportGrid) {
        searchInput.addEventListener('input', (event) => {
            const query = event.target.value.toLowerCase().trim();
            const cards = reportGrid.querySelectorAll('.report-card');

            cards.forEach(card => {
                const cardText = card.textContent.toLowerCase();
                if (cardText.includes(query)) {
                    card.style.display = 'flex';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    }

    // Active state micro-interactions on buttons
    const buttons = document.querySelectorAll('button');
    buttons.forEach(btn => {
        btn.addEventListener('click', () => {
            btn.style.transform = 'scale(0.97)';
            setTimeout(() => {
                btn.style.transform = 'none';
            }, 100);
        });
    });
});