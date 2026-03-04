@extends('layouts.app')

@section('content')

<style>
  /* ── Page Reset ── */
  body { background: #0f0f0f !important; }

  /* ── Split Layout ── */
  .login-wrapper {
    min-height: 100vh;
    display: flex;
  }

  /* ── Left Panel — Visual ── */
  .login-visual {
    position: relative;
    flex: 0 0 60%;
    display: none;
    overflow: hidden;
    background: #0a0a0a;
  }
  @media (min-width: 992px) {
    .login-visual { display: block; }
  }
  .login-visual-img {
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
  .login-visual-overlay {
    position: absolute; inset: 0;
    background: linear-gradient(135deg, rgba(15,15,15,0.5) 0%, rgba(15,15,15,0.2) 50%, rgba(15,15,15,0.7) 100%);
  }
  .login-visual-content {
    position: absolute; inset: 0;
    display: flex; flex-direction: column;
    justify-content: space-between;
    padding: 3rem;
    color: white;
  }
  .login-visual-quote {
    max-width: 380px;
    margin-top: auto;
    margin-bottom: 2rem;
  }

  /* ── Right Panel — Form ── */
  .login-form-panel {
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
    .login-form-panel {
      flex: 0 0 100%;
      width: 100%;
      padding: 2.5rem 1.5rem;
    }
  }

  /* Subtle grain texture on form panel */
  .login-form-panel::before {
    content: '';
    position: absolute; inset: 0;
    background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noise'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noise)'/%3E%3C/svg%3E");
    opacity: 0.03;
    pointer-events: none;
  }

  .login-form-inner {
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
  .btn-login {
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
  .btn-login::after {
    content: '';
    position: absolute; inset: 0;
    background: rgba(255,255,255,0.15);
    transform: translateX(-100%);
    transition: transform 0.4s ease;
  }
  .btn-login:hover::after { transform: translateX(0); }
  .btn-login:hover { background: #d4b87e; border-color: #d4b87e; }
  .btn-login:active { transform: scale(0.99); }

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

  /* ── Checkbox ── */
  .deru-check-input {
    appearance: none;
    width: 16px; height: 16px;
    border: 1px solid rgba(255,255,255,0.2);
    background: transparent;
    cursor: pointer;
    position: relative;
    flex-shrink: 0;
    transition: border-color 0.2s ease;
    border-radius: 0;
  }
  .deru-check-input:checked {
    background: #c9a96e;
    border-color: #c9a96e;
  }
  .deru-check-input:checked::after {
    content: '✓';
    position: absolute; top: 50%; left: 50%;
    transform: translate(-50%, -50%);
    font-size: 10px; color: #0f0f0f; font-weight: 700;
  }
  .deru-check-label {
    font-size: 12px;
    color: rgba(255,255,255,0.7);
    letter-spacing: 0.04em;
    cursor: pointer;
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

<div class="login-wrapper">

  {{-- ── LEFT: VISUAL PANEL ── --}}
  <div class="login-visual">
    <img
      src="https://images.unsplash.com/photo-1521369909029-2afed882baee?w=1200&q=85"
      alt="DERU Collection"
      class="login-visual-img"
    >
    <div class="login-visual-overlay"></div>
    <div class="login-visual-content">

      {{-- Logo --}}
      <a href="/" style="text-decoration:none;">
        <span style="font-family:'Cormorant Garamond',serif; font-size:1.8rem; font-weight:700; letter-spacing:0.25em; color:white;">DERU</span>
      </a>

      {{-- Quote --}}
      <div class="login-visual-quote">
        <p style="font-family:'Cormorant Garamond',serif; font-size:2.2rem; font-weight:400; font-style:italic; color:white; line-height:1.3; margin-bottom:1.5rem;">
          "Crafted for those who live beyond ordinary."
        </p>
        <div style="width:40px; height:1px; background:#c9a96e;"></div>
      </div>

    </div>
  </div>

  {{-- ── RIGHT: FORM PANEL ── --}}
  <div class="login-form-panel">
    <div class="login-form-inner">

      {{-- Mobile logo --}}
      <div class="d-lg-none text-center mb-5">
        <a href="/" style="text-decoration:none;">
          <span style="font-family:'Cormorant Garamond',serif; font-size:2rem; font-weight:700; letter-spacing:0.25em; color:white;">DERU</span>
        </a>
      </div>

      {{-- Heading --}}
      <div class="mb-5">
        <p style="font-size:10px; letter-spacing:0.35em; text-transform:uppercase; color:#c9a96e; margin-bottom:0.75rem;">Welcome Back</p>
        <h1 style="font-family:'Cormorant Garamond',serif; font-size:2.8rem; font-weight:700; color:white; line-height:1; margin:0;">
          Sign In
        </h1>
      </div>

      {{-- ── THE FORM — Laravel auth logic fully preserved ── --}}
      <form method="POST" action="{{ route('login') }}">
        @csrf

        {{-- Email --}}
        <div class="mb-4 mt-4" style="animation: fadeUp 0.6s ease 0.3s both; margin-top: 30px;">
          <label for="email" class="deru-label">Email Address</label>
          <input
            id="email"
            type="email"
            name="email"
            value="{{ old('email') }}"
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

        {{-- Password --}}
        <div class="mb-4" style="animation: fadeUp 0.6s ease 0.4s both; margin-top: 30px;">
          <label for="password" class="deru-label mb-0">Password</label>
          <div class="input-wrapper">
            <input
              id="password"
              type="password"
              name="password"
              required
              autocomplete="current-password"
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

        {{-- Remember Me --}}
        <div class="d-flex align-items-center gap-2 mb-4" style="animation: fadeUp 0.6s ease 0.5s both; margin-top: 20px">
          <input
            type="checkbox"
            name="remember"
            id="remember"
            class="deru-check-input"
            {{ old('remember') ? 'checked' : '' }}
          >
          <label for="remember" class="deru-check-label">Keep me signed in</label>
        </div>

        {{-- Submit --}}
        <div style="animation: fadeUp 0.6s ease 0.6s both; margin-top: 20px;">
          <button type="submit" class="btn-login">
            Sign In &nbsp;→
          </button>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-2 mt-2">
          @if (Route::has('password.request'))
            <a href="{{ route('password.request') }}"
               style="font-size:11px; color:rgba(255,255,255,0.55); text-decoration:none; letter-spacing:0.05em; transition:color 0.2s ease;"
               onmouseover="this.style.color='#c9a96e'"
               onmouseout="this.style.color='rgba(255,255,255,0.55)'">
              Forgot password?
            </a>
          @endif
        </div>

      </form>
      {{-- ── END FORM ── --}}

      {{-- Divider --}}
      <div class="deru-divider my-5">or</div>

      {{-- Register CTA --}}
      <div class="text-center" style="animation: fadeUp 0.6s ease 0.7s both;">
        <p style="font-size:13px; color:rgba(255,255,255,0.65); margin-bottom:1rem;">Don't have an account?</p>
        @if (Route::has('register'))
          <a href="{{ route('register') }}"
             style="display:inline-flex; align-items:center; gap:8px; font-size:11px; font-weight:600; letter-spacing:0.2em; text-transform:uppercase; color:white; text-decoration:none; border:1px solid rgba(255,255,255,0.15); padding:12px 32px; transition:all 0.3s ease;"
             onmouseover="this.style.borderColor='#c9a96e'; this.style.color='#c9a96e';"
             onmouseout="this.style.borderColor='rgba(255,255,255,0.15)'; this.style.color='white';">
            Create Account
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
  const toggleBtn  = document.getElementById('togglePassword');
  const passwordInput = document.getElementById('password');
  const toggleIcon = document.getElementById('toggleIcon');

  if (toggleBtn) {
    toggleBtn.addEventListener('click', () => {
      const isPassword = passwordInput.type === 'password';
      passwordInput.type = isPassword ? 'text' : 'password';
      toggleIcon.classList.toggle('fa-eye',      !isPassword);
      toggleIcon.classList.toggle('fa-eye-slash', isPassword);
    });
  }
</script>

@endsection