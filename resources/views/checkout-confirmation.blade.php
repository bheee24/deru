<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>DERU | Order Confirmed</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;0,700;1,400&family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    * { margin:0; padding:0; box-sizing:border-box; }
    body { font-family:'Montserrat',sans-serif; background:#f1f0ec; color:#0f0f0f; }
    .site-header { display:flex; align-items:center; justify-content:center; padding:20px 5vw; border-bottom:1px solid rgba(0,0,0,0.08); background:#f1f0ec; }
    @keyframes fadeUp { from{opacity:0;transform:translateY(20px)} to{opacity:1;transform:translateY(0)} }
    .fade-up  { animation:fadeUp 0.6s ease forwards; }
    .delay-1  { animation-delay:0.15s; opacity:0; }
    .delay-2  { animation-delay:0.3s;  opacity:0; }
    .delay-3  { animation-delay:0.45s; opacity:0; }
    @keyframes checkPop { 0%{transform:scale(0);opacity:0} 60%{transform:scale(1.15)} 100%{transform:scale(1);opacity:1} }
    .check-circle { width:72px; height:72px; border-radius:50%; background:#c9a96e; display:flex; align-items:center; justify-content:center; margin:0 auto 1.5rem; animation:checkPop 0.6s cubic-bezier(0.34,1.56,0.64,1) 0.2s both; }
    .detail-row { display:flex; justify-content:space-between; align-items:flex-start; padding:10px 0; border-bottom:1px solid rgba(0,0,0,0.05); font-size:13px; gap:1rem; }
    .detail-row:last-child { border-bottom:none; }
    .detail-label { color:#7a7a72; flex-shrink:0; }
    .detail-value { font-weight:500; text-align:right; }
    .order-item-row { display:flex; align-items:center; gap:12px; padding:10px 0; border-bottom:1px solid rgba(0,0,0,0.05); }
    .order-item-row:last-child { border-bottom:none; }
    .item-img { width:48px; height:58px; object-fit:cover; background:#e8e5de; flex-shrink:0; }
    .btn-deru-primary { display:inline-flex; align-items:center; justify-content:center; gap:10px; background:#c9a96e; color:#0f0f0f; padding:14px 36px; font-family:'Montserrat',sans-serif; font-size:11px; font-weight:600; letter-spacing:0.2em; text-transform:uppercase; text-decoration:none; transition:all 0.3s ease; border:1px solid #c9a96e; }
    .btn-deru-primary:hover { background:transparent; color:#c9a96e; }
    .btn-deru-outline { display:inline-flex; align-items:center; justify-content:center; gap:10px; background:transparent; color:#0f0f0f; padding:12px 32px; font-family:'Montserrat',sans-serif; font-size:11px; font-weight:600; letter-spacing:0.2em; text-transform:uppercase; text-decoration:none; transition:all 0.3s ease; border:1px solid #0f0f0f; }
    .btn-deru-outline:hover { background:#0f0f0f; color:white; }
    .info-card { background:white; border:1px solid rgba(0,0,0,0.06); padding:1.5rem 2rem; margin-bottom:1.25rem; }
    .info-card-title { font-family:'Cormorant Garamond',serif; font-size:1.2rem; font-weight:700; margin-bottom:1.25rem; padding-bottom:0.75rem; border-bottom:1px solid rgba(0,0,0,0.06); }
  </style>
</head>
<body>

<header class="site-header">
  <a href="/" style="text-decoration:none;">
    <span style="font-family:'Cormorant Garamond',serif; font-size:1.8rem; font-weight:700; letter-spacing:0.2em; color:#0f0f0f;">DeruApparel</span>
  </a>
</header>

<main class="py-5">
  <div class="container" style="max-width:660px;">

    <div class="text-center mb-4 fade-up">
      <div class="check-circle">
        <i class="fas fa-check" style="color:#0f0f0f; font-size:1.5rem;"></i>
      </div>
      <p style="font-size:11px; letter-spacing:0.3em; text-transform:uppercase; color:#c9a96e; margin-bottom:0.75rem;">Order Confirmed</p>
      <h1 style="font-family:'Cormorant Garamond',serif; font-size:clamp(2rem,5vw,3rem); font-weight:700; line-height:1.1; margin-bottom:1rem;">
        Thank you, {{ $order->first_name }}!
      </h1>
      <p style="font-size:13px; color:#7a7a72; max-width:400px; margin:0 auto; line-height:1.7;">
        Your order is confirmed. A receipt has been sent to <strong style="color:#0f0f0f;">{{ $order->email }}</strong>.
      </p>
    </div>

    {{-- Reference --}}
    <div class="fade-up delay-1" style="background:#0f0f0f; color:white; padding:1.5rem 2rem; text-align:center; margin-bottom:1.25rem;">
      <p style="font-size:9px; letter-spacing:0.25em; text-transform:uppercase; color:rgba(255,255,255,0.4); margin-bottom:4px;">Order Reference</p>
      <p style="font-family:'Cormorant Garamond',serif; font-size:2rem; font-weight:700; letter-spacing:0.1em; color:#c9a96e; margin:0;">
        {{ $order->reference }}
      </p>
      <p style="font-size:11px; color:rgba(255,255,255,0.3); margin-top:4px;">
        Placed {{ $order->created_at->format('d M Y, H:i') }}
      </p>
    </div>

    {{-- Items --}}
    <div class="info-card fade-up delay-1">
      <h3 class="info-card-title">
        <i class="fas fa-box" style="color:#c9a96e; margin-right:8px;"></i> Items Ordered
      </h3>
      @foreach($order->items as $item)
      <div class="order-item-row">
        @if($item->product_img)
          <img src="{{ $item->product_img }}" class="item-img" alt="{{ $item->product_name }}">
        @else
          <div class="item-img" style="display:flex; align-items:center; justify-content:center;">
            <i class="fas fa-image" style="color:#c8c5be;"></i>
          </div>
        @endif
        <div style="flex:1; min-width:0;">
          <p style="font-size:13px; font-weight:500; margin:0;">{{ $item->product_name }}</p>
          <p style="font-size:11px; color:#7a7a72; margin:2px 0 0;">Qty: {{ $item->quantity }} &middot; £{{ number_format($item->price, 2) }} each</p>
        </div>
        <p style="font-size:13px; font-weight:600; margin:0; flex-shrink:0;">£{{ number_format($item->subtotal, 2) }}</p>
      </div>
      @endforeach
      <div style="margin-top:1rem; padding-top:1rem; border-top:1px solid rgba(0,0,0,0.08);">
        <div class="detail-row" style="border:none; padding:4px 0;">
          <span class="detail-label">Subtotal</span>
          <span class="detail-value">£{{ number_format($order->subtotal, 2) }}</span>
        </div>
        <div class="detail-row" style="border:none; padding:4px 0;">
          <span class="detail-label">Shipping ({{ $order->shipping_method }})</span>
          <span class="detail-value" style="{{ $order->shipping_cost == 0 ? 'color:#16a34a;' : '' }}">
            {{ $order->shipping_cost == 0 ? 'Free' : '£'.number_format($order->shipping_cost, 2) }}
          </span>
        </div>
        <div style="display:flex; justify-content:space-between; font-size:15px; font-weight:700; border-top:2px solid rgba(0,0,0,0.08); padding-top:12px; margin-top:6px;">
          <span>Total Paid</span>
          <span>£{{ number_format($order->total, 2) }}</span>
        </div>
      </div>
    </div>

    {{-- Payment + Delivery --}}
    <div class="row g-3 fade-up delay-2 mb-4">
      <div class="col-12 col-md-6">
        <div class="info-card" style="margin-bottom:0; height:100%;">
          <h3 class="info-card-title">
            <i class="fas fa-credit-card" style="color:#c9a96e; margin-right:8px;"></i> Payment
          </h3>
          <div class="detail-row">
            <span class="detail-label">Status</span>
            <span class="detail-value" style="color:#047857;"><i class="fas fa-check-circle" style="margin-right:3px;"></i> Paid</span>
          </div>
          <div class="detail-row">
            <span class="detail-label">Card</span>
            <span class="detail-value">{{ $order->card_summary }}</span>
          </div>
          <div class="detail-row">
            <span class="detail-label">Amount</span>
            <span class="detail-value" style="font-weight:700;">£{{ number_format($order->total, 2) }}</span>
          </div>
          <div class="detail-row">
            <span class="detail-label">Transaction ID</span>
            <span class="detail-value" style="font-family:monospace; font-size:10px; color:#7a7a72; word-break:break-all;">{{ $order->payment_intent_id }}</span>
          </div>
        </div>
      </div>
      <div class="col-12 col-md-6">
        <div class="info-card" style="margin-bottom:0; height:100%;">
          <h3 class="info-card-title">
            <i class="fas fa-truck" style="color:#c9a96e; margin-right:8px;"></i> Delivery
          </h3>
          <div class="detail-row">
            <span class="detail-label">Name</span>
            <span class="detail-value">{{ $order->full_name }}</span>
          </div>
          <div class="detail-row">
            <span class="detail-label">Address</span>
            <span class="detail-value">{{ $order->full_address }}</span>
          </div>
          <div class="detail-row">
            <span class="detail-label">Method</span>
            <span class="detail-value">{{ $order->shipping_method }}</span>
          </div>
          @if($order->notes)
          <div class="detail-row">
            <span class="detail-label">Notes</span>
            <span class="detail-value">{{ $order->notes }}</span>
          </div>
          @endif
        </div>
      </div>
    </div>

    <div class="fade-up delay-3 d-flex gap-3 justify-content-center flex-wrap">
      <a href="/" class="btn-deru-primary">Continue Shopping <i class="fas fa-arrow-right" style="font-size:9px;"></i></a>
      <a href="{{ url('/home') }}#orders" class="btn-deru-outline">View My Orders</a>
    </div>

  </div>
</main>

<footer style="text-align:center; padding:2rem; margin-top:4rem; border-top:1px solid rgba(0,0,0,0.06);">
  <p style="font-size:12px; color:#7a7a72;">© {{ date('Y') }} DeruApparel. All rights reserved.</p>
</footer>
</body>
</html>