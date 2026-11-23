@extends('layouts.app')

@section('title', 'Users')

@section('content')
@include('admin._nav')
<h1 class="disp">Users</h1>

<section class="panel">
  @foreach($users as $u)
    <div class="row">
      <span><b>{{ $u->name }}</b> <small class="muted">{{ $u->email }} · {{ ucfirst($u->role) }} · {{ $u->businesses_count }} {{ \Illuminate\Support\Str::plural('listing', $u->businesses_count) }}</small>
        <span class="badge {{ $u->status === 'active' ? 'approved' : 'rejected' }}">{{ ucfirst($u->status) }}</span></span>
      @if($u->id !== auth()->id())
        <form method="POST" action="{{ route('admin.users.toggle', $u) }}">@csrf @method('PATCH')
          <button class="btn" type="submit">{{ $u->status === 'active' ? 'Suspend' : 'Reactivate' }}</button></form>
      @endif
    </div>
  @endforeach
</section>

{{ $users->links() }}
@endsection
