@extends('layouts.app')
@section('title', 'Log In')

@section('content')
<section class="login-section">
  <div class="container" style="max-width: 700px;background:transparent;box-shadow:none">
    <h3 style="font-weight: 800;margin-bottom:16px">Log In</h3>
@if ($errors->login->has('login'))
    <span class="text-danger mb-2">
        {{ $errors->login->first('login') }}
    </span>
@endif
                @if($errors->any())
            <div class="alert alert-danger mb-3">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    <form class="login-form d-block"  method="POST" action="#">
      @csrf
      <label for="loginInput">Mobile Number or Email ID</label>
      <input
        type="text"
        id="loginInput"
        name="login"
        placeholder="Enter Mobile Number or Email ID"
        required
        value="{{ old('login') }}"
      />
            @error('login')
                <small class="text-danger">{{ $message }}</small>
            @enderror
      <label for="password">Password</label>
      <input
        type="password"
        id="password"
        name="password"
        placeholder="Enter your password"
        required
      />
            @error('password')
                <small class="text-danger">{{ $message }}</small>
            @enderror
      <button type="submit" class="btn-primary">Log In</button>
    </form>

  </div>
</section>
@endsection

@section('scripts')
<script>
  // You can add JS here if needed for login validation or effects
</script>
@endsection
