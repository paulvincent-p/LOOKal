@extends('layouts.app')

@section('title', $business->name)

@section('content')
@if($business->status !== 'approved')
  <div class="flash err">This listing is <b>{{ $business->status }}</b> and is only visible to you.
    @if($business->admin_note) Note: {{ $business->admin_note }} @endif
  </div>
@endif

<article class="detail">
  @if($business->photoUrl())<img class="hero-img" src="{{ $business->photoUrl() }}" alt="{{ $business->name }}">@endif
  <h1 class="disp">{{ $business->name }}</h1>
  <div class="tags">
    <span class="tag">{{ $business->category->name }}</span>
    <span class="tag">Brgy. {{ $business->barangay->name }}</span>
    @if($business->is_verified)<span class="tag ok">Verified</span>@endif
    @if($average)<span class="tag">★ {{ $average }} ({{ $reviews->count() }})</span>@endif
  </div>

  @if($business->description)<p class="lead">{{ $business->description }}</p>@endif

  <dl class="facts">
    <dt>Phone</dt><dd>{{ $business->phone }}</dd>
    @if($business->hours)<dt>Hours</dt><dd>{{ $business->hours }}</dd>@endif
    @if($business->address)<dt>Address</dt><dd>{{ $business->address }}</dd>@endif
  </dl>

  @php($track = route('listings.event', $business))
  <div class="acts big">
    <a class="btn dark" href="tel:{{ $business->dialNumber() }}" data-track-url="{{ $track }}" data-action="call">Call</a>
    <a class="btn" href="sms:{{ $business->dialNumber() }}" data-track-url="{{ $track }}" data-action="text">Text</a>
    @if($business->messenger_link)
      <a class="btn" href="{{ $business->messenger_link }}" target="_blank" rel="noopener" data-track-url="{{ $track }}" data-action="messenger">Messenger</a>
    @endif
    <a class="btn" href="{{ $business->mapUrl() }}" target="_blank" rel="noopener" data-track-url="{{ $track }}" data-action="map">Map</a>
    <a class="btn" href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(route('listings.show', $business)) }}" target="_blank" rel="noopener" data-track-url="{{ $track }}" data-action="share">Share</a>
  </div>
</article>

<section class="panel">
  <h2 class="disp">Reviews</h2>
  @forelse($reviews as $r)
    <div class="review">
      <b>{{ $r->reviewer_name }}</b> <span class="rate">{{ str_repeat('★', $r->rating) }}{{ str_repeat('☆', 5 - $r->rating) }}</span>
      <small>{{ $r->created_at->format('M j, Y') }}</small>
      @if($r->comment)<p>{{ $r->comment }}</p>@endif
    </div>
  @empty
    <p class="muted">No reviews yet.</p>
  @endforelse

  @if($business->status === 'approved')
  <form method="POST" action="{{ route('listings.review', $business) }}" class="form-grid">
    @csrf
    <h3>Leave a review</h3>
    <label>Your name<input name="reviewer_name" value="{{ old('reviewer_name') }}" maxlength="60" required></label>
    <label>Rating
      <select name="rating" required>
        @for($i = 5; $i >= 1; $i--)<option value="{{ $i }}">{{ $i }} {{ $i === 1 ? 'star' : 'stars' }}</option>@endfor
      </select>
    </label>
    <label class="full">Comment<textarea name="comment" rows="2" maxlength="255">{{ old('comment') }}</textarea></label>
    <div class="full"><button class="btn dark" type="submit">Post review</button></div>
  </form>

  <details class="report">
    <summary>Report this listing</summary>
    <form method="POST" action="{{ route('listings.report', $business) }}" class="form-grid">
      @csrf
      <label>Reason
        <select name="reason" required>
          @foreach(['Wrong information', 'Closed or not available', 'Spam or fake', 'Abusive content', 'Other'] as $reason)
            <option>{{ $reason }}</option>
          @endforeach
        </select>
      </label>
      <label class="full">Details<textarea name="details" rows="2" maxlength="255"></textarea></label>
      <div class="full"><button class="btn" type="submit">Send report</button></div>
    </form>
  </details>
  @endif
</section>
@endsection
