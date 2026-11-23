@extends('layouts.app')

@section('title', 'QR code')

@section('content')
<section class="panel narrow center">
  <h1 class="disp">{{ $business->name }}</h1>
  <p class="muted">Print this QR code and post it at your store, tricycle, or stall. Scanning it opens your LOOKal page.</p>
  <div id="qr" class="qr"></div>
  <p><small>{{ $url }}</small></p>
  <div class="acts center">
    <a id="download" class="btn dark" download="lookal-{{ $business->id }}-qr.png" href="#">Download PNG</a>
    <button class="btn" type="button" onclick="window.print()">Print</button>
    <a class="btn" href="{{ route('owner.dashboard') }}">Back</a>
  </div>
</section>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script>
  var box = document.getElementById('qr');
  new QRCode(box, { text: @json($url), width: 256, height: 256 });
  setTimeout(function () {
    var canvas = box.querySelector('canvas');
    if (canvas) { document.getElementById('download').href = canvas.toDataURL('image/png'); }
  }, 300);
</script>
@endpush
