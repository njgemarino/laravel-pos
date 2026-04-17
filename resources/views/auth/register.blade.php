<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - POS System</title>

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
            max-width: 420px;
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
            letter-spacing: .08em;
        }

        .auth-input,
        .auth-select {
            width: 100%;
            background: #faf9f5;
            border: 0.5px solid #e5e2d8;
            border-radius: 8px;
            padding: 9px 10px;
            font-size: 12px;
            outline: none;
            font-family: inherit;
            color: #0f0f0f;
        }

        .auth-input:focus,
        .auth-select:focus {
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
            font-family: inherit;
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
            margin-top: 4px;
        }

        .auth-alert {
            font-size: 12px;
            margin-bottom: 10px;
            color: #dc2626;
        }
    </style>
</head>

<body>

<div class="auth-container">

    <div class="auth-card">

        <div class="auth-title">POS System</div>
        <div class="auth-sub">Create a new account</div>

        @if ($errors->any())
            <div class="auth-alert">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="auth-group">
                <label class="auth-label">Name</label>
                <input type="text"
                       name="name"
                       value="{{ old('name') }}"
                       class="auth-input"
                       required
                       autofocus>
                @error('name')
                    <div class="auth-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="auth-group">
                <label class="auth-label">Email</label>
                <input type="email"
                       name="email"
                       value="{{ old('email') }}"
                       class="auth-input"
                       required>
                @error('email')
                    <div class="auth-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="auth-group">
                <label class="auth-label">Password</label>
                <input type="password"
                       name="password"
                       class="auth-input"
                       required>
                @error('password')
                    <div class="auth-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="auth-group">
                <label class="auth-label">Confirm Password</label>
                <input type="password"
                       name="password_confirmation"
                       class="auth-input"
                       required>
                @error('password_confirmation')
                    <div class="auth-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="auth-group">
                <label class="auth-label">Register As</label>
                <select name="role" class="auth-select" required>
                    <option value="">Select Role</option>
                    <option value="cashier" {{ old('role') == 'cashier' ? 'selected' : '' }}>Cashier</option>
                    <option value="inventory" {{ old('role') == 'inventory' ? 'selected' : '' }}>Inventory Staff</option>
                </select>
                @error('role')
                    <div class="auth-error">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="auth-btn">
                Register
            </button>

            <a href="{{ route('login') }}" class="auth-btn-soft">
                Already registered?
            </a>
        </form>

    </div>

</div>

</body>
</html>