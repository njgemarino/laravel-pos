<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - POS System</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            margin: 0;
            background: #f5f4f0;
            font-family: 'DM Sans', sans-serif;
        }

        .auth-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .auth-card {
            width: 100%;
            max-width: 380px;
            background: #fff;
            border: 0.5px solid #e5e2d8;
            border-radius: 14px;
            padding: 24px;
        }

        .auth-title {
            font-size: 18px;
            font-weight: 600;
            color: #0f0f0f;
        }

        .auth-sub {
            font-size: 12px;
            color: #999;
            margin-top: 3px;
            margin-bottom: 18px;
        }

        .auth-group {
            margin-bottom: 12px;
        }

        .auth-label {
            font-size: 10px;
            text-transform: uppercase;
            color: #bbb;
            margin-bottom: 4px;
            display: block;
        }

        .auth-input {
            width: 100%;
            background: #faf9f5;
            border: 0.5px solid #e5e2d8;
            border-radius: 8px;
            padding: 9px 10px;
            font-size: 12px;
            outline: none;
        }

        .auth-input:focus {
            border-color: #0f0f0f;
            background: #fff;
        }

        .auth-btn {
            width: 100%;
            background: #0f0f0f;
            color: #fff;
            border: none;
            border-radius: 8px;
            padding: 10px;
            font-size: 13px;
            font-weight: 500;
            cursor: pointer;
            transition: opacity .15s;
        }

        .auth-btn:hover {
            opacity: .85;
        }

        .auth-btn-soft {
            width: 100%;
            margin-top: 8px;
            background: transparent;
            border: 0.5px solid #e5e2d8;
            border-radius: 8px;
            padding: 10px;
            font-size: 12px;
            color: #888;
            text-decoration: none;
            display: flex;
            justify-content: center;
        }

        .auth-btn-soft:hover {
            background: #f5f3ec;
            color: #0f0f0f;
        }

        .auth-error {
            font-size: 11px;
            color: #dc2626;
            margin-top: 3px;
        }

        .auth-alert {
            font-size: 12px;
            margin-bottom: 10px;
        }

        .auth-success {
            color: #059669;
        }
    </style>
</head>

<body>

<div class="auth-container">

    <div class="auth-card">

        <div class="auth-title">POS System</div>
        <div class="auth-sub">Login to your account</div>

        @if(session('success'))
            <div class="auth-alert auth-success">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="auth-alert auth-error">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="auth-group">
                <label class="auth-label">Email</label>
                <input type="email"
                       name="email"
                       value="{{ old('email') }}"
                       class="auth-input"
                       required autofocus>
            </div>

            <div class="auth-group">
                <label class="auth-label">Password</label>
                <input type="password"
                       name="password"
                       class="auth-input"
                       required>
            </div>

            <button type="submit" class="auth-btn">
                Login
            </button>

            <a href="{{ route('register') }}" class="auth-btn-soft">
                Create account
            </a>

        </form>

    </div>

</div>

</body>
</html>