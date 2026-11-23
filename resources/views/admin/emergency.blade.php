@extends('layouts.app')

@section('title', 'Emergency contacts')

@section('content')
@include('admin._nav')
<h1 class="disp">Emergency contacts</h1>

<section class="panel">
  <form method="POST" action="{{ route('admin.emergency.store') }}" class="form-grid">
    @csrf
    <label>Name<input name="name" maxlength="80" placeholder="Barangay hall" required></label>
    <label>Number<input name="number" inputmode="tel" required></label>
    <label>Type (optional)<input name="type" maxlength="40" placeholder="Police, health, fire…"></label>
    <label>Barangay
      <select name="barangay_id">
        <option value="">Town-wide</option>
        @foreach($barangays as $b)<option value="{{ $b->id }}">{{ $b->name }}</option>@endforeach
      </select>
    </label>
    <div class="full"><button class="btn dark" type="submit">Add contact</button></div>
  </form>
</section>

<section class="panel">
  @forelse($contacts as $c)
    <div class="row">
      <span><b>{{ $c->name }}</b> · {{ $c->number }} <small class="muted">{{ $c->type }} · {{ $c->barangay ? 'Brgy. '.$c->barangay->name : 'Town-wide' }}</small></span>
      <form method="POST" action="{{ route('admin.emergency.destroy', $c) }}" onsubmit="return confirm('Delete this contact?')">
        @csrf @method('DELETE')
        <button class="btn danger" type="submit">Delete</button>
      </form>
    </div>
  @empty
    <p class="muted">No contacts yet.</p>
  @endforelse
</section>
@endsection
