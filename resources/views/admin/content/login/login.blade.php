<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Đăng Nhập Hệ Thống</title>

    {{-- google fonts --}}
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    {{-- font-awesome icon --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
        integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    {{-- bootstrap css --}}
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">

    <style>
        :root {
            --primary-color: #dc3545;
            --primary-dark: #c82333;
            --secondary-color: #f8f9fa;
            --danger-color: #ff4444;
            --warning-color: #ffbb33;
            --text-dark: #212529;
            --text-light: #6c757d;
            --light-gray: #e9ecef;
        }

        body {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            font-family: 'Poppins', sans-serif;
            height: 100vh;
            display: flex;
            align-items: center;
            background-image: url('{{ asset('images/auth-bg-pattern.png') }}');
            background-size: cover;
            background-position: center;
        }

        .login-container {
            max-width: 450px;
            width: 100%;
            margin: 0 auto;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            background: rgba(255, 255, 255, 0.98);
            animation: fadeInUp 0.6s ease-out;
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .login-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            color: white;
            padding: 2.5rem 2rem;
            text-align: center;
            position: relative;
            border-bottom: 4px solid rgba(0, 0, 0, 0.1);
        }

        .brand-logo {
            height: 80px;
            margin-bottom: 1.5rem;
            filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.2));
        }

        .login-header h1 {
            font-weight: 700;
            margin-bottom: 0.5rem;
            font-size: 2rem;
            text-shadow: 0 1px 3px rgba(0, 0, 0, 0.2);
            letter-spacing: 0.5px;
        }

        .login-header p {
            opacity: 0.9;
            font-size: 1rem;
            margin-bottom: 0;
            font-weight: 400;
        }

        .login-body {
            padding: 2.5rem;
            background-color: white;
        }

        .form-label {
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 0.75rem;
            font-size: 0.95rem;
            display: flex;
            align-items: center;
        }

        .form-label i {
            margin-right: 8px;
            color: var(--primary-color);
            font-size: 1rem;
        }

        .input-group {
            margin-bottom: 1rem;
            transition: all 0.3s;
            border-radius: 8px;
            overflow: hidden;
            border: 1px solid var(--light-gray);
        }

        .input-group:focus-within {
            box-shadow: 0 0 0 3px rgba(220, 53, 69, 0.25);
            border-color: var(--primary-color);
        }

        .input-group-text {
            background-color: var(--secondary-color);
            border: none;
            color: var(--text-dark);
            padding: 0 1rem;
            min-width: 45px;
            justify-content: center;
            border-right: 1px solid var(--light-gray);
        }

        .form-control {
            border: none;
            padding: 12px 15px;
            background-color: white;
            font-size: 0.95rem;
            color: var(--text-dark);
        }

        .form-control:focus {
            box-shadow: none;
            background-color: white;
        }

        .btn-login {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            border: none;
            padding: 12px;
            font-weight: 600;
            letter-spacing: 0.5px;
            transition: all 0.3s;
            border-radius: 8px;
            font-size: 1rem;
            box-shadow: 0 4px 10px rgba(220, 53, 69, 0.3);
            margin-top: 1rem;
            text-transform: uppercase;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(220, 53, 69, 0.4);
            background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary-color) 100%);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .security-alert {
            border-left: 4px solid var(--warning-color);
            background-color: rgba(255, 193, 7, 0.1);
            font-size: 0.85rem;
            padding: 0.75rem 1rem;
            border-radius: 6px;
            margin-top: 1.5rem;
        }

        .toggle-password {
            cursor: pointer;
            background-color: white;
            border: none;
            min-width: 45px;
            color: var(--text-light);
            transition: all 0.2s;
            border-left: 1px solid var(--light-gray);
        }

        .toggle-password:hover {
            color: var(--primary-color);
            background-color: var(--light-gray);
        }

        .error-message {
            font-size: 0.85rem;
            color: var(--danger-color);
            margin-top: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-weight: 500;
        }

        .forgot-password {
            color: var(--primary-color);
            font-size: 0.9rem;
            transition: all 0.2s;
            text-decoration: none;
            font-weight: 500;
        }

        .forgot-password:hover {
            color: var(--primary-dark);
            text-decoration: underline;
        }

        .form-check-input:checked {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .form-check-label {
            font-size: 0.9rem;
            color: var(--text-dark);
            font-weight: 500;
        }

        .divider {
            display: flex;
            align-items: center;
            margin: 1.5rem 0;
            color: var(--text-light);
            font-size: 0.9rem;
        }

        .divider::before,
        .divider::after {
            content: "";
            flex: 1;
            border-bottom: 1px solid var(--light-gray);
            margin: 0 10px;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (max-width: 576px) {
            .login-container {
                margin: 0 1.5rem;
                border-radius: 10px;
            }

            .login-body {
                padding: 1.75rem;
            }

            .login-header {
                padding: 2rem 1.5rem;
            }

            .brand-logo {
                height: 70px;
                margin-bottom: 1.25rem;
            }

            .login-header h1 {
                font-size: 1.75rem;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="login-container">
            <div class="login-header">
                <img src="{{ asset('images/logo.png') }}" alt="Company Logo" class="brand-logo">
                <h1>HỆ THỐNG QUẢN TRỊ</h1>
            </div>

            <div class="login-body">
                <form action="{{ route('admin.authLogin') }}" method="POST" id="loginForm">
                    @csrf

                    <div class="mb-4">
                        <label for="email" class="form-label">
                            <i class="fas fa-envelope"></i> Email
                        </label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-user-tie"></i></span>
                            <input type="email" class="form-control" id="email" name="email"
                                value="{{ old('email') }}" placeholder="Nhập email quản trị" required>
                        </div>
                        @if ($errors->has('email'))
                            <div class="error-message mt-2">
                                <i class="fas fa-exclamation-circle me-1"></i>
                                {{ $errors->first('email') }}
                            </div>
                        @endif
                    </div>

                    <div class="mb-4">
                        <label for="password" class="form-label">
                            <i class="fas fa-lock"></i> Mật khẩu
                        </label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-key"></i></span>
                            <input type="password" class="form-control" id="password" name="password"
                                placeholder="Nhập mật khẩu" required>
                            <button class="btn toggle-password" type="button" id="togglePassword">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        @if ($errors->has('password'))
                            <div class="error-message mt-2">
                                <i class="fas fa-exclamation-circle me-1"></i>
                                {{ $errors->first('password') }}
                            </div>
                        @endif
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="remember" name="remember">
                            <label class="form-check-label" for="remember">Ghi nhớ đăng nhập</label>
                        </div>
                        <a href="#!" class="forgot-password">
                            <i class="fas fa-question-circle me-1"></i>Quên mật khẩu?
                        </a>
                    </div>

                    <button type="submit" class="btn btn-login w-100 text-white mb-4">
                        <i class="fas fa-sign-in-alt me-2"></i> ĐĂNG NHẬP
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Toggle password visibility
        document.getElementById('togglePassword').addEventListener('click', function() {
            const passwordInput = document.getElementById('password');
            const icon = this.querySelector('i');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                icon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        });

        // Form validation
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            const email = document.getElementById('email');
            const password = document.getElementById('password');

            if (!email.value || !password.value) {
                e.preventDefault();

                if (!email.value) {
                    email.classList.add('is-invalid');
                }

                if (!password.value) {
                    password.classList.add('is-invalid');
                }

                // Scroll to first error
                const firstError = document.querySelector('.is-invalid');
                if (firstError) {
                    firstError.scrollIntoView({
                        behavior: 'smooth',
                        block: 'center'
                    });
                }
            }
        });

        // Clear validation on input
        document.getElementById('email').addEventListener('input', function() {
            this.classList.remove('is-invalid');
        });

        document.getElementById('password').addEventListener('input', function() {
            this.classList.remove('is-invalid');
        });
    </script>

    {{-- bootstrap js --}}
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>

</body>

</html>
