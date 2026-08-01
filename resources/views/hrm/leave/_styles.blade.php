<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<style>
    /* ═══ HRM LEAVE MODULE — SHARED STYLES ═══════════════════════════ */
    /* Prevent infinite onerror loop on broken images */
    img.hrm-emp-photo,
    img.slip-emp-photo {
        min-width: 1px;
        min-height: 1px;
    }

    .hrm-page {
        font-size: 13px;
    }

    .hrm-page-title {
        font-size: 15px;
        font-weight: 500;
        color: #1a3c5e;
        display: flex;
        align-items: center;
        gap: 7px;
        margin-bottom: 12px;
    }

    /* Cards */
    .hrm-card {
        background: #fff;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        overflow: hidden;
        margin-bottom: 12px;
        box-shadow: 0 1px 4px rgba(0, 0, 0, .06);
    }

    .hrm-card-header {
        background: #1a3c5e;
        color: #fff;
        padding: 7px 14px;
        font-size: 12.5px;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 7px;
    }

    .hrm-card-body {
        padding: 12px 14px;
    }

    .hrm-card-body.p-0 {
        padding: 0;
    }

    /* Form */
    .hrm-label {
        font-size: 11.5px;
        color: #555;
        margin-bottom: 3px;
        display: block;
        font-weight: 500;
    }

    .hrm-control {
        width: 100%;
        background: #fff;
        border: 1px solid #ced4da;
        border-radius: 6px;
        padding: 4px 9px;
        font-size: 12.5px;
        color: #212529;
        height: 30px;
        transition: border-color .15s;
    }

    .hrm-control:focus {
        border-color: #1a3c5e;
        outline: none;
        box-shadow: 0 0 0 2px rgba(26, 60, 94, .12);
    }

    .hrm-control[readonly],
    .hrm-control.readonly {
        background: #f4f6f9;
        color: #666;
    }

    .hrm-control.bg-calc {
        background: #f0f4f0;
        color: #198754;
    }

    /* Input group */
    .hrm-input-group {
        display: flex;
    }

    .hrm-input-group .hrm-control {
        border-radius: 6px 0 0 6px;
    }

    .hrm-ig-btn {
        background: #1a3c5e;
        color: #fff;
        border: 1px solid #1a3c5e;
        padding: 0 10px;
        border-radius: 0 6px 6px 0;
        cursor: pointer;
        font-size: 13px;
    }

    .hrm-ig-btn:hover {
        background: #14304d;
    }

    /* Buttons */
    .hrm-btn {
        border: none;
        border-radius: 6px;
        padding: 6px 16px;
        font-size: 12.5px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        transition: opacity .15s;
        text-decoration: none;
    }

    .hrm-btn:hover {
        opacity: .88;
        text-decoration: none;
    }

    .hrm-btn-primary {
        background: #1a3c5e;
        color: #fff;
    }

    .hrm-btn-secondary {
        background: #6c757d;
        color: #fff;
    }

    .hrm-btn-danger {
        background: transparent;
        color: #dc3545;
        border: 1px solid #dc3545;
    }

    .hrm-btn-danger:hover {
        background: #dc3545;
        color: #fff;
        opacity: 1;
    }

    .hrm-btn-success-outline {
        background: transparent;
        color: #198754;
        border: 1px solid #198754;
        padding: 2px 7px;
        font-size: 11.5px;
        border-radius: 5px;
        cursor: pointer;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
    }

    .hrm-btn-danger-outline {
        background: transparent;
        color: #dc3545;
        border: 1px solid #dc3545;
        padding: 2px 7px;
        font-size: 11.5px;
        border-radius: 5px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
    }

    .hrm-btn-danger-outline:hover {
        background: #dc3545;
        color: #fff;
    }

    .hrm-btn-success-outline:hover {
        background: #198754;
        color: #fff;
    }

    .hrm-btn-add {
        background: #1a3c5e;
        color: #fff;
        border: none;
        padding: 4px 11px;
        border-radius: 6px;
        cursor: pointer;
        font-size: 11.5px;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }

    .hrm-btn-add:hover {
        background: #14304d;
    }

    /* Photo */
    .hrm-emp-photo {
        width: 82px;
        height: 100px;
        object-fit: cover;
        border: 2px solid #dee2e6;
        border-radius: 5px;
        background: #f4f6f9;
    }

    /* Balance cards */
    .hrm-bal-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 9px;
    }

    .hrm-bal-card {
        border-radius: 6px;
        padding: 9px 11px;
        border: 1px solid #dee2e6;
        border-left-width: 3px;
        background: #fff;
    }

    .hrm-bal-card.casual {
        border-left-color: #1a6bb5;
    }

    .hrm-bal-card.sick {
        border-left-color: #c0392b;
    }

    .hrm-bal-card.earn {
        border-left-color: #1d8a5e;
    }

    .hrm-bal-title {
        font-size: 11px;
        font-weight: 600;
        margin-bottom: 5px;
    }

    .hrm-bal-title.casual {
        color: #1a6bb5;
    }

    .hrm-bal-title.sick {
        color: #c0392b;
    }

    .hrm-bal-title.earn {
        color: #1d8a5e;
    }

    .hrm-bal-nums {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr;
        text-align: center;
        gap: 2px;
    }

    .hrm-bal-lbl {
        font-size: 9.5px;
        color: #888;
    }

    .hrm-bal-val {
        font-size: 14px;
        font-weight: 600;
    }

    .hrm-bal-val.casual {
        color: #1a6bb5;
    }

    .hrm-bal-val.sick {
        color: #c0392b;
    }

    .hrm-bal-val.earn {
        color: #1d8a5e;
    }

    /* Detail table */
    .hrm-detail-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 11.5px;
    }

    .hrm-detail-table thead th {
        background: #f0f3f6;
        padding: 6px;
        text-align: left;
        border: 1px solid #dee2e6;
        font-weight: 500;
        color: #333;
        white-space: nowrap;
        font-size: 11px;
    }

    .hrm-detail-table tbody td {
        padding: 3px 4px;
        border: 1px solid #dee2e6;
        vertical-align: middle;
    }

    .hrm-detail-table tbody tr:hover td {
        background: #f8fafc;
    }

    /* Table cell inputs */
    .td-input {
        width: 100%;
        border: none;
        background: transparent;
        font-size: 11.5px;
        color: #212529;
        outline: none;
        padding: 2px 3px;
    }

    .td-input:focus {
        background: #eef4ff;
        border-radius: 3px;
    }

    .td-input[readonly] {
        color: #888;
    }

    .td-input.calc {
        color: #198754;
    }

    .td-select {
        width: 100%;
        border: none;
        background: transparent;
        font-size: 11.5px;
        color: #212529;
        outline: none;
        padding: 1px 2px;
    }

    .td-select:focus {
        background: #eef4ff;
        border-radius: 3px;
    }

    /* Flatpickr */
    .fp-wrap {
        position: relative;
    }

    .fp-wrap .flatpickr-input {
        width: 100%;
        border: none;
        background: transparent;
        font-size: 11.5px;
        outline: none;
        padding: 2px 3px;
        color: #212529;
    }

    .fp-wrap .flatpickr-input:focus {
        background: #eef4ff;
        border-radius: 3px;
    }

    .flatpickr-input {
        background: #fff !important;
    }

    /* Action bar */
    .hrm-action-bar {
        position: relative;
        display: flex;
        justify-content: center;
        align-items: center;
        margin: 12px 0 4px;
        padding: 12px 14px;
        background: #fff;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        box-shadow: 0 1px 4px rgba(0, 0, 0, .04);
    }

    .hrm-action-group {
        display: flex;
        gap: 8px;
        align-items: center;
        justify-content: center;
        flex-wrap: wrap;
    }

    .hrm-delete-master {
        position: absolute;
        right: 14px;
        top: 50%;
        transform: translateY(-50%);
    }

    @media (max-width: 767.98px) {
        .hrm-action-bar {
            flex-direction: column;
            gap: 10px;
        }

        .hrm-delete-master {
            position: static;
            transform: none;
        }
    }

    /* List table */
    .hrm-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 12.5px;
    }

    .hrm-table thead th {
        background: #f0f3f6;
        padding: 7px 10px;
        border: 1px solid #dee2e6;
        font-weight: 500;
        font-size: 12px;
        color: #333;
    }

    .hrm-table tbody td {
        padding: 6px 10px;
        border: 1px solid #dee2e6;
        color: #333;
    }

    .hrm-table tbody tr:hover td {
        background: #f5f8fc;
    }

    .hrm-pagination {
        padding: 10px 14px;
    }

    /* Search card */
    .hrm-search-card {
        background: #fff;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        padding: 10px 14px;
        margin-bottom: 12px;
    }

    /* Alerts / toasts */
    .hrm-alert-info {
        background: #e8f1fb;
        border: 1px solid #b8d0f0;
        color: #1a3c5e;
        border-radius: 6px;
        padding: 9px 14px;
        font-size: 12.5px;
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .hrm-alert-success {
        background: #e6f4ea;
        border: 1px solid #a8d5b5;
        color: #1d6a35;
        border-radius: 6px;
        padding: 9px 14px;
        font-size: 12.5px;
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .hrm-alert-error {
        background: #fdecea;
        border: 1px solid #f5b7b1;
        color: #922b21;
        border-radius: 6px;
        padding: 9px 14px;
        font-size: 12.5px;
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .hrm-badge-info {
        background: #dceeff;
        color: #1a3c5e;
        border: 1px solid #b0d0f0;
        border-radius: 12px;
        padding: 2px 10px;
        font-size: 11px;
        font-weight: 500;
    }

    /* Inline toast (no-redirect save feedback) */
    #hrmToast {
        position: fixed;
        bottom: 20px;
        right: 20px;
        z-index: 9999;
        min-width: 220px;
        padding: 11px 18px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 500;
        box-shadow: 0 4px 16px rgba(0, 0, 0, .18);
        display: none;
        align-items: center;
        gap: 9px;
    }

    #hrmToast.success {
        background: #1a3c5e;
        color: #fff;
    }

    #hrmToast.error {
        background: #c0392b;
        color: #fff;
    }
</style>
<script>
    /* Global photo error guard — prevents infinite reload loop.
   Works for all .hrm-emp-photo and .slip-emp-photo images. */
    window.hrmPhotoFallback = function(el) {
        if (!el || el.tagName !== 'IMG') return;

        el.onerror = null;
        const fallback = el.dataset.fallback || '/images/no-photo.png';

        if (el.dataset.fallbackTried === '1') {
            el.style.visibility = 'hidden';
            return;
        }

        el.dataset.fallbackTried = '1';
        if (el.src.indexOf(fallback) === -1) {
            el.src = fallback;
        } else {
            el.style.visibility = 'hidden';
        }
    };

    document.addEventListener('error', function(e) {
        const el = e.target;
        if (el.tagName !== 'IMG') return;
        if (el.classList.contains('hrm-emp-photo') || el.classList.contains('slip-emp-photo')) {
            window.hrmPhotoFallback(el);
        }
    }, true);
</script>
