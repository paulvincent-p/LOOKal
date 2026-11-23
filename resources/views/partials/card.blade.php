<article class="card">
  @if($b->photoUrl())<img class="thumb" src="{{ $b->photoUrl() }}" alt="" loading="lazy">@endif
  <h3 class="disp"><a href="{{ route('listings.show', $b) }}">{{ $b->name }}</a></h3>
  <div class="tags">
    <span class="tag">{{ $b->category->name }}</span>
    <span class="tag">Brgy. {{ $b->barangay->name }}</span>
    @if($b->is_verified)<span class="tag ok">Verified</span>@endif
  </div>
  @if(! empty($b->reviews_total))<p class="rate">★ {{ number_format($b->avg_rating, 1) }} ({{ $b->reviews_total }})</p>@endif
  @if($b->description)<p>{{ \Illuminate\Support\Str::limit($b->description, 110) }}</p>@endif
  <div class="acts">
    <a class="btn dark" href="tel:{{ $b->dialNumber() }}" data-track-url="{{ route('listings.event', $b) }}" data-action="call">Call</a>
    <a class="btn" href="{{ route('listings.show', $b) }}">Details</a>
  </div>
</article>
