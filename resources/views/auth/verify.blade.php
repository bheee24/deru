<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>DERU | Verify Your Email</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;0,700;1,400&family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body {
      font-family: 'Montserrat', sans-serif;
      background: #f1f0ec; color: #0f0f0f;
      min-height: 100vh; display: flex; flex-direction: column;
    }

    /* ── Header ── */
    .site-header {
      display: flex; align-items: center; justify-content: center;
      padding: 20px 5vw; background: #f1f0ec;
      border-bottom: 1px solid rgba(0,0,0,0.08);
    }

    /* ── Main card ── */
    .verify-wrap {
      flex: 1; display: flex; align-items: center; justify-content: center;
      padding: 3rem 1.5rem;
    }
    .verify-card {
      background: white; border: 1px solid rgba(0,0,0,0.06);
      padding: 3rem 2.5rem; max-width: 520px; width: 100%; text-align: center;
    }

    /* ── Icon circle ── */
    .icon-circle {
      width: 72px; height: 72px; border-radius: 50%;
      background: black; border: 1px solid rgba(201,169,110,0.3);
      display: flex; align-items: center; justify-content: center;
      margin: 0 auto 1.75rem;
    }

    /* ── Buttons ── */
    .btn-deru {
      display: inline-flex; align-items: center; justify-content: center; gap: 8px;
      padding: 13px 32px; background: black; color: white;
      font-family: 'Montserrat', sans-serif; font-size: 11px; font-weight: 700;
      letter-spacing: 0.18em; text-transform: uppercase;
      border: 1px solid white; text-decoration: none; cursor: pointer;
      transition: all 0.25s ease; width: 100%;
    }
    .btn-deru:hover { background: transparent; color: black;border: 1px solid black; }
    .btn-deru:disabled { opacity: 0.6; cursor: not-allowed; }
    .btn-deru-outline {
      display: inline-flex; align-items: center; justify-content: center; gap: 8px;
      padding: 11px 32px; background: transparent; color: #0f0f0f;
      font-family: 'Montserrat', sans-serif; font-size: 11px; font-weight: 600;
      letter-spacing: 0.15em; text-transform: uppercase;
      border: 1px solid rgba(0,0,0,0.2); text-decoration: none; cursor: pointer;
      transition: all 0.25s ease; width: 100%;
    }
    .btn-deru-outline:hover { border-color: #0f0f0f; color: white;background:black }

    /* ── Steps ── */
    .steps { text-align: left; margin: 2rem 0; }
    .step {
      display: flex; align-items: flex-start; gap: 14px;
      padding: 10px 0; border-bottom: 1px solid rgba(0,0,0,0.05);
    }
    .step:last-child { border-bottom: none; }
    .step-num {
      width: 24px; height: 24px; border-radius: 50%; background: #0f0f0f;
      color: white; font-size: 10px; font-weight: 700;
      display: flex; align-items: center; justify-content: center; flex-shrink: 0;
      margin-top: 1px;
    }
  </style>
</head>
<body>

  <header class="site-header">
    <a href="/" style="text-decoration:none;">
      <span style="font-family:'Cormorant Garamond',serif; font-size:2rem; font-weight:700; letter-spacing:0.2em; color:#0f0f0f;">DERU</span>
    </a>
  </header>

  <div class="verify-wrap">
    <div class="verify-card">

      {{-- Icon --}}
      <div class="icon-circle">
        <i class="fas fa-envelope" style="font-size:1.5rem; color:white;"></i>
      </div>

      {{-- Heading --}}
      <p style="font-size:10px; letter-spacing:0.25em; text-transform:uppercase; color:#7a7a72; margin-bottom:8px;">One more step</p>
      <h1 style="font-family:'Cormorant Garamond',serif; font-size:2rem; font-weight:700; line-height:1.1; margin-bottom:1rem;">
        Verify Your Email
      </h1>
      <p style="font-size:13px; color:#5a5a52; line-height:1.8; margin-bottom:0;">
        We've sent a verification link to
        <strong style="color:#0f0f0f;">{{ auth()->user()->email }}</strong>.
        Click the link in that email to activate your account.
      </p>

      {{-- Steps ── --}}
      <div class="steps">
        @php
          $steps = [
            'Check your inbox (and spam folder) for an email from DERU.',
            'Click the "Verify Email Address" button in the email.',
            'You\'ll be redirected back and your account will be active.',
          ];
        @endphp
        @foreach($steps as $i => $step)
        <div class="step">
          <div class="step-num">{{ $i + 1 }}</div>
          <p style="font-size:12px; color:#5a5a52; line-height:1.7; margin:0;">{{ $step }}</p>
        </div>
        @endforeach
      </div>

      {{-- Success flash ── --}}
      @if(session('resent'))
        <div style="background:rgba(201,169,110,0.1); border:1px solid rgba(201,169,110,0.25);
                    color:#b8903a; padding:10px 16px; font-size:12px; margin-bottom:1.25rem;
                    display:flex; align-items:center; gap:8px;">
          <i class="fas fa-check-circle"></i>
          A fresh verification link has been sent to your email.
        </div>
      @endif

      {{-- Resend form ── --}}
      <form method="POST" action="{{ route('verification.resend') }}" style="margin-bottom:12px;">
        @csrf
        <button type="submit" class="btn-deru">
          <i class="fas fa-paper-plane" style="font-size:10px;"></i>
          Resend Verification Email
        </button>
      </form>

      {{-- Sign out ── --}}
      <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="btn-deru-outline">
          Sign Out
        </button>
      </form>

      <p style="font-size:11px; color:#7a7a72; margin-top:1.5rem; line-height:1.7;">
        Wrong email address?
        <a href="{{ route('profile.email') }}" style="color:#7a7a72; text-decoration:none;">Update it in your profile</a>
        after verifying, or sign out and register again.
      </p>

    </div>
  </div>

</body>
</html>