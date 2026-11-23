@extends('layouts.app')

@section('title', 'Reports')

@section('content')
@include('admin._nav')
<h1 class="disp">Reports</h1>

@forelse($reports as $r)
  <section class="panel">
    <div class="title-row">
      <span><b>{{ $r->reason }}</b> on <a href="{{ route('listings.show', $r->business) }}">{{ $r->business->name }}</a></span>
      <span class="badge {{ $r->status === 'open' ? 'pending' : 'approved' }}">{{ ucfirst($r->status) }}</span>
    </div>
    @if($r->details)<p>{{ $r->details }}</p>@endif
    <p class="muted">{{ $r->created_at->format('M j, Y g:i A') }}</p>
    @if($r->status === 'open')
      <div class="acts">
        <form method="POST" action="{{ route('admin.reports.resolve', $r) }}">@csrf @method('PATCH')
          <button class="btn dark" type="submit">Mark as resolved</button></form>
        <a class="btn" href="{{ route('admin.businesses.edit', $r->business) }}">Edit listing</a>
      </div>
    @endif
  </section>
@empty
  <div class="empty">No reports.</div>
@endforelse

{{ $reports->links() }}
@endsection
