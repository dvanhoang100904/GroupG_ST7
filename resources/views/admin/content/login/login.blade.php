<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Đăng Nhập Hệ Thống</title>

    {{-- font-awesome icon --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
        integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    {{-- bootstrap css --}}
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">


    <style>
        .admin-login {
            max-width: 450px;
            margin: 5% auto;
            padding: 2rem;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            background-color: #f8f9fa;
        }

        .admin-login h2 {
            color: #343a40;
            font-weight: 600;
        }

        .btn-login {
            padding: 10px;
            font-weight: 500;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="login-container admin-login">
            <h2 class="text-center mb-4">Admin Dashboard</h2>
            <form action="{{ route('admin.authLogin') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-user-shield"></i></span>
                        <input type="email" class="form-control" value="{{ old('email') }}" id="email"
                            name="email" placeholder="Nhập tên đăng nhập admin" required>
                        @if ($errors->has('email'))
                            <span class="text-danger">{{ $errors->first('email') }}</span>
                        @endif
                    </div>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Mật khẩu</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-key"></i></span>
                        <input type="password" class="form-control" id="password" name="password"
                            placeholder="Nhập mật khẩu admin" required>
                        @if ($errors->has('password'))
                            <span class="text-danger">{{ $errors->first('password') }}</span>
                        @endif
                        <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>

                <div class="d-flex justify-content-between mb-3">
                    <div class="form-check">
                    </div>
                    <a href="#!" class="text-decoration-none text-dark">Quên mật khẩu?</a>
                </div>

                <!-- Submit button -->
                <button type="submit" class="btn btn-dark btn-login w-100 mb-3">
                    <i class="fas fa-sign-in-alt me-2"></i> Đăng nhập
                </button>

                <!-- Security warning -->
                <div class="alert alert-warning mt-3">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Cảnh báo: Đây là khu vực quản trị hệ thống. Chỉ nhân viên được ủy quyền mới được phép truy cập.
                </div>
            </form>
        </div>
    </div>

    <script>
        // Toggle password visibility
        document.getElementById('togglePassword').addEventListener('click', function() {
            const passwordInput = document.getElementById('password');
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            this.querySelector('i').classList.toggle('fa-eye-slash');
        });
    </script>

    {{-- bootstrap js --}}
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>

</body>

</html>
