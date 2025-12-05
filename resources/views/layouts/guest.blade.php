<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Sewa Motor')</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html, body {
            height: 100%;
        }

        body {
            background: linear-gradient(135deg, #0d47a1 0%, #1565c0 50%, #2196f3 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .guest-container {
            width: 100%;
            max-width: 420px;
            padding: 20px;
        }

        .auth-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            overflow: hidden;
            animation: slideUp 0.4s ease-out;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .auth-header {
            background: linear-gradient(135deg, #0d47a1 0%, #1565c0 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }

        .auth-header i {
            font-size: 40px;
            margin-bottom: 15px;
            display: block;
        }

        .auth-header h1 {
            font-size: 28px;
            font-weight: bold;
            margin: 0;
            margin-bottom: 5px;
        }

        .auth-header p {
            font-size: 14px;
            opacity: 0.9;
            margin: 0;
        }

        .auth-body {
            padding: 30px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            font-weight: 600;
            color: #333;
            margin-bottom: 8px;
            display: block;
            font-size: 14px;
        }

        .form-control {
            border: 1.5px solid #e0e0e0;
            border-radius: 8px;
            padding: 12px 15px;
            font-size: 14px;
            transition: all 0.3s ease;
            background-color: #f9f9f9;
        }

        .form-control:focus {
            background-color: white;
            border-color: #0d47a1;
            box-shadow: 0 0 0 3px rgba(13, 71, 161, 0.1);
            outline: none;
        }

        .input-group .form-control {
            border-right: none;
        }

        .input-group .btn {
            border-left: none;
        }

        .form-control.is-invalid {
            border-color: #dc3545;
            background-color: #fff5f5;
        }

        .invalid-feedback {
            display: block;
            color: #dc3545;
            font-size: 12px;
            margin-top: 5px;
        }

        .form-select {
            border: 1.5px solid #e0e0e0;
            border-radius: 8px;
            padding: 12px 15px;
            font-size: 14px;
            background-color: #f9f9f9;
        }

        .form-select:focus {
            border-color: #0d47a1;
            box-shadow: 0 0 0 3px rgba(13, 71, 161, 0.1);
            outline: none;
        }

        .form-check {
            margin-bottom: 15px;
        }

        .form-check-input {
            border: 1.5px solid #e0e0e0;
            border-radius: 4px;
            width: 18px;
            height: 18px;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .form-check-input:checked {
            background-color: #0d47a1;
            border-color: #0d47a1;
        }

        .form-check-label {
            cursor: pointer;
            font-size: 14px;
            color: #555;
            margin-left: 5px;
        }

        .btn-submit {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            background: linear-gradient(135deg, #0d47a1 0%, #1565c0 100%);
            color: white;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 20px;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(13, 71, 161, 0.3);
        }

        .btn-submit:active {
            transform: translateY(0);
        }

        .auth-footer {
            padding: 20px 30px;
            text-align: center;
            background: #f9f9f9;
            border-top: 1px solid #e0e0e0;
            font-size: 14px;
        }

        .auth-footer a {
            color: #0d47a1;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.2s ease;
        }

        .auth-footer a:hover {
            color: #1565c0;
            text-decoration: underline;
        }

        .forgot-password-link {
            display: inline-block;
            color: #0d47a1;
            text-decoration: none;
            font-size: 13px;
            font-weight: 500;
            transition: color 0.2s ease;
            margin-top: 10px;
        }

        .forgot-password-link:hover {
            color: #1565c0;
            text-decoration: underline;
        }

        .btn-toggle-password {
            background: white;
            border: 1.5px solid #e0e0e0;
            border-left: none;
            color: #666;
            cursor: pointer;
            padding: 12px 15px;
            transition: all 0.2s ease;
        }

        .btn-toggle-password:hover {
            background: #f9f9f9;
            color: #0d47a1;
        }

        .alert {
            border-radius: 8px;
            border: none;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 15px;
            font-size: 14px;
        }

        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
        }

        .alert i {
            font-size: 16px;
        }

        .brand-logo {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            font-size: 24px;
            font-weight: bold;
            color: white;
            margin-bottom: 30px;
        }

        .brand-logo i {
            font-size: 32px;
        }

        /* Responsive */
        @media (max-width: 480px) {
            .guest-container {
                padding: 10px;
            }

            .auth-card {
                border-radius: 8px;
            }

            .auth-header {
                padding: 25px 20px;
            }

            .auth-header h1 {
                font-size: 24px;
            }

            .auth-body {
                padding: 20px;
            }

            .auth-footer {
                padding: 15px 20px;
                font-size: 13px;
            }
        }
    </style>

    @stack('styles')
</head>
<body>
    <div class="guest-container">
        <div class="auth-card">
            <div class="auth-header">
                <div class="brand-logo">
                    <i class="fas fa-motorcycle"></i>
                    <span>Sewa Motor</span>
                </div>
                <p>@yield('subtitle', 'Sistem Penyewaan Motor')</p>
            </div>

            <div class="auth-body">
                @if($errors->any())
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle"></i>
                        <span>{{ $errors->first() }}</span>
                    </div>
                @endif

                @yield('content')
            </div>

            <div class="auth-footer">
                @yield('footer-link')
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    @stack('scripts')
</body>
</html>
