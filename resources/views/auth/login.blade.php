<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đăng nhập</title>
    <link href="https://fonts.googleapis.com/css2?family=Quicksand&display=swap" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Quicksand&display=swap');
        *, :before, :after {
            margin:0;
            padding:0;
            box-sizing: border-box;
            font-family: inherit;
        }

        body {
            font-family: 'Quicksand', sans-serif;
            background-image: url(https://raw.githubusercontent.com/CiurescuP/LogIn-Form/main/bg.jpg);
            background-repeat: no-repeat;
            background-size: cover;
            height: 100vh;
        }

        form {
            height: auto;
            width: 450px;
            background-color: rgba(255, 255, 255,0.13);
            position: absolute;
            transform: translate(-50%,-50%);
            top: 50%;
            left: 50%;
            border-radius: 17px;
            backdrop-filter: blur(5px);
            border: 5px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 0 40px rgba(129, 236, 174, 0.6);
            padding: 30px;
        }

        form *{
            font-family: 'Quicksand', sans-serif;
            color:#ffffff;
            letter-spacing: 1px;
            outline: none;
            border: none;
        }

        form h3 {
            font-size: 35px;
            font-weight: 600;
            line-height: 45px;
            text-align: center;
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-top: 20px;
            font-size: 18px;
            font-weight: 700;
        }

        input {
            margin-top: 10px;
            margin-bottom: 10px;
            padding: 11px 15px;
            font-size: 14px;
            font-weight: 300;
            background: rgba(0, 0, 0, 0.22);
            border: 2px solid #38363654;
            border-radius: 5px;
            width: 100%;
        }

        input:hover {
            background: #434343;
            transition: all 0.50s ease;
        }

        input:focus {
            box-shadow: 0px 2px 2px #0000002b, 0px 5px 10px #00000036;
            background: #434343;        
        }

        ::placeholder {     
            color: #e5e5e5;
        }

        button {
            margin-top: 30px;
            width: 100%;    
            background: rgba(0, 0, 0, 0.22);
            border: 2px solid #38363654;
            border-radius: 5px;
            color: #e1e1e1;
            padding: 10px 15px;
            font-size: 18px;
            font-weight: 600;
            cursor: pointer;
        } 

        button:hover {
            background: #629677;
            transition: all 0.50s ease;
        }

        button:focus {
            box-shadow: 0px 0px 0px 2px rgba(103, 110, 103, 0.71);
            background: #629677;
        }

        .messages {
            margin-top: 10px;
            margin-bottom: 15px;
            font-size: 14px;
            text-align: center;
        }

        .messages.success {
            color: #d4ffcc;
        }

        .messages.error {
            color: #ffc5c5;
        }
    </style>
</head>
<body>
    <form method="POST" action="{{ route('login.submit') }}">
        @csrf
        <h3>Đăng nhập</h3>

        @if(session('success'))
            <div class="messages success">{{ session('success') }}</div>
        @endif

        @if($errors->any())
            <div class="messages error">
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <label for="email">Email</label>
        <input type="email" name="email" value="{{ old('email') }}" placeholder="Nhập email">

        <label for="password">Mật khẩu</label>
        <input type="password" name="password" placeholder="Nhập mật khẩu">

        <button type="submit">Đăng nhập</button>
        <br><br>
        <a href="{{ route('register') }}">
        Chưa có tài khoản? Đăng ký
        </a>
    </form>
</body>
</html>
