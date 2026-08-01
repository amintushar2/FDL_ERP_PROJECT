<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>System Unavailable</title>

    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            background: linear-gradient(135deg, #4e73df, #1cc88a);
            height: 100vh;
        }

        .error-card {
            border-radius: 15px;
            animation: fadeIn 0.6s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .icon {
            font-size: 60px;
            color: #e74a3b;
        }
    </style>
</head>

<body class="d-flex align-items-center justify-content-center">

    <div class="container text-center">
        <div class="card shadow-lg p-5 error-card">

            <div class="mb-4">
                <i class="bi bi-database-x icon"></i>
            </div>

            <h2 class="fw-bold text-danger mb-3">
                Database Connection Failed
            </h2>

            <p class="text-muted mb-4">
                We’re currently unable to connect to the database.<br>
                This may be a temporary issue. Please try again shortly.
            </p>

            <div class="d-flex justify-content-center gap-3">
                <a href="/" class="btn btn-primary px-4">
                    <i class="bi bi-house-door"></i> Home
                </a>

                <button onclick="location.reload()" class="btn btn-outline-secondary px-4">
                    <i class="bi bi-arrow-clockwise"></i> Retry
                </button>
            </div>

        </div>

        <div class="mt-4 text-white-50">
            <small>Error Code: DB-CONNECTION-FAILED</small>
        </div>
    </div>

</body>

</html>
