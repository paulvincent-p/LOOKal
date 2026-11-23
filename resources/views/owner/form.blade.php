@extends('layouts.app')

@section('title', $title)

@section('content')
<section class="panel">
  <h1 class="disp">{{ $title }}</h1>
  @if(auth()->user()->role === 'owner')
    <p class="muted">Every new listing or change is checked by an administrator before it shows to the public.</p>
  @endif

  <form method="POST" action="{{ $action }}" enctype="multipart/form-data" class="form-grid">
    @csrf
    @if($method !== 'POST') @method($method) @endif

    <label>Business name<input name="name" value="{{ old('name', $business->name) }}" maxlength="100" required></label>
    <label>Category
      <select name="category_id" required>
        <option value="">Choose…</option>
        @foreach($categories as $c)
          <option value="{{ $c->id }}" @selected(old('category_id', $business->category_id) == $c->id)>{{ $c->name }}</option>
        @endforeach
      </select>
    </label>
    <label>Barangay
      <select name="barangay_id" required>
        <option value="">Choose…</option>
        @foreach($barangays as $b)
          <option value="{{ $b->id }}" @selected(old('barangay_id', $business->barangay_id) == $b->id)>{{ $b->name }}</option>
        @endforeach
      </select>
    </label>
    <label>Mobile or phone number<input name="phone" value="{{ old('phone', $business->phone) }}" inputmode="tel" placeholder="09XX XXX XXXX" required></label>
    <label>Messenger link (optional)<input name="messenger_link" value="{{ old('messenger_link', $business->messenger_link) }}" placeholder="https://m.me/yourpage"></label>
    <label>Opening hours (optional)<input name="hours" value="{{ old('hours', $business->hours) }}" maxlength="100" placeholder="Mon to Sat, 8am to 6pm"></label>
    <label class="full">Address or landmark (optional)<input name="address" value="{{ old('address', $business->address) }}" maxlength="150"></label>
    <label class="full">Short description<textarea name="description" rows="3" maxlength="500" placeholder="What do you offer?">{{ old('description', $business->description) }}</textarea></label>
    <label>Latitude (optional)<input name="latitude" value="{{ old('latitude', $business->latitude) }}" inputmode="decimal" placeholder="16.0215"></label>
    <label>Longitude (optional)<input name="longitude" value="{{ old('longitude', $business->longitude) }}" inputmode="decimal" placeholder="120.2316"></label>
    <label class="full">Photo (optional, JPG, PNG or WebP, up to 2 MB)<input type="file" name="photo" accept="image/*"></label>
    @if($business->photoUrl())<div class="full"><img class="thumb" src="{{ $business->photoUrl() }}" alt="Current photo"></div>@endif

    <div class="full acts">
      <button class="btn dark" type="submit">Save</button>
      <a class="btn" href="{{ $cancel }}">Cancel</a>
    </div>
  </form>
  <p class="muted">Tip: to get coordinates, open Google Maps, right-click your location, and click the numbers shown.</p>
</section>
@endsection
