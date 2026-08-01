{{-- resources/views/setup/partials/modals-common.blade.php --}}
<div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width:380px">
        <div class="modal-content">
            <div class="modal-header modal-header-danger">
                <div class="d-flex align-items-center gap-2">
                    <div style="width:32px;height:32px;border-radius:50%;background:#FEE2E2;border:1.5px solid #FACACA;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                        <svg width="15" height="15" viewBox="0 0 16 16" fill="none" stroke="#E03B3B" stroke-width="2.2"><path d="M8 2l6 12H2z"/><line x1="8" y1="7" x2="8" y2="10"/><circle cx="8" cy="12.5" r=".8" fill="#E03B3B"/></svg>
                    </div>
                    <span style="font-size:13px;font-weight:800;color:#1C2B3A">Confirm Delete</span>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" style="font-size:13px;font-weight:600;color:#4A5A6E;padding:18px 20px">
                Delete <strong id="deleteRecordName">this record</strong>?
                <div class="mt-1" style="font-size:12px;color:#8A97A8">This action cannot be undone.</div>
            </div>
            <div class="modal-footer modal-footer-setup justify-content-end gap-2">
                <button class="btn-modal-cancel" data-bs-dismiss="modal">Cancel</button>
                <button class="btn btn-danger btn-sm fw-bold px-3" id="btnConfirmDelete" style="font-family:'Nunito Sans',sans-serif;font-size:12px;height:32px">
                    <svg width="11" height="11" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2" class="me-1"><polyline points="3,4 13,4"/><path d="M5 4V2h6v2M4 4l1 10h6l1-10"/></svg>Delete Record
                </button>
            </div>
        </div>
    </div>
</div>
<div id="setupToast" class="toast align-items-center text-white border-0" role="alert" aria-live="assertive" aria-atomic="true">
    <div class="d-flex">
        <div class="toast-body fw-bold"></div>
        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
    </div>
</div>
