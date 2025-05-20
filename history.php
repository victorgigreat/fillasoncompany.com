<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Selector</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
</head>
<body>
    <div class="mb-3">
        <label for="product-select" class="form-label">Product</label>
        <select class="form-select select2-product" id="product-select" required>
            <option value="">Select Product</option>
            <!-- Products will be loaded via AJAX -->
        </select>
    </div>

    <script>
        $(document).ready(function() {
            // Initialize Select2
            $('#product-select').select2({
                placeholder: "Select Product",
                allowClear: true
            });

            // Load products
            loadProductsForSelects();
        });

        function loadProductsForSelects() {
            $.ajax({
                url: 'api/get_products.php',
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    let options = '<option value="">Select Product</option>';
                    data.forEach(product => {
                        options += `<option value="${product.id}">${product.name} (Stock: ${product.current_stock})</option>`;
                    });
                    $('#product-select').html(options).trigger('change'); // Update Select2
                },
                error: function(xhr) {
                    alert('Error loading products: ' + xhr.responseText);
                }
            });
        }
    </script>
</body>
</html>