document.addEventListener('DOMContentLoaded', function () {
    // Get the URL parameters
    const urlParams = new URLSearchParams(window.location.search);
    const status = urlParams.get('status');

    // Bootstrap Modal element
    var statusModal = new bootstrap.Modal(document.getElementById('statusModal'));

    // Check if there's a status parameter in the URL
    if (status === 'updated') {
        document.getElementById('statusMessage').innerHTML = 'Supplier updated successfully!';
        document.getElementById('statusMessage').classList.add('alert-success');
        statusModal.show();
    } else if (status === 'created') {
        document.getElementById('statusMessage').innerHTML = 'New supplier added successfully!';
        document.getElementById('statusMessage').classList.add('alert-success');
        statusModal.show();
    } else if (status === 'deleted') {
        document.getElementById('statusMessage').innerHTML = 'Supplier deleted successfully!';
        document.getElementById('statusMessage').classList.add('alert-danger');
        statusModal.show();
    }

    // Add event listeners for the Edit buttons
    var editButtons = document.querySelectorAll('.editBtn');
    editButtons.forEach(function (button) {
        button.addEventListener('click', function () {
            // Get the supplier details from data attributes
            var supplierId = this.getAttribute('data-id');
            var supplierName = this.getAttribute('data-name');
            var contactInfo = this.getAttribute('data-contact');
            var paymentTerms = this.getAttribute('data-terms');

            // Set the modal form values
            document.getElementById('supplier_id').value = supplierId;
            document.getElementById('supplier_name').value = supplierName;
            document.getElementById('contact_info').value = contactInfo;
            document.getElementById('payment_terms').value = paymentTerms;
        });
    });
});
