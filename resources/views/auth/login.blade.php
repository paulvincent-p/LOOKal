@extends('layouts.app')

@section('title', 'Log in')

@section('content')
<section class="panel narrow">
  <h1 class="disp">Log in</h1>
  <form method="POST" action="{{ route('login') }}" class="form-grid one">
    @csrf
    <label>Email<input type="email" name="email" value="{{ old('email') }}" required autofocus></label>
    <label>Password<input type="password" name="password" required></label>
    <label class="check"><input type="checkbox" name="remember" value="1"> Remember me</label>
    <button class="btn dark" type="submit">Log in</button>
  </form>
  <p class="muted">No account yet? <a href="{{ route('register') }}">List your business</a>.</p>
</section>
@endsection
