<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>DERU | Order {{ $order->reference }}</title>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;0,700;1,400&family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: 'Montserrat', sans-serif; background: #f1f0ec; color: #0f0f0f; min-height: 100vh; }

    /* ── Header ── */
    .site-header {
      position: sticky; top: 0; z-index: 100;
      display: flex; align-items: center; justify-content: space-between;
      padding: 16px 5vw; background: #f1f0ec;
      border-bottom: 1px solid rgba(0,0,0,0.08);
    }

    /* ── Cards ── */
    .info-card {
      background: white; border: 1px solid rgba(0,0,0,0.06);
      padding: 1.75rem 2rem; margin-bottom: 1.25rem;
    }
    .info-card-title {
      font-family: 'Cormorant Garamond', serif;
      font-size: 1.2rem; font-weight: 700;
      margin-bottom: 1.25rem; padding-bottom: 0.75rem;
      border-bottom: 1px solid rgba(0,0,0,0.06);
      display: flex; align-items: center; gap: 8px;
    }

    /* ── Detail rows ── */
    .detail-row {
      display: flex; justify-content: space-between; align-items: flex-start;
      padding: 9px 0; border-bottom: 1px solid rgba(0,0,0,0.05);
      font-size: 13px; gap: 1rem;
    }
    .detail-row:last-child { border-bottom: none; }
    .detail-label { color: #7a7a72; flex-shrink: 0; min-width: 110px; }
    .detail-value { font-weight: 500; text-align: right; }

    /* ── Order items ── */
    .order-item-row {
      display: flex; align-items: center; gap: 14px;
      padding: 12px 0; border-bottom: 1px solid rgba(0,0,0,0.05);
    }
    .order-item-row:last-child { border-bottom: none; }
    .item-img {
      width: 52px; height: 64px; object-fit: cover;
      background: #e8e5de; flex-shrink: 0;
    }

    /* ── Status badge ── */
    .status-badge {
      display: inline-flex; align-items: center; gap: 6px;
      font-size: 10px; font-weight: 700; letter-spacing: 0.12em;
      text-transform: uppercase; padding: 5px 12px; border-radius: 9999px;
    }

    /* ── Buttons ── */
    .btn-deru-outline {
      display: inline-flex; align-items: center; gap: 8px;
      background: transparent; color: #0f0f0f; padding: 10px 24px;
      font-family: 'Montserrat', sans-serif; font-size: 10px; font-weight: 600;
      letter-spacing: 0.15em; text-transform: uppercase; text-decoration: none;
      transition: all 0.25s ease; border: 1px solid rgba(0,0,0,0.2);
    }
    .btn-deru-outline:hover { background: #0f0f0f; color: white; border-color: #0f0f0f; }

    /* ── Timeline (status tracker) ── */
    .timeline { display: flex; align-items: flex-start; gap: 0; }
    .timeline-step { flex: 1; display: flex; flex-direction: column; align-items: center; position: relative; }
    .timeline-step:not(:last-child)::after {
      content: ''; position: absolute; top: 14px; left: 50%; width: 100%;
      height: 2px; background: rgba(0,0,0,0.08); z-index: 0;
    }
    .timeline-step.done:not(:last-child)::after { background: #c9a96e; }
    .timeline-dot {
      width: 28px; height: 28px; border-radius: 50%; border: 2px solid rgba(0,0,0,0.1);
      background: white; display: flex; align-items: center; justify-content: center;
      font-size: 10px; z-index: 1; transition: all 0.3s ease;
    }
    .timeline-step.done .timeline-dot { background: #c9a96e; border-color: #c9a96e; color: #0f0f0f; }
    .timeline-step.current .timeline-dot { background: #0f0f0f; border-color: #0f0f0f; color: white; }
    .timeline-label { font-size: 9px; letter-spacing: 0.1em; text-transform: uppercase; margin-top: 6px; color: rgba(0,0,0,0.3); text-align: center; }
    .timeline-step.done .timeline-label,
    .timeline-step.current .timeline-label { color: #0f0f0f; font-weight: 600; }

    @keyframes fadeUp { from { opacity:0; transform:translateY(14px); } to { opacity:1; transform:translateY(0); } }
    .fade-up  { animation: fadeUp 0.4s ease forwards; }
    .delay-1  { animation-delay: 0.08s; opacity: 0; }
    .delay-2  { animation-delay: 0.16s; opacity: 0; }
    .delay-3  { animation-delay: 0.24s; opacity: 0; }
  </style>
</head>
<body>

{{-- ── Header ── --}}
<header class="site-header">
  <a href="/" style="text-decoration:none;">
    <span style="font-family:'Cormorant Garamond',serif; font-size:1.6rem; font-weight:700; letter-spacing:0.2em; color:#0f0f0f;">DeruApparel</span>
  </a>
  <a href="{{ url('/home') }}#orders" class="btn-deru-outline">
    <i class="fas fa-arrow-left" style="font-size:9px;"></i> My Orders
  </a>
</header>

@php
  $statuses  = ['pending', 'processing', 'shipped', 'delivered'];
  $cancelled = $order->status === 'cancelled';
  $currentIdx = array_search($order->status, $statuses);
@endphp

<main class="py-5">
  <div class="container-fluid px-4 px-lg-5" style="max-width: 900px;">

    {{-- ── Page heading ── --}}
    <div class="fade-up" style="margin-bottom: 2rem;">
      <p style="font-size:11px; letter-spacing:0.25em; text-transform:uppercase; color:#7a7a72; margin-bottom:4px;">Order Detail</p>
      <div style="display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:1rem;">
        <h1 style="font-family:'Cormorant Garamond',serif; font-size:2rem; font-weight:700; line-height:1; margin:0;">
          {{ $order->reference }}
        </h1>
        @if($cancelled)
          <span class="status-badge" style="background:rgba(220,38,38,0.08); color:#dc2626;">
            <i class="fas fa-times-circle"></i> Cancelled
          </span>
        @else
          <span class="status-badge" style="background:rgba(0,0,0,0.06); color:{{ $order->status_colour }};">
            <i class="fas fa-circle" style="font-size:6px;"></i> {{ ucfirst($order->status) }}
          </span>
        @endif
      </div>
      <p style="font-size:12px; color:#7a7a72; margin-top:6px;">
        Placed {{ $order->created_at->format('d F Y \a\t H:i') }}
      </p>
    </div>

    {{-- ── Status Timeline ── --}}
    @if(!$cancelled)
    <div class="info-card fade-up delay-1">
      <h3 class="info-card-title">
        <i class="fas fa-route" style="color:#c9a96e;"></i> Order Progress
      </h3>
      <div class="timeline py-2">
        @foreach($statuses as $i => $step)
          @php
            $isDone    = $currentIdx !== false && $i < $currentIdx;
            $isCurrent = $currentIdx !== false && $i === $currentIdx;
          @endphp
          <div class="timeline-step {{ $isDone ? 'done' : ($isCurrent ? 'current' : '') }}">
            <div class="timeline-dot">
              @if($isDone)
                <i class="fas fa-check" style="font-size:9px;"></i>
              @elseif($isCurrent)
                <i class="fas fa-circle" style="font-size:7px;"></i>
              @endif
            </div>
            <span class="timeline-label">{{ ucfirst($step) }}</span>
          </div>
        @endforeach
      </div>
    </div>
    @endif

    <div class="row g-4">

      {{-- ── Left column ── --}}
      <div class="col-12 col-lg-7">

        {{-- Items --}}
        <div class="info-card fade-up delay-1">
          <h3 class="info-card-title">
            <i class="fas fa-box" style="color:#c9a96e;"></i>
            Items ({{ $order->items->count() }})
          </h3>

          @foreach($order->items as $item)
          <div class="order-item-row">
            @if($item->product_img)
              <img src="{{ $item->product_img }}" class="item-img" alt="{{ $item->product_name }}">
            @else
              <div class="item-img" style="display:flex; align-items:center; justify-content:center;">
                <i class="fas fa-image" style="color:#c8c5be; font-size:14px;"></i>
              </div>
            @endif
            <div style="flex:1; min-width:0;">
              <p style="font-size:13px; font-weight:500; margin:0;">{{ $item->product_name }}</p>
              <p style="font-size:11px; color:#7a7a72; margin:3px 0 0;">
                £{{ number_format($item->price, 2) }} &times; {{ $item->quantity }}
              </p>
            </div>
            <p style="font-size:13px; font-weight:600; margin:0; flex-shrink:0;">
              £{{ number_format($item->subtotal, 2) }}
            </p>
          </div>
          @endforeach

          {{-- Totals --}}
          <div style="margin-top:1.25rem; padding-top:1.25rem; border-top:1px solid rgba(0,0,0,0.08);">
            <div class="detail-row" style="border:none; padding:4px 0;">
              <span class="detail-label">Subtotal</span>
              <span class="detail-value">£{{ number_format($order->subtotal, 2) }}</span>
            </div>
            <div class="detail-row" style="border:none; padding:4px 0;">
              <span class="detail-label">Shipping</span>
              <span class="detail-value" style="{{ $order->shipping_cost == 0 ? 'color:#16a34a;' : '' }}">
                {{ $order->shipping_cost == 0 ? 'Free' : '£'.number_format($order->shipping_cost, 2) }}
              </span>
            </div>
            <div style="display:flex; justify-content:space-between; font-size:15px; font-weight:700;
                        border-top:2px solid rgba(0,0,0,0.08); padding-top:12px; margin-top:8px;">
              <span>Total Paid</span>
              <span>£{{ number_format($order->total, 2) }}</span>
            </div>
          </div>
        </div>

        {{-- Delivery address --}}
        <div class="info-card fade-up delay-2">
          <h3 class="info-card-title">
            <i class="fas fa-truck" style="color:#c9a96e;"></i> Delivery
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
          @if($order->phone)
          <div class="detail-row">
            <span class="detail-label">Phone</span>
            <span class="detail-value">{{ $order->phone }}</span>
          </div>
          @endif
          @if($order->notes)
          <div class="detail-row">
            <span class="detail-label">Notes</span>
            <span class="detail-value" style="font-style:italic; color:#7a7a72;">{{ $order->notes }}</span>
          </div>
          @endif
        </div>

      </div>

      {{-- ── Right column ── --}}
      <div class="col-12 col-lg-5">

        {{-- Payment --}}
        <div class="info-card fade-up delay-2" style="position:sticky; top:80px;">
          <h3 class="info-card-title">
            <i class="fas fa-credit-card" style="color:#c9a96e;"></i> Payment
          </h3>
          <div class="detail-row">
            <span class="detail-label">Status</span>
            <span class="detail-value" style="color:#047857;">
              <i class="fas fa-check-circle" style="margin-right:3px;"></i> Paid
            </span>
          </div>
          <div class="detail-row">
            <span class="detail-label">Card</span>
            <span class="detail-value">{{ $order->card_summary }}</span>
          </div>
          <div class="detail-row">
            <span class="detail-label">Amount</span>
            <span class="detail-value" style="font-weight:700; font-size:14px;">
              £{{ number_format($order->total, 2) }}
            </span>
          </div>
          <div class="detail-row">
            <span class="detail-label">Transaction ID</span>
            <span class="detail-value" style="font-family:monospace; font-size:10px; color:#7a7a72; word-break:break-all;">
              {{ $order->payment_intent_id }}
            </span>
          </div>
          <div class="detail-row">
            <span class="detail-label">Order Ref</span>
            <span class="detail-value" style="font-family:monospace; color:#c9a96e; font-weight:700;">
              {{ $order->reference }}
            </span>
          </div>

          <div style="margin-top:1.5rem; padding-top:1.25rem; border-top:1px solid rgba(0,0,0,0.06);">
            <p style="font-size:11px; color:#7a7a72; line-height:1.7;">
              <i class="fas fa-info-circle" style="color:#c9a96e; margin-right:4px;"></i>
              For any issues with your order, contact us at
              <a href="/cdn-cgi/l/email-protection#88fbfdf8f8e7fafcc8ecedfafda6ebe7e5" style="color:#0f0f0f; font-weight:500;"><span class="__cf_email__" data-cfemail="bccfc9ccccd3cec8fcd8d9cec992dfd3d1">[email&#160;protected]</span></a>
              quoting your order reference.
            </p>
          </div>
        </div>

      </div>
    </div>

  </div>
</main>

<footer style="text-align:center; padding:2rem; margin-top:3rem; border-top:1px solid rgba(0,0,0,0.06);">
  <p style="font-size:12px; color:#7a7a72;">© {{ date('Y') }} DeruApparel. All rights reserved.</p>