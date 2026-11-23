@extends('layouts.app')

@section('title', 'My listings')

@section('content')
<div class="title-row">
  <h1 class="disp">My listings</h1>
  <a class="btn dark" href="{{ route('owner.businesses.create') }}">+ Add business</a>
</div>

@forelse($businesses as $b)
  @php($s = $stats[$b->id] ?? [])
  <section class="panel">
    <div class="title-row">
      <h2 class="disp">{{ $b->name }}</h2>
      <span class="badge {{ $b->status }}">{{ ucfirst($b->status) }}</span>
    </div>
    <div class="tags">
      <span class="tag">{{ $b->category->name }}</span>
      <span class="tag">Brgy. {{ $b->barangay->name }}</span>
      @if($b->is_verified)<span class="tag ok">Verified</span>@endif
    </div>
    @if($b->status === 'rejected' && $b->admin_note)<p class="flash err">Admin note: {{ $b->admin_note }}</p>@endif
    @if($b->status === 'pending')<p class="muted">Waiting for administrator approval.</p>@endif

    <div class="stats">
      <div><b>{{ $s['view'] ?? 0 }}</b><span>Views</span></div>
      <div><b>{{ $s['call'] ?? 0 }}</b><span>Calls</span></div>
      <div><b>{{ $s['text'] ?? 0 }}</b><span>Texts</span></div>
      <div><b>{{ $s['messenger'] ?? 0 }}</b><span>Messenger</span></div>
      <div><b>{{ $s['map'] ?? 0 }}</b><span>Map opens</span></div>
      <div><b>{{ $s['share'] ?? 0 }}</b><span>Shares</span></div>
    </div>

    <div class="acts">
      @if($b->status === 'approved')
        <a class="btn" href="{{ route('listings.show', $b) }}">View</a>
        <a class="btn" href="{{ route('owner.businesses.qr', $b) }}">QR code</a>
      @endif
      <a class="btn" href="{{ route('owner.businesses.edit', $b) }}">Edit</a>
      <form method="POST" action="{{ route('owner.businesses.destroy', $b) }}" onsubmit="return confirm('Delete this listing?')">
        @csrf @method('DELETE')
        <button class="btn danger" type="submit">Delete</button>
      </form>
    </div>
  </section>
@empty
  <div class="empty">You have no listings yet. <a href="{{ route('owner.businesses.create') }}">Add your first business</a>.</div>
@endforelse
@endsection
