<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/js/app.js'])
    <link href="{{ asset('dist/css/adminlte.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ URL::asset('plugins/fontawesome7/css/all.min.css') }}" media="print"
        onload="this.media='all'">
    <link href="{{ asset('bootstrap/dist/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('erpcss/select2.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('erpcss/sweetalert2.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('erpcss/select2-bootstrap-5-theme.min.css') }}" rel="stylesheet">

    <link href="{{ asset('flatpickr/dist/flatpickr.min.css') }}" rel="stylesheet">


    {{-- Icons loaded async so they never block paint --}}

    <link rel="stylesheet" href="{{ URL::asset('bootstrap_icon/bootstrap-icons.min.css') }}" media="print"
        onload="this.media='all'">
    <style>
        body {
            background: #f9fafb;
        }
    </style>

    @stack('styles')
    <style>
        @font-face {
            font-family: 'SutonnyMJ';
            src: url('{{ asset('fonts/SutonnyMJ.ttf') }}') format('truetype');
            font-weight: normal;
            font-style: normal;
            font-display: swap;
        }

        @font-face {
            font-family: 'SuttonyMJ';
            src: url('{{ asset('fonts/SutonnyMJ.ttf') }}') format('truetype');
            font-weight: normal;
            font-style: normal;
            font-display: swap;
        }

        @font-face {
            font-family: 'SuttonyMj';
            src: url('{{ asset('fonts/SutonnyMJ.ttf') }}') format('truetype');
            font-weight: normal;
            font-style: normal;
            font-display: swap;
        }

        .bangla-text {
            font-family: 'SutonnyMJ', 'SuttonyMJ', 'SuttonyMj', Arial, sans-serif !important;
        }
    </style>
</head>

<body class="sidebar-mini layout-fixed sidebar-collapse">
    <div class="wrapper">

        {{-- Sidebar included normally - AdminLTE handles it --}}
        @include('topbar.sidebar')

        <div class="container-fluid">
            <div class="content-wrapper">

                @section('title', 'Page Title')
                @yield('content')


            </div>
        </div>

    </div>


    <script src="{{ URL::asset('mainjs/jquery.min.js') }}"></script>
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
            }
        });
    </script>

    <!-- 2. Select2 CSS -->

    <!-- 3. Select2 JS -->

    <script src="{{ URL::asset('mainjs/select2.min.js') }}"></script>

    <!-- 4. Your helper AFTER Select2 -->
    <script src="{{ URL::asset('mainjs/lov_helper.js') }}"></script>

    <!-- 5. Other scripts -->
    <script src="{{ asset('bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>

    <script src="{{ URL::asset('dist/js/adminlte.min.js') }}"></script>

    <script src="{{ URL::asset('mainjs/jquery.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('mainjs/dataTables.bootstrap5.min.js') }}"></script>
    <script src="{{ URL::asset('mainjs/dataTables.buttons.min.js') }}"></script>
    <script src="{{ URL::asset('mainjs/buttons.bootstrap5.min.js') }}"></script>
    <script src="{{ URL::asset('mainjs/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('flatpickr/dist/flatpickr.min.js') }}"></script>


    <script>
        function showToast(message, type = 'success') {

            let toastEl = document.getElementById('liveToast');

            toastEl.classList.remove('text-bg-success', 'text-bg-danger');
            toastEl.classList.add(type === 'success' ? 'text-bg-success' : 'text-bg-danger');

            document.getElementById('toastMessage').innerText = message;

            let toast = new bootstrap.Toast(toastEl);
            toast.show();
        }
    </script>

    @stack('scripts')

    <div class="position-fixed top-0 end-0 p-3" style="z-index: 9999">
        <div id="liveToast" class="toast align-items-center text-bg-success border-0" role="alert">
            <div class="d-flex">
                <div class="toast-body" id="toastMessage">
                    Success message
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>
    </div>
    dvdd
</body>
<div>
    <footer class="main-footer text-center py-2" style="background:#0b1828;border-top:2px solid #f59e0b;">
        <span class="fw-bold text-uppercase"
            style="font-family:'Rajdhani',sans-serif;font-size:12px;letter-spacing:1.5px;color:#f59e0b;">FDL</span>
        <span class="mx-1" style="color:#4a6a8a;">·</span>
        <span class="text-uppercase"
            style="font-family:'Rajdhani',sans-serif;font-size:12px;letter-spacing:1px;color:#94aec4;">
            Enterprise Resource Planning
        </span>
    </footer>
</div>

</html>
