<div class="container mt-5 mb-5">
    <h1>Coupons</h1>
    <form method="post" action="<?= site_url('product/coupons') ?>" >
        <label for="code">Coupon Code:</label>
        <input type="text" name="code" id="code" placeholder="enter coupon code" required>

        <label for="discount_type">Discount Type:</label>
        <select name="discount_type" id="discount_type" required>
            <option value="percentage">Percentage</option>
            <option value="fixed">Fixed</option>
        </select>

        <label for="expiry_date">Expiry Date:</label>
        <input type="date" name="expiry_date" id="expiry_date">

        <label for="min_order_amount">Minimum Order Amount:</label>
        <input 
            type="number" 
            name="min_order_amount" 
            id="min_order_amount" 
            value="<?= old('min_order_amount', $coupon['min_order_amount'] ?? '0.00') ?>" 
            step="0.01"
        >

        <label for="max_usage">Maximum Usage:</label>
        <input 
            type="number" 
            name="max_usage" 
            id="max_usage" 
            value="<?= old('max_usage', $coupon['max_usage'] ?? '') ?>" 
            min="0"
        >

        <!-- Other form fields -->
        <button type="submit">Add Coupon</button>
    </form>

</div>