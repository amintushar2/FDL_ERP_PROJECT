{{-- resources/views/setup/partials/styles.blade.php --}}
@push('styles')
    <link
        href="https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@400;600;700;800&family=DM+Mono:wght@400;500;600&display=swap"
        rel="stylesheet">
    <style>
        :root {
            --navy: #112240;
            --navy2: #0d1b33;
            --blue: #1558A8;
            --blue2: #0D4490;
            --red: #E03B3B;
            --green: #1A6E3C;
            --bg: #F0F2F6;
            --bd: #E0E6EF;
            --bd2: #C8D4E0;
            --t2: #4A5A6E;
            --t3: #8A97A8;
            --inp: #F5F7FA;
        }

        body {
            font-family: 'Nunito Sans', sans-serif;
            background: var(--bg);
            font-size: 13px;
        }

        /* ── NO LOADING on nav clicks ── */
        .setup-nav-link {
            cursor: pointer;
        }

        /* ── SETUP PAGE WRAP ── */
        .setup-page-wrap {
            display: flex;
            min-height: calc(100vh - 56px);
        }

        /* ── SETUP SIDEBAR ── */
        .setup-sidebar {
            width: 210px;
            background: #fff;
            border-right: 1px solid var(--bd);
            flex-shrink: 0;
            padding: 8px 0;
            position: sticky;
            top: 0;
            height: 100vh;
            overflow-y: auto;
        }

        .setup-sb-label {
            font-size: 9.5px;
            font-weight: 800;
            letter-spacing: .12em;
            color: var(--t3);
            padding: 10px 18px 6px;
            text-transform: uppercase;
        }

        .setup-nav-link {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 8.5px 18px;
            color: var(--t2);
            font-size: 12.5px;
            font-weight: 700;
            text-decoration: none;
            border-left: 3px solid transparent;
            transition: all .13s;
        }

        .setup-nav-link:hover {
            background: #F0F4FF;
            color: var(--blue);
            border-left-color: var(--bd2);
        }

        .setup-nav-link.active {
            background: #EBF4FF;
            color: var(--blue2);
            border-left-color: var(--blue);
            font-weight: 800;
        }

        .setup-nav-link svg {
            opacity: .6;
            flex-shrink: 0;
        }

        .setup-nav-link.active svg {
            opacity: 1;
        }

        /* ── MAIN ── */
        .setup-main {
            flex: 1;
            min-width: 0;
            display: flex;
            flex-direction: column;
        }

        /* PAGE HEADER */
        .setup-page-header {
            background: var(--navy);
            padding: 14px 24px 12px;
            border-bottom: 1px solid rgba(255, 255, 255, .07);
        }

        .setup-page-header h4 {
            color: #fff;
            font-size: 16px;
            font-weight: 800;
            margin: 0 0 2px;
        }

        .setup-page-header .bc {
            font-size: 11px;
            color: rgba(255, 255, 255, .42);
            margin: 0;
        }

        .setup-page-header .bc span {
            margin: 0 5px;
            opacity: .5;
        }

        /* TOOLBAR */
        .setup-toolbar {
            background: #fff;
            border-bottom: 1px solid var(--bd);
            height: 46px;
            display: flex;
            align-items: center;
            padding: 0 22px;
            gap: 7px;
            box-shadow: 0 1px 4px rgba(0, 0, 0, .05);
            position: sticky;
            top: 0;
            z-index: 10;
        }

        .t-sep {
            width: 1px;
            height: 20px;
            background: var(--bd);
            margin: 0 2px;
        }

        .btn-tb {
            font-family: 'Nunito Sans', sans-serif;
            font-size: 12px;
            font-weight: 800;
            height: 30px;
            padding: 0 14px;
            border-radius: 5px;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            transition: all .13s;
        }

        .btn-tb-new {
            background: var(--navy);
            color: #fff;
            border: 1px solid var(--navy);
        }

        .btn-tb-new:hover {
            background: var(--navy2);
        }

        .btn-tb-save {
            background: #EBF7F0;
            color: var(--green);
            border: 1px solid #8ECFAA;
        }

        .btn-tb-save:hover {
            background: #d0ecdd;
        }

        .srch-group {
            margin-left: auto;
            display: flex;
            align-items: center;
        }

        .srch-wrap {
            position: relative;
        }

        .srch-wrap::before {
            content: '⌕';
            position: absolute;
            left: 9px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 15px;
            color: var(--t3);
            pointer-events: none;
        }

        .srch-inp {
            height: 30px;
            width: 210px;
            font-size: 12px;
            border: 1px solid var(--bd2);
            border-right: none;
            border-radius: 5px 0 0 5px;
            background: var(--inp);
            color: #1C2B3A;
            padding: 0 10px 0 30px;
            outline: none;
            font-family: 'Nunito Sans', sans-serif;
            transition: border-color .13s;
        }

        .srch-inp:focus {
            border-color: var(--blue);
            background: #fff;
        }

        .srch-btn {
            height: 30px;
            padding: 0 16px;
            background: var(--blue);
            border: 1px solid var(--blue);
            border-radius: 0 5px 5px 0;
            color: #fff;
            font-family: 'Nunito Sans', sans-serif;
            font-size: 12px;
            font-weight: 800;
            cursor: pointer;
            transition: background .13s;
        }

        .srch-btn:hover {
            background: var(--blue2);
        }

        /* BODY */
        .setup-body {
            padding: 18px 22px 36px;
        }

        /* SEC HEADER */
        .sec-hd {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 14px;
        }

        .sec-title {
            font-size: 14px;
            font-weight: 800;
            color: var(--navy);
            display: flex;
            align-items: center;
            gap: 8px;
            white-space: nowrap;
        }

        .sec-line {
            flex: 1;
            height: 1.5px;
            background: linear-gradient(90deg, var(--bd2), transparent);
        }

        /* CARD */
        .setup-card {
            background: #fff;
            border: 1px solid var(--bd);
            border-radius: 8px;
            box-shadow: 0 1px 6px rgba(0, 0, 0, .06);
            overflow: hidden;
        }

        .setup-card-hd {
            padding: 11px 18px;
            border-bottom: 1px solid var(--bd);
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 8px;
        }

        .card-hd-left {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .card-dot {
            width: 9px;
            height: 9px;
            border-radius: 50%;
            background: var(--navy);
            flex-shrink: 0;
        }

        .card-ttl {
            font-size: 11px;
            font-weight: 800;
            color: var(--navy);
            text-transform: uppercase;
            letter-spacing: .09em;
        }

        .card-search {
            height: 28px;
            width: 180px;
            font-size: 12px;
            border: 1px solid var(--bd2);
            border-radius: 5px;
            background: var(--inp);
            color: #1C2B3A;
            padding: 0 10px;
            outline: none;
            font-family: 'Nunito Sans', sans-serif;
            transition: border-color .13s;
        }

        .card-search:focus {
            border-color: var(--blue);
            background: #fff;
        }

        .btn-add-record {
            background: var(--navy);
            border: none;
            color: #fff;
            font-family: 'Nunito Sans', sans-serif;
            font-size: 12px;
            font-weight: 800;
            height: 28px;
            padding: 0 13px;
            border-radius: 5px;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            transition: background .13s;
            white-space: nowrap;
        }

        .btn-add-record:hover {
            background: var(--navy2);
        }

        /* TABLE */
        .setup-table {
            width: 100%;
            border-collapse: collapse;
        }

        .setup-table thead tr {
            background: #F5F7FA;
        }

        .setup-table th {
            font-size: 10px;
            font-weight: 800;
            letter-spacing: .08em;
            text-transform: uppercase;
            color: var(--navy);
            padding: 10px 18px;
            border-bottom: 1px solid var(--bd);
            white-space: nowrap;
        }

        .setup-table td {
            padding: 12px 18px;
            font-size: 13px;
            color: #1C2B3A;
            border-bottom: 1px solid var(--bd);
            vertical-align: middle;
        }

        .setup-table tbody tr:hover {
            background: #F5F8FF;
        }

        .setup-table tbody tr:last-child td {
            border-bottom: none;
        }

        .mono {
            font-family: 'DM Mono', monospace;
            font-size: 12px;
            font-weight: 600;
            color: var(--navy);
        }

        /* BADGES */
        .badge-active {
            display: inline-block;
            font-size: 11px;
            font-weight: 700;
            padding: 3px 12px;
            border-radius: 20px;
            background: #EBF7F0;
            color: #1A6E3C;
            border: 1.5px solid #8ECFAA;
        }

        .badge-inactive {
            display: inline-block;
            font-size: 11px;
            font-weight: 700;
            padding: 3px 12px;
            border-radius: 20px;
            background: #FEF2F2;
            color: #E03B3B;
            border: 1.5px solid #FACACA;
        }

        /* ACTION BTNS */
        .btn-row-edit {
            background: #EBF4FF;
            border: 1px solid #ADC0F0;
            color: var(--blue2);
            font-family: 'Nunito Sans', sans-serif;
            font-size: 11.5px;
            font-weight: 700;
            height: 27px;
            padding: 0 11px;
            border-radius: 5px;
            cursor: pointer;
            margin-right: 4px;
            transition: all .13s;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .btn-row-edit:hover {
            background: #d6e6ff;
        }

        .btn-row-del {
            background: #FEF2F2;
            border: 1px solid #FACACA;
            color: var(--red);
            font-family: 'Nunito Sans', sans-serif;
            font-size: 11.5px;
            font-weight: 700;
            height: 27px;
            padding: 0 11px;
            border-radius: 5px;
            cursor: pointer;
            transition: all .13s;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .btn-row-del:hover {
            background: #fee2e2;
        }

        /* ── PAGINATION ── */
        .setup-pagination {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px 18px;
            border-top: 1px solid var(--bd);
            background: #FAFBFD;
            flex-wrap: wrap;
            gap: 8px;
        }

        .pg-info {
            font-size: 11.5px;
            color: var(--t3);
            font-weight: 600;
        }

        .pg-links {
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .pg-links a,
        .pg-links span {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 30px;
            height: 28px;
            padding: 0 8px;
            border: 1px solid var(--bd2);
            border-radius: 5px;
            font-size: 12px;
            font-weight: 700;
            color: var(--t2);
            text-decoration: none;
            transition: all .13s;
            background: #fff;
        }

        .pg-links a:hover {
            background: #EBF4FF;
            color: var(--blue);
            border-color: #ADC0F0;
        }

        .pg-active {
            background: var(--navy) !important;
            color: #fff !important;
            border-color: var(--navy) !important;
        }

        .pg-disabled {
            color: var(--bd2) !important;
            cursor: default;
        }

        .pg-dots {
            border: none !important;
            background: none !important;
            color: var(--t3) !important;
        }

        /* EMPTY */
        .empty-state {
            text-align: center;
            padding: 48px 20px;
            color: var(--t3);
        }

        .empty-state svg {
            opacity: .3;
            margin: 0 auto 12px;
            display: block;
        }

        .empty-state p {
            font-size: 13px;
            font-weight: 600;
        }

        /* MODAL */
        .modal-content {
            border: 1px solid var(--bd) !important;
            border-radius: 8px !important;
            overflow: hidden;
        }

        .modal-header-navy {
            background: var(--navy);
            border-bottom: none;
            padding: 14px 20px;
        }

        .modal-header-navy .modal-title {
            color: #fff;
            font-size: 13px;
            font-weight: 800;
            display: flex;
            align-items: center;
            gap: 8px;
            font-family: 'Nunito Sans', sans-serif;
        }

        .modal-header-navy .btn-close {
            filter: invert(1);
            opacity: .6;
        }

        .modal-header-navy .btn-close:hover {
            opacity: 1;
        }

        .f-lbl {
            font-size: 10.5px;
            font-weight: 800;
            color: var(--t2);
            letter-spacing: .06em;
            text-transform: uppercase;
            display: block;
            margin-bottom: 5px;
        }

        .f-inp,
        .f-sel {
            height: 38px;
            padding: 0 12px;
            border: 1px solid var(--bd2);
            border-radius: 5px;
            background: var(--inp);
            color: #1C2B3A;
            font-size: 13px;
            font-family: 'Nunito Sans', sans-serif;
            width: 100%;
            outline: none;
            transition: border-color .13s, box-shadow .13s;
            appearance: none;
        }

        .f-inp:focus,
        .f-sel:focus {
            border-color: var(--blue);
            box-shadow: 0 0 0 3px rgba(21, 88, 168, .1);
            background: #fff;
        }

        .f-inp::placeholder {
            color: var(--t3);
            font-size: 12px;
        }

        .f-inp.is-invalid,
        .f-sel.is-invalid {
            border-color: var(--red) !important;
        }

        .err-msg {
            color: var(--red);
            font-size: 11px;
            margin-top: 3px;
            min-height: 14px;
        }

        .modal-footer-setup {
            background: #FAFBFD;
            border-top: 1px solid var(--bd);
            padding: 11px 20px;
        }

        .btn-modal-cancel {
            background: #F5F7FA;
            border: 1px solid var(--bd2);
            color: var(--t2);
            font-family: 'Nunito Sans', sans-serif;
            font-size: 12px;
            font-weight: 700;
            height: 32px;
            padding: 0 16px;
            border-radius: 5px;
            cursor: pointer;
        }

        .btn-modal-cancel:hover {
            background: #eaedf2;
        }

        .btn-modal-save {
            background: var(--navy);
            border: none;
            color: #fff;
            font-family: 'Nunito Sans', sans-serif;
            font-size: 12px;
            font-weight: 800;
            height: 32px;
            padding: 0 18px;
            border-radius: 5px;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            transition: background .13s;
        }

        .btn-modal-save:hover {
            background: var(--navy2);
        }

        .modal-header-danger {
            background: #FEF2F2;
            border-bottom: 1px solid #FACACA;
            padding: 13px 20px;
        }

        #setupToast {
            position: fixed;
            bottom: 22px;
            right: 22px;
            min-width: 240px;
            max-width: 340px;
            z-index: 9999;
            border-radius: 6px;
            font-size: 12.5px;
            font-weight: 700;
            border: none;
        }
    </style>
@endpush
