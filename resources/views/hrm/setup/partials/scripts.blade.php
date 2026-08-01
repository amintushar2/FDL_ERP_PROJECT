{{-- resources/views/setup/partials/scripts.blade.php --}}
@push('scripts')
<script>
const SETUP_CSRF = document.querySelector('meta[name="csrf-token"]')?.content || '';

/* ── SERVER-SIDE SEARCH (no JS filter, just redirect with ?q=) ── */
function doServerSearch() {
    const q = (document.getElementById('toolbarSearch')?.value || document.getElementById('cardSearch')?.value || '').trim();
    const url = new URL(window.location.href);
    if (q) url.searchParams.set('q', q);
    else   url.searchParams.delete('q');
    url.searchParams.delete('page');
    window.location.href = url.toString();
}

/* ── TOAST ── */
const TOAST_BG = { green:'#1A6E3C', red:'#C02828', navy:'#112240', warn:'#7A5500' };
function showSetupToast(msg, type = 'green') {
    const el = document.getElementById('setupToast');
    if (!el) return;
    el.style.background = TOAST_BG[type] || TOAST_BG.navy;
    el.querySelector('.toast-body').textContent = (type==='green'?'✓ ':type==='red'?'⚠ ':'')+msg;
    bootstrap.Toast.getOrCreateInstance(el, { delay: 3000 }).show();
}

/* ── MODAL HELPERS ── */
function clearModalErrors(modalId) {
    document.querySelectorAll(`#${modalId} .err-msg`).forEach(el => el.textContent = '');
    document.querySelectorAll(`#${modalId} .is-invalid`).forEach(el => el.classList.remove('is-invalid'));
}
function validateModalField(id, msg) {
    const el  = document.getElementById(id);
    const err = document.getElementById(id + '_err');
    if (!el || !el.value.trim()) {
        if (el)  el.classList.add('is-invalid');
        if (err) err.textContent = msg;
        return false;
    }
    return true;
}

/* ── FETCH HELPER ── */
async function setupFetch(url, method = 'GET', body = null) {
    const opts = {
        method,
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': SETUP_CSRF,
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
        },
    };
    if (body) opts.body = JSON.stringify(body);
    const res  = await fetch(url, opts);
    const data = await res.json();
    return { ok: res.ok, data };
}

/* ── SAVE BUTTON LOADING ── */
function saveBtnLoading(id) {
    const btn = document.getElementById(id);
    if (btn) { btn.disabled=true; btn.dataset.orig=btn.innerHTML; btn.innerHTML='<span class="spinner-border spinner-border-sm me-1"></span>Saving…'; }
    return btn;
}
function saveBtnReset(btn) {
    if (btn) { btn.disabled=false; btn.innerHTML=btn.dataset.orig; }
}

/* ── DELETE CONFIRM ── */
let _deleteCb = null;
function setupConfirmDelete(name, callback) {
    _deleteCb = callback;
    document.getElementById('deleteRecordName').textContent = name;
    bootstrap.Modal.getOrCreateInstance(document.getElementById('deleteConfirmModal')).show();
}
document.addEventListener('DOMContentLoaded', function () {
    const btn = document.getElementById('btnConfirmDelete');
    if (btn) {
        btn.addEventListener('click', function () {
            bootstrap.Modal.getInstance(document.getElementById('deleteConfirmModal'))?.hide();
            if (_deleteCb) _deleteCb();
        });
    }

    /* ── Sync toolbar & card search boxes ── */
    const ts = document.getElementById('toolbarSearch');
    const cs = document.getElementById('cardSearch');
    if (ts && cs) {
        const q = new URLSearchParams(window.location.search).get('q') || '';
        ts.value = q; cs.value = q;
        ts.addEventListener('input', () => cs.value = ts.value);
        cs.addEventListener('input', () => ts.value = cs.value);
        ts.addEventListener('keydown', e => { if(e.key==='Enter') doServerSearch(); });
        cs.addEventListener('keydown', e => { if(e.key==='Enter') doServerSearch(); });
    }

    /* ── headername support ── */
    const hn = document.getElementById('headername');
    if (hn && document.title) hn.innerText = document.title.replace(' | FDL ERP','').replace(' Setup','') + ' Setup';
});
</script>
@endpush
