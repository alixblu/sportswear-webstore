<!-- Edit Form Section (hidden by default) -->
<div id="edit-form-section" class="edit-form">
    <div class="tabs">
        <div class="tab active" onclick="switchTab('details')">Details</div>
        <div class="tab" onclick="switchTab('variants')">Variants</div>
    </div>
    <?php include './form.php' ?>
</div>
<!--
    <form id="editForm">
        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" id="edit-name" name="name" placeholder="Enter product's name ..." required>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label for="category">Category</label>
                <select id="edit-category" name="category" required>
                    <option value="">Select category</option>

                </select>
            </div>
            <div class="form-group">
                <label for="brand">Brand</label>
                <select id="edit-brand" name="brand" required>
                    <option value="">Select brand</option>

                </select>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label for="markup">Markup Percentage</label>
                <input type="number" id="edit-markup" name="markup" min="0" step="0.1" required>
            </div>
            <div class="form-group">
                <label for="discount">Discount ID</label>
                <select name="discount" id="edit-discount">
                    <option value="">Select discount</option>

                </select>
            </div>
        </div>
        <div class="form-group">
            <label for="description">Description</label>
            <textarea id="edit-description" name="description" placeholder="Enter a detailed description for producct ..." required></textarea>
        </div>

        <div class="form-actions">
            <button type="button" class="btn btn-outline" id="cancel-edit-btn">Cancel edit</button>
            <button type="submit" class="btn btn-primary" id="form-submit-btn">Save</button>
        </div>
    </form>
-->