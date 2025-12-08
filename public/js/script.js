// public/js/script.js

document.addEventListener('DOMContentLoaded', () => {
    initPlayerSearch();
    initAutoHideAlerts();
});

function initPlayerSearch() {
    const searchInput = document.getElementById('search');
    const table = document.getElementById('players-table');

    if (!searchInput || !table) {
        return;
    }

    const rows = table.querySelectorAll('tbody tr');

    searchInput.addEventListener('input', function () {
        const filter = this.value.toLowerCase();

        rows.forEach(row => {
            const nameCell = row.querySelector('.player-name');
            const countryCell = row.querySelector('.player-country');

            const name = nameCell ? nameCell.textContent.toLowerCase() : '';
            const country = countryCell ? countryCell.textContent.toLowerCase() : '';

            const match = name.includes(filter) || country.includes(filter);
            row.style.display = match ? '' : 'none';
        });
    });
}

function initAutoHideAlerts() {
    const alerts = document.querySelectorAll('.alert-auto-hide');

    alerts.forEach(alert => {
        setTimeout(() => {
            alert.classList.add('fade-out');

            alert.addEventListener('transitionend', () => {
                if (alert && alert.parentNode) {
                    alert.parentNode.removeChild(alert);
                }
            });
        }, 5000);
    });
}
