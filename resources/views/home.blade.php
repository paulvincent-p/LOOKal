@extends('layouts.app')

@section('content')
<section class="hero">
  <h1 class="disp">Look local. Find it fast.</h1>
  <p>The Yellow Pages of Lingayen: stores, tricycles, home-based sellers, and services from your kabaley.</p>
  <form method="GET" action="{{ route('home') }}" class="search" role="search">
    <input type="search" name="q" value="{{ $q }}" placeholder="Search: bakery, tricycle, nails…" aria-label="Search for a business or service">
    <select name="category" aria-label="Category">
      <option value="">All categories</option>
      @foreach($categories as $c)
        <option value="{{ $c->id }}" @selected(request('category') == $c->id)>{{ $c->name }}</option>
      @endforeach
    </select>
    <select name="barangay" aria-label="Barangay">
      <option value="">All barangays</option>
      @foreach($barangays as $b)
        <option value="{{ $b->id }}" @selected(request('barangay') == $b->id)>{{ $b->name }}</option>
      @endforeach
    </select>
    <button class="btn dark" type="submit">Search</button>
  </form>
</section>

@if($townContacts->count())
<div class="sos">
  <span><b>Emergency?</b>
    @foreach($townContacts as $t)
      {{ $t->name }}: <a href="tel:{{ preg_replace('/[^0-9+]/', '', $t->number) }}">{{ $t->number }}</a>@if(! $loop->last) · @endif
    @endforeach
  </span>
  <a href="{{ route('emergency') }}">All emergency numbers</a>
</div>
@endif

<p class="count">{{ $businesses->total() }} {{ \Illuminate\Support\Str::plural('listing', $businesses->total()) }}</p>

<div class="grid">
  @forelse($businesses as $b)
    @include('partials.card', ['b' => $b])
  @empty
    <div class="empty">No results. Try another word, or <a href="{{ route('register') }}">add your business</a>.</div>
  @endforelse
</div>

{{ $businesses->links() }}
@endsection
