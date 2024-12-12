<div class="container mt-5 mb-5">
    <h1>Add Coupon</h1>
    <form method="post" action="<?= site_url('product/addCoupons') ?>">
        <!-- Coupon Code -->
        <div class="form-group">
            <label for="code">Coupon Code:</label>
            <input 
                type="text" 
                name="code" 
                id="code" 
                placeholder="Enter coupon code" 
                class="form-control" 
                required
            >
        </div>

        <!-- Discount Type -->
        <div class="form-group">
            <label for="discount_type">Discount Type:</label>
            <select name="discount_type" id="discount_type" class="form-control" required>
                <option value="">Select Discount Type</option>
                <option value="percentage">Percentage</option>
                <option value="fixed">Fixed</option>
            </select>
        </div>

        <!-- Discount Value -->
        <div class="form-group">
            <label for="discount_value">Discount Value:</label>
            <input 
                type="number" 
                name="discount_value" 
                id="discount_value" 
                value="0.00" 
                step="0.01" 
                class="form-control" 
                required
            >
            <small class="form-text text-muted">
                For percentage, use values like 10 for 10%. For fixed, use amounts like 100.
            </small>
        </div>

        <!-- Maximum Discount Value (Applicable only for percentage) -->
        <div class="form-group" id="max_discount_field" style="display: none;">
            <label for="max_discount_value">Maximum Discount Value:</label>
            <input 
                type="number" 
                name="max_discount_value" 
                id="max_discount_value" 
                step="0.01" 
                class="form-control"
            >
            <small class="form-text text-muted">
                Maximum discount cap (only for percentage discounts). Leave blank if no cap is required.
            </small>
        </div>

        <!-- Expiry Date -->
        <div class="form-group">
            <label for="expiry_date">Expiry Date:</label>
            <input 
                type="date" 
                name="expiry_date" 
                id="expiry_date" 
                class="form-control" 
                required
            >
        </div>

        <!-- Minimum Order Amount -->
        <div class="form-group">
            <label for="min_order_amount">Minimum Order Amount:</label>
            <input 
                type="number" 
                name="min_order_amount" 
                id="min_order_amount" 
                value="0.00" 
                step="0.01" 
                class="form-control"
            >
        </div>

        <!-- Maximum Usage -->
        <div class="form-group">
            <label for="max_usage">Maximum Usage:</label>
            <input 
                type="number" 
                name="max_usage" 
                id="max_usage" 
                min="1" 
                class="form-control"
            >
        </div>

        <!-- Submit Button -->
        <button type="submit" class="btn btn-primary mt-3">Add Coupon</button>
    </form>
</div>

<!-- JavaScript for Dynamic Field Display -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const discountType = document.getElementById('discount_type');
        const maxDiscountField = document.getElementById('max_discount_field');

        // Function to toggle max discount field
        const toggleMaxDiscountField = () => {
            if (discountType.value === 'percentage') {
                maxDiscountField.style.display = 'block';
            } else {
                maxDiscountField.style.display = 'none';
            }
        };

        // Attach change event listener
        discountType.addEventListener('change', toggleMaxDiscountField);

        // Initialize on page load
        toggleMaxDiscountField();
    });
</script>
