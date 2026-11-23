@extends('layouts.app')

@section('title', 'Listings')

@section('content')
@include('admin._nav')
<div class="title-row">
  <h1 class="disp">Listings</h1>
  <div class="filters">
    @foreach(['pending' => 'Pending', 'approved' => 'Approved', 'rejected' => 'Rejected', 'all' => 'All'] as $key => $label)
      <a class="chip {{ $status === $key ? 'on' : '' }}" href="{{ route('admin.businesses.index', ['status' => $key]) }}">{{ $label }}</a>
    @endforeach
  </div>
</div>

@forelse($businesses as $b)
  <section class="panel">
    <div class="title-row">
      <h2 class="disp">{{ $b->name }}</h2>
      <span class="badge {{ $b->status }}">{{ ucfirst($b->status) }}</span>
    </div>
    <div class="tags">
      <span class="tag">{{ $b->category->name }}</span>
      <span class="tag">Brgy. {{ $b->barangay->name }}</span>
      <span class="tag">{{ $b->phone }}</span>
      @if($b->is_verified)<span class="tag ok">Verified</span>@endif
    </div>
    <p class="muted">Owner: {{ $b->owner->name }} ({{ $b->owner->email }}) · Submitted {{ $b->created_at->format('M j, Y') }}</p>
    @if($b->description)<p>{{ $b->description }}</p>@endif
    @if($b->admin_note)<p class="muted">Note: {{ $b->admin_note }}</p>@endif

    <div class="acts wrap">
      <a class="btn" href="{{ route('listings.show', $b) }}">Preview</a>
      <a class="btn" href="{{ route('admin.businesses.edit', $b) }}">Edit</a>

      <form method="POST" action="{{ route('admin.businesses.status', $b) }}" class="inline-form">
        @csrf @method('PATCH')
        <input type="hidden" name="status" value="approved">
        <label class="check"><input type="checkbox" name="is_verified" value="1" checked> Verified</label>
        <button class="btn dark" type="submit">Approve</button>
      </form>

      <form method="POST" action="{{ route('admin.businesses.status', $b) }}" class="inline-form">
        @csrf @method('PATCH')
        <input type="hidden" name="status" value="rejected">
        <input name="admin_note" placeholder="Reason (shown to owner)" maxlength="255">
        <button class="btn" type="submit">Reject</button>
      </form>

      <form method="POST" action="{{ route('admin.businesses.destroy', $b) }}" onsubmit="return confirm('Delete this listing for good?')">
        @csrf @method('DELETE')
        <button class="btn danger" type="submit">Delete</button>
      </form>
    </div>
  </section>
@empty
  <div class="empty">No listings in this view.</div>
@endforelse

{{ $businesses->links() }}
@endsection
