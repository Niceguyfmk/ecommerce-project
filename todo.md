E-Commerce application

Attributes:
        <h3>Product Attributes</h3>
        <?php foreach ($attributes as $attribute): ?>
            <div class="mb-3">

                <label for="attribute_<?= $attribute['attribute_id'] ?>" class="form-label"><?= esc($attribute['attribute_name']) ?></label>
                <input type="text" class="form-control" id="attribute_<?= $attribute['attribute_id'] ?>"
                    name="attributes[<?= $attribute['attribute_id'] ?>][value]" placeholder="Enter value (e.g., Red, XL, H&M)">

            </div>

            <div class="mb-3">
                <label for="attribute_<?= $attribute['attribute_id'] ?>_price" class="form-label">Additional Price</label>
                <input type="number" step="0.01" class="form-control" id="attribute_<?= $attribute['attribute_id'] ?>_price"
                    name="attributes[<?= $attribute['attribute_id'] ?>][additional_price]" placeholder="Enter additional price">
            </div>

            <div class="mb-3">
                <label for="attribute_<?= $attribute['attribute_id'] ?>_quantity" class="form-label">Quantity</label>
                <input type="number" class="form-control" id="attribute_<?= $attribute['attribute_id'] ?>_quantity"
                    name="attributes[<?= $attribute['attribute_id'] ?>][quantity]" placeholder="Enter quantity">
            </div>

        <?php endforeach; ?>



