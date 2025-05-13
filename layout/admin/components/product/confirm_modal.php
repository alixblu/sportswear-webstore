<div id="confirmModal" class="confirm-modal">
    <div class="confirm-content">
        <div class="confirm-header">
            <h3>Confirm Deletion</h3>
            <button class="modal-close" onclick="document.getElementById('confirmModal').style.display='none'">&times;</button>
        </div>
        <div class="confirm-body">
            <p id="confirmMessage">Are you sure you want to delete this product?</p>
        </div>
        <div class="confirm-footer">
            <button class="btn btn-outline" onclick="document.getElementById('confirmModal').style.display='none'">Cancel</button>
            <button id="confirmBtn" class="btn btn-danger">Delete</button>
        </div>
    </div>
</div>

<script>
    // Helper to properly display the modal as flex
    window.showConfirmModal = function() {
        const modal = document.getElementById('confirmModal');
        if (modal) {
            modal.style.display = 'flex';
        }
    };
</script>
