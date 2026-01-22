<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset Password</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f5f5f5;
            padding: 40px;
        }
        .card {
            max-width: 400px;
            margin: auto;
            background: #fff;
            padding: 25px;
            border-radius: 6px;
        }
        input, button {
            width: 100%;
            padding: 10px;
            margin-top: 10px;
        }
        button {
            background: #2563eb;
            color: #fff;
            border: none;
            cursor: pointer;
        }
        .error {
            color: red;
            font-size: 14px;
        }
    </style>
</head>
<body>

<div class="card">
    <h2>Reset Password</h2>

    <form method="POST" action="{{ route('password.update') }}">
        @csrf

        <!-- Password Reset Token -->
        <input type="hidden" name="token" value="{{ $token }}">

        <!-- Email -->
        <input type="email" name="email" value="{{ $email ?? old('email') }}" required autofocus>

        @error('email')
            <div class="error">{{ $message }}</div>
        @enderror

        <!-- Password -->
        <input type="password" name="password" placeholder="New password" required>

        @error('password')
            <div class="error">{{ $message }}</div>
        @enderror

        <!-- Confirm Password -->
        <input type="password" name="password_confirmation" placeholder="Confirm password" required>

        <button type="submit">Reset Password</button>
    </form>
</div>

</body>
</html>
