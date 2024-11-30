$(document).ready(function () {
    // Handle quantity input changes
    $('.quantity-input').on('input', function () {
        const productName = $(this).data('product');
        const quantity = $(this).val();
        updateCart(productName, quantity);
    });

    // Handle delete button click
    $('.delete-btn').on('click', function () {
        const productName = $(this).data('product');
        deleteProduct(productName);
    });

    // Function to update the cart
    function updateCart(productName, quantity) {
        $.post('cart.php', {
            action: 'update',
            product_name: productName,
            quantity: quantity
        }, function (response) {
            const data = JSON.parse(response);
            if (data.status === 'success') {
                if (quantity == 0) {
                    $(`tr[data-product="${productName}"]`).remove(); // Remove row if quantity is 0
                }
                refreshCart(data.total);
            }
        });
    }

    // Function to delete a product
    function deleteProduct(productName) {
        $.post('cart.php', {
            action: 'delete',
            product_name: productName
        }, function (response) {
            const data = JSON.parse(response);
            if (data.status === 'success') {
                $(`tr[data-product="${productName}"]`).remove(); // Remove row from table
                refreshCart(data.total);
            }
        });
    }

    // Refresh cart total
    function refreshCart(total) {
        $('#cartTotal').text(total);
        updateItemTotals();
    }

    // Update item totals
    function updateItemTotals() {
        $('#cartBody tr').each(function () {
            const quantity = $(this).find('.quantity-input').val();
            const price = $(this).find('td:nth-child(2)').text().replace('$', '');
            const itemTotal = parseFloat(price) * parseInt(quantity, 10);
            $(this).find('.item-total').text('$' + itemTotal.toFixed(2));
        });
    }
});
