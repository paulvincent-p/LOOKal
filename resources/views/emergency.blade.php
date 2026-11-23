@extends('layouts.app')

@section('title', 'Emergency contacts')

@section('content')
<h1 class="disp">Emergency contacts</h1>
<p class="muted">For life-threatening emergencies, call the national hotline right away.</p>

<section class="panel">
  <h2 class="disp">Town-wide</h2>
  @forelse($townContacts as $c)
    <div class="row"><span><b>{{ $c->name }}</b> @if($c->type)<small>{{ $c->type }}</small>@endif</span>
      <a class="btn dark" href="tel:{{ preg_replace('/[^0-9+]/', '', $c->number) }}">{{ $c->number }}</a></div>
  @empty
    <p class="muted">No contacts yet.</p>
  @endforelse
</section>

@foreach($barangayContacts as $barangay => $list)
<section class="panel">
  <h2 class="disp">Brgy. {{ $barangay }}</h2>
  @foreach($list as $c)
    <div class="row"><span><b>{{ $c->name }}</b> @if($c->type)<small>{{ $c->type }}</small>@endif</span>
      <a class="btn dark" href="tel:{{ preg_replace('/[^0-9+]/', '', $c->number) }}">{{ $c->number }}</a></div>
  @endforeach
</section>
@endforeach
@endsection
