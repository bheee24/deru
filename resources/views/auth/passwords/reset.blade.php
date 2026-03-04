@extends('layouts.app')

@section('content')

<style>
  /* ── Page Reset ── */
  body { background: #0f0f0f !important; }

  /* ── Split Layout ── */
  .reset-wrapper {
    min-height: 100vh;
    display: flex;
  }

  /* ── Left Panel — Visual ── */
  .reset-visual {
    position: relative;
    flex: 0 0 60%;
    display: none;
    overflow: hidden;
    background: #0a0a0a;
  }
  @media (min-width: 992px) {
    .reset-visual { display: block; }
  }
  .reset-visual-img {
    position: absolute; inset: 0;
    width: 100%; height: 100%;
    object-fit: cover;
    opacity: 0.55;
    transition: transform 8s ease;
    animation: slowZoom 12s ease-in-out infinite alternate;
  }
  @keyframes slowZoom {
    from { transform: scale(1); }
    to   { transform: scale(1.06); }
  }
  .reset-visual-overlay {
    position: absolute; inset: 0;
    background: linear-gradient(135deg, rgba(15,15,15,0.5) 0%, rgba(15,15,15,0.2) 50%, rgba(15,15,15,0.7) 100%);
  }
  .reset-visual-content {
    position: absolute; inset: 0;
    display: flex; flex-direction: column;
    justify-content: space-between;
    padding: 3rem;
    color: white;
  }
  .reset-visual-quote {
    max-width: 380px;
    margin-top: auto;
    margin-bottom: 2rem;
  }

  /* ── Right Panel — Form ── */
  .reset-form-panel {
    flex: 0 0 40%;
    width: 40%;
    background: #0f0f0f;
    display: flex;
    flex-direction: column;
    justify-content: center;
    padding: 3rem 3.5rem;
    position: relative;
    overflow: hidden;
  }
  @media (max-width: 991px) {
    .reset-form-panel {
      flex: 0 0 100%;
      width: 100%;
      padding: 2.5rem 1.5rem;
    }
  }

  /* Subtle grain texture on form panel */
  .reset-form-panel::before {
    content: '';
    position: absolute; inset: 0;
    background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noise'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noise)'/%3E%3C/svg%3E");
    opacity: 0.03;
    pointer-events: none;
  }

  .reset-form-inner {
    position: relative; z-index: 1;
    opacity: 0;
    animation: fadeUp 0.7s ease 0.2s forwards;
  }

  /* ── Form Fields ── */
  .deru-label {
    display: block;
    font-family: 'Montserrat', sans-serif;
    font-size: 10px;
    font-weight: 600;
    letter-spacing: 0.25em;
    text-transform: uppercase;
    color: rgba(255,255,255,0.85);
    margin-bottom: 8px;
  }
  .deru-input {
    width: 100%;
    background: rgba(255,255,255,0.04) !important;
    border: 1px solid rgba(255,255,255,0.1) !important;
    border-radius: 0 !important;
    color: white !important;
    font-family: 'Montserrat', sans-serif !important;
    font-size: 14px !important;
    padding: 14px 16px !important;
    transition: border-color 0.25s ease, background 0.25s ease !important;
    outline: none !important;
  }
  .deru-input:focus {
    background: rgba(255,255,255,0.07) !important;
    border-color: #c9a96e !important;
    box-shadow: 0 0 0 0 transparent !important;
    color: white !important;
  }
  .deru-input::placeholder { color: rgba(255,255,255,0.35) !important; }
  .deru-input.is-invalid {
    border-color: #e05c5c !important;
  }
  .deru-input:-webkit-autofill,
  .deru-input:-webkit-autofill:focus {
    -webkit-box-shadow: 0 0 0 1000px #1a1a1a inset !important;
    -webkit-text-fill-color: white !important;
    caret-color: white;
  }

  /* ── Submit Button ── */
  .btn-reset {
    width: 100%;
    background: #c9a96e;
    color: #0f0f0f;
    border: 1px solid #c9a96e;
    font-family: 'Montserrat', sans-serif;
    font-size: 11px;
    font-weight: 700;
    letter-spacing: 0.25em;
    text-transform: uppercase;
    padding: 16px;
    cursor: pointer;
    transition: all 0.3s ease;
    border-radius: 0;
    position: relative;
    overflow: hidden;
  }
  .btn-reset::after {
    content: '';
    position: absolute; inset: 0;
    background: rgba(255,255,255,0.15);
    transform: translateX(-100%);
    transition: transform 0.4s ease;
  }
  .btn-reset:hover::after { transform: translateX(0); }
  .btn-reset:hover { background: #d4b87e; border-color: #d4b87e; }
  .btn-reset:active { transform: scale(0.99); }

  /* ── Divider ── */
  .deru-divider {
    display: flex; align-items: center; gap: 1rem;
    color: rgba(255,255,255,0.55);
    font-size: 11px; letter-spacing: 0.15em; text-transform: uppercase;
  }
  .deru-divider::before,
  .deru-divider::after {
    content: ''; flex: 1;
    height: 1px; background: rgba(255,255,255,0.18);
  }

  /* ── Password Toggle ── */
  .input-wrapper { position: relative; }
  .password-toggle {
    position: absolute; right: 14px; top: 50%; transform: translateY(-50%);
    background: none; border: none; cursor: pointer;
    color: rgba(255,255,255,0.45); padding: 0;
    transition: color 0.2s ease;
  }
  .password-toggle:hover { color: #c9a96e; }

  /* ── Error feedback ── */
  .deru-error {
    font-size: 11px;
    color: #e05c5c;
    letter-spacing: 0.05em;
    margin-top: 6px;
    display: flex; align-items: center; gap: 5px;
  }

  /* ── Animations ── */
  @keyframes fadeUp {
    from { opacity: 0; transform: translateY(20px); }
    to   { opacity: 1; transform: translateY(0); }
  }
</style>

<div class="reset-wrapper">

  {{-- ── LEFT: VISUAL PANEL ── --}}
  <div class="reset-visual">
    <img
      src="https://images.unsplash.com/photo-1588850561407-ed78c282e89b?w=1200&q=85"
      alt="DERU Collection"
      class="reset-visual-img"
    >
    <div class="reset-visual-overlay"></div>
    <div class="reset-visual-content">

      {{-- Logo --}}
      <a href="/" style="text-decoration:none;">
        <span style="font-family:'Cormorant Garamond',serif; font-size:1.8rem; font-weight:700; letter-spacing:0.25em; color:white;">DERU</span>
      </a>

      {{-- Quote --}}
      <div class="reset-visual-quote">
        <p style="font-family:'Cormorant Garamond',serif; font-size:2.2rem; font-weight:400; font-style:italic; color:white; line-height:1.3; margin-bottom:1.5rem;">
          "A fresh start — for your password and your wardrobe."
        </p>
        <div style="width:40px; height:1px; background:#c9a96e;"></div>
      </div>

    </div>
  </div>

  {{-- ── RIGHT: FORM PANEL ── --}}
  <div class="reset-form-panel">
    <div class="reset-form-inner">

      {{-- Mobile logo --}}
      <div class="d-lg-none text-center mb-5">
        <a href="/" style="text-decoration:none;">
          <span style="font-family:'Cormorant Garamond',serif; font-size:2rem; font-weight:700; letter-spacing:0.25em; color:white;">DERU</span>
        </a>
      </div>

      {{-- Heading --}}
      <div class="mb-5">
        <p style="font-size:10px; letter-spacing:0.35em; text-transform:uppercase; color:#c9a96e; margin-bottom:0.75rem;">Account Recovery</p>
        <h1 style="font-family:'Cormorant Garamond',serif; font-size:2.8rem; font-weight:700; color:white; line-height:1; margin:0;">
          Reset Password
        </h1>
      </div>

      {{-- ── THE FORM — Laravel auth logic fully preserved ── --}}
      <form method="POST" action="{{ route('password.update') }}">
        @csrf

        {{-- Hidden token — required by Laravel ── --}}
        <input type="hidden" name="token" value="{{ $token }}">

        {{-- Email --}}
        <div class="mb-4" style="animation: fadeUp 0.6s ease 0.3s both;">
          <label for="email" class="deru-label">Email Address</label>
          <input
            id="email"
            type="email"
            name="email"
            value="{{ $email ?? old('email') }}"
            required
            autocomplete="email"
            autofocus
            placeholder="your@email.com"
            class="deru-input @error('email') is-invalid @enderror"
          >
          @error('email')
            <div class="deru-error">
              <i class="fas fa-exclamation-circle" style="font-size:10px;"></i>
              <strong>{{ $message }}</strong>
            </div>
          @enderror
        </div>

        {{-- New Password --}}
        <div class="mb-4" style="animation: fadeUp 0.6s ease 0.4s both;">
          <label for="password" class="deru-label">New Password</label>
          <div class="input-wrapper">
            <input
              id="password"
              type="password"
              name="password"
              required
              autocomplete="new-password"
              placeholder="••••••••"
              class="deru-input @error('password') is-invalid @enderror"
            >
            <button type="button" class="password-toggle" id="togglePassword" tabindex="-1" aria-label="Toggle password visibility">
              <i class="fas fa-eye" id="toggleIcon" style="font-size:13px;"></i>
            </button>
          </div>
          @error('password')
            <div class="deru-error">
              <i class="fas fa-exclamation-circle" style="font-size:10px;"></i>
              <strong>{{ $message }}</strong>
            </div>
          @enderror
        </div>

        {{-- Confirm Password --}}
        <div class="mb-4" style="animation: fadeUp 0.6s ease 0.5s both;">
          <label for="password-confirm" class="deru-label">Confirm Password</label>
          <div class="input-wrapper">
            <input
              id="password-confirm"
              type="password"
              name="password_confirmation"
              required
              autocomplete="new-password"
              placeholder="••••••••"
              class="deru-input"
            >
            <button type="button" class="password-toggle" id="togglePasswordConfirm" tabindex="-1" aria-label="Toggle confirm password visibility">
              <i class="fas fa-eye" id="toggleIconConfirm" style="font-size:13px;"></i>
            </button>
          </div>
        </div>

        {{-- Submit --}}
        <div style="animation: fadeUp 0.6s ease 0.6s both; margin-top: 20px;">
          <button type="submit" class="btn-reset">
            Reset Password &nbsp;→
          </button>
        </div>

      </form>
      {{-- ── END FORM ── --}}

      {{-- Divider --}}
      <div class="deru-divider my-5">or</div>

      {{-- Back to login --}}
      <div class="text-center" style="animation: fadeUp 0.6s ease 0.7s both;">
        <p style="font-size:13px; color:rgba(255,255,255,0.65); margin-bottom:1rem;">Remember your password?</p>
        @if (Route::has('login'))
          <a href="{{ route('login') }}"
             style="display:inline-flex; align-items:center; gap:8px; font-size:11px; font-weight:600; letter-spacing:0.2em; text-transform:uppercase; color:white; text-decoration:none; border:1px solid rgba(255,255,255,0.15); padding:12px 32px; transition:all 0.3s ease;"
             onmouseover="this.style.borderColor='#c9a96e'; this.style.color='#c9a96e';"
             onmouseout="this.style.borderColor='rgba(255,255,255,0.15)'; this.style.color='white';">
            Sign In
          </a>
        @endif
      </div>

      {{-- Back to store --}}
      <div class="text-center mt-4">
        <a href="/"
           style="font-size:11px; color:rgba(255,255,255,0.45); text-decoration:none; letter-spacing:0.1em; transition:color 0.2s ease;"
           onmouseover="this.style.color='rgba(255,255,255,0.8)'"
           onmouseout="this.style.color='rgba(255,255,255,0.45)'">
          ← Back to Store
        </a>
      </div>

    </div>
  </div>

</div>

<script>
  // ── Password visibility toggle ──
  const toggleBtn = document.getElementById('togglePassword');
  const passwordInput = document.getElementById('password');
  const toggleIcon = document.getElementById('toggleIcon');

  if (toggleBtn) {
    toggleBtn.addEventListener('click', () => {
      const isPassword = passwordInput.type === 'password';
      passwordInput.type = isPassword ? 'text' : 'password';
      toggleIcon.classList.toggle('fa-eye',       !isPassword);
      toggleIcon.classList.toggle('fa-eye-slash',  isPassword);
    });
  }

  // ── Confirm Password visibility toggle ──
  const toggleBtnConfirm = document.getElementById('togglePasswordConfirm');
  const passwordConfirmInput = document.getElementById('password-confirm');
  const toggleIconConfirm = document.getElementById('toggleIconConfirm');

  if (toggleBtnConfirm) {
    toggleBtnConfirm.addEventListener('click', () => {
      const isPassword = passwordConfirmInput.type === 'password';
      passwordConfirmInput.type = isPassword ? 'text' : 'password';
      toggleIconConfirm.classList.toggle('fa-eye',       !isPassword);
      toggleIconConfirm.classList.toggle('fa-eye-slash',  isPassword);
    });
  }
</script>

@endsection