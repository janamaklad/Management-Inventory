function addToCart(productName) {
    alert(productName + " has been added to your cart!");
}

// Filtering and Search Functionality
document.getElementById('searchInput').addEventListener('keyup', function() {
    let input = this.value.toLowerCase();
    let productCards = document.querySelectorAll('.card');
    productCards.forEach(card => {
        let title = card.querySelector('.card-title').innerText.toLowerCase();
        card.style.display = title.includes(input) ? '' : 'none';
    });
});

document.getElementById('filterSelect').addEventListener('change', function() {
    let filterValue = this.value;
    let productCards = document.querySelectorAll('.card');
    productCards.forEach(card => {
        let stockLevel = card.querySelector('.card-text:nth-child(3)').innerText.toLowerCase();
        if (filterValue === '') {
            card.style.display = '';
        } else {
            card.style.display = stockLevel.includes(filterValue) ? '' : 'none';
        }
    });
});
