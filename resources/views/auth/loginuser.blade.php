<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Four Design - ERP Login</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link
        href="https://fonts.googleapis.com/css2?family=Sora:wght@400;500;600;700&family=Caveat:wght@400;700&display=swap"
        rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary: #10b981;
            --primary-light: #6ee7b7;
            --accent-pink: #ec4899;
            --accent-blue: #06b6d4;
            --dark: #0f172a;
            --text-dark: #1e293b;
            --text-light: #64748b;
            --bg-light: #f8fafc;
            --transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        html,
        body {
            font-family: 'Sora', sans-serif;
            background: linear-gradient(135deg, #f8fafc 0%, #eef2f7 100%);
            color: var(--text-dark);
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* ===== ANIMATED BACKGROUND ===== */
        .page-wrapper {
            position: relative;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
        }

        /* Background decorative elements */
        .bg-element-1 {
            position: fixed;
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, rgba(16, 185, 129, 0.1), transparent);
            border-radius: 50%;
            top: -200px;
            right: -200px;
            animation: moveCircle1 20s ease-in-out infinite;
            z-index: 0;
            pointer-events: none;
        }

        .bg-element-2 {
            position: fixed;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(236, 72, 153, 0.08), transparent);
            border-radius: 50%;
            bottom: -150px;
            left: -150px;
            animation: moveCircle2 25s ease-in-out infinite;
            z-index: 0;
            pointer-events: none;
        }

        @keyframes moveCircle1 {

            0%,
            100% {
                transform: translate(0, 0);
            }

            50% {
                transform: translate(50px, 50px);
            }
        }

        @keyframes moveCircle2 {

            0%,
            100% {
                transform: translate(0, 0);
            }

            50% {
                transform: translate(-40px, -40px);
            }
        }

        /* ===== MAIN LOGIN CARD ===== */
        .login-card-main {
            max-width: 900px;
            width: 100%;
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            position: relative;
            z-index: 1;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0;
            min-height: 550px;
        }

        /* ===== TOP SECTION (Above the grid) ===== */
        .card-top-banner {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 120px;
            background: linear-gradient(135deg, var(--dark) 0%, #1a2a4a 50%, var(--primary) 100%);
            display: flex;
            align-items: center;
            padding: 0 40px;
            grid-column: 1 / -1;
        }

        .banner-content {
            display: flex;
            align-items: center;
            gap: 20px;
            width: 100%;
        }

        .banner-logo {
            width: 200px;
            height: 50px;
            background: white;
            border-radius: 12px;
            padding: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15);
            animation: slideInLeft 0.8s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        .banner-logo img {
            width: 200px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            object-fit: contain;
        }

        .banner-text {
            color: white;
            animation: slideInDown 0.8s cubic-bezier(0.34, 1.56, 0.64, 1) 0.1s both;
        }

        .banner-title {
            font-size: 24px;
            font-weight: 700;
            letter-spacing: -0.5px;
            margin: 0;
        }

        .banner-subtitle {
            font-size: 11px;
            opacity: 0.9;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            margin: 4px 0 0 0;
        }

        /* ===== LEFT CONTENT SECTION ===== */
        .card-left-content {
            padding: 140px 40px 40px 40px;
            background: linear-gradient(to bottom, rgba(16, 185, 129, 0.02), rgba(16, 185, 129, 0.01));
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            border-right: 2px solid #f1f5f9;
            animation: slideInLeft 0.8s cubic-bezier(0.34, 1.56, 0.64, 1) 0.2s both;
        }

        .content-headline {
            margin-bottom: 30px;
        }

        .content-title {
            font-size: 28px;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 12px;
            line-height: 1.3;
            letter-spacing: -0.5px;
        }

        .content-accent-line {
            width: 40px;
            height: 4px;
            background: linear-gradient(90deg, var(--primary), var(--accent-blue));
            border-radius: 2px;
            margin-bottom: 16px;
        }

        .content-description {
            font-size: 14px;
            color: var(--text-light);
            line-height: 1.7;
            margin-bottom: 30px;
        }

        /* Quick stats */
        .quick-stats {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .stat-card {
            padding: 16px;
            background: white;
            border-radius: 10px;
            border-left: 4px solid var(--primary);
            transition: var(--transition);
        }

        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 16px rgba(16, 185, 129, 0.15);
        }

        .stat-number {
            font-size: 20px;
            font-weight: 700;
            color: var(--primary);
        }

        .stat-label {
            font-size: 11px;
            color: var(--text-light);
            margin-top: 4px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Info box at bottom */
        .info-box {
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.08), rgba(6, 182, 212, 0.08));
            border-radius: 10px;
            padding: 16px;
            border: 1px solid rgba(16, 185, 129, 0.2);
        }

        .info-icon {
            font-size: 18px;
            margin-right: 8px;
        }

        .info-text {
            font-size: 12px;
            color: var(--text-dark);
            line-height: 1.6;
        }

        /* ===== RIGHT LOGIN FORM ===== */
        .card-form-section {
            padding: 140px 40px 40px 40px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            animation: slideInRight 0.8s cubic-bezier(0.34, 1.56, 0.64, 1) 0.2s both;
        }

        /* Form header */
        .form-header-section {
            margin-bottom: 40px;
        }

        .form-title {
            font-size: 26px;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 6px;
            letter-spacing: -0.5px;
        }

        .form-subtitle {
            font-size: 13px;
            color: var(--text-light);
            font-weight: 400;
        }

        /* Alerts */
        .alert-custom {
            padding: 12px 14px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-size: 12px;
            border-left: 4px solid;
            display: flex;
            gap: 10px;
            animation: slideDown 0.5s ease-out;
        }

        .alert-custom.success {
            background: #f0fdf4;
            border-left-color: var(--primary);
            color: #166534;
        }

        .alert-custom.error {
            background: #fef2f2;
            border-left-color: #ef4444;
            color: #991b1b;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Form groups */
        .form-field {
            margin-bottom: 20px;
        }

        .form-field-label {
            display: block;
            font-size: 11px;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .form-field-input {
            width: 100%;
            padding: 12px 14px;
            border: 1.5px solid #e2e8f0;
            border-radius: 8px;
            font-size: 14px;
            font-family: 'Sora', sans-serif;
            background: white;
            color: var(--text-dark);
            transition: var(--transition);
        }

        .form-field-input::placeholder {
            color: #cbd5e1;
        }

        .form-field-input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
            background: #fafbfc;
        }

        .form-field-input:hover:not(:focus) {
            border-color: rgba(16, 185, 129, 0.3);
        }

        /* Error message */
        .field-error {
            color: #ef4444;
            font-size: 11px;
            margin-top: 5px;
            font-weight: 500;
        }

        /* Form footer options */
        .form-footer-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
            font-size: 12px;
        }

        .checkbox-wrapper {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .checkbox-wrapper input {
            width: 16px;
            height: 16px;
            accent-color: var(--primary);
            cursor: pointer;
        }

        .checkbox-wrapper label {
            cursor: pointer;
            font-weight: 500;
            color: var(--text-dark);
            user-select: none;
        }

        .forgot-link {
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s ease;
        }

        .forgot-link:hover {
            color: var(--accent-blue);
        }

        /* Submit button */
        .btn-submit-login {
            width: 100%;
            padding: 13px 20px;
            background: linear-gradient(135deg, var(--primary) 0%, #10b981 100%);
            border: none;
            border-radius: 8px;
            color: white;
            font-size: 14px;
            font-weight: 700;
            font-family: 'Sora', sans-serif;
            cursor: pointer;
            transition: var(--transition);
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
            position: relative;
            overflow: hidden;
            letter-spacing: 0.3px;
            margin-bottom: 16px;
        }

        .btn-submit-login::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }

        .btn-submit-login:hover {
            background: linear-gradient(135deg, #059669 0%, #047857 100%);
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(16, 185, 129, 0.4);
        }

        .btn-submit-login:hover::before {
            left: 100%;
        }

        .btn-submit-login:active {
            transform: translateY(0);
        }

        .btn-submit-login:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        .loader-spin {
            display: inline-block;
            width: 12px;
            height: 12px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-top-color: white;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        /* Support link */
        .support-link {
            text-align: center;
            font-size: 12px;
            color: var(--text-light);
        }

        .support-link a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s ease;
        }

        .support-link a:hover {
            color: var(--accent-blue);
        }

        /* ===== ANIMATIONS ===== */
        @keyframes slideInLeft {
            from {
                opacity: 0;
                transform: translateX(-40px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(40px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes slideInDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 1024px) {
            .login-card-main {
                grid-template-columns: 1fr;
                min-height: auto;
            }

            .card-left-content {
                padding: 140px 30px 30px 30px;
                border-right: none;
                border-bottom: 2px solid #f1f5f9;
            }

            .card-form-section {
                padding: 30px;
            }

            .content-title {
                font-size: 24px;
            }

            .form-title {
                font-size: 22px;
            }

            .quick-stats {
                grid-template-columns: 1fr 1fr;
            }
        }

        @media (max-width: 768px) {
            .page-wrapper {
                padding: 20px 15px;
            }

            .login-card-main {
                grid-template-columns: 1fr;
                min-height: auto;
                border-radius: 16px;
            }

            .card-top-banner {
                padding: 0 20px;
                height: 100px;
            }

            .banner-logo {
                width: 50px;
                height: 50px;
            }

            .banner-title {
                font-size: 20px;
            }

            .card-left-content {
                padding: 110px 25px 25px 25px;
            }

            .card-form-section {
                padding: 25px;
            }

            .content-title,
            .form-title {
                font-size: 20px;
            }

            .content-description {
                font-size: 13px;
                margin-bottom: 20px;
            }

            .quick-stats {
                gap: 15px;
            }

            .stat-card {
                padding: 12px;
            }

            .form-footer-options {
                flex-direction: column;
                gap: 10px;
                align-items: flex-start;
            }
        }

        @media (max-width: 480px) {
            .page-wrapper {
                padding: 15px 10px;
            }

            .login-card-main {
                border-radius: 12px;
            }

            .card-top-banner {
                padding: 0 15px;
                height: 90px;
            }

            .banner-logo {
                width: 45px;
                height: 45px;
            }

            .banner-title {
                font-size: 18px;
            }

            .card-left-content {
                padding: 100px 20px 20px 20px;
            }

            .card-form-section {
                padding: 20px;
            }

            .content-title {
                font-size: 18px;
            }

            .form-title {
                font-size: 18px;
            }

            .quick-stats {
                display: none;
            }

            .info-box {
                display: none;
            }

            .form-field {
                margin-bottom: 16px;
            }

            .form-field-input {
                padding: 11px 12px;
                font-size: 13px;
            }
        }
    </style>
</head>

<body>
    <!-- Background elements -->
    <div class="bg-element-1"></div>
    <div class="bg-element-2"></div>

    <!-- Main wrapper -->
    <div class="page-wrapper">
        <div class="login-card-main">
            <!-- TOP BANNER -->
            <div class="card-top-banner">
                <div class="banner-content">
                    <div class="banner-logo">
                        <img src="data:image/png;base64,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"
                            alt="Four Design">
                    </div>
                    <div class="banner-text">
                        <h1 class="banner-title">Four Design</h1>
                        <p class="banner-subtitle">Internal ERP Portal</p>
                    </div>
                </div>
            </div>

            <!-- LEFT CONTENT SECTION -->
            <div class="card-left-content">
                <div>
                    <!-- Headline -->
                    <div class="content-headline">
                        <h2 class="content-title">Manage Your Operations</h2>
                        <div class="content-accent-line"></div>
                        <p class="content-description">
                            Access production schedules, inventory tracking, quality metrics, and export documentation
                            in one unified platform.
                        </p>
                    </div>

                    <!-- Quick stats -->
                    <div class="quick-stats">
                        <div class="stat-card">
                            <div class="stat-number">4000+</div>
                            <div class="stat-label">Daily Orders</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-number">50+</div>
                            <div class="stat-label">Departments</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-number">99.9%</div>
                            <div class="stat-label">System Uptime</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-number">24/7</div>
                            <div class="stat-label">Live Monitoring</div>
                        </div>
                    </div>
                </div>

                <!-- Info box -->
                <div class="info-box">
                    <div class="info-text">
                        <span class="info-icon">🔒</span>
                        Secure authentication with encrypted data transmission
                    </div>
                </div>
            </div>

            <!-- RIGHT FORM SECTION -->
            <div class="card-form-section">
                <div>
                    <!-- Form header -->
                    <div class="form-header-section">
                        <h2 class="form-title">Sign In</h2>
                        <p class="form-subtitle">Enter your credentials to continue</p>
                    </div>

                    <!-- Login form -->
                    <form id="loginForm" action="{{ route('auth.check') }}" method="POST">
                        <!-- Alerts -->
                        @if (Session::get('fail'))
                            <div class="alert-custom error">
                                <span>⚠</span>
                                {{ Session::get('fail') }}
                            </div>
                        @endif

                        @if (Session::get('success'))
                            <div class="alert-custom success">
                                <span>✓</span>
                                {{ Session::get('success') }}
                            </div>
                        @endif

                        @csrf

                        <!-- Employee ID -->
                        <div class="form-field">
                            <label for="user_id" class="form-field-label">Employee ID</label>
                            <input type="text" id="user_id" name="user_id" class="form-field-input"
                                placeholder="Your employee ID" value="{{ old('user_id') }}" autocomplete="username"
                                required>
                            @error('user_id')
                                <div class="field-error">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div class="form-field">
                            <label for="initial_password" class="form-field-label">Password</label>
                            <input type="password" id="initial_password" name="initial_password"
                                class="form-field-input" placeholder="Enter password" autocomplete="current-password"
                                required>
                            @error('initial_password')
                                <div class="field-error">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Options -->
                        <div class="form-footer-options">
                            <div class="checkbox-wrapper">
                                <input type="checkbox" id="remember" name="remember">
                                <label for="remember">Remember me</label>
                            </div>
                            <a href="#" class="forgot-link">Forgot Password?</a>
                        </div>

                        <!-- Submit button -->
                        <button type="submit" class="btn-submit-login">
                            <span id="buttonText">Login</span>
                        </button>
                    </form>
                </div>

                <!-- Support link -->
                <div class="support-link">
                    Need help? <a href="#">Contact IT Department</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>

    <script>
        const form = document.getElementById('loginForm');
        const buttonText = document.getElementById('buttonText');
        const button = form.querySelector('.btn-submit-login');

        form.addEventListener('submit', function() {
            button.disabled = true;
            buttonText.innerHTML = '<span class="loader-spin"></span>';
        });

        const inputs = document.querySelectorAll('.form-field-input');
        inputs.forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.classList.add('focused');
            });
            input.addEventListener('blur', function() {
                this.parentElement.classList.remove('focused');
            });
        });
    </script>
</body>

</html>
