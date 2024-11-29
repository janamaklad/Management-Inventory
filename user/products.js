function addToCart(productName) {
    const data = { productName: productName };

    fetch('/Management-Inventory/Cart/cart_handler.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(data),
    })
        .then(response => response.json())
        .then(result => {
            if (result.success) {
                alert(`${productName} added to cart!`);
            } else {
                alert('Failed to add product to cart.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred.');
        });
}

// Filtering and Search Functionality
document.getElementById('searchInput').addEventListener('keyup', function () {
    let input = this.value.toLowerCase();
    let productCards = document.querySelectorAll('.card');
    productCards.forEach(card => {
        let title = card.querySelector('.card-title').innerText.toLowerCase();
        card.style.display = title.includes(input) ? '' : 'none';
    });
});

document.getElementById('filterSelect').addEventListener('change', function () {
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
