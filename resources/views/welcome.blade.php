<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>DERU | Beyond Ordinary</title>
  <meta name="description" content="Discover DERU's beyond ordinary caps and standout styles.">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;0,700;1,400&family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">

  <!-- Tailwind CSS CDN -->
  <script src="https://cdn.tailwindcss.com"></script>

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="{{ mix('css/app.css') }}">
  <script src="{{ mix('js/app.js') }}" defer></script>

  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            deru: {
              black: '#0f0f0f',
              cream: '#f1f0ec',
              warm: '#e8e5de',
              accent: '#c9a96e',
              muted: '#7a7a72',
            }
          },
          fontFamily: {
            display: ['Cormorant Garamond', 'serif'],
            body: ['Montserrat', 'sans-serif'],
          }
        }
      }
    }
  </script>

  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    html { scroll-behavior: smooth; }
    body { font-family: 'Montserrat', sans-serif; background: #f1f0ec; color: #0f0f0f; }

    /* ── Topbar Announcement Rotator ── */
    .announcement-wrapper { position: relative; height: 20px; overflow: hidden; }
    .announcement { position: absolute; inset: 0; width: 100%; opacity: 0; transition: opacity 0.6s ease-in-out; }
    .announcement.active { opacity: 1; }

    /* ── Sticky Header ── */
    .site-header {
      position: sticky; top: 0; z-index: 1000;
      display: flex; align-items: center; justify-content: space-between; gap: 1rem;
      padding: 18px 5vw;
      background: #f1f0ec;
      border-bottom: 1px solid rgba(0,0,0,0.08);
      transition: box-shadow 0.3s ease;
    }
    .site-header.scrolled { box-shadow: 0 2px 20px rgba(0,0,0,0.08); }

    /* ── Currency Switcher ── */
    .currency-switcher {
      position: relative; display: flex; align-items: center;
    }
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

    /* ── Mobile Slide Menu ── */
    .mobile-menu {
      position: fixed; inset-block: 0; left: -100%;
      width: 80%; max-width: 320px;
      background: #0f0f0f; color: white;
      transition: left 0.35s cubic-bezier(0.4,0,0.2,1);
      z-index: 1100; overflow-y: auto;
    }
    .mobile-menu.active { left: 0; }
    .menu-overlay {
      position: fixed; inset: 0; background: rgba(0,0,0,0.5);
      opacity: 0; pointer-events: none;
      transition: opacity 0.35s ease; z-index: 1099;
    }
    .menu-overlay.active { opacity: 1; pointer-events: all; }

    /* ── Hero Section ── */
    .hero-section {
      position: relative; height: 90vh; min-height: 600px; overflow: hidden;
      background: #0f0f0f;
    }
    .hero-bg {
      position: absolute; inset: 0;
      background: linear-gradient(135deg, #0f0f0f 0%, #1e1a16 50%, #0f0f0f 100%);
    }
    .hero-grain {
      position: absolute; inset: 0; opacity: 0.04;
      background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noise'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noise)'/%3E%3C/svg%3E");
    }
    .hero-video-placeholder {
      position: absolute; inset: 0;
      background: linear-gradient(180deg, rgba(15,15,15,0.3) 0%, rgba(15,15,15,0.7) 100%);
    }
    .hero-content {
      position: absolute; inset: 0;
      display: flex; flex-direction: column;
      align-items: center; justify-content: center;
      text-align: center; color: white; padding: 0 5vw;
    }
    .hero-eyebrow {
      font-family: 'Montserrat', sans-serif;
      font-size: 11px; letter-spacing: 0.35em; text-transform: uppercase;
      color: #c9a96e; margin-bottom: 1.5rem;
      opacity: 0; animation: fadeUp 0.8s ease 0.3s forwards;
    }
    .hero-title {
      font-family: 'Cormorant Garamond', serif;
      font-size: clamp(4rem, 10vw, 9rem); font-weight: 700; line-height: 0.9;
      letter-spacing: -0.02em; margin-bottom: 2rem;
      opacity: 0; animation: fadeUp 0.8s ease 0.5s forwards;
    }
    .hero-title em { font-style: italic; color: #c9a96e; }
    .hero-cta {
      opacity: 0; animation: fadeUp 0.8s ease 0.7s forwards;
    }
    .hero-scroll-hint {
      position: absolute; bottom: 2.5rem; left: 50%; transform: translateX(-50%);
      display: flex; flex-direction: column; align-items: center; gap: 8px;
      color: rgba(255,255,255,0.4); font-size: 10px; letter-spacing: 0.2em; text-transform: uppercase;
      opacity: 0; animation: fadeUp 0.8s ease 1s forwards;
    }
    .scroll-line {
      width: 1px; height: 40px;
      background: linear-gradient(to bottom, rgba(201,169,110,0.8), transparent);
      animation: scrollPulse 2s ease-in-out infinite;
    }

    /* ── Buttons ── */
    .btn-deru-primary {
      display: inline-flex; align-items: center; gap: 10px;
      background: #c9a96e; color: #0f0f0f;
      padding: 14px 36px; font-family: 'Montserrat', sans-serif;
      font-size: 11px; font-weight: 600; letter-spacing: 0.2em; text-transform: uppercase;
      text-decoration: none; transition: all 0.3s ease; border: 1px solid #c9a96e;
    }
    .btn-deru-primary:hover { background: transparent; color: #c9a96e; }
    .btn-deru-outline {
      display: inline-flex; align-items: center; gap: 10px;
      background: transparent; color: #0f0f0f;
      padding: 12px 32px; font-family: 'Montserrat', sans-serif;
      font-size: 11px; font-weight: 600; letter-spacing: 0.2em; text-transform: uppercase;
      text-decoration: none; transition: all 0.3s ease; border: 1px solid #0f0f0f;
    }
    .btn-deru-outline:hover { background: #0f0f0f; color: white; }

    /* ── Collection Cards ── */
    .collection-card {
      position: relative; overflow: hidden; cursor: pointer;
      aspect-ratio: 3/4;
    }
    .collection-card img {
      width: 100%; height: 100%; object-fit: cover;
      transition: transform 0.7s cubic-bezier(0.4,0,0.2,1);
    }
    .collection-card:hover img { transform: scale(1.06); }
    .collection-card-overlay {
      position: absolute; inset: 0;
      background: linear-gradient(to top, rgba(0,0,0,0.75) 0%, rgba(0,0,0,0) 50%);
      transition: background 0.4s ease;
    }
    .collection-card:hover .collection-card-overlay {
      background: linear-gradient(to top, rgba(0,0,0,0.85) 0%, rgba(0,0,0,0.1) 60%);
    }
    .collection-card-content {
      position: absolute; bottom: 0; left: 0; right: 0; padding: 2rem;
      transform: translateY(8px); transition: transform 0.4s ease;
    }
    .collection-card:hover .collection-card-content { transform: translateY(0); }

    /* ── Tabs ── */
    .tab-btn {
      padding: 10px 24px; background: transparent;
      border: 1px solid rgba(0,0,0,0.15); border-radius: 9999px;
      font-family: 'Montserrat', sans-serif; font-size: 12px;
      font-weight: 500; letter-spacing: 0.08em; text-transform: uppercase;
      cursor: pointer; transition: all 0.25s ease; white-space: nowrap;
    }
    .tab-btn:hover { background: #0f0f0f; color: white; border-color: #0f0f0f; }
    .tab-btn.active { background: #0f0f0f; color: white; border-color: #0f0f0f; }

    /* ── Product Card ── */
    .product-card {
      background: white; overflow: hidden;
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .product-card:hover { transform: translateY(-6px); box-shadow: 0 20px 40px rgba(0,0,0,0.1); }
    .product-card-img {
      width: 100%; aspect-ratio: 3/4; object-fit: cover;
      background: #e8e5de;
      transition: transform 0.5s ease;
    }
    .product-card:hover .product-card-img { transform: scale(1.04); }
    .product-img-wrap { overflow: hidden; }

    /* ── Story Section ── */
    .story-section {
      background: #0f0f0f; color: white;
      position: relative; overflow: hidden;
    }
    .story-section::before {
      content: 'DERU'; position: absolute;
      font-family: 'Cormorant Garamond', serif; font-size: 20vw; font-weight: 700;
      color: rgba(255,255,255,0.03); top: 50%; left: 50%;
      transform: translate(-50%, -50%); white-space: nowrap; pointer-events: none;
    }

    /* ── Footer ── */
    .site-footer { background: #0f0f0f; color: white; }
    .footer-link { color: rgba(255,255,255,0.5); text-decoration: none; font-size: 13px; transition: color 0.2s ease; }
    .footer-link:hover { color: #c9a96e; }

    /* ── Animations ── */
    @keyframes fadeUp {
      from { opacity: 0; transform: translateY(24px); }
      to   { opacity: 1; transform: translateY(0); }
    }
    @keyframes scrollPulse {
      0%, 100% { opacity: 0.4; transform: scaleY(1); }
      50%       { opacity: 1;   transform: scaleY(1.2); }
    }

    /* ── Marquee ── */
    .marquee-track {
      display: flex; gap: 0; white-space: nowrap;
      animation: marquee 18s linear infinite;
    }
    .marquee-track:hover { animation-play-state: paused; }
    @keyframes marquee {
      from { transform: translateX(0); }
      to   { transform: translateX(-50%); }
    }

    /* ── Toast ── */
    @keyframes toastIn {
      from { opacity: 0; transform: translateY(16px); }
      to   { opacity: 1; transform: translateY(0); }
    }
    @keyframes toastOut {
      from { opacity: 1; transform: translateY(0); }
      to   { opacity: 0; transform: translateY(10px); }
    }
    .cart-count-badge { transition: transform 0.3s ease; }
  </style>
</head>
<body>

{{-- ─────────────────────────────────────────────
     TOPBAR ANNOUNCEMENT BANNER
───────────────────────────────────────────── --}}
<div class="bg-black text-white text-center py-2" style="font-size:12px; letter-spacing:0.1em;">
  <div class="announcement-wrapper">
    <div class="announcement active">
      <p>&#10094;&nbsp; FREE DELIVERY ON ORDERS OVER £100 &nbsp;&#10095;</p>
    </div>
    <div class="announcement">
      <p>&#10094;&nbsp; NEW ARRIVALS JUST DROPPED 🔥 SHOP NOW &nbsp;&#10095;</p>
    </div>
    <div class="announcement">
      <p>&#10094;&nbsp; COMPLIMENTARY GIFT WRAPPING ON ALL ORDERS &nbsp;&#10095;</p>
    </div>
  </div>
</div>

{{-- ─────────────────────────────────────────────
     HEADER
───────────────────────────────────────────── --}}
<header class="site-header" id="siteHeader">

  {{-- Hamburger --}}
  <button class="menu-toggle bg-transparent border-0 p-0 d-lg-none" aria-label="Open menu" id="menuToggle">
    <i class="fas fa-bars text-xl" style="color:#0f0f0f;"></i>
  </button>

  {{-- Desktop Nav Left --}}
  <nav class="d-none d-lg-flex align-items-center gap-4" style="flex:1;">
    <a href="#" class="text-decoration-none" style="font-size:12px; letter-spacing:0.12em; text-transform:uppercase; font-weight:500; color:#0f0f0f;">New Arrivals</a>
    <a href="#" class="text-decoration-none" style="font-size:12px; letter-spacing:0.12em; text-transform:uppercase; font-weight:500; color:#0f0f0f;">Shop</a>
    <a href="#" class="text-decoration-none" style="font-size:12px; letter-spacing:0.12em; text-transform:uppercase; font-weight:500; color:#0f0f0f;">Beyond Ordinary</a>
  </nav>

  {{-- Logo --}}
  <div class="text-center" style="flex:1;">
    <a href="/" class="text-decoration-none">
      <span style="font-family:'Cormorant Garamond',serif; font-size:2rem; font-weight:700; letter-spacing:0.2em; color:#0f0f0f;">DERU</span>
    </a>
  </div>

  {{-- Icons Right --}}
  <div class="d-flex align-items-center gap-4 justify-content-end" style="flex:1;">

    {{-- Currency Switcher --}}
    <div class="currency-switcher d-none d-lg-flex">
      <select class="currency-select" id="currencySelect" onchange="setCurrency(this.value)">
        <option value="GBP">£ GBP</option>
        <option value="USD">$ USD</option>
        <option value="EUR">€ EUR</option>
        <option value="NGN">₦ NGN</option>
        <option value="GHS">₵ GHS</option>
        <option value="ZAR">R ZAR</option>
        <option value="CAD">$ CAD</option>
        <option value="AUD">$ AUD</option>
      </select>
    </div>

    <a href="#" class="text-decoration-none" style="color:#0f0f0f;">
      <i class="fas fa-search" style="font-size:15px;"></i>
    </a>
    @if (Route::has('login'))
     @auth
       <a href="{{ auth()->user()->isAdmin() ? route('admin.dashboard') : url('/home') }}"
          class="btn-deru-primary w-25 text-center d-block mb-2" style="text-decoration:none;">
         {{ auth()->user()->isAdmin() ? 'Admin Panel' : auth()->user()->name }}
       </a>
       <form method="POST" action="{{ route('logout') }}">
         @csrf
         <button type="submit"
                 style="width:100%; background:none; border:none; color:rgba(255,255,255,0.4); font-family:'Montserrat',sans-serif; font-size:12px; letter-spacing:0.1em; text-transform:uppercase; cursor:pointer; padding:10px 0;">
           Sign Out
         </button>
       </form>
     @else
       <a href="{{ route('login') }}" class="btn-deru-primary w-25 text-center d-block mb-2" style="text-decoration:none;">Login</a>
       @if (Route::has('register'))
         <a href="{{ route('register') }}" class="d-block text-center mt-3"
            style="color:rgba(255,255,255,0.5); font-size:12px; letter-spacing:0.1em; text-transform:uppercase; text-decoration:none;">
           Create Account
         </a>
       @endif
     @endauth
    @endif
    @php $cartCount = array_sum(array_column(session()->get('cart', []), 'quantity')); @endphp
    <a href="{{ route('cart.index') }}" class="text-decoration-none position-relative" style="color:#0f0f0f;">
      <i class="fas fa-shopping-bag" style="font-size:15px;"></i>
      <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill cart-count-badge"
            style="background:#c9a96e; font-size:9px; padding:2px 5px; {{ $cartCount === 0 ? 'display:none;' : '' }}">
        {{ $cartCount > 0 ? $cartCount : '' }}
      </span>
    </a>
  </div>

</header>

{{-- Mobile Menu Overlay --}}
<div class="menu-overlay" id="menuOverlay"></div>

{{-- Mobile Slide Menu --}}
<nav class="mobile-menu" id="mobileMenu">
  <div class="d-flex align-items-center justify-content-between p-4" style="border-bottom:1px solid rgba(255,255,255,0.08);">
    <span style="font-family:'Cormorant Garamond',serif; font-size:1.4rem; font-weight:700; letter-spacing:0.2em; color:white;">DERU</span>
    <button class="bg-transparent border-0 text-white fs-4" id="menuClose">×</button>
  </div>
  <ul class="list-unstyled m-0 p-0">
    @php $mobileLinks = ['New Arrivals', 'Shop', 'Beyond Ordinary', 'Fragrance', 'Our Story']; @endphp
    @foreach($mobileLinks as $link)
    <li style="border-bottom:1px solid rgba(255,255,255,0.06);">
      <a href="#" class="d-block px-4 py-4 text-decoration-none text-white"
         style="font-size:13px; letter-spacing:0.15em; text-transform:uppercase; font-weight:500; transition:color 0.2s;">
        {{ $link }}
      </a>
    </li>
    @endforeach
    <li class="p-4 mt-2">
      @if (Route::has('login'))
        @auth
          <a href="{{ url('/dashboard') }}" class="btn-deru-primary w-100 text-center">My Account</a>
        @else
          <a href="{{ route('login') }}" class="btn-deru-primary w-100 text-center d-block mb-2">Login</a>
          @if (Route::has('register'))
            <a href="{{ route('register') }}" class="d-block text-center mt-3" style="color:rgba(255,255,255,0.5); font-size:12px; letter-spacing:0.1em; text-transform:uppercase;">Create Account</a>
          @endif
        @endauth
      @endif
    </li>

    {{-- Currency in mobile menu --}}
    <li style="padding:0 1rem 1.5rem;">
      <p style="font-size:9px; letter-spacing:0.2em; text-transform:uppercase; color:rgba(255,255,255,0.35); margin-bottom:8px; padding:0 4px;">Currency</p>
      <div class="currency-switcher" style="width:100%;">
        <select class="currency-select" id="currencySelectMobile" onchange="setCurrency(this.value)"
                style="width:100%; background:rgba(255,255,255,0.05); color:white; border-color:rgba(255,255,255,0.15);">
          <option value="GBP">£ GBP — British Pound</option>
          <option value="USD">$ USD — US Dollar</option>
          <option value="EUR">€ EUR — Euro</option>
          <option value="NGN">₦ NGN — Nigerian Naira</option>
          <option value="GHS">₵ GHS — Ghanaian Cedi</option>
          <option value="ZAR">R ZAR — South African Rand</option>
          <option value="CAD">C$ CAD — Canadian Dollar</option>
          <option value="AUD">A$ AUD — Australian Dollar</option>
        </select>
      </div>
      <p style="font-size:10px; color:rgba(255,255,255,0.25); margin-top:8px; padding:0 4px; line-height:1.5;">
        Display only. Checkout is always processed in GBP.
      </p>
    </li>

  </ul>
</nav>

<main>

  {{-- ─────────────────────────────────────────────
       HERO SECTION
  ───────────────────────────────────────────── --}}
  <section class="hero-section">
    <div class="hero-bg"></div>
    <div class="hero-grain"></div>

    {{-- Mock: Replace with real <video> tag when available --}}
    <div class="hero-video-placeholder"></div>

    <div class="hero-content">
      <p class="hero-eyebrow">Autumn / Winter 2025</p>
      <h1 class="hero-title">New<br><em>Arrivals</em><br>AW25</h1>
      <div class="hero-cta d-flex gap-3 flex-wrap justify-content-center">
        <a href="#" class="btn-deru-primary">Shop Now <i class="fas fa-arrow-right ms-1" style="font-size:10px;"></i></a>
        <a href="#" class="btn-deru-outline" style="color:white; border-color:rgba(255,255,255,0.3);">Explore Collection</a>
      </div>
    </div>

    <div class="hero-scroll-hint">
      <div class="scroll-line"></div>
      <span>Scroll</span>
    </div>
  </section>

  {{-- ─────────────────────────────────────────────
       MARQUEE STRIP
  ───────────────────────────────────────────── --}}
  <div class="overflow-hidden py-3" style="background:#0f0f0f; border-top:1px solid rgba(255,255,255,0.06);">
    <div class="marquee-track">
      @php
        $marqueeItems = ['Beyond Ordinary', '✦', 'New Arrivals', '✦', 'AW25 Collection', '✦', 'Premium Caps', '✦', 'Fragrance', '✦', 'Free Delivery Over £100', '✦', 'Beyond Ordinary', '✦', 'New Arrivals', '✦', 'AW25 Collection', '✦', 'Premium Caps', '✦', 'Fragrance', '✦', 'Free Delivery Over £100', '✦'];
      @endphp
      @foreach($marqueeItems as $item)
        <span class="px-5" style="font-family:'Montserrat',sans-serif; font-size:11px; letter-spacing:0.25em; text-transform:uppercase; color:rgba(255,255,255,0.4);">{{ $item }}</span>
      @endforeach
    </div>
  </div>

  {{-- ─────────────────────────────────────────────
       COLLECTION GRID
  ───────────────────────────────────────────── --}}
  <section class="py-5" style="background:#f1f0ec;">
    <div class="container-fluid px-4 px-lg-5">

      <div class="text-center mb-5">
        <p style="font-size:11px; letter-spacing:0.3em; text-transform:uppercase; color:#7a7a72; margin-bottom:0.5rem;">Explore</p>
        <h2 style="font-family:'Cormorant Garamond',serif; font-size:clamp(2.5rem,5vw,4rem); font-weight:700; line-height:1;">Our Collections</h2>
      </div>

      @if($categories->isEmpty())
        <p style="text-align:center; color:#7a7a72; font-size:13px;">No collections yet.</p>
      @else
      <div class="row g-3">

        {{-- First category — large card --}}
        <div class="col-12 col-md-6">
          <a href="{{ url('/?category=' . $categories->first()->slug) }}" style="text-decoration:none;" class="collection-card h-100 d-block">
            @if($categories->first()->cover_image)
              <img src="{{ asset('storage/' . $categories->first()->cover_image) }}"
                   alt="{{ $categories->first()->name }}" style="min-height:500px;">
            @else
              <div style="min-height:500px; width:100%; background:#1a1a1a;"></div>
            @endif
            <div class="collection-card-overlay"></div>
            <div class="collection-card-content">
              @if($categories->first()->tag)
                <span class="badge mb-2" style="background:#c9a96e; color:#0f0f0f; font-size:9px; letter-spacing:0.15em; font-weight:600; padding:5px 10px; border-radius:0;">
                  {{ $categories->first()->tag }}
                </span>
              @endif
              <h3 style="font-family:'Cormorant Garamond',serif; font-size:2.2rem; font-weight:700; color:white; line-height:1.1; margin-bottom:0.5rem;">
                {{ $categories->first()->name }}
              </h3>
              <p style="font-size:12px; color:rgba(255,255,255,0.5); letter-spacing:0.05em; margin-bottom:1rem;">
                {{ $categories->first()->products_count }} {{ Str::plural('style', $categories->first()->products_count) }}
              </p>
              <span style="font-size:11px; letter-spacing:0.2em; text-transform:uppercase; color:#c9a96e; font-weight:600;">Shop Now →</span>
            </div>
          </a>
  
        </div>

        {{-- Remaining categories — stacked cards --}}
        <div class="col-12 col-md-6">
          <div class="row g-3 h-100">
            @foreach($categories->skip(1) as $cat)
            <div class="col-12">
              <a href="{{ url('/?category=' . $cat->slug) }}" style="text-decoration:none;" class="collection-card d-block" style="aspect-ratio:16/9;">
                @if($cat->cover_image)
                  <img src="{{ asset('storage/' . $cat->cover_image) }}"
                       alt="{{ $cat->name }}" style="aspect-ratio:16/9; min-height:unset; width:100%; height:100%; object-fit:cover;">
                @else
                  <div style="aspect-ratio:16/9; background:#1a1a1a; width:100%;"></div>
                @endif
                <div class="collection-card-overlay"></div>
                <div class="collection-card-content">
                  @if($cat->tag)
                    <span class="badge mb-2" style="background:#c9a96e; color:#0f0f0f; font-size:9px; letter-spacing:0.15em; font-weight:600; padding:5px 10px; border-radius:0;">
                      {{ $cat->tag }}
                    </span>
                  @endif
                  <h3 style="font-family:'Cormorant Garamond',serif; font-size:1.8rem; font-weight:700; color:white; line-height:1.1; margin-bottom:0.4rem;">
                    {{ $cat->name }}
                  </h3>
                  <p style="font-size:12px; color:rgba(255,255,255,0.5); margin-bottom:0.8rem;">
                    {{ $cat->products_count }} {{ Str::plural('style', $cat->products_count) }}
                  </p>
                  <span style="font-size:11px; letter-spacing:0.2em; text-transform:uppercase; color:#c9a96e; font-weight:600;">Shop Now →</span>
                </div>
              </a>
            </div>
            @endforeach
          </div>
        </div>

      </div>
      @endif
    </div>
  </section>

  {{-- ─────────────────────────────────────────────
       FEATURED PRODUCTS WITH TABS
  ───────────────────────────────────────────── --}}
  <section class="py-5" style="background:#f1f0ec;">
    <div class="container-fluid px-4 px-lg-5">

      <div class="d-flex flex-column flex-md-row align-items-md-end justify-content-between mb-5 gap-3">
        <div>
          <p style="font-size:11px; letter-spacing:0.3em; text-transform:uppercase; color:#7a7a72; margin-bottom:0.5rem;">Featured</p>
          <h2 style="font-family:'Cormorant Garamond',serif; font-size:clamp(2.5rem,5vw,4rem); font-weight:700; line-height:1; margin:0;">The Edit</h2>
        </div>
        <div class="d-flex gap-2 flex-wrap">
          <a href="{{ url('/') }}"
             class="tab-btn {{ !request('category') ? 'active' : '' }}"
             style="text-decoration:none;">All</a>
          @foreach($categories as $cat)
            <a href="{{ url('/?category=' . $cat->slug) }}"
               class="tab-btn {{ request('category') === $cat->slug ? 'active' : '' }}"
               style="text-decoration:none;">{{ $cat->name }}</a>
          @endforeach
        </div>
      </div>

      <div class="row g-3" id="productGrid">
        @forelse($products as $product)
        <div class="col-6 col-md-4 col-lg-3">
          <div class="product-card">
            <div class="product-img-wrap position-relative">
              {{-- Product image --}}
              @if($product->image)
                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="product-card-img">
              @else
                <div class="product-card-img" style="background:#e8e5de; display:flex; align-items:center; justify-content:center;">
                  <i class="fas fa-image" style="font-size:2rem; color:#c8c5be;"></i>
                </div>
              @endif

              {{-- Hover overlay — two buttons ── --}}
              <div class="product-card-overlay position-absolute bottom-0 start-0 end-0 m-2"
                   style="display:flex; flex-direction:column; gap:6px; opacity:0; transition:opacity 0.3s ease;">

                <button
                  class="btn-deru-primary text-center border-0 product-add-btn"
                  style="width:100%; justify-content:center; padding:10px; font-size:10px;"
                  data-product-id="{{ $product->id }}"
                  data-product-name="{{ $product->name }}"
                  data-product-price="{{ $product->price }}"
                  data-product-img="{{ $product->image ? asset('storage/' . $product->image) : '' }}"
                >
                  <i class="fas fa-shopping-bag" style="font-size:9px;"></i> Add to Bag
                </button>

                <a href="{{ route('products.show', $product) }}"
                   style="display:flex; align-items:center; justify-content:center; gap:6px;
                          width:100%; padding:8px; background:rgba(241,240,236,0.92);
                          color:#0f0f0f; font-family:'Montserrat',sans-serif; font-size:10px;
                          font-weight:600; letter-spacing:0.15em; text-transform:uppercase;
                          text-decoration:none; border:1px solid rgba(0,0,0,0.12);
                          transition:background 0.2s ease;">
                  <i class="fas fa-eye" style="font-size:9px;"></i> View Product
                </a>

              </div>
            </div>

            {{-- Card info — product name links to product page ── --}}
            <div class="p-3">
              <a href="{{ route('products.show', $product) }}" style="text-decoration:none; color:inherit;">
                <h4 style="font-size:13px; font-weight:500; letter-spacing:0.03em; margin-bottom:4px; transition:color 0.2s;"
                    onmouseover="this.style.color='#c9a96e'" onmouseout="this.style.color='inherit'">
                  {{ $product->name }}
                </h4>
              </a>
              <p style="font-size:13px; font-weight:600; color:#0f0f0f; margin:0;"
                 data-price-gbp="{{ $product->price }}">
                <span class="price-display">£{{ number_format($product->price, 2) }}</span>
              </p>
            </div>
          </div>
        </div>
        @empty
        <div class="col-12 text-center py-5">
          <p style="color:#7a7a72; font-size:13px;">No products available yet.</p>
        </div>
        @endforelse
      </div>

      <div class="text-center mt-5">
        <a href="#" class="btn-deru-outline">View All Products</a>
      </div>
    </div>
  </section>

  {{-- ─────────────────────────────────────────────
       OUR STORY SECTION
  ───────────────────────────────────────────── --}}
  <section class="story-section py-5">
    <div class="container-fluid px-4 px-lg-5 py-5" style="position:relative; z-index:1;">
      <div class="row align-items-center g-5">
        <div class="col-12 col-lg-5">
          <p style="font-size:11px; letter-spacing:0.3em; text-transform:uppercase; color:#c9a96e; margin-bottom:1rem;">Our Story</p>
          <h2 style="font-family:'Cormorant Garamond',serif; font-size:clamp(3rem,6vw,5rem); font-weight:700; line-height:1; color:white; margin-bottom:2rem;">
            Beyond <em style="font-style:italic; color:#c9a96e;">Ordinary</em><br>By Design
          </h2>
          <p style="font-size:14px; line-height:1.8; color:rgba(255,255,255,0.55); margin-bottom:2.5rem; max-width:420px;">
            From the very beginning, our mission has been simple: to create premium products that combine luxury craftsmanship with modern design. Every stitch, every silhouette, every scent is intentional — built for those who refuse to blend in.
          </p>
          <a href="#" class="btn-deru-primary">Discover Our Story</a>
        </div>
        <div class="col-12 col-lg-7">
          <div class="row g-3">
            <div class="col-7">
              <img src="https://images.unsplash.com/photo-1521369909029-2afed882baee?w=700&q=80"
                   alt="DERU Campaign" style="width:100%; aspect-ratio:3/4; object-fit:cover;">
            </div>
            <div class="col-5 d-flex flex-column justify-content-end gap-3">
              <img src="https://images.unsplash.com/photo-1575428652377-a2d80e2277fc?w=400&q=80"
                   alt="DERU Detail" style="width:100%; aspect-ratio:1; object-fit:cover;">
              <div class="p-3 text-center" style="background:rgba(201,169,110,0.1); border:1px solid rgba(201,169,110,0.2);">
                <p style="font-family:'Cormorant Garamond',serif; font-size:2.5rem; font-weight:700; color:#c9a96e; line-height:1; margin:0;">AW25</p>
                <p style="font-size:10px; letter-spacing:0.2em; color:rgba(255,255,255,0.4); margin:0; text-transform:uppercase;">Collection</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  {{-- ─────────────────────────────────────────────
       EMAIL SIGNUP
  ───────────────────────────────────────────── --}}
  <section class="py-5 text-center" style="background:#e8e5de;">
    <div class="container py-4">
      <p style="font-size:11px; letter-spacing:0.3em; text-transform:uppercase; color:#7a7a72; margin-bottom:1rem;">Exclusive Access</p>
      <h2 style="font-family:'Cormorant Garamond',serif; font-size:clamp(2rem,4vw,3.5rem); font-weight:700; margin-bottom:1rem;">Get 10% Off Your First Order</h2>
      <p style="font-size:14px; color:#7a7a72; margin-bottom:2.5rem;">Join the DERU community for early access to new arrivals and exclusive offers.</p>
      <form class="d-flex gap-0 justify-content-center mx-auto" style="max-width:480px;" onsubmit="return false;">
        <input type="email" placeholder="Your email address"
               class="flex-grow-1 px-4 py-3 border-0 outline-none"
               style="background:white; font-family:'Montserrat',sans-serif; font-size:13px; outline:none; min-width:0;">
        <button type="submit" class="btn-deru-primary border-0 px-4 flex-shrink-0" style="white-space:nowrap;">Subscribe →</button>
      </form>
    </div>
  </section>

</main>

{{-- ─────────────────────────────────────────────
     FOOTER
───────────────────────────────────────────── --}}
<footer class="site-footer pt-5 pb-4">
  <div class="container-fluid px-4 px-lg-5">
    <div class="row g-5 pb-5" style="border-bottom:1px solid rgba(255,255,255,0.08);">
      <div class="col-12 col-md-4">
        <h3 style="font-family:'Cormorant Garamond',serif; font-size:2rem; font-weight:700; letter-spacing:0.2em; color:white; margin-bottom:1rem;">DERU</h3>
        <p style="font-size:13px; line-height:1.8; color:rgba(255,255,255,0.4); max-width:260px;">Premium caps and fragrance for those who live beyond ordinary.</p>
        <div class="d-flex gap-3 mt-4">
          @foreach(['instagram', 'tiktok', 'twitter'] as $social)
          <a href="#" style="width:36px; height:36px; border:1px solid rgba(255,255,255,0.15); display:flex; align-items:center; justify-content:center; color:rgba(255,255,255,0.4); text-decoration:none; transition:all 0.2s ease; font-size:13px;"
             onmouseover="this.style.borderColor='#c9a96e';this.style.color='#c9a96e';"
             onmouseout="this.style.borderColor='rgba(255,255,255,0.15)';this.style.color='rgba(255,255,255,0.4)';">
            <i class="fab fa-{{ $social }}"></i>
          </a>
          @endforeach
        </div>
      </div>

      @php
        $footerLinks = [
          'Shop'    => ['New Arrivals', 'Best Sellers', 'Beyond Ordinary', 'Fragrance', 'Accessories'],
          'Help'    => ['Shipping & Returns', 'FAQ', 'Size Guide', 'Contact Us', 'Track Order'],
          'Company' => ['Our Story', 'Press', 'Careers', 'Privacy Policy', 'Terms of Service'],
        ];
      @endphp

      @foreach($footerLinks as $heading => $links)
      <div class="col-6 col-md-2">
        <h5 style="font-size:11px; letter-spacing:0.25em; text-transform:uppercase; color:white; font-weight:600; margin-bottom:1.5rem;">{{ $heading }}</h5>
        <ul class="list-unstyled m-0 d-flex flex-column gap-2">
          @foreach($links as $link)
            <li><a href="#" class="footer-link">{{ $link }}</a></li>
          @endforeach
        </ul>
      </div>
      @endforeach
    </div>

    <div class="d-flex flex-column flex-md-row align-items-center justify-content-between gap-3 pt-4">
      <p style="font-size:12px; color:rgba(255,255,255,0.3); margin:0;">© {{ date('Y') }} DERU. All rights reserved.</p>
      <div class="d-flex gap-3 align-items-center">
        @foreach(['Visa', 'Mastercard', 'PayPal', 'Apple Pay'] as $payment)
          <span style="font-size:11px; color:rgba(255,255,255,0.25); letter-spacing:0.05em;">{{ $payment }}</span>
        @endforeach
      </div>
    </div>
  </div>
</footer>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
  // ── Announcement Rotator ──
  const announcements = document.querySelectorAll('.announcement');
  let current = 0;
  setInterval(() => {
    announcements[current].classList.remove('active');
    current = (current + 1) % announcements.length;
    announcements[current].classList.add('active');
  }, 3500);

  // ── Mobile Menu ──
  const menuToggle  = document.getElementById('menuToggle');
  const menuClose   = document.getElementById('menuClose');
  const mobileMenu  = document.getElementById('mobileMenu');
  const menuOverlay = document.getElementById('menuOverlay');

  function openMenu()  { mobileMenu.classList.add('active'); menuOverlay.classList.add('active'); document.body.style.overflow = 'hidden'; }
  function closeMenu() { mobileMenu.classList.remove('active'); menuOverlay.classList.remove('active'); document.body.style.overflow = ''; }

  menuToggle.addEventListener('click', openMenu);
  menuClose.addEventListener('click', closeMenu);
  menuOverlay.addEventListener('click', closeMenu);

  // ── Sticky Header Shadow ──
  window.addEventListener('scroll', () => {
    document.getElementById('siteHeader').classList.toggle('scrolled', window.scrollY > 20);
  });

  // ── Product Card Hover — show overlay ──
  document.querySelectorAll('.product-card').forEach(card => {
    const overlay = card.querySelector('.product-card-overlay');
    if (!overlay) return;
    card.addEventListener('mouseenter', () => overlay.style.opacity = '1');
    card.addEventListener('mouseleave', () => {
      const addBtn = overlay.querySelector('.product-add-btn');
      if (!addBtn || !addBtn.classList.contains('adding')) overlay.style.opacity = '0';
    });
  });

  // ── Add to Cart (AJAX) ──
  const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

  document.querySelectorAll('.product-add-btn').forEach(btn => {
    btn.addEventListener('click', async function () {
      const originalHTML = this.innerHTML;
      this.classList.add('adding');
      this.innerHTML = '<i class="fas fa-spinner fa-spin" style="font-size:10px;"></i> Adding...';
      this.disabled = true;

      try {
        const response = await fetch('{{ route("cart.add") }}', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json',
          },
          body: JSON.stringify({
            product_id:    this.dataset.productId,
            product_name:  this.dataset.productName,
            product_price: this.dataset.productPrice,
            product_img:   this.dataset.productImg,
          }),
        });

        const data = await response.json();

        if (data.success) {
          // ── Success state ──
          this.innerHTML = '<i class="fas fa-check" style="font-size:10px;"></i> Added!';
          this.style.background = '#0f0f0f';
          this.style.borderColor = '#0f0f0f';
          this.style.color = 'white';

          // Update cart badge
          updateCartBadge(data.cart_count);

          // Show toast notification
          showToast(data.message, data.cart_count);

          // Reset button after 2s
          setTimeout(() => {
            this.innerHTML = originalHTML;
            this.style.background = '';
            this.style.borderColor = '';
            this.style.color = '';
            this.disabled = false;
            this.classList.remove('adding');
          }, 2000);
        }
      } catch (err) {
        this.innerHTML = originalHTML;
        this.disabled = false;
        this.classList.remove('adding');
      }
    });
  });

  // ── Update cart badge in header ──
  function updateCartBadge(count) {
    const badge = document.querySelector('.cart-count-badge');
    if (!badge) return;
    badge.textContent = count;
    badge.style.display = count > 0 ? '' : 'none';

    // Pulse animation
    badge.style.transform = 'scale(1.4)';
    setTimeout(() => badge.style.transform = '', 300);
  }

  // ── Toast notification ──
  function showToast(message, cartCount) {
    // Remove existing toast
    document.querySelector('.deru-toast')?.remove();

    const toast = document.createElement('div');
    toast.className = 'deru-toast';
    toast.innerHTML = `
      <div style="display:flex; align-items:center; gap:12px;">
        <i class="fas fa-check-circle" style="color:#c9a96e; font-size:16px; flex-shrink:0;"></i>
        <div>
          <p style="margin:0; font-size:13px; font-weight:500;">${message}</p>
          <p style="margin:0; font-size:11px; color:rgba(255,255,255,0.55); margin-top:2px;">${cartCount} item${cartCount !== 1 ? 's' : ''} in your bag</p>
        </div>
        <a href="{{ route('cart.index') }}" style="margin-left:auto; font-size:11px; letter-spacing:0.1em; text-transform:uppercase; color:#c9a96e; text-decoration:none; white-space:nowrap; flex-shrink:0;">View Bag →</a>
      </div>
    `;
    toast.style.cssText = `
      position:fixed; bottom:2rem; right:2rem; z-index:9999;
      background:#0f0f0f; color:white; padding:1rem 1.5rem;
      border-left:3px solid #c9a96e; min-width:320px; max-width:400px;
      box-shadow:0 8px 30px rgba(0,0,0,0.2);
      animation: toastIn 0.4s ease forwards;
    `;

    document.body.appendChild(toast);

    // Auto dismiss after 3.5s
    setTimeout(() => {
      toast.style.animation = 'toastOut 0.3s ease forwards';
      setTimeout(() => toast.remove(), 300);
    }, 3500);
  }

  // ── Tabs are now real links (?category=slug) — no JS needed ──

  // ─────────────────────────────────────────────
  // CURRENCY SWITCHER
  // Rates are approximate — payment always converts back to GBP
  // ─────────────────────────────────────────────
  const CURRENCIES = {
    GBP: { symbol: '£',  rate: 1        },
    USD: { symbol: '$',  rate: 1.27     },
    EUR: { symbol: '€',  rate: 1.17     },
    NGN: { symbol: '₦',  rate: 2050     },
    GHS: { symbol: '₵',  rate: 19.5     },
    ZAR: { symbol: 'R',  rate: 23.5     },
    CAD: { symbol: 'C$', rate: 1.73     },
    AUD: { symbol: 'A$', rate: 1.95     },
  };

  function formatCurrency(amountGBP, currency) {
    const { symbol, rate } = CURRENCIES[currency];
    const converted = amountGBP * rate;
    // NGN and ZAR look better without decimals
    const decimals = ['NGN', 'GHS', 'ZAR'].includes(currency) ? 0 : 2;
    return symbol + new Intl.NumberFormat('en-GB', {
      minimumFractionDigits: decimals,
      maximumFractionDigits: decimals,
    }).format(converted);
  }

  function applyPrices(currency) {
    document.querySelectorAll('[data-price-gbp]').forEach(el => {
      const gbp   = parseFloat(el.dataset.priceGbp);
      const span  = el.querySelector('.price-display');
      if (span) span.textContent = formatCurrency(gbp, currency);
    });
  }

  function setCurrency(currency) {
    if (!CURRENCIES[currency]) return;
    localStorage.setItem('deru_currency', currency);
    applyPrices(currency);
    // Sync both selects (desktop header + mobile menu)
    document.querySelectorAll('.currency-select').forEach(s => s.value = currency);
  }

  // ── On page load — restore saved preference ──
  (function initCurrency() {
    const saved = localStorage.getItem('deru_currency') || 'GBP';
    document.querySelectorAll('.currency-select').forEach(s => s.value = saved);
    if (saved !== 'GBP') applyPrices(saved);
  })();
</script>

</body>
</html>