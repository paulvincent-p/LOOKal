@extends('layouts.app')

@section('title', 'Admin dashboard')

@section('content')
@include('admin._nav')
<h1 class="disp">Dashboard</h1>

<div class="stats big">
  <a href="{{ route('admin.businesses.index', ['status' => 'pending']) }}"><b>{{ $counts['pending'] }}</b><span>Pending approval</span></a>
  <div><b>{{ $counts['approved'] }}</b><span>Approved listings</span></div>
  <div><b>{{ $counts['rejected'] }}</b><span>Rejected</span></div>
  <div><b>{{ $counts['owners'] }}</b><span>Owners</span></div>
  <div><b>{{ $counts['reviews'] }}</b><span>Reviews</span></div>
  <a href="{{ route('admin.reports.index') }}"><b>{{ $counts['reports'] }}</b><span>Open reports</span></a>
</div>

<section class="panel">
  <h2 class="disp">Activity (all time)</h2>
  <div class="stats">
    @foreach(['view' => 'Views', 'call' => 'Calls', 'text' => 'Texts', 'messenger' => 'Messenger', 'map' => 'Map opens', 'share' => 'Shares'] as $key => $label)
      <div><b>{{ $actions[$key] ?? 0 }}</b><span>{{ $label }}</span></div>
    @endforeach
  </div>
</section>

<section class="panel">
  <h2 class="disp">Most viewed listings</h2>
  @forelse($top as $row)
    <div class="row"><span>{{ $names[$row->business_id] ?? 'Deleted listing' }}</span><b>{{ $row->total }} views</b></div>
  @empty
    <p class="muted">No views yet.</p>
  @endforelse
  <div class="acts"><a class="btn" href="{{ route('admin.businesses.export') }}">Export all listings (CSV)</a></div>
</section>
@endsection
