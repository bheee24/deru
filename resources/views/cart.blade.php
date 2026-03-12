<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>DERU | Your Bag</title>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;0,700;1,400&family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: 'Montserrat', sans-serif; background: #f1f0ec; color: #0f0f0f; }

    .site-header {
      position: sticky; top: 0; z-index: 1000;
      display: flex; align-items: center; justify-content: space-between;
      padding: 18px 5vw; background: #f1f0ec;
      border-bottom: 1px solid rgba(0,0,0,0.08);
    }

    .btn-deru-primary {
      display: inline-flex; align-items: center; gap: 10px;
      background: black; color:white;
      padding: 14px 36px; font-family: 'Montserrat', sans-serif;
      font-size: 11px; font-weight: 600; letter-spacing: 0.2em; text-transform: uppercase;
      text-decoration: none; transition: all 0.3s ease; border: 1px solid #c9a96e;
      cursor: pointer;
    }
    .btn-deru-primary:hover { background: transparent; color: black; }
    .btn-deru-outline {
      display: inline-flex; align-items: center; gap: 10px;
      background: transparent; color: #0f0f0f;
      padding: 12px 32px; font-family: 'Montserrat', sans-serif;
      font-size: 11px; font-weight: 600; letter-spacing: 0.2em; text-transform: uppercase;
      text-decoration: none; transition: all 0.3s ease; border: 1px solid #0f0f0f;
    }
    .btn-deru-outline:hover { background: #0f0f0f; color: white; }

    .cart-item {
      background: white; padding: 1.5rem;
      border-bottom: 1px solid rgba(0,0,0,0.06);
      display: flex; gap: 1.5rem; align-items: flex-start;
      transition: background 0.2s ease;
    }
    .cart-item:hover { background: #faf9f7; }
    .cart-item:last-child { border-bottom: none; }

    .qty-input {
      width: 60px; text-align: center;
      border: 1px solid rgba(0,0,0,0.15); border-radius: 0;
      font-family: 'Montserrat', sans-serif; font-size: 13px;
      padding: 6px 8px; background: #f9f8f6;
      outline: none;
    }
    .qty-input:focus { border-color: #c9a96e; }

    .remove-btn {
      background: none; border: none; cursor: pointer;
      color: rgba(0,0,0,0.25); font-size: 12px;
      transition: color 0.2s ease; padding: 0;
    }
    .remove-btn:hover { color: #dc2626; }

    @keyframes fadeUp {
      from { opacity: 0; transform: translateY(16px); }
      to   { opacity: 1; transform: translateY(0); }
    }
    .fade-up { animation: fadeUp 0.5s ease forwards; }
  </style>
</head>
<body>

{{-- Header --}}
<header class="site-header">
  <a href="/" style="text-decoration:none;">
    <span style="font-family:'Cormorant Garamond',serif; font-size:1.8rem; font-weight:700; letter-spacing:0.2em; color:#0f0f0f;">DERU</span>
  </a>
  <div class="d-flex align-items-center gap-4">
    @auth
      <a href="{{ url('/dashboard') }}" style="color:#0f0f0f; text-decoration:none; font-size:12px; letter-spacing:0.1em; text-transform:uppercase;">Account</a>
    @else
      <a href="{{ route('login') }}" style="color:#0f0f0f; text-decoration:none; font-size:12px; letter-spacing:0.1em; text-transform:uppercase;">Login</a>
    @endauth
    <a href="{{ route('cart.index') }}" class="position-relative" style="color:#0f0f0f; text-decoration:none;">
      <i class="fas fa-shopping-bag" style="font-size:15px;"></i>
      @php $cartCount = array_sum(array_column(session()->get('cart', []), 'quantity')); @endphp
      @if($cartCount > 0)
        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill"
              style="background:black; font-size:9px; padding:2px 5px;">{{ $cartCount }}</span>
      @endif
    </a>
  </div>
</header>

<main style="min-height: 80vh;">
  <div class="container-fluid px-4 px-lg-5 py-5">

    {{-- Page heading --}}
    <div class="mb-5 fade-up">
      <p style="font-size:11px; letter-spacing:0.3em; text-transform:uppercase; color:#7a7a72; margin-bottom:0.5rem;">Your</p>
      <h1 style="font-family:'Cormorant Garamond',serif; font-size:clamp(2.5rem,5vw,4rem); font-weight:700; line-height:1; margin:0;">
        Shopping Bag
        @if($cartCount > 0)
          <span style="font-size:1.2rem; color:#7a7a72; font-weight:400;">({{ $cartCount }} {{ Str::plural('item', $cartCount) }})</span>
        @endif
      </h1>
    </div>

    {{-- Flash message --}}
    @if(session('success'))
      <div style="background:black; border:1px solid rgba(201,169,110,0.3); color:red; padding:12px 16px; font-size:12px; letter-spacing:0.04em; margin-bottom:1.5rem; display:flex; align-items:center; gap:10px;">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
      </div>
    @endif

    @if(empty($cart))

      {{-- ── Empty Cart ── --}}
      <div class="text-center py-5 fade-up">
        <i class="fas fa-shopping-bag" style="font-size:3rem; color:#d0cec8; margin-bottom:1.5rem; display:block;"></i>
        <h2 style="font-family:'Cormorant Garamond',serif; font-size:2rem; font-weight:700; margin-bottom:1rem;">Your bag is empty</h2>
        <p style="font-size:13px; color:#7a7a72; margin-bottom:2rem;">Looks like you haven't added anything yet.</p>
        <a href="/" class="btn-deru-primary" style="text-decoration:none;">Continue Shopping</a>
      </div>

    @else

      <div class="row g-4">

        {{-- ── Cart Items ── --}}
        <div class="col-12 col-lg-8 fade-up">
          <div style="background:white; border:1px solid rgba(0,0,0,0.06);">

            {{-- Cart header --}}
            <div style="padding:1rem 1.5rem; border-bottom:1px solid rgba(0,0,0,0.06); display:flex; justify-content:flex-end;">
              <form method="POST" action="{{ route('cart.clear') }}">
                @csrf
                <button type="submit" style="background:none; border:none; font-size:11px; letter-spacing:0.1em; text-transform:uppercase; color:#7a7a72; cursor:pointer; transition:color 0.2s ease;"
                        onmouseover="this.style.color='black'"
                        onmouseout="this.style.color='#7a7a72'"
                        onclick="return confirm('Clear your entire bag?')">
                  <i class="fas fa-trash" style="font-size:9px; margin-right:4px;"></i> Clear Bag
                </button>
              </form>
            </div>

            {{-- Items --}}
            @foreach($cart as $id => $item)
            <div class="cart-item">

              {{-- Product Image --}}
              <div style="flex-shrink:0;">
                @if($item['img'])
                  <img src="{{ $item['img'] }}" alt="{{ $item['name'] }}"
                       style="width:90px; height:110px; object-fit:cover; background:#e8e5de;">
                @else
                  <div style="width:90px; height:110px; background:#e8e5de; display:flex; align-items:center; justify-content:center;">
                    <i class="fas fa-image" style="color:#c8c5be; font-size:1.2rem;"></i>
                  </div>
                @endif
              </div>

              {{-- Product Info --}}
              <div style="flex:1; min-width:0;">
                <h4 style="font-size:14px; font-weight:600; letter-spacing:0.03em; margin-bottom:4px;">{{ $item['name'] }}</h4>
                <p style="font-size:13px; font-weight:600; color:black; margin-bottom:1rem;">£{{ number_format($item['price'], 2) }}</p>

                {{-- Quantity + Remove --}}
                <div style="display:flex; align-items:center; gap:1rem; flex-wrap:wrap;">

                  {{-- Quantity form --}}
                  <form method="POST" action="{{ route('cart.update') }}" style="display:flex; align-items:center; gap:0;">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $id }}">
                    <button type="submit" name="quantity" value="{{ max(1, $item['quantity'] - 1) }}"
                            style="width:32px; height:32px; background:#f1f0ec; border:1px solid rgba(0,0,0,0.1); cursor:pointer; font-size:14px; display:flex; align-items:center; justify-content:center; transition:background 0.2s ease; border-radius:0;"
                            onmouseover="this.style.background='#e8e5de'"
                            onmouseout="this.style.background='#f1f0ec'">−</button>
                    <span style="width:44px; height:32px; border-top:1px solid rgba(0,0,0,0.1); border-bottom:1px solid rgba(0,0,0,0.1); display:flex; align-items:center; justify-content:center; font-size:13px; font-weight:500; background:white;">{{ $item['quantity'] }}</span>
                    <button type="submit" name="quantity" value="{{ $item['quantity'] + 1 }}"
                            style="width:32px; height:32px; background:#f1f0ec; border:1px solid rgba(0,0,0,0.1); cursor:pointer; font-size:14px; display:flex; align-items:center; justify-content:center; transition:background 0.2s ease; border-radius:0;"
                            onmouseover="this.style.background='#e8e5de'"
                            onmouseout="this.style.background='#f1f0ec'">+</button>
                  </form>

                  {{-- Remove --}}
                  <form method="POST" action="{{ route('cart.remove') }}">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $id }}">
                    <button type="submit" class="remove-btn">
                      <i class="fas fa-times" style="margin-right:4px;"></i> Remove
                    </button>
                  </form>

                </div>
              </div>

              {{-- Item subtotal --}}
              <div style="flex-shrink:0; text-align:right;">
                <p style="font-size:14px; font-weight:700; margin:0;">£{{ number_format($item['price'] * $item['quantity'], 2) }}</p>
              </div>

            </div>
            @endforeach
          </div>

          {{-- Continue shopping --}}
          <div style="margin-top:1.5rem;">
            <a href="/" class="btn-deru-outline" style="text-decoration:none;">
              <i class="fas fa-arrow-left" style="font-size:10px;"></i> Continue Shopping
            </a>
          </div>
        </div>

        {{-- ── Order Summary ── --}}
        <div class="col-12 col-lg-4 fade-up" style="animation-delay:0.15s;">
          <div style="background:white; border:1px solid rgba(0,0,0,0.06); padding:2rem; position:sticky; top:100px;">

            <h3 style="font-family:'Cormorant Garamond',serif; font-size:1.5rem; font-weight:700; margin-bottom:1.5rem;">Order Summary</h3>

            {{-- Line items --}}
            @foreach($cart as $item)
            <div style="display:flex; justify-content:space-between; font-size:12px; margin-bottom:8px; gap:1rem;">
              <span style="color:#7a7a72; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">{{ $item['name'] }} × {{ $item['quantity'] }}</span>
              <span style="font-weight:500; flex-shrink:0;">£{{ number_format($item['price'] * $item['quantity'], 2) }}</span>
            </div>
            @endforeach

            <div style="border-top:1px solid rgba(0,0,0,0.08); margin:1.5rem 0; padding-top:1.5rem;">
              <div style="display:flex; justify-content:space-between; font-size:13px; margin-bottom:8px;">
                <span style="color:#7a7a72;">Subtotal</span>
                <span style="font-weight:500;">£{{ number_format($total, 2) }}</span>
              </div>
              <div style="display:flex; justify-content:space-between; font-size:13px; margin-bottom:8px;">
                <span style="color:#7a7a72;">Shipping</span>
                <span style="color:#16a34a; font-weight:500;">{{ $total >= 100 ? 'Free' : '£' . number_format(5.99, 2) }}</span>
              </div>
              @if($total < 100)
              <p style="font-size:11px; color:#7a7a72; margin-top:6px; margin-bottom:0;">
                Add £{{ number_format(100 - $total, 2) }} more for free delivery
              </p>
              @endif
            </div>

            <div style="display:flex; justify-content:space-between; font-size:15px; font-weight:700; margin-bottom:2rem;">
              <span>Total</span>
              <span>£{{ $total >= 100 ? number_format($total, 2) : number_format($total + 5.99, 2) }}</span>
            </div>

            {{-- Checkout button --}}
            @auth
              <a href="{{ route('checkout') }}" class="btn-deru-primary w-100 justify-content-center" style="text-decoration:none;">
                Proceed to Checkout <i class="fas fa-arrow-right" style="font-size:10px;"></i>
              </a>
            @else
              {{-- Guest: prompt login before checkout --}}
              <div style="margin-bottom:1rem;">
                <a href="{{ route('login') }}" class="btn-deru-primary w-100 justify-content-center" style="text-decoration:none;">
                  Login to Checkout <i class="fas fa-arrow-right" style="font-size:10px;"></i>
                </a>
              </div>
              <p style="font-size:11px; text-align:center; color:#7a7a72; margin-bottom:0.75rem;">or</p>
              <a href="{{ route('register') }}" class="btn-deru-outline w-100 justify-content-center" style="text-decoration:none;">
                Create an Account
              </a>
              <p style="font-size:11px; color:#7a7a72; text-align:center; margin-top:1rem; margin-bottom:0;">
                Your bag will be saved when you log in.
              </p>
            @endauth

          </div>
        </div>

      </div>
    @endif

  </div>
</main>

<footer style="background:#0f0f0f; color:white; padding:2rem 5vw; margin-top:4rem;">
  <div style="display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:1rem;">
    <span style="font-family:'Cormorant Garamond',serif; font-size:1.4rem; font-weight:700; letter-spacing:0.2em;">DERU</span>
    <p style="font-size:12px; color:rgba(255,255,255,0.3); margin:0;">© {{ date('Y') }} DERU. All rights reserved.</p>
  </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>