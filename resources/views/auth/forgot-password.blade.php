<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Forgot Password</title>
</head>
<body>

<h2>Forgot your password?</h2>

@if (session('status'))
    <p style="color: green;">{{ session('status') }}</p>
@endif

<form method="POST" action="{{ route('password.email') }}">
    @csrf

    <input type="email" name="email" placeholder="Email address" required>

    @error('email')
        <div style="color:red;">{{ $message }}</div>
    @enderror

    <button type="submit">Send Password Reset Link</button>
</form>

</body>
</html>
