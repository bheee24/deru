<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>DERU | Checkout</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;0,700;1,400&family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <!-- Stripe.js -->
  <script src="https://js.stripe.com/v3/"></script>

  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: 'Montserrat', sans-serif; background: #f1f0ec; color: #0f0f0f; }

    .site-header {
      position: sticky; top: 0; z-index: 100;
      display: flex; align-items: center; justify-content: space-between;
      padding: 18px 5vw; background: #f1f0ec;
      border-bottom: 1px solid rgba(0,0,0,0.08);
    }

    /* Steps */
    .step-indicator { display: flex; align-items: center; gap: 0; }
    .step { display: flex; align-items: center; gap: 8px; font-size: 11px; letter-spacing: 0.1em; text-transform: uppercase; color: rgba(0,0,0,0.3); font-weight: 500; }
    .step.active { color: #0f0f0f; }
    .step.done   { color: #c9a96e; }
    .step-num { width: 24px; height: 24px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 10px; font-weight: 700; background: rgba(0,0,0,0.08); color: rgba(0,0,0,0.3); flex-shrink: 0; }
    .step.active .step-num { background: #0f0f0f; color: white; }
    .step.done   .step-num { background: #c9a96e; color: #0f0f0f; }
    .step-divider { width: 32px; height: 1px; background: rgba(0,0,0,0.1); margin: 0 8px; }

    /* Form */
    .checkout-label { display: block; font-size: 10px; font-weight: 600; letter-spacing: 0.12em; text-transform: uppercase; color: #0f0f0f; margin-bottom: 6px; }
    .checkout-input { width: 100%; padding: 12px 14px; border: 1px solid rgba(0,0,0,0.15); background: white; font-family: 'Montserrat', sans-serif; font-size: 13px; color: #0f0f0f; outline: none; transition: border-color 0.2s ease; border-radius: 0; }
    .checkout-input:focus { border-color: #c9a96e; }
    .checkout-input.is-invalid { border-color: #dc2626; }

    /* Stripe Element container */
    .stripe-element-wrap {
      padding: 12px 14px;
      border: 1px solid rgba(0,0,0,0.15);
      background: white;
      transition: border-color 0.2s ease;
      min-height: 46px;
      display: flex;
      align-items: center;
    }
    .stripe-element-wrap > div { width: 100%; }
    .stripe-element-wrap.focused { border-color: #c9a96e; }
    .stripe-element-wrap.error   { border-color: #dc2626; }

    /* Buttons */
    .btn-deru-primary {
      display: inline-flex; align-items: center; justify-content: center; gap: 10px;
      background: #c9a96e; color: #0f0f0f; padding: 14px 36px;
      font-family: 'Montserrat', sans-serif; font-size: 11px; font-weight: 600;
      letter-spacing: 0.2em; text-transform: uppercase; text-decoration: none;
      transition: all 0.3s ease; border: 1px solid #c9a96e; cursor: pointer; width: 100%;
    }
    .btn-deru-primary:hover:not(:disabled) { background: transparent; color: #c9a96e; }
    .btn-deru-primary:disabled { opacity: 0.6; cursor: not-allowed; }
    .btn-deru-outline {
      display: inline-flex; align-items: center; justify-content: center; gap: 10px;
      background: transparent; color: #0f0f0f; padding: 12px 32px;
      font-family: 'Montserrat', sans-serif; font-size: 11px; font-weight: 600;
      letter-spacing: 0.2em; text-transform: uppercase; text-decoration: none;
      transition: all 0.3s ease; border: 1px solid #0f0f0f;
    }
    .btn-deru-outline:hover { background: #0f0f0f; color: white; }

    /* Card */
    .checkout-card { background: white; border: 1px solid rgba(0,0,0,0.06); padding: 2rem; margin-bottom: 1.5rem; }
    .checkout-card-title { font-family: 'Cormorant Garamond', serif; font-size: 1.4rem; font-weight: 700; margin-bottom: 1.5rem; padding-bottom: 1rem; border-bottom: 1px solid rgba(0,0,0,0.06); }

    /* Shipping radio */
    .shipping-option {
      display: flex; align-items: center; justify-content: space-between;
      padding: 14px 16px; border: 1px solid rgba(0,0,0,0.15); cursor: pointer;
      transition: border-color 0.2s ease;
    }
    .shipping-option.selected { border-color: #c9a96e; }

    /* Order panel */
    .order-panel { background: white; border: 1px solid rgba(0,0,0,0.06); }
    .order-item { display: flex; gap: 12px; align-items: center; padding: 12px 0; border-bottom: 1px solid rgba(0,0,0,0.05); }
    .order-item:last-child { border-bottom: none; }
    .order-item-img { width: 56px; height: 68px; object-fit: cover; background: #e8e5de; flex-shrink: 0; }

    /* Payment error */
    .payment-error {
      background: rgba(220,38,38,0.06); border: 1px solid rgba(220,38,38,0.2);
      color: #dc2626; padding: 10px 14px; font-size: 12px;
      display: none; margin-top: 12px; align-items: center; gap: 8px;
    }
    .payment-error.visible { display: flex; }

    @keyframes fadeUp { from { opacity:0; transform:translateY(16px); } to { opacity:1; transform:translateY(0); } }
    .fade-up { animation: fadeUp 0.5s ease forwards; }
    .delay-1 { animation-delay: 0.1s; opacity: 0; }
    .delay-2 { animation-delay: 0.2s; opacity: 0; }

    @media (max-width: 991px) { .order-panel { position: static !important; } }
  </style>
</head>
<body>

<header class="site-header">
  <a href="/" style="text-decoration:none;">
    <span style="font-family:'Cormorant Garamond',serif; font-size:1.8rem; font-weight:700; letter-spacing:0.2em; color:#0f0f0f;">DERU</span>
  </a>
  <div class="step-indicator d-none d-md-flex">
    <div class="step done">
      <div class="step-num"><i class="fas fa-check" style="font-size:8px;"></i></div>
      <span>Bag</span>
    </div>
    <div class="step-divider"></div>
    <div class="step active">
      <div class="step-num">2</div>
      <span>Details</span>
    </div>
    <div class="step-divider"></div>
    <div class="step active">
      <div class="step-num">3</div>
      <span>Payment</span>
    </div>
  </div>
  <a href="{{ route('cart.index') }}" class="btn-deru-outline" style="font-size:10px; padding:8px 20px; width:auto;">
    <i class="fas fa-arrow-left" style="font-size:9px;"></i> Back to Bag
  </a>
</header>

@php
  $cart     = session()->get('cart', []);
  $subtotal = $subtotal ?? 0;
  $shipping = $shipping ?? 0;
  $total    = $total    ?? 0;
@endphp

<main class="py-5">
  <div class="container-fluid px-4 px-lg-5">

    @if($errors->has('payment'))
      <div style="background:rgba(220,38,38,0.06); border:1px solid rgba(220,38,38,0.2); color:#dc2626; padding:12px 16px; font-size:12px; margin-bottom:1.5rem; display:flex; align-items:center; gap:8px;">
        <i class="fas fa-exclamation-circle"></i> {{ $errors->first('payment') }}
      </div>
    @endif

    <div class="row g-5">

      {{-- ── Left: Form ── --}}
      <div class="col-12 col-lg-7 fade-up delay-1">

        {{-- Hidden form submitted after Stripe confirms --}}
        <form method="POST" action="{{ route('checkout.store') }}" id="checkoutForm">
          @csrf
          <input type="hidden" name="payment_intent_id" id="paymentIntentId">

          {{-- Contact --}}
          <div class="checkout-card">
            <h3 class="checkout-card-title">Contact Information</h3>
            <div class="row g-3">
              <div class="col-12">
                <label class="checkout-label">Email Address</label>
                <input type="email" name="email" class="checkout-input @error('email') is-invalid @enderror"
                       value="{{ old('email', auth()->user()->email) }}" required>
                @error('email')<p style="font-size:11px;color:#dc2626;margin-top:4px;">{{ $message }}</p>@enderror
              </div>
              <div class="col-12">
                <label class="checkout-label">Phone <span style="font-weight:400;text-transform:none;letter-spacing:0;">(optional)</span></label>
                <input type="tel" name="phone" class="checkout-input" value="{{ old('phone') }}" placeholder="+44 7700 000000">
              </div>
            </div>
          </div>

          {{-- Delivery --}}
          <div class="checkout-card">
            <h3 class="checkout-card-title">Delivery Address</h3>
            <div class="row g-3">
              <div class="col-6">
                <label class="checkout-label">First Name</label>
                <input type="text" name="first_name" class="checkout-input @error('first_name') is-invalid @enderror"
                       value="{{ old('first_name', explode(' ', auth()->user()->name)[0] ?? '') }}" required>
                @error('first_name')<p style="font-size:11px;color:#dc2626;margin-top:4px;">{{ $message }}</p>@enderror
              </div>
              <div class="col-6">
                <label class="checkout-label">Last Name</label>
                <input type="text" name="last_name" class="checkout-input @error('last_name') is-invalid @enderror"
                       value="{{ old('last_name', explode(' ', auth()->user()->name)[1] ?? '') }}" required>
                @error('last_name')<p style="font-size:11px;color:#dc2626;margin-top:4px;">{{ $message }}</p>@enderror
              </div>
              <div class="col-12">
                <label class="checkout-label">Address Line 1</label>
                <input type="text" name="address_line1" class="checkout-input @error('address_line1') is-invalid @enderror"
                       value="{{ old('address_line1') }}" placeholder="123 Example Street" required>
                @error('address_line1')<p style="font-size:11px;color:#dc2626;margin-top:4px;">{{ $message }}</p>@enderror
              </div>
              <div class="col-12">
                <label class="checkout-label">Address Line 2 <span style="font-weight:400;text-transform:none;letter-spacing:0;">(optional)</span></label>
                <input type="text" name="address_line2" class="checkout-input" value="{{ old('address_line2') }}">
              </div>
              <div class="col-6">
                <label class="checkout-label">City</label>
                <input type="text" name="city" class="checkout-input @error('city') is-invalid @enderror"
                       value="{{ old('city') }}" placeholder="London" required>
                @error('city')<p style="font-size:11px;color:#dc2626;margin-top:4px;">{{ $message }}</p>@enderror
              </div>
              <div class="col-6">
                <label class="checkout-label">Postcode</label>
                <input type="text" name="postcode" class="checkout-input @error('postcode') is-invalid @enderror"
                       value="{{ old('postcode') }}" placeholder="SW1A 1AA" required>
                @error('postcode')<p style="font-size:11px;color:#dc2626;margin-top:4px;">{{ $message }}</p>@enderror
              </div>
              <div class="col-12">
                <label class="checkout-label">Country</label>
                <select name="country" class="checkout-input" required>
                  <option value="">Select country...</option>
                  <option value="GB" selected>United Kingdom</option>
                  <option value="NG">Nigeria</option>
                  <option value="US">United States</option>
                  <option value="IE">Ireland</option>
                  <option value="CA">Canada</option>
                  <option value="AU">Australia</option>
                  <option value="GH">Ghana</option>
                  <option value="ZA">South Africa</option>
                </select>
              </div>
            </div>
          </div>

          {{-- Shipping --}}
          <div class="checkout-card">
            <h3 class="checkout-card-title">Shipping Method</h3>

            {{-- Free shipping notice --}}
            @if($subtotal >= 80)
              <div style="background:rgba(201,169,110,0.1); border:1px solid rgba(201,169,110,0.25); padding:10px 14px; margin-bottom:1.25rem; display:flex; align-items:center; gap:8px;">
                <i class="fas fa-check-circle" style="color:#c9a96e; font-size:13px;"></i>
                <span style="font-size:12px; font-weight:500;">Your order qualifies for <strong>free UK delivery!</strong></span>
              </div>
            @else
              <div style="background:#f9f8f6; border:1px solid rgba(0,0,0,0.06); padding:10px 14px; margin-bottom:1.25rem;">
                <p style="font-size:11px; color:#7a7a72; margin-bottom:5px;">Spend <strong style="color:#0f0f0f;">£{{ number_format(80 - $subtotal, 2) }}</strong> more to unlock free UK delivery</p>
                <div style="background:#e8e5de; height:3px; border-radius:2px;">
                  <div style="background:#c9a96e; height:100%; width:{{ min(($subtotal/80)*100, 100) }}%; border-radius:2px; transition:width 0.3s ease;"></div>
                </div>
              </div>
            @endif

            {{-- UK options (shown when GB selected) --}}
            <div id="shipping-uk">
              <p style="font-size:9px; letter-spacing:0.2em; text-transform:uppercase; color:#7a7a72; margin-bottom:10px; font-weight:600;">UK Delivery</p>
              <div style="display:flex; flex-direction:column; gap:10px;">

                <label class="shipping-option selected" id="label-uk-dpd">
                  <div style="display:flex; align-items:center; gap:12px;">
                    <input type="radio" name="shipping_method" value="uk_dpd" checked
                           style="accent-color:#c9a96e;" onchange="handleShippingChange(this)">
                    <div>
                      <p style="font-size:12px; font-weight:600; margin:0;">DPD Delivery</p>
                      <p style="font-size:11px; color:#7a7a72; margin:2px 0 0;">1–2 business days · Ships within 24–48hrs</p>
                    </div>
                  </div>
                  <span style="font-size:13px; font-weight:600; white-space:nowrap;" id="uk-dpd-price">
                    {{ $subtotal >= 80 ? 'Free' : '£5.50' }}
                  </span>
                </label>

              </div>
            </div>

            {{-- International options (shown for non-UK countries) --}}
            <div id="shipping-international" style="display:none;">
              <p style="font-size:9px; letter-spacing:0.2em; text-transform:uppercase; color:#7a7a72; margin-bottom:10px; font-weight:600;">International Delivery</p>
              <div style="display:flex; flex-direction:column; gap:10px;">

                <label class="shipping-option selected" id="label-intl-europe">
                  <div style="display:flex; align-items:center; gap:12px;">
                    <input type="radio" name="shipping_method" value="intl_europe"
                           style="accent-color:#c9a96e;" onchange="handleShippingChange(this)">
                    <div>
                      <p style="font-size:12px; font-weight:600; margin:0;">European Tracked</p>
                      <p style="font-size:11px; color:#7a7a72; margin:2px 0 0;">4–6 business days · Tracked</p>
                    </div>
                  </div>
                  <span style="font-size:13px; font-weight:600;">£12.50</span>
                </label>

                <label class="shipping-option" id="label-intl-world">
                  <div style="display:flex; align-items:center; gap:12px;">
                    <input type="radio" name="shipping_method" value="intl_world"
                           style="accent-color:#c9a96e;" onchange="handleShippingChange(this)">
                    <div>
                      <p style="font-size:12px; font-weight:600; margin:0;">International Tracked</p>
                      <p style="font-size:11px; color:#7a7a72; margin:2px 0 0;">4–8 business days · USA, Australia & Rest of World</p>
                    </div>
                  </div>
                  <span style="font-size:13px; font-weight:600;">£12.50</span>
                </label>

              </div>
            </div>

            {{-- Shipping policy notes --}}
            <div style="margin-top:1.25rem; padding:12px 14px; background:#f9f8f6; border:1px solid rgba(0,0,0,0.06);">
              <p style="font-size:10px; font-weight:600; letter-spacing:0.1em; text-transform:uppercase; margin-bottom:8px; color:#0f0f0f;">Shipping Information</p>
              <ul style="font-size:11px; color:#7a7a72; line-height:1.8; margin:0; padding-left:1rem;">
                <li>Orders placed before 13:00 GMT on weekdays ship the same day</li>
                <li>Weekend orders ship the following Monday</li>
                <li>All orders ship from our UK warehouse within 24–48 hours</li>
                <li>We are not responsible for import or customs fees on international orders</li>
                <li>Orders cannot be changed or cancelled once placed</li>
              </ul>
            </div>
          </div>

          {{-- Notes --}}
          <div class="checkout-card">
            <h3 class="checkout-card-title">Order Notes <span style="font-size:1rem; font-weight:400; color:#7a7a72;">(optional)</span></h3>
            <textarea name="notes" rows="3" class="checkout-input" style="resize:vertical;"
                      placeholder="Any special instructions...">{{ old('notes') }}</textarea>
          </div>

          {{-- ── Stripe Card Element ── --}}
          <div class="checkout-card">
            <h3 class="checkout-card-title">
              <i class="fab fa-stripe" style="color:#635bff; margin-right:8px;"></i>
              Payment Details
            </h3>
            <p style="font-size:12px; color:#7a7a72; margin-bottom:1.5rem;">
              Your card details are encrypted and handled securely by Stripe. We never store your card number.
            </p>

            <div class="row g-3">
              <div class="col-12">
                <label class="checkout-label">Card Number</label>
                <div class="stripe-element-wrap" id="wrap-cardNumber">
                  <div id="cardNumber"></div>
                </div>
              </div>
              <div class="col-6">
                <label class="checkout-label">Expiry Date</label>
                <div class="stripe-element-wrap" id="wrap-cardExpiry">
                  <div id="cardExpiry"></div>
                </div>
              </div>
              <div class="col-6">
                <label class="checkout-label">CVC</label>
                <div class="stripe-element-wrap" id="wrap-cardCvc">
                  <div id="cardCvc"></div>
                </div>
              </div>
            </div>

            <div class="payment-error" id="paymentError">
              <i class="fas fa-exclamation-circle"></i>
              <span id="paymentErrorMsg"></span>
            </div>

            <div style="margin-top:1.5rem;">
              <button type="submit" class="btn-deru-primary" id="payBtn">
                <i class="fas fa-lock" style="font-size:10px;"></i>
                Pay £<span id="payTotal">{{ number_format($total, 2) }}</span>
              </button>
            </div>

            <div style="display:flex; align-items:center; justify-content:center; gap:1.5rem; margin-top:1rem; flex-wrap:wrap;">
              <div style="display:flex; align-items:center; gap:5px; font-size:11px; color:#7a7a72;">
                <i class="fas fa-lock" style="color:#c9a96e; font-size:11px;"></i> SSL Encrypted
              </div>
              <div style="display:flex; align-items:center; gap:5px; font-size:11px; color:#7a7a72;">
                <i class="fab fa-stripe" style="color:#635bff; font-size:14px;"></i> Powered by Stripe
              </div>
              @foreach(['fa-cc-visa', 'fa-cc-mastercard', 'fa-cc-amex'] as $card)
                <i class="fab {{ $card }}" style="font-size:1.4rem; color:rgba(0,0,0,0.2);"></i>
              @endforeach
            </div>
          </div>

        </form>
      </div>

      {{-- ── Right: Order Summary ── --}}
      <div class="col-12 col-lg-5 fade-up delay-2">
        <div class="order-panel p-4" style="position:sticky; top:90px;">

          <h3 style="font-family:'Cormorant Garamond',serif; font-size:1.4rem; font-weight:700; margin-bottom:1.5rem; padding-bottom:1rem; border-bottom:1px solid rgba(0,0,0,0.06);">
            Order Summary
          </h3>

          @foreach($cart as $id => $item)
          <div class="order-item">
            @if($item['img'])
              <img src="{{ $item['img'] }}" alt="{{ $item['name'] }}" class="order-item-img">
            @else
              <div class="order-item-img" style="display:flex;align-items:center;justify-content:center;">
                <i class="fas fa-image" style="color:#c8c5be;"></i>
              </div>
            @endif
            <div style="flex:1; min-width:0;">
              <p style="font-size:13px; font-weight:500; margin:0; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $item['name'] }}</p>
              <p style="font-size:11px; color:#7a7a72; margin:2px 0 0;">Qty: {{ $item['quantity'] }}</p>
            </div>
            <p style="font-size:13px; font-weight:600; margin:0; flex-shrink:0;">£{{ number_format($item['price'] * $item['quantity'], 2) }}</p>
          </div>
          @endforeach

          <div style="padding-top:1.5rem; margin-top:0.5rem; border-top:1px solid rgba(0,0,0,0.06);">
            <div style="display:flex; justify-content:space-between; font-size:13px; margin-bottom:8px;">
              <span style="color:#7a7a72;">Subtotal</span>
              <span>£{{ number_format($subtotal, 2) }}</span>
            </div>
            <div style="display:flex; justify-content:space-between; font-size:13px; margin-bottom:8px;">
              <span style="color:#7a7a72;">Shipping</span>
              <span id="summaryShipping" style="{{ $shipping == 0 ? 'color:#16a34a;' : '' }}">
                {{ $shipping == 0 ? 'Free' : '£' . number_format($shipping, 2) }}
              </span>
            </div>
            <div style="display:flex; justify-content:space-between; font-size:15px; font-weight:700; padding-top:1rem; margin-top:0.5rem; border-top:1px solid rgba(0,0,0,0.08);">
              <span>Total</span>
              <span>£<span id="summaryTotal">{{ number_format($total, 2) }}</span></span>
            </div>
          </div>

          @if($subtotal < 100)
          <div style="margin-top:1.25rem; padding:12px 14px; background:#f9f8f6; border:1px solid rgba(0,0,0,0.06);">
            <p style="font-size:11px; color:#7a7a72; margin-bottom:6px;">
              Add <strong style="color:#0f0f0f;">£{{ number_format(100 - $subtotal, 2) }}</strong> more for free standard delivery
            </p>
            <div style="background:#e8e5de; height:3px; border-radius:2px;">
              <div style="background:#c9a96e; height:100%; width:{{ min(($subtotal/100)*100, 100) }}%; border-radius:2px;"></div>
            </div>
          </div>
          @endif

        </div>
      </div>

    </div>
  </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
  // ── Core Stripe setup ──
  const stripe       = Stripe('{{ $stripeKey }}');
  const clientSecret = '{{ $clientSecret }}';
  const elements     = stripe.elements();
  const csrfToken    = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
  const subtotal     = {{ $subtotal }};

  // ── Shipping rates ──
  const RATES = {
    uk_dpd:      subtotal >= 80 ? 0 : 5.50,
    intl_europe: 12.50,
    intl_world:  12.50,
  };
  const EU_COUNTRIES = ['AT','BE','BG','HR','CY','CZ','DK','EE','FI','FR','DE','GR','HU','IS','IE','IT','LV','LI','LT','LU','MT','NL','NO','PL','PT','RO','SK','SI','ES','SE','CH'];

  // ── Mount Stripe Elements ──
  const elementStyle = {
    base: {
      fontFamily: "'Montserrat', sans-serif",
      fontSize:   '14px',
      lineHeight: '1.5',
      color:      '#0f0f0f',
      '::placeholder': { color: 'rgba(0,0,0,0.35)' },
    },
    invalid: { color: '#dc2626' },
  };

  const cardNumber = elements.create('cardNumber', { style: elementStyle, showIcon: true });
  const cardExpiry = elements.create('cardExpiry', { style: elementStyle });
  const cardCvc    = elements.create('cardCvc',    { style: elementStyle });

  cardNumber.mount('#cardNumber');
  cardExpiry.mount('#cardExpiry');
  cardCvc.mount('#cardCvc');

  // Focus/blur/error border effects
  [
    { el: cardNumber, wrap: 'wrap-cardNumber' },
    { el: cardExpiry, wrap: 'wrap-cardExpiry' },
    { el: cardCvc,    wrap: 'wrap-cardCvc'    },
  ].forEach(({ el, wrap }) => {
    el.on('focus', () => document.getElementById(wrap).classList.add('focused'));
    el.on('blur',  () => document.getElementById(wrap).classList.remove('focused'));
    el.on('change', e => {
      const w = document.getElementById(wrap);
      w.classList.toggle('error', !!e.error);
      if (e.error) showError(e.error.message);
      else         hideError();
    });
  });

  // ── Country → shipping panel logic ──
  function handleCountryChange(select) {
    const country = select.value;
    const isUK    = (country === 'GB' || country === '');
    const isEU    = EU_COUNTRIES.includes(country);

    document.getElementById('shipping-uk').style.display            = isUK ? 'block' : 'none';
    document.getElementById('shipping-international').style.display = isUK ? 'none'  : 'block';

    if (isUK) {
      selectShipping('uk_dpd');
    } else if (isEU) {
      document.getElementById('label-intl-europe').style.display = 'block';
      document.getElementById('label-intl-world').style.display  = 'none';
      selectShipping('intl_europe');
    } else {
      document.getElementById('label-intl-europe').style.display = 'none';
      document.getElementById('label-intl-world').style.display  = 'block';
      selectShipping('intl_world');
    }
  }

  function selectShipping(value) {
    document.querySelectorAll('input[name="shipping_method"]').forEach(r => r.checked = false);
    const radio = document.querySelector('input[name="shipping_method"][value="' + value + '"]');
    if (radio) radio.checked = true;
    document.querySelectorAll('.shipping-option').forEach(el => el.classList.remove('selected'));
    const label = document.getElementById('label-' + value.replace(/_/g, '-'));
    if (label) label.classList.add('selected');
    updateTotals(value);
  }

  function handleShippingChange(radio) {
    document.querySelectorAll('.shipping-option').forEach(el => el.classList.remove('selected'));
    const label = document.getElementById('label-' + radio.value.replace(/_/g, '-'));
    if (label) label.classList.add('selected');
    updateTotals(radio.value);
  }

  function updateTotals(shippingMethod) {
    const shipping = RATES[shippingMethod] !== undefined ? RATES[shippingMethod] : 0;
    const total    = subtotal + shipping;
    const el       = document.getElementById('summaryShipping');
    el.textContent = shipping === 0 ? 'Free' : '£' + shipping.toFixed(2);
    el.style.color = shipping === 0 ? '#16a34a' : '';
    document.getElementById('summaryTotal').textContent = total.toFixed(2);
    document.getElementById('payTotal').textContent     = total.toFixed(2);
    fetch('{{ route("checkout.updateIntent") }}', {
      method:  'POST',
      headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
      body:    JSON.stringify({ shipping_method: shippingMethod }),
    }).catch(() => {});
  }

  // Wire country dropdown
  const countrySelect = document.querySelector('select[name="country"]');
  if (countrySelect) {
    countrySelect.addEventListener('change', () => handleCountryChange(countrySelect));
    handleCountryChange(countrySelect);
  }

  // ── Form submit — confirm payment with Stripe then POST to Laravel ──
  document.getElementById('checkoutForm').addEventListener('submit', async (e) => {
    e.preventDefault();

    const btn = document.getElementById('payBtn');
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin" style="font-size:10px;"></i> Processing...';
    hideError();

    const { paymentIntent, error } = await stripe.confirmCardPayment(clientSecret, {
      payment_method: {
        card: cardNumber,
        billing_details: {
          name:  document.querySelector('[name=first_name]').value + ' ' + document.querySelector('[name=last_name]').value,
          email: document.querySelector('[name=email]').value,
        },
      },
    });

    if (error) {
      showError(error.message);
      btn.disabled = false;
      btn.innerHTML = '<i class="fas fa-lock" style="font-size:10px;"></i> Pay £' + document.getElementById('payTotal').textContent;
      return;
    }

    // Success — set hidden field and submit to Laravel
    document.getElementById('paymentIntentId').value = paymentIntent.id;
    e.target.submit();
  });

  function showError(msg) {
    const el = document.getElementById('paymentError');
    document.getElementById('paymentErrorMsg').textContent = msg;
    el.classList.add('visible');
  }
  function hideError() {
    document.getElementById('paymentError').classList.remove('visible');
  }
</script>

</body>
</html>