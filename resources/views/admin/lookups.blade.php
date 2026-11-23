@extends('layouts.app')

@section('title', $label)

@section('content')
@include('admin._nav')
<h1 class="disp">{{ $label }}</h1>

<section class="panel">
  <form method="POST" action="{{ route('admin.lookups.store', $type) }}" class="inline-form">
    @csrf
    <input name="name" placeholder="New name" maxlength="60" required>
    <button class="btn dark" type="submit">Add</button>
  </form>
</section>

<section class="panel">
  @foreach($items as $item)
    <div class="row">
      <span>{{ $item->name }} <small class="muted">({{ $item->businesses_count }} {{ \Illuminate\Support\Str::plural('listing', $item->businesses_count) }})</small></span>
      <form method="POST" action="{{ route('admin.lookups.destroy', [$type, $item->id]) }}" onsubmit="return confirm('Delete {{ addslashes($item->name) }}?')">
        @csrf @method('DELETE')
        <button class="btn danger" type="submit">Delete</button>
      </form>
    </div>
  @endforeach
</section>
@endsection
