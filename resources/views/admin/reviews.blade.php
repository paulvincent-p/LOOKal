@extends('layouts.app')

@section('title', 'Reviews')

@section('content')
@include('admin._nav')
<h1 class="disp">Reviews</h1>

@forelse($reviews as $r)
  <section class="panel">
    <div class="title-row">
      <span><b>{{ $r->reviewer_name }}</b> on <a href="{{ route('listings.show', $r->business) }}">{{ $r->business->name }}</a></span>
      <span class="badge {{ $r->status === 'visible' ? 'approved' : 'rejected' }}">{{ ucfirst($r->status) }}</span>
    </div>
    <p class="rate">{{ str_repeat('★', $r->rating) }}{{ str_repeat('☆', 5 - $r->rating) }} <small>{{ $r->created_at->format('M j, Y') }}</small></p>
    @if($r->comment)<p>{{ $r->comment }}</p>@endif
    <div class="acts">
      <form method="POST" action="{{ route('admin.reviews.toggle', $r) }}">@csrf @method('PATCH')
        <button class="btn" type="submit">{{ $r->status === 'visible' ? 'Hide' : 'Show' }}</button></form>
      <form method="POST" action="{{ route('admin.reviews.destroy', $r) }}" onsubmit="return confirm('Delete this review?')">@csrf @method('DELETE')
        <button class="btn danger" type="submit">Delete</button></form>
    </div>
  </section>
@empty
  <div class="empty">No reviews yet.</div>
@endforelse

{{ $reviews->links() }}
@endsection
