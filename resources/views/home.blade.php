<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>DERU | My Account</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">

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

    /* ── Sidebar ── */
    .dashboard-sidebar {
      width: 260px; flex-shrink: 0;
      background: #0f0f0f; min-height: calc(100vh - 61px);
      display: flex; flex-direction: column;
      position: sticky; top: 61px; align-self: flex-start;
      height: calc(100vh - 61px);
    }
    .nav-item {
      display: flex; align-items: center; gap: 12px;
      padding: 13px 24px; color: rgba(255,255,255,0.5);
      text-decoration: none; font-size: 12px;
      letter-spacing: 0.08em; text-transform: uppercase; font-weight: 500;
      transition: all 0.2s ease; border-left: 2px solid transparent;
    }
    .nav-item:hover { color: white; background: rgba(255,255,255,0.04); }
    .nav-item.active { color: white; border-left-color: #c9a96e; background: rgba(201,169,110,0.06); }
    .nav-item i { width: 16px; text-align: center; font-size: 13px; }
    .nav-section-label {
      font-size: 9px; letter-spacing: 0.25em; text-transform: uppercase;
      color: rgba(255,255,255,0.2); padding: 20px 24px 8px; font-weight: 600;
    }

    /* ── Content ── */
    .dashboard-content {
      flex: 1; padding: 2.5rem 3rem; min-width: 0;
      overflow-y: auto;
    }

    /* ── Stat Cards ── */
    .stat-card {
      background: white; padding: 1.5rem;
      border: 1px solid rgba(0,0,0,0.06);
      transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .stat-card:hover { transform: translateY(-3px); box-shadow: 0 8px 24px rgba(0,0,0,0.07); }

    /* ── Cart Table ── */
    .deru-table { width: 100%; border-collapse: collapse; }
    .deru-table th {
      font-size: 9px; letter-spacing: 0.2em; text-transform: uppercase;
      color: #7a7a72; font-weight: 600; padding: 10px 16px;
      border-bottom: 1px solid rgba(0,0,0,0.06); text-align: left; white-space: nowrap;
    }
    .deru-table td {
      padding: 14px 16px; font-size: 13px;
      border-bottom: 1px solid rgba(0,0,0,0.04); vertical-align: middle;
    }
    .deru-table tr:last-child td { border-bottom: none; }
    .deru-table tr:hover td { background: #faf9f7; }

    /* ── Buttons ── */
    .btn-deru {
      display: inline-flex; align-items: center; gap: 8px;
      background: #c9a96e; color: #0f0f0f;
      padding: 10px 24px; font-family: 'Montserrat', sans-serif;
      font-size: 10px; font-weight: 600; letter-spacing: 0.15em; text-transform: uppercase;
      border: 1px solid #c9a96e; cursor: pointer; transition: all 0.2s ease;
      text-decoration: none;
    }
    .btn-deru:hover { background: transparent; color: #c9a96e; }
    .btn-deru-outline {
      display: inline-flex; align-items: center; gap: 8px;
      background: transparent; color: #0f0f0f;
      padding: 9px 20px; font-family: 'Montserrat', sans-serif;
      font-size: 10px; font-weight: 600; letter-spacing: 0.15em; text-transform: uppercase;
      border: 1px solid rgba(0,0,0,0.2); cursor: pointer; transition: all 0.2s ease;
      text-decoration: none;
    }
    .btn-deru-outline:hover { background: #0f0f0f; color: white; border-color: #0f0f0f; }
    .btn-danger {
      display: inline-flex; align-items: center; gap: 6px;
      background: none; border: none; cursor: pointer;
      font-size: 10px; letter-spacing: 0.1em; text-transform: uppercase;
      font-family: 'Montserrat', sans-serif; color: rgba(0,0,0,0.3);
      padding: 0; transition: color 0.2s ease;
    }
    .btn-danger:hover { color: #dc2626; }

    /* ── Qty Control ── */
    .qty-control { display: flex; align-items: center; }
    .qty-btn {
      width: 28px; height: 28px; border: 1px solid rgba(0,0,0,0.12);
      background: #f1f0ec; cursor: pointer; font-size: 14px; font-weight: 500;
      display: flex; align-items: center; justify-content: center;
      transition: background 0.15s ease; flex-shrink: 0;
    }
    .qty-btn:hover { background: #e8e5de; }
    .qty-num {
      width: 36px; height: 28px; border-top: 1px solid rgba(0,0,0,0.12);
      border-bottom: 1px solid rgba(0,0,0,0.12); display: flex;
      align-items: center; justify-content: center; font-size: 12px; font-weight: 500;
      background: white;
    }

    /* ── Order Badge ── */
    .badge-status {
      font-size: 9px; font-weight: 600; letter-spacing: 0.1em; text-transform: uppercase;
      padding: 4px 10px; border-radius: 9999px;
    }
    .badge-pending    { background: rgba(201,169,110,0.12); color: #b8903a; }
    .badge-processing { background: rgba(59,130,246,0.1);   color: #2563eb; }
    .badge-shipped    { background: rgba(16,185,129,0.1);   color: #059669; }
    .badge-delivered  { background: rgba(16,185,129,0.15);  color: #047857; }
    .badge-cancelled  { background: rgba(220,38,38,0.08);   color: #dc2626; }

    /* ── Tab panels ── */
    .tab-panel { display: none; }
    .tab-panel.active { display: block; }

    /* ── Animations ── */
    @keyframes fadeUp {
      from { opacity: 0; transform: translateY(14px); }
      to   { opacity: 1; transform: translateY(0); }
    }
    .fade-up { animation: fadeUp 0.4s ease forwards; }
    .delay-1 { animation-delay: 0.05s; opacity: 0; }
    .delay-2 { animation-delay: 0.1s;  opacity: 0; }
    .delay-3 { animation-delay: 0.15s; opacity: 0; }
    .delay-4 { animation-delay: 0.2s;  opacity: 0; }

    /* ── Mobile ── */
    @media (max-width: 991px) {
      .dashboard-sidebar { display: none; }
      .dashboard-content { padding: 1.5rem; }
    }
  </style>
</head>
<body>

{{-- ── Header ── --}}
<header class="site-header">
  <a href="/" style="text-decoration:none;">
    <span style="font-family:'Cormorant Garamond',serif; font-size:1.6rem; font-weight:700; letter-spacing:0.2em; color:#0f0f0f;">DERU</span>
  </a>

  <div style="display:flex; align-items:center; gap:1rem;">
    <span style="font-size:12px; color:#7a7a72;">
      Welcome, <strong style="color:#0f0f0f;">{{ auth()->user()->name }}</strong>
    </span>
    <a href="/" class="text-decoration-none" style="font-size:12px; letter-spacing:0.08em; text-transform:uppercase; color:#7a7a72;">
      <i class="fas fa-store" style="font-size:12px; margin-right:4px;"></i> Shop
    </a>
    <form method="POST" action="{{ route('logout') }}" style="margin:0;">
      @csrf
      <button type="submit" style="background:none; border:none; cursor:pointer; font-family:'Montserrat',sans-serif; font-size:12px; letter-spacing:0.08em; text-transform:uppercase; color:#7a7a72; padding:0; transition:color 0.2s ease;"
              onmouseover="this.style.color='#0f0f0f'"
              onmouseout="this.style.color='#7a7a72'">
        <i class="fas fa-sign-out-alt" style="font-size:12px; margin-right:4px;"></i> Sign Out
      </button>
    </form>
  </div>
</header>

@php
  $cart     = session()->get('cart', []);
  $subtotal = array_sum(array_map(fn($i) => $i['price'] * $i['quantity'], $cart));
  $shipping = $subtotal >= 100 ? 0 : ($subtotal > 0 ? 5.99 : 0);
  $total    = $subtotal + $shipping;
  $cartCount = array_sum(array_column($cart, 'quantity'));

  // Orders loaded from route via compact('orders')
@endphp

<div style="display:flex;">

  {{-- ── Sidebar ── --}}
  <aside class="dashboard-sidebar">
    <div style="padding:1.5rem 24px 1rem; border-bottom:1px solid rgba(255,255,255,0.06);">
      <div style="width:40px; height:40px; border-radius:50%; background:#c9a96e; display:flex; align-items:center; justify-content:center; margin-bottom:10px;">
        <span style="font-family:'Cormorant Garamond',serif; font-size:1.1rem; font-weight:700; color:#0f0f0f;">
          {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
        </span>
      </div>
      <p style="font-size:13px; font-weight:600; color:white; margin:0;">{{ auth()->user()->name }}</p>
      <p style="font-size:11px; color:rgba(255,255,255,0.35); margin:2px 0 0; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">{{ auth()->user()->email }}</p>
    </div>

    <nav style="flex:1; padding-top:8px;">
      <span class="nav-section-label">My Account</span>
      <a href="#" class="nav-item active" onclick="switchTab('overview', this)">
        <i class="fas fa-home"></i> Overview
      </a>
      <a href="#" class="nav-item" onclick="switchTab('cart', this)">
        <i class="fas fa-shopping-bag"></i> My Bag
        @if($cartCount > 0)
          <span style="margin-left:auto; background:#c9a96e; color:#0f0f0f; font-size:9px; font-weight:700; padding:2px 7px; border-radius:9999px;">{{ $cartCount }}</span>
        @endif
      </a>
      <a href="#" class="nav-item" onclick="switchTab('orders', this)">
        <i class="fas fa-box"></i> Order History
      </a>
      <span class="nav-section-label">Settings</span>
      <a href="#" class="nav-item" onclick="switchTab('profile', this)">
        <i class="fas fa-user"></i> Profile
      </a>
    </nav>

    <div style="padding:1.5rem 24px; border-top:1px solid rgba(255,255,255,0.06);">
      <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" style="background:none; border:none; cursor:pointer; font-family:'Montserrat',sans-serif; font-size:11px; letter-spacing:0.1em; text-transform:uppercase; color:rgba(255,255,255,0.3); padding:0; transition:color 0.2s ease; display:flex; align-items:center; gap:8px;"
                onmouseover="this.style.color='white'"
                onmouseout="this.style.color='rgba(255,255,255,0.3)'">
          <i class="fas fa-sign-out-alt"></i> Sign Out
        </button>
      </form>
    </div>
  </aside>

  {{-- ── Main Content ── --}}
  <main class="dashboard-content">

    {{-- Flash messages --}}
    @if(session('success'))
      <div style="background:rgba(201,169,110,0.1); border:1px solid rgba(201,169,110,0.3); color:#b8903a; padding:10px 16px; font-size:12px; margin-bottom:1.5rem; display:flex; align-items:center; gap:8px;">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
      </div>
    @endif

    {{-- ════════════════════════════════
         TAB: OVERVIEW
    ════════════════════════════════ --}}
    <div class="tab-panel active" id="panel-overview">

      <div class="fade-up" style="margin-bottom:2rem;">
        <p style="font-size:11px; letter-spacing:0.25em; text-transform:uppercase; color:#7a7a72; margin-bottom:4px;">Dashboard</p>
        <h1 style="font-family:'Cormorant Garamond',serif; font-size:2.2rem; font-weight:700; line-height:1;">
          Good {{ now()->hour < 12 ? 'morning' : (now()->hour < 18 ? 'afternoon' : 'evening') }},
          <em style="color:#c9a96e; font-style:italic;">{{ explode(' ', auth()->user()->name)[0] }}</em>
        </h1>
      </div>

      {{-- Stat cards --}}
      <div class="row g-3 mb-4">
        <div class="col-6 col-lg-3 fade-up delay-1">
          <div class="stat-card">
            <p style="font-size:9px; letter-spacing:0.2em; text-transform:uppercase; color:#7a7a72; margin-bottom:8px;">Items in Bag</p>
            <p style="font-family:'Cormorant Garamond',serif; font-size:2.2rem; font-weight:700; line-height:1; margin-bottom:4px;">{{ $cartCount }}</p>
            <a href="#" onclick="switchTab('cart', null)" style="font-size:10px; color:#c9a96e; text-decoration:none; letter-spacing:0.05em;">View bag →</a>
          </div>
        </div>
        <div class="col-6 col-lg-3 fade-up delay-2">
          <div class="stat-card">
            <p style="font-size:9px; letter-spacing:0.2em; text-transform:uppercase; color:#7a7a72; margin-bottom:8px;">Bag Total</p>
            <p style="font-family:'Cormorant Garamond',serif; font-size:2.2rem; font-weight:700; line-height:1; margin-bottom:4px;">
              £{{ $subtotal > 0 ? number_format($subtotal, 2) : '0.00' }}
            </p>
            @if($subtotal > 0)
              <a href="{{ route('checkout') }}" style="font-size:10px; color:#c9a96e; text-decoration:none; letter-spacing:0.05em;">Checkout →</a>
            @else
              <a href="/" style="font-size:10px; color:#c9a96e; text-decoration:none; letter-spacing:0.05em;">Start shopping →</a>
            @endif
          </div>
        </div>
        <div class="col-6 col-lg-3 fade-up delay-3">
          <div class="stat-card">
            <p style="font-size:9px; letter-spacing:0.2em; text-transform:uppercase; color:#7a7a72; margin-bottom:8px;">Total Orders</p>
            <p style="font-family:'Cormorant Garamond',serif; font-size:2.2rem; font-weight:700; line-height:1; margin-bottom:4px;">{{ count($orders) }}</p>
            <a href="#" onclick="switchTab('orders', null)" style="font-size:10px; color:#c9a96e; text-decoration:none; letter-spacing:0.05em;">View history →</a>
          </div>
        </div>
        <div class="col-6 col-lg-3 fade-up delay-4">
          <div class="stat-card">
            <p style="font-size:9px; letter-spacing:0.2em; text-transform:uppercase; color:#7a7a72; margin-bottom:8px;">Member Since</p>
            <p style="font-family:'Cormorant Garamond',serif; font-size:2.2rem; font-weight:700; line-height:1; margin-bottom:4px;">
              {{ auth()->user()->created_at->format('Y') }}
            </p>
            <span style="font-size:10px; color:#7a7a72; letter-spacing:0.05em;">{{ auth()->user()->created_at->format('M Y') }}</span>
          </div>
        </div>
      </div>

      {{-- Current bag snapshot --}}
      @if($cartCount > 0)
      <div class="fade-up delay-2" style="background:white; border:1px solid rgba(0,0,0,0.06); margin-bottom:1.5rem;">
        <div style="padding:1rem 1.5rem; border-bottom:1px solid rgba(0,0,0,0.06); display:flex; align-items:center; justify-content:space-between;">
          <h3 style="font-size:13px; font-weight:600; letter-spacing:0.05em; margin:0;">Current Bag</h3>
          <a href="{{ route('checkout') }}" class="btn-deru" style="text-decoration:none; padding:8px 20px;">
            <i class="fas fa-lock" style="font-size:9px;"></i> Checkout
          </a>
        </div>
        @foreach($cart as $id => $item)
        <div style="display:flex; align-items:center; gap:14px; padding:12px 1.5rem; border-bottom:1px solid rgba(0,0,0,0.04);">
          @if($item['img'])
            <img src="{{ $item['img'] }}" style="width:48px; height:58px; object-fit:cover; background:#e8e5de; flex-shrink:0;">
          @else
            <div style="width:48px; height:58px; background:#e8e5de; flex-shrink:0; display:flex; align-items:center; justify-content:center;">
              <i class="fas fa-image" style="color:#c8c5be; font-size:12px;"></i>
            </div>
          @endif
          <div style="flex:1; min-width:0;">
            <p style="font-size:13px; font-weight:500; margin:0;">{{ $item['name'] }}</p>
            <p style="font-size:12px; color:#7a7a72; margin:2px 0 0;">Qty: {{ $item['quantity'] }}</p>
          </div>
          <p style="font-size:13px; font-weight:600; margin:0; flex-shrink:0;">£{{ number_format($item['price'] * $item['quantity'], 2) }}</p>
        </div>
        @endforeach
        <div style="padding:12px 1.5rem; display:flex; justify-content:space-between; align-items:center;">
          <span style="font-size:12px; color:#7a7a72;">
            {{ $cartCount }} {{ Str::plural('item', $cartCount) }} · Shipping {{ $shipping == 0 ? 'Free' : '£' . number_format($shipping, 2) }}
          </span>
          <span style="font-size:14px; font-weight:700;">Total £{{ number_format($total, 2) }}</span>
        </div>
      </div>
      @else
      <div class="fade-up delay-2" style="background:white; border:1px solid rgba(0,0,0,0.06); padding:3rem; text-align:center; margin-bottom:1.5rem;">
        <i class="fas fa-shopping-bag" style="font-size:2rem; color:#d0cec8; margin-bottom:1rem; display:block;"></i>
        <p style="font-size:13px; color:#7a7a72; margin-bottom:1.5rem;">Your bag is empty.</p>
        <a href="/" class="btn-deru" style="text-decoration:none;">Browse Products</a>
      </div>
      @endif

    </div>

    {{-- ════════════════════════════════
         TAB: MY BAG
    ════════════════════════════════ --}}
    <div class="tab-panel" id="panel-cart">

      <div style="margin-bottom:2rem;">
        <p style="font-size:11px; letter-spacing:0.25em; text-transform:uppercase; color:#7a7a72; margin-bottom:4px;">Shopping</p>
        <h1 style="font-family:'Cormorant Garamond',serif; font-size:2.2rem; font-weight:700; line-height:1;">
          My Bag
          @if($cartCount > 0)
            <span style="font-size:1rem; font-weight:400; color:#7a7a72;">({{ $cartCount }} {{ Str::plural('item', $cartCount) }})</span>
          @endif
        </h1>
      </div>

      @if(empty($cart))
        <div style="background:white; border:1px solid rgba(0,0,0,0.06); padding:4rem; text-align:center;">
          <i class="fas fa-shopping-bag" style="font-size:2.5rem; color:#d0cec8; margin-bottom:1.5rem; display:block;"></i>
          <h3 style="font-family:'Cormorant Garamond',serif; font-size:1.6rem; font-weight:700; margin-bottom:0.75rem;">Your bag is empty</h3>
          <p style="font-size:13px; color:#7a7a72; margin-bottom:2rem;">Add items from the shop to get started.</p>
          <a href="/" class="btn-deru" style="text-decoration:none;">Browse Products</a>
        </div>
      @else
      <div class="row g-4">

        {{-- Cart items --}}
        <div class="col-12 col-xl-8">
          <div style="background:white; border:1px solid rgba(0,0,0,0.06);">

            <div style="padding:12px 16px; border-bottom:1px solid rgba(0,0,0,0.06); display:flex; justify-content:flex-end;">
              <form method="POST" action="{{ route('cart.clear') }}">
                @csrf
                <button type="submit" class="btn-danger" onclick="return confirm('Clear your entire bag?')">
                  <i class="fas fa-trash" style="font-size:9px;"></i> Clear All
                </button>
              </form>
            </div>

            <table class="deru-table">
              <thead>
                <tr>
                  <th>Product</th>
                  <th>Price</th>
                  <th>Quantity</th>
                  <th>Subtotal</th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
                @foreach($cart as $id => $item)
                <tr>
                  <td>
                    <div style="display:flex; align-items:center; gap:12px;">
                      @if($item['img'])
                        <img src="{{ $item['img'] }}" style="width:52px; height:64px; object-fit:cover; background:#e8e5de; flex-shrink:0;">
                      @else
                        <div style="width:52px; height:64px; background:#e8e5de; flex-shrink:0; display:flex; align-items:center; justify-content:center;">
                          <i class="fas fa-image" style="color:#c8c5be;"></i>
                        </div>
                      @endif
                      <span style="font-weight:500;">{{ $item['name'] }}</span>
                    </div>
                  </td>
                  <td>£{{ number_format($item['price'], 2) }}</td>
                  <td>
                    <div class="qty-control">
                      <form method="POST" action="{{ route('cart.update') }}" style="display:contents;">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $id }}">
                        <button name="quantity" value="{{ max(1, $item['quantity'] - 1) }}" class="qty-btn">−</button>
                        <div class="qty-num">{{ $item['quantity'] }}</div>
                        <button name="quantity" value="{{ $item['quantity'] + 1 }}" class="qty-btn">+</button>
                      </form>
                    </div>
                  </td>
                  <td style="font-weight:600;">£{{ number_format($item['price'] * $item['quantity'], 2) }}</td>
                  <td>
                    <form method="POST" action="{{ route('cart.remove') }}">
                      @csrf
                      <input type="hidden" name="product_id" value="{{ $id }}">
                      <button type="submit" class="btn-danger">
                        <i class="fas fa-times"></i>
                      </button>
                    </form>
                  </td>
                </tr>
                @endforeach
              </tbody>
            </table>

          </div>

          <div style="margin-top:1rem;">
            <a href="/" class="btn-deru-outline" style="text-decoration:none;">
              <i class="fas fa-arrow-left" style="font-size:9px;"></i> Continue Shopping
            </a>
          </div>
        </div>

        {{-- Order summary --}}
        <div class="col-12 col-xl-4">
          <div style="background:white; border:1px solid rgba(0,0,0,0.06); padding:1.5rem; position:sticky; top:90px;">
            <h3 style="font-family:'Cormorant Garamond',serif; font-size:1.4rem; font-weight:700; margin-bottom:1.5rem;">Summary</h3>

            @foreach($cart as $item)
            <div style="display:flex; justify-content:space-between; font-size:12px; margin-bottom:6px; gap:1rem;">
              <span style="color:#7a7a72; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">{{ $item['name'] }} × {{ $item['quantity'] }}</span>
              <span style="font-weight:500; flex-shrink:0;">£{{ number_format($item['price'] * $item['quantity'], 2) }}</span>
            </div>
            @endforeach

            <div style="border-top:1px solid rgba(0,0,0,0.08); margin:1.25rem 0; padding-top:1.25rem;">
              <div style="display:flex; justify-content:space-between; font-size:13px; margin-bottom:6px;">
                <span style="color:#7a7a72;">Subtotal</span>
                <span>£{{ number_format($subtotal, 2) }}</span>
              </div>
              <div style="display:flex; justify-content:space-between; font-size:13px; margin-bottom:6px;">
                <span style="color:#7a7a72;">Shipping</span>
                <span style="{{ $shipping == 0 ? 'color:#16a34a;' : '' }}">
                  {{ $shipping == 0 ? 'Free' : '£' . number_format($shipping, 2) }}
                </span>
              </div>
              @if($subtotal < 100)
                <p style="font-size:11px; color:#7a7a72; margin-top:4px;">
                  Add £{{ number_format(100 - $subtotal, 2) }} more for free delivery
                </p>
              @endif
            </div>

            <div style="display:flex; justify-content:space-between; font-size:15px; font-weight:700; margin-bottom:1.5rem;">
              <span>Total</span>
              <span>£{{ number_format($total, 2) }}</span>
            </div>

            <a href="{{ route('checkout') }}" class="btn-deru w-100" style="text-decoration:none;">
              <i class="fas fa-lock" style="font-size:9px;"></i> Proceed to Checkout
            </a>
          </div>
        </div>

      </div>
      @endif
    </div>

    {{-- ════════════════════════════════
         TAB: ORDER HISTORY
    ════════════════════════════════ --}}
    <div class="tab-panel" id="panel-orders">

      <div style="margin-bottom:2rem;">
        <p style="font-size:11px; letter-spacing:0.25em; text-transform:uppercase; color:#7a7a72; margin-bottom:4px;">Account</p>
        <h1 style="font-family:'Cormorant Garamond',serif; font-size:2.2rem; font-weight:700; line-height:1;">Order History</h1>
      </div>

      @if(empty($orders))
        <div style="background:white; border:1px solid rgba(0,0,0,0.06); padding:4rem; text-align:center;">
          <i class="fas fa-box-open" style="font-size:2.5rem; color:#d0cec8; margin-bottom:1.5rem; display:block;"></i>
          <h3 style="font-family:'Cormorant Garamond',serif; font-size:1.6rem; font-weight:700; margin-bottom:0.75rem;">No orders yet</h3>
          <p style="font-size:13px; color:#7a7a72; margin-bottom:2rem;">Your completed orders will appear here.</p>
          <a href="/" class="btn-deru" style="text-decoration:none;">Start Shopping</a>
        </div>
      @else
        {{-- When Orders module is built, loop $orders here --}}
        <div style="background:white; border:1px solid rgba(0,0,0,0.06); overflow-x:auto;">
          <table class="deru-table">
            <thead>
              <tr>
                <th>Order Ref</th>
                <th>Date</th>
                <th>Items</th>
                <th>Total</th>
                <th>Status</th>
                <th>Card</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              @foreach($orders as $order)
              <tr>
                <td style="font-family:monospace; font-size:12px; color:#c9a96e;">{{ $order->reference }}</td>
                <td style="color:#7a7a72;">{{ $order->created_at->format('d M Y') }}</td>
                <td>{{ $order->items->count() }} {{ Str::plural('item', $order->items->count()) }}</td>
                <td style="font-weight:600;">£{{ number_format($order->total, 2) }}</td>
                <td>
                  <span style="font-size:9px; font-weight:600; letter-spacing:0.1em; text-transform:uppercase; padding:4px 10px; border-radius:9999px; background:rgba(0,0,0,0.06); color:{{ $order->status_colour }};">
                    {{ ucfirst($order->status) }}
                  </span>
                </td>
                <td style="font-size:11px; color:#7a7a72;">{{ $order->card_summary }}</td>
                <td>
                  <a href="{{ route('orders.show', $order) }}" style="font-size:11px; color:#c9a96e; text-decoration:none; letter-spacing:0.05em;">View →</a>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      @endif
    </div>

    {{-- ════════════════════════════════
         TAB: PROFILE
    ════════════════════════════════ --}}
    <div class="tab-panel" id="panel-profile">

      {{-- Page heading --}}
      <div style="margin-bottom:2rem;">
        <p style="font-size:11px; letter-spacing:0.25em; text-transform:uppercase; color:#7a7a72; margin-bottom:4px;">Settings</p>
        <h1 style="font-family:'Cormorant Garamond',serif; font-size:2.2rem; font-weight:700; line-height:1;">My Profile</h1>
      </div>

      @php $u = auth()->user(); @endphp

      {{-- ── Avatar / account summary ── --}}
      <div style="background:white; border:1px solid rgba(0,0,0,0.06); padding:1.75rem 2rem; max-width:640px; margin-bottom:1.5rem; display:flex; align-items:center; gap:20px;">
        <div style="width:64px; height:64px; border-radius:50%; background:#c9a96e; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
          <span style="font-family:'Cormorant Garamond',serif; font-size:1.75rem; font-weight:700; color:#0f0f0f;">
            {{ strtoupper(substr($u->name, 0, 1)) }}
          </span>
        </div>
        <div>
          <p style="font-size:15px; font-weight:600; margin:0;">{{ $u->name }}</p>
          <p style="font-size:12px; color:#7a7a72; margin:3px 0 0;">{{ $u->email }}</p>
          <p style="font-size:11px; color:#7a7a72; margin:3px 0 0;">Member since {{ $u->created_at->format('F Y') }}</p>
        </div>
      </div>

      @php
        $profileInput  = 'width:100%; padding:11px 14px; border:1px solid rgba(0,0,0,0.15); background:white; font-family:\'Montserrat\',sans-serif; font-size:13px; color:#0f0f0f; outline:none; transition:border-color 0.2s;';
        $profileLabel  = 'display:block; font-size:10px; font-weight:600; letter-spacing:0.12em; text-transform:uppercase; margin-bottom:6px;';
        $profileSubmit = 'padding:11px 28px; background:#c9a96e; color:#0f0f0f; border:1px solid #c9a96e; font-family:\'Montserrat\',sans-serif; font-size:10px; font-weight:700; letter-spacing:0.18em; text-transform:uppercase; cursor:pointer; transition:all 0.25s;';
        $sectionHead   = 'font-family:\'Cormorant Garamond\',serif; font-size:1.3rem; font-weight:700; margin-bottom:1.25rem; padding-bottom:0.75rem; border-bottom:1px solid rgba(0,0,0,0.06);';
        $card          = 'background:white; border:1px solid rgba(0,0,0,0.06); padding:1.75rem 2rem; max-width:640px; margin-bottom:1.5rem;';
      @endphp

      {{-- ── 1. Personal Information ── --}}
      <div style="{{ $card }}">
        <h3 style="{{ $sectionHead }}">
          <i class="fas fa-user" style="color:#c9a96e; margin-right:8px; font-size:1rem;"></i> Personal Information
        </h3>

        @if(session('success_info'))
          <div style="background:rgba(201,169,110,0.1); border:1px solid rgba(201,169,110,0.25); color:#b8903a; padding:9px 14px; font-size:12px; margin-bottom:1.25rem; display:flex; align-items:center; gap:8px;">
            <i class="fas fa-check-circle"></i> {{ session('success_info') }}
          </div>
        @endif

        <form method="POST" action="{{ route('profile.info') }}">
          @csrf
          @method('PATCH')
          <div class="row g-3">
            <div class="col-6">
              <label style="{{ $profileLabel }}">First Name</label>
              <input type="text" name="first_name"
                     value="{{ old('first_name', $u->first_name ?? explode(' ', $u->name)[0]) }}"
                     style="{{ $profileInput }} {{ $errors->has('first_name') ? 'border-color:#dc2626;' : '' }}"
                     required>
              @error('first_name')<p style="font-size:11px; color:#dc2626; margin-top:4px;">{{ $message }}</p>@enderror
            </div>
            <div class="col-6">
              <label style="{{ $profileLabel }}">Last Name</label>
              <input type="text" name="last_name"
                     value="{{ old('last_name', $u->last_name ?? (explode(' ', $u->name)[1] ?? '')) }}"
                     style="{{ $profileInput }} {{ $errors->has('last_name') ? 'border-color:#dc2626;' : '' }}"
                     required>
              @error('last_name')<p style="font-size:11px; color:#dc2626; margin-top:4px;">{{ $message }}</p>@enderror
            </div>
            <div class="col-12">
              <label style="{{ $profileLabel }}">Phone <span style="font-weight:400; text-transform:none; letter-spacing:0;">(optional)</span></label>
              <input type="tel" name="phone"
                     value="{{ old('phone', $u->phone) }}"
                     placeholder="+44 7700 000000"
                     style="{{ $profileInput }}">
            </div>
            <div class="col-12">
              <button type="submit" style="{{ $profileSubmit }}">Save Changes</button>
            </div>
          </div>
        </form>
      </div>

      {{-- ── 2. Default Delivery Address ── --}}
      <div style="{{ $card }}">
        <h3 style="{{ $sectionHead }}">
          <i class="fas fa-map-marker-alt" style="color:#c9a96e; margin-right:8px; font-size:1rem;"></i> Default Delivery Address
        </h3>
        <p style="font-size:12px; color:#7a7a72; margin-bottom:1.25rem; margin-top:-0.5rem;">
          Saved here to pre-fill your checkout automatically.
        </p>

        @if(session('success_address'))
          <div style="background:rgba(201,169,110,0.1); border:1px solid rgba(201,169,110,0.25); color:#b8903a; padding:9px 14px; font-size:12px; margin-bottom:1.25rem; display:flex; align-items:center; gap:8px;">
            <i class="fas fa-check-circle"></i> {{ session('success_address') }}
          </div>
        @endif

        <form method="POST" action="{{ route('profile.address') }}">
          @csrf
          @method('PATCH')
          <div class="row g-3">
            <div class="col-12">
              <label style="{{ $profileLabel }}">Address Line 1</label>
              <input type="text" name="address_line1"
                     value="{{ old('address_line1', $u->address_line1) }}"
                     placeholder="123 Example Street"
                     style="{{ $profileInput }} {{ $errors->has('address_line1') ? 'border-color:#dc2626;' : '' }}"
                     required>
              @error('address_line1')<p style="font-size:11px; color:#dc2626; margin-top:4px;">{{ $message }}</p>@enderror
            </div>
            <div class="col-12">
              <label style="{{ $profileLabel }}">Address Line 2 <span style="font-weight:400; text-transform:none; letter-spacing:0;">(optional)</span></label>
              <input type="text" name="address_line2"
                     value="{{ old('address_line2', $u->address_line2) }}"
                     style="{{ $profileInput }}">
            </div>
            <div class="col-6">
              <label style="{{ $profileLabel }}">City</label>
              <input type="text" name="city"
                     value="{{ old('city', $u->city) }}"
                     placeholder="London"
                     style="{{ $profileInput }} {{ $errors->has('city') ? 'border-color:#dc2626;' : '' }}"
                     required>
              @error('city')<p style="font-size:11px; color:#dc2626; margin-top:4px;">{{ $message }}</p>@enderror
            </div>
            <div class="col-6">
              <label style="{{ $profileLabel }}">Postcode</label>
              <input type="text" name="postcode"
                     value="{{ old('postcode', $u->postcode) }}"
                     placeholder="SW1A 1AA"
                     style="{{ $profileInput }} {{ $errors->has('postcode') ? 'border-color:#dc2626;' : '' }}"
                     required>
              @error('postcode')<p style="font-size:11px; color:#dc2626; margin-top:4px;">{{ $message }}</p>@enderror
            </div>
            <div class="col-12">
              <label style="{{ $profileLabel }}">Country</label>
              <select name="country"
                      style="{{ $profileInput }} cursor:pointer;"
                      required>
                <option value="">Select country...</option>
                @php
                  $countries = ['GB'=>'United Kingdom','NG'=>'Nigeria','US'=>'United States',
                                'IE'=>'Ireland','CA'=>'Canada','AU'=>'Australia',
                                'GH'=>'Ghana','ZA'=>'South Africa'];
                @endphp
                @foreach($countries as $code => $label)
                  <option value="{{ $code }}" {{ old('country', $u->country ?? 'GB') === $code ? 'selected' : '' }}>
                    {{ $label }}
                  </option>
                @endforeach
              </select>
            </div>
            <div class="col-12">
              <button type="submit" style="{{ $profileSubmit }}">Save Address</button>
            </div>
          </div>
        </form>
      </div>

      {{-- ── 3. Update Email ── --}}
      <div style="{{ $card }}">
        <h3 style="{{ $sectionHead }}">
          <i class="fas fa-envelope" style="color:#c9a96e; margin-right:8px; font-size:1rem;"></i> Email Address
        </h3>

        @if(session('success_email'))
          <div style="background:rgba(201,169,110,0.1); border:1px solid rgba(201,169,110,0.25); color:#b8903a; padding:9px 14px; font-size:12px; margin-bottom:1.25rem; display:flex; align-items:center; gap:8px;">
            <i class="fas fa-check-circle"></i> {{ session('success_email') }}
          </div>
        @endif

        <form method="POST" action="{{ route('profile.email') }}">
          @csrf
          @method('PATCH')
          <div class="row g-3">
            <div class="col-12">
              <label style="{{ $profileLabel }}">New Email Address</label>
              <input type="email" name="email"
                     value="{{ old('email', $u->email) }}"
                     style="{{ $profileInput }} {{ $errors->has('email') ? 'border-color:#dc2626;' : '' }}"
                     required>
              @error('email')<p style="font-size:11px; color:#dc2626; margin-top:4px;">{{ $message }}</p>@enderror
            </div>
            <div class="col-12">
              <label style="{{ $profileLabel }}">Current Password <span style="font-weight:400; text-transform:none; letter-spacing:0;">to confirm</span></label>
              <input type="password" name="current_password"
                     placeholder="Enter your current password"
                     style="{{ $profileInput }} {{ $errors->has('current_password_email') ? 'border-color:#dc2626;' : '' }}">
              @error('current_password_email')<p style="font-size:11px; color:#dc2626; margin-top:4px;">{{ $message }}</p>@enderror
            </div>
            <div class="col-12">
              <button type="submit" style="{{ $profileSubmit }}">Update Email</button>
            </div>
          </div>
        </form>
      </div>

      {{-- ── 4. Change Password ── --}}
      <div style="{{ $card }}">
        <h3 style="{{ $sectionHead }}">
          <i class="fas fa-lock" style="color:#c9a96e; margin-right:8px; font-size:1rem;"></i> Change Password
        </h3>

        @if(session('success_password'))
          <div style="background:rgba(201,169,110,0.1); border:1px solid rgba(201,169,110,0.25); color:#b8903a; padding:9px 14px; font-size:12px; margin-bottom:1.25rem; display:flex; align-items:center; gap:8px;">
            <i class="fas fa-check-circle"></i> {{ session('success_password') }}
          </div>
        @endif

        <form method="POST" action="{{ route('profile.password') }}">
          @csrf
          @method('PATCH')
          <div class="row g-3">
            <div class="col-12">
              <label style="{{ $profileLabel }}">Current Password</label>
              <input type="password" name="current_password"
                     placeholder="Your current password"
                     style="{{ $profileInput }} {{ $errors->has('current_password_pw') ? 'border-color:#dc2626;' : '' }}">
              @error('current_password_pw')<p style="font-size:11px; color:#dc2626; margin-top:4px;">{{ $message }}</p>@enderror
            </div>
            <div class="col-12">
              <label style="{{ $profileLabel }}">New Password</label>
              <input type="password" name="password"
                     placeholder="Min 8 characters, mixed case + numbers"
                     style="{{ $profileInput }} {{ $errors->has('password') ? 'border-color:#dc2626;' : '' }}">
              @error('password')<p style="font-size:11px; color:#dc2626; margin-top:4px;">{{ $message }}</p>@enderror
            </div>
            <div class="col-12">
              <label style="{{ $profileLabel }}">Confirm New Password</label>
              <input type="password" name="password_confirmation"
                     placeholder="Repeat new password"
                     style="{{ $profileInput }}">
            </div>
            <div class="col-12">
              <button type="submit" style="{{ $profileSubmit }}">Change Password</button>
            </div>
          </div>
        </form>
      </div>

    </div>

  </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
  function switchTab(tab, clickedLink) {
    // Hide all panels
    document.querySelectorAll('.tab-panel').forEach(p => p.classList.remove('active'));
    // Deactivate all nav items
    document.querySelectorAll('.nav-item').forEach(n => n.classList.remove('active'));

    // Show selected panel
    document.getElementById('panel-' + tab).classList.add('active');

    // Activate clicked nav item (if passed)
    if (clickedLink) {
      clickedLink.classList.add('active');
    } else {
      // Find and activate matching nav item
      document.querySelectorAll('.nav-item').forEach(n => {
        if (n.getAttribute('onclick') && n.getAttribute('onclick').includes("'" + tab + "'")) {
          n.classList.add('active');
        }
      });
    }

    return false;
  }

  // Open to correct tab based on URL hash
  const hash = window.location.hash.replace('#', '');
  if (['cart', 'orders', 'profile'].includes(hash)) {
    switchTab(hash, null);
  }
</script>

</body>
</html>