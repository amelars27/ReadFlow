<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content border-0 shadow" style="background-color: #EAF9FF;">
            <div class="modal-body text-center py-4">
                <p class="fw-semibold mb-1" id="deleteModalMessage">Are you sure?</p>
                <p class="text-muted small mb-0">This action cannot be undone.</p>
            </div>
            <div class="modal-footer border-0 justify-content-center gap-2 pb-4 pt-0">
                <button type="button" class="btn" style="background-color: #EAF9FF; color: #1B347E;" data-bs-dismiss="modal">Cancel</button>
                <form id="deleteForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn text-white" style="background-color: #1B347E;">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>
