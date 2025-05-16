<div id="confirmModal" class="confirm-modal">
    <div class="confirm-content">
        <div class="confirm-header">
            <h3>Confirm Action</h3>
            <button class="modal-close" onclick="document.getElementById('confirmModal').style.display='none'">&times;</button>
        </div>
        <div class="confirm-body">
            <p id="confirmMessage"></p>
        </div>
        <div class="confirm-footer">
            <button class="btn btn-outline" onclick="document.getElementById('confirmModal').style.display='none'">Cancel</button>
            <button id="confirmBtn" class="btn btn-danger">OK</button>
        </div>
    </div>
</div>

<script>
    // Helper to properly display the modal as flex
    window.showConfirmModal = function(type) {
        const modal = document.getElementById('confirmModal');
        const message = document.getElementById('confirmMessage');
        if (modal && message) {
            message.textContent = `Are you sure you want to ${type} this product?`;
            modal.style.display = 'flex';
        }
    };
</script>
