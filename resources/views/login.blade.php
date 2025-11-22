@extends('layouts.app')
@section('title', 'Log In')

@section('content')
<section class="login-section">
  <div class="container">
    <h1>Log In to BloodConnect</h1>

    <form class="login-form" method="POST" action="#">
      @csrf
      <label for="loginInput">Login with Mobile Number or Email ID</label>
      <input
        type="text"
        id="loginInput"
        name="login"
        placeholder="Enter Mobile Number or Email ID"
        required
      />

      <label for="password">Password</label>
      <input
        type="password"
        id="password"
        name="password"
        placeholder="Enter your password"
        required
      />

      <button type="submit" class="btn-primary">Log In</button>
    </form>

    <p class="register-prompt">
      Don't have an account? <a href="{{ route('registration') }}">Register here</a>
    </p>
  </div>
</section>
@endsection

@section('scripts')
<script>
  // You can add JS here if needed for login validation or effects
</script>
@endsection
