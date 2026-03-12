<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>DERU | {{ $product->name }}</title>
  <meta name="description" content="{{ $product->summary ?? 'Shop '.$product->name.' at DERU.' }}">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;0,700;1,400&family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    html { scroll-behavior: smooth; }
    body { font-family: 'Montserrat', sans-serif; background: #f1f0ec; color: #0f0f0f; }

    /* ── Header ── */
    .site-header {
      position: sticky; top: 0; z-index: 100;
      display: flex; align-items: center; justify-content: space-between;
      padding: 18px 5vw; background: #f1f0ec;
      border-bottom: 1px solid rgba(0,0,0,0.08);
      transition: box-shadow 0.3s ease;
    }
    .site-header.scrolled { box-shadow: 0 2px 20px rgba(0,0,0,0.08); }

    /* ── Breadcrumb ── */
    .breadcrumb-bar {
      padding: 12px 5vw; font-size: 11px; letter-spacing: 0.08em;
      color: #7a7a72; border-bottom: 1px solid rgba(0,0,0,0.05);
      background: white;
    }
    .breadcrumb-bar a { color: #7a7a72; text-decoration: none; }
    .breadcrumb-bar a:hover { color: #c9a96e; }
    .breadcrumb-bar span { margin: 0 8px; }

    /* ── Product image ── */
    .product-img-main {
      width: 100%; aspect-ratio: 3/4; object-fit: cover;
      background: #e8e5de; display: block;
    }
    .product-img-placeholder {
      width: 100%; aspect-ratio: 3/4; background: #e8e5de;
      display: flex; align-items: center; justify-content: center;
    }

    /* ── Sticky product info panel ── */
    .product-info { position: sticky; top: 90px; }

    /* ── Buttons ── */
    .btn-add-cart {
      display: flex; align-items: center; justify-content: center; gap: 10px;
      width: 100%; padding: 16px; background: black; color: white;
      font-family: 'Montserrat', sans-serif; font-size: 11px; font-weight: 700;
      letter-spacing: 0.2em; text-transform: uppercase; border: 1px solid ;
      cursor: pointer; transition: all 0.3s ease;
    }
    .btn-add-cart:hover:not(:disabled) { background: black; color:white; }
    .btn-add-cart:disabled { opacity: 0.6; cursor: not-allowed; }
    .btn-add-cart.added { background: #0f0f0f; border-color: #0f0f0f; color: white; }

    .btn-outline-dark {
      display: flex; align-items: center; justify-content: center; gap: 10px;
      width: 100%; padding: 14px; background: transparent; color: #0f0f0f;
      font-family: 'Montserrat', sans-serif; font-size: 11px; font-weight: 600;
      letter-spacing: 0.2em; text-transform: uppercase; border: 1px solid #0f0f0f;
      text-decoration: none; transition: all 0.3s ease;
    }
    .btn-outline-dark:hover { background: #0f0f0f; color: white; }

    /* ── Quantity selector ── */
    .qty-wrap {
      display: flex; align-items: center; border: 1px solid rgba(0,0,0,0.15);
      background: white; width: fit-content;
    }
    .qty-btn {
      width: 42px; height: 42px; display: flex; align-items: center; justify-content: center;
      background: none; border: none; cursor: pointer; font-size: 14px; color: #0f0f0f;
      transition: background 0.2s;
    }
    .qty-btn:hover { background: rgba(0,0,0,0.04); }
    .qty-input {
      width: 52px; text-align: center; border: none; border-left: 1px solid rgba(0,0,0,0.1);
      border-right: 1px solid rgba(0,0,0,0.1); font-family: 'Montserrat', sans-serif;
      font-size: 13px; font-weight: 600; padding: 0; height: 42px; outline: none;
      background: white;
    }

    /* ── Accordion (description/details) ── */
    .accordion-item { border-bottom: 1px solid rgba(0,0,0,0.08); }
    .accordion-btn {
      width: 100%; display: flex; align-items: center; justify-content: space-between;
      padding: 16px 0; background: none; border: none; cursor: pointer;
      font-family: 'Montserrat', sans-serif; font-size: 11px; font-weight: 600;
      letter-spacing: 0.15em; text-transform: uppercase; color: #0f0f0f;
    }
    .accordion-btn i { transition: transform 0.3s ease; }
    .accordion-btn.open i { transform: rotate(45deg); }
    .accordion-body { display: none; padding-bottom: 16px; font-size: 13px; line-height: 1.8; color: #5a5a52; }
    .accordion-body.open { display: block; }

    /* ── Related products ── */
    .related-card {
      cursor: pointer; transition: transform 0.3s ease;
    }
    .related-card:hover { transform: translateY(-4px); }
    .related-img {
      width: 100%; aspect-ratio: 3/4; object-fit: cover; background: #e8e5de; display: block;
    }

    /* ── Toast ── */
    .toast-notification {
      position: fixed; bottom: 24px; left: 50%; transform: translateX(-50%) translateY(80px);
      background: #0f0f0f; color: white; padding: 12px 24px; font-size: 12px;
      letter-spacing: 0.08em; z-index: 9999; transition: transform 0.4s ease;
      display: flex; align-items: center; gap: 10px; white-space: nowrap;
    }
    .toast-notification.show { transform: translateX(-50%) translateY(0); }

    /* ── Badge ── */
    .stock-badge {
      display: inline-flex; align-items: center; gap: 5px;
      font-size: 10px; font-weight: 600; letter-spacing: 0.1em; text-transform: uppercase;
      padding: 4px 10px;
    }

    @keyframes fadeUp { from { opacity:0; transform:translateY(16px); } to { opacity:1; transform:translateY(0); } }
    .fade-up { animation: fadeUp 0.5s ease forwards; }
    .delay-1 { animation-delay: 0.1s; opacity: 0; }

    /* ── Currency Switcher ── */
    .currency-switcher { position: relative; display: flex; align-items: center; }
    .currency-select {
      appearance: none; -webkit-appearance: none;
      background: transparent; border: 1px solid rgba(0,0,0,0.15);
      font-family: 'Montserrat', sans-serif; font-size: 11px; font-weight: 600;
      letter-spacing: 0.1em; color: #0f0f0f; padding: 5px 24px 5px 10px;
      cursor: pointer; outline: none; transition: border-color 0.2s ease;
    }
    .currency-select:hover, .currency-select:focus { border-color: #c9a96e; }
    .currency-switcher::after {
      content: '▾'; position: absolute; right: 8px; font-size: 9px;
      color: #7a7a72; pointer-events: none;
    }

    @media (max-width: 991px) {
      .product-info { position: static; }
    }
  </style>
</head>
<body>

{{-- ── Header ── --}}
<header class="site-header" id="siteHeader">
  <a href="/" style="text-decoration:none;">
    <span style="font-family:'Cormorant Garamond',serif; font-size:2rem; font-weight:700; letter-spacing:0.2em; color:#0f0f0f;">DERU</span>
  </a>

  <nav class="d-none d-lg-flex align-items-center gap-4">
    <a href="/" style="font-size:12px; letter-spacing:0.12em; text-transform:uppercase; font-weight:500; color:#0f0f0f; text-decoration:none;">Shop</a>
    @if($product->category)
      <a href="{{ url('/?category='.$product->category->slug) }}"
         style="font-size:12px; letter-spacing:0.12em; text-transform:uppercase; font-weight:500; color:#0f0f0f; text-decoration:none;">
        {{ $product->category->name }}
      </a>
    @endif
  </nav>

  <div class="d-flex align-items-center gap-3">

    {{-- Currency Switcher --}}
    <div class="currency-switcher d-none d-lg-flex">
      <select class="currency-select" id="currencySelect" onchange="setCurrency(this.value)">
        <option value="GBP">£ GBP</option>
        <option value="USD">$ USD</option>
        <option value="EUR">€ EUR</option>
        <option value="NGN">₦ NGN</option>
        <option value="GHS">₵ GHS</option>
        <option value="ZAR">R ZAR</option>
        <option value="CAD">C$ CAD</option>
        <option value="AUD">A$ AUD</option>
      </select>
    </div>

    @auth
      <a href="{{ auth()->user()->isAdmin() ? route('admin.dashboard') : url('/home') }}"
         style="font-size:12px; color:#0f0f0f; text-decoration:none; letter-spacing:0.08em;">
        <i class="fas fa-user" style="font-size:13px;"></i>
        <span class="d-none d-xl-inline ms-1">{{ auth()->user()->first_name ?? explode(' ', auth()->user()->name)[0] }}</span>
      </a>
    @else
      <a href="{{ route('login') }}" style="font-size:11px; letter-spacing:0.12em; text-transform:uppercase; color:#0f0f0f; text-decoration:none;">Login</a>
    @endauth
    @php $cartCount = array_sum(array_column(session()->get('cart', []), 'quantity')); @endphp
    <a href="{{ route('cart.index') }}" class="text-decoration-none position-relative" style="color:#0f0f0f;">
      <i class="fas fa-shopping-bag" style="font-size:16px;"></i>
      <span id="cartBadge" class="position-absolute top-0 start-100 translate-middle badge rounded-pill"
            style="background:black; font-size:9px; padding:2px 5px; {{ $cartCount === 0 ? 'display:none;' : '' }}">
        {{ $cartCount ?: '' }}
      </span>
    </a>
  </div>
</header>

{{-- ── Breadcrumb ── --}}
<div class="breadcrumb-bar">
  <a href="/">Home</a>
  <span>›</span>
  @if($product->category)
    <a href="{{ url('/?category='.$product->category->slug) }}">{{ $product->category->name }}</a>
    <span>›</span>
  @endif
  <span style="color:#0f0f0f; font-weight:500;">{{ $product->name }}</span>
</div>

{{-- ── Main Product Section ── --}}
<main class="py-4 py-lg-5">
  <div class="container-fluid px-4 px-lg-5">
    <div class="row g-4 g-lg-5">

      {{-- ── Left: Product Image ── --}}
      <div class="col-12 col-lg-6 fade-up">
        @if($product->image)
          <img src="{{ asset('storage/' . $product->image) }}"
               alt="{{ $product->name }}"
               class="product-img-main">
        @else
          <div class="product-img-placeholder">
            <i class="fas fa-image" style="font-size:4rem; color:#c8c5be;"></i>
          </div>
        @endif

        {{-- Category tag below image --}}
        @if($product->category)
          <div style="margin-top:12px;">
            <a href="{{ url('/?category='.$product->category->slug) }}"
               style="font-size:10px; letter-spacing:0.2em; text-transform:uppercase; color:#7a7a72; text-decoration:none;">
              <i class="fas fa-tag" style="font-size:9px; margin-right:4px;"></i>
              {{ $product->category->name }}
            </a>
          </div>
        @endif
      </div>

      {{-- ── Right: Product Info ── --}}
      <div class="col-12 col-lg-6 fade-up delay-1">
        <div class="product-info">

          {{-- Stock badge --}}
          @if($product->in_stock)
            <span class="stock-badge" style="background:rgba(16,185,129,0.1); color:#047857; margin-bottom:12px;">
              <i class="fas fa-circle" style="font-size:6px;"></i> In Stock
            </span>
          @else
            <span class="stock-badge" style="background:rgba(220,38,38,0.08); color:#dc2626; margin-bottom:12px;">
              <i class="fas fa-circle" style="font-size:6px;"></i> Out of Stock
            </span>
          @endif

          {{-- Name + Price --}}
          <h1 style="font-family:'Cormorant Garamond',serif; font-size:clamp(2rem,4vw,3rem); font-weight:700; line-height:1.1; margin-bottom:1rem; margin-top:8px;">
            {{ $product->name }}
          </h1>

          <p style="font-size:1.75rem; font-weight:700; color:#0f0f0f; margin-bottom:1.5rem; letter-spacing:-0.01em;"
             data-price-gbp="{{ $product->price }}">
            <span class="price-display">£{{ number_format($product->price, 2) }}</span>
          </p>

          {{-- Summary --}}
          @if($product->summary)
            <p style="font-size:13px; color:#5a5a52; line-height:1.8; margin-bottom:1.75rem;">
              {{ $product->summary }}
            </p>
          @endif

          {{-- Quantity + Add to Cart ── --}}
          <div style="margin-bottom:1.25rem;">
            <p style="font-size:10px; font-weight:600; letter-spacing:0.15em; text-transform:uppercase; margin-bottom:10px;">Quantity</p>
            <div class="qty-wrap">
              <button class="qty-btn" id="qtyMinus" onclick="changeQty(-1)">−</button>
              <input type="number" id="qtyInput" class="qty-input" value="1" min="1" max="99" readonly>
              <button class="qty-btn" id="qtyPlus" onclick="changeQty(1)">+</button>
            </div>
          </div>

          <div style="display:flex; flex-direction:column; gap:10px; margin-bottom:1.75rem;">
            <button
              id="addToCartBtn"
              class="btn-add-cart"
              {{ !$product->in_stock ? 'disabled' : '' }}
              data-product-id="{{ $product->id }}"
              data-product-name="{{ $product->name }}"
              data-product-price="{{ $product->price }}"
              data-product-img="{{ $product->image ? asset('storage/' . $product->image) : '' }}"
            >
              <i class="fas fa-shopping-bag" style="font-size:11px;"></i>
              {{ $product->in_stock ? 'Add to Bag' : 'Out of Stock' }}
            </button>

            <a href="{{ route('cart.index') }}" class="btn-outline-dark">
              <i class="fas fa-shopping-bag" style="font-size:10px;"></i>
              View Bag
            </a>
          </div>

          {{-- Trust signals --}}
          <div style="display:flex; gap:1.5rem; flex-wrap:wrap; padding:1rem 0; border-top:1px solid rgba(0,0,0,0.08); border-bottom:1px solid rgba(0,0,0,0.08); margin-bottom:1.5rem;">
            <div style="display:flex; align-items:center; gap:6px; font-size:11px; color:#7a7a72;">
              <i class="fas fa-truck" style="color:#black;"></i> Free UK delivery over £80
            </div>
            <div style="display:flex; align-items:center; gap:6px; font-size:11px; color:#7a7a72;">
              <i class="fas fa-undo" style="color:#black;"></i> Easy returns
            </div>
            <div style="display:flex; align-items:center; gap:6px; font-size:11px; color:#7a7a72;">
              <i class="fas fa-lock" style="color:#black;"></i> Secure checkout
            </div>
          </div>

          {{-- Accordions ── --}}
          <div class="accordion-item">
            <button class="accordion-btn" onclick="toggleAccordion(this)">
              Description <i class="fas fa-plus" style="font-size:10px;"></i>
            </button>
            <div class="accordion-body">
              {{ $product->description ?? $product->summary ?? 'No description available.' }}
            </div>
          </div>

          <div class="accordion-item">
            <button class="accordion-btn" onclick="toggleAccordion(this)">
              Shipping & Delivery <i class="fas fa-plus" style="font-size:10px;"></i>
            </button>
            <div class="accordion-body">
              <p style="margin-bottom:8px;">We ship all orders from our UK warehouse within 24–48 hours.</p>
              <ul style="padding-left:1.2rem; line-height:2;">
                <li>UK (DPD 1–2 days) — £5.50 or <strong>free</strong> over £80</li>
                <li>Europe (Tracked 4–6 days) — £12.50</li>
                <li>International (Tracked 4–8 days) — £12.50</li>
              </ul>
              <p style="margin-top:8px; color:#7a7a72; font-size:12px;">Orders placed before 13:00 GMT on weekdays ship the same day.</p>
            </div>
          </div>

          <div class="accordion-item">
            <button class="accordion-btn" onclick="toggleAccordion(this)">
              Returns & Exchanges <i class="fas fa-plus" style="font-size:10px;"></i>
            </button>
            <div class="accordion-body">
              Please contact us at
              <a href="{{ 'mailto:support@' . config('app.domain', 'deru.com') }}"
                 style="color:#c9a96e;">support&#64;deru.com</a>
              with your order reference.
              We cannot make changes or cancellations once an order has been placed, but we'll do our best to help.
            </div>
          </div>

        </div>
      </div>

    </div>
  </div>
</main>

{{-- ── Related Products ── --}}
@if($related->isNotEmpty())
<section style="padding:4rem 5vw; border-top:1px solid rgba(0,0,0,0.08);">
  <div style="display:flex; align-items:baseline; justify-content:space-between; margin-bottom:2rem;">
    <div>
      <p style="font-size:11px; letter-spacing:0.25em; text-transform:uppercase; color:#7a7a72; margin-bottom:4px;">You may also like</p>
      <h2 style="font-family:'Cormorant Garamond',serif; font-size:2rem; font-weight:700; line-height:1; margin:0;">Related Styles</h2>
    </div>
    <a href="{{ url('/?category='.($product->category?->slug ?? '')) }}"
       style="font-size:11px; letter-spacing:0.1em; text-transform:uppercase; color:black; text-decoration:none;">
      View All →
    </a>
  </div>

  <div class="row g-3">
    @foreach($related as $rel)
    <div class="col-6 col-md-3">
      <a href="{{ route('products.show', $rel) }}" style="text-decoration:none; color:inherit;" class="related-card d-block">
        @if($rel->image)
          <img src="{{ asset('storage/' . $rel->image) }}" alt="{{ $rel->name }}" class="related-img">
        @else
          <div class="related-img" style="display:flex; align-items:center; justify-content:center;">
            <i class="fas fa-image" style="font-size:2rem; color:#c8c5be;"></i>
          </div>
        @endif
        <div style="padding:12px 0;">
          <p style="font-size:12px; font-weight:500; margin:0; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $rel->name }}</p>
          <p style="font-size:13px; font-weight:700; margin:4px 0 0;"
             data-price-gbp="{{ $rel->price }}">
            <span class="price-display">£{{ number_format($rel->price, 2) }}</span>
          </p>
        </div>
      </a>
    </div>
    @endforeach
  </div>
</section>
@endif

{{-- ── Toast ── --}}
<div class="toast-notification" id="toast">
  <i class="fas fa-check-circle" style="color:#c9a96e;"></i>
  <span id="toastMsg">Added to bag</span>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
  const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

  // ── Sticky header shadow ──
  window.addEventListener('scroll', () => {
    document.getElementById('siteHeader').classList.toggle('scrolled', window.scrollY > 20);
  });

  // ── Quantity controls ──
  function changeQty(delta) {
    const input = document.getElementById('qtyInput');
    const newVal = Math.max(1, Math.min(99, parseInt(input.value) + delta));
    input.value = newVal;
  }

  // ── Accordion ──
  function toggleAccordion(btn) {
    const body = btn.nextElementSibling;
    const isOpen = body.classList.contains('open');
    // Close all
    document.querySelectorAll('.accordion-body').forEach(b => b.classList.remove('open'));
    document.querySelectorAll('.accordion-btn').forEach(b => b.classList.remove('open'));
    // Open this one if it was closed
    if (!isOpen) {
      body.classList.add('open');
      btn.classList.add('open');
    }
  }

  // ── Add to Cart ──
  document.getElementById('addToCartBtn').addEventListener('click', async function () {
    const qty = parseInt(document.getElementById('qtyInput').value) || 1;
    const btn = this;
    const originalHTML = btn.innerHTML;

    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin" style="font-size:11px;"></i> Adding...';

    try {
      const response = await fetch('{{ route("cart.add") }}', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': csrfToken,
          'Accept': 'application/json',
        },
        body: JSON.stringify({
          product_id:    btn.dataset.productId,
          product_name:  btn.dataset.productName,
          product_price: btn.dataset.productPrice,
          product_img:   btn.dataset.productImg,
          quantity:      qty,
        }),
      });

      const data = await response.json();

      if (data.success) {
        btn.classList.add('added');
        btn.innerHTML = '<i class="fas fa-check" style="font-size:11px;"></i> Added to Bag!';

        // Update cart badge
        const badge = document.getElementById('cartBadge');
        badge.textContent = data.cart_count;
        badge.style.display = 'inline';

        // Show toast
        document.getElementById('toastMsg').textContent = qty + ' × ' + btn.dataset.productName + ' added to bag';
        const toast = document.getElementById('toast');
        toast.classList.add('show');
        setTimeout(() => toast.classList.remove('show'), 3000);

        // Reset button after 2.5s
        setTimeout(() => {
          btn.classList.remove('added');
          btn.innerHTML = originalHTML;
          btn.disabled = false;
        }, 2500);
      }
    } catch (e) {
      btn.innerHTML = originalHTML;
      btn.disabled = false;
    }
  });

  // ── Currency Switcher (payment always processes in GBP) ──
  const CURRENCIES = {
    GBP: { symbol: '£',  rate: 1       },
    USD: { symbol: '$',  rate: 1.27    },
    EUR: { symbol: '€',  rate: 1.17    },
    NGN: { symbol: '₦',  rate: 2050    },
    GHS: { symbol: '₵',  rate: 19.5    },
    ZAR: { symbol: 'R',  rate: 23.5    },
    CAD: { symbol: 'C$', rate: 1.73    },
    AUD: { symbol: 'A$', rate: 1.95    },
  };

  function formatCurrency(amountGBP, currency) {
    const { symbol, rate } = CURRENCIES[currency];
    const decimals = ['NGN', 'GHS', 'ZAR'].includes(currency) ? 0 : 2;
    return symbol + new Intl.NumberFormat('en-GB', {
      minimumFractionDigits: decimals,
      maximumFractionDigits: decimals,
    }).format(amountGBP * rate);
  }

  function applyPrices(currency) {
    document.querySelectorAll('[data-price-gbp]').forEach(el => {
      const gbp  = parseFloat(el.dataset.priceGbp);
      const span = el.querySelector('.price-display');
      if (span) span.textContent = formatCurrency(gbp, currency);
    });
  }

  function setCurrency(currency) {
    if (!CURRENCIES[currency]) return;
    localStorage.setItem('deru_currency', currency);
    applyPrices(currency);
    document.querySelectorAll('.currency-select').forEach(s => s.value = currency);
  }

  (function initCurrency() {
    const saved = localStorage.getItem('deru_currency') || 'GBP';
    document.querySelectorAll('.currency-select').forEach(s => s.value = saved);
    if (saved !== 'GBP') applyPrices(saved);
  })();
</script>
</body>
</html>