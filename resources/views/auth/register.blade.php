@extends('layouts.app')

@section('title', 'List your business')

@section('content')
<section class="panel narrow">
  <h1 class="disp">Create an owner account</h1>
  <p class="muted">Free. After you sign up, add your business and an administrator will approve it.</p>
  <form method="POST" action="{{ route('register') }}" class="form-grid one">
    @csrf
    <label>Your name<input name="name" value="{{ old('name') }}" maxlength="100" required></label>
    <label>Email<input type="email" name="email" value="{{ old('email') }}" required></label>
    <label>Mobile number<input name="phone" value="{{ old('phone') }}" inputmode="tel" placeholder="09XX XXX XXXX" required></label>
    <label>Password (8+ characters)<input type="password" name="password" minlength="8" required></label>
    <label>Confirm password<input type="password" name="password_confirmation" minlength="8" required></label>
    <button class="btn dark" type="submit">Create account</button>
  </form>
  <p class="muted">Already registered? <a href="{{ route('login') }}">Log in</a>.</p>
</section>
@endsection
