<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>@yield('title', 'The Yellow Pages of Lingayen') | LOOKal</title>
<link rel="icon" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'%3E%3Ctext y='.9em' font-size='90'%3E👀%3C/text%3E%3C/svg%3E">
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Lilita+One&display=swap">
<link rel="stylesheet" href="{{ asset('css/lookal.css') }}">
</head>
<body>
<header class="top">
  <div class="bar">
    <a class="brand disp" href="{{ route('home') }}" aria-label="LOOKal home">@include('partials.logo') LOOKal</a>
    <nav>
      <a href="{{ route('home') }}">Search</a>
      <a href="{{ route('emergency') }}">Emergency</a>
      @auth
        @if(auth()->user()->isAdmin())
          <a href="{{ route('admin.dashboard') }}">Admin</a>
        @else
          <a href="{{ route('owner.dashboard') }}">My listings</a>
        @endif
        <form method="POST" action="{{ route('logout') }}" class="inline">@csrf<button class="link" type="submit">Log out</button></form>
      @else
        <a href="{{ route('login') }}">Log in</a>
        <a class="btn dark" href="{{ route('register') }}">List your business</a>
      @endauth
    </nav>
  </div>
</header>

<main class="wrap">
  @if(session('status'))<div class="flash ok" role="status">{{ session('status') }}</div>@endif
  @if($errors->any())
    <div class="flash err" role="alert">
      <ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
    </div>
  @endif
  @yield('content')
</main>

<footer class="foot">
  <b class="disp">LOOKal</b><br>
  Hyper-Local &amp; Informal Sector Directory · Lingayen, Pangasinan
</footer>

<script>
document.addEventListener('click', function (e) {
  var el = e.target.closest('[data-track-url]');
  if (!el) return;
  var fd = new FormData();
  fd.append('action', el.getAttribute('data-action'));
  fd.append('_token', document.querySelector('meta[name="csrf-token"]').content);
  try {
    fetch(el.getAttribute('data-track-url'), { method: 'POST', body: fd, keepalive: true, headers: { 'Accept': 'application/json' } });
  } catch (err) {}
});
</script>
@stack('scripts')
</body>
</html>
