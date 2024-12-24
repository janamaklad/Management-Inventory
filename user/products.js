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
    let productCards = document.querySelectorAll('#productContainer .card');

    productCards.forEach(card => {
        let title = card.querySelector('.card-title').innerText.toLowerCase();
        let isVisible = title.includes(input);
        card.style.display = isVisible ? '' : 'none';
    });
});

document.getElementById('filterSelect').addEventListener('change', function () {
    let filterValue = this.value.toLowerCase(); // Get selected category value
    let productCards = document.querySelectorAll('#productContainer .card'); // Select all product cards

    productCards.forEach(card => {
        let category = card.getAttribute('data-category'); // Get the card's category
        let isVisible = filterValue === 'category' || category === filterValue; // Show if matches or "All Categories"
        card.style.display = isVisible ? '' : 'none'; // Toggle visibility
    });
});

