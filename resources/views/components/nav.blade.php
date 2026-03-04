{{--
  ── DERU Auth Nav Component ──
  Usage: @include('components.auth-nav')

  Shows:
  - If logged in as admin  → name + link to Admin Panel
  - If logged in as user   → name + link to /home
  - If guest               → Login link

  Optional props:
  - $dark = true  → white text (for dark backgrounds)
  - $dark = false → dark text (default, for light backgrounds)
--}}

@php $dark = $dark ?? false; $color = $dark ? 'white' : '#0f0f0f'; @endphp

@auth
  <div class="d-flex align-items-center gap-3">
    <a href="{{ auth()->user()->isAdmin() ? route('admin.dashboard') : url('/home') }}"
       style="color:{{ $color }}; text-decoration:none; font-size:12px; letter-spacing:0.08em; text-transform:uppercase; font-weight:500; display:flex; align-items:center; gap:7px; transition:opacity 0.2s ease;"
       onmouseover="this.style.opacity='0.7'"
       onmouseout="this.style.opacity='1'">
      <i class="fas fa-user" style="font-size:12px;"></i>
      <span>{{ auth()->user()->isAdmin() ? 'Admin Panel' : auth()->user()->name }}</span>
    </a>
    <form method="POST" action="{{ route('logout') }}" style="margin:0;">
      @csrf
      <button type="submit"
              style="background:none; border:none; cursor:pointer; font-family:'Montserrat',sans-serif; font-size:12px; letter-spacing:0.08em; text-transform:uppercase; font-weight:500; color:{{ $dark ? 'rgba(255,255,255,0.4)' : 'rgba(0,0,0,0.35)' }}; padding:0; transition:color 0.2s ease;"
              onmouseover="this.style.color='{{ $dark ? 'white' : '#0f0f0f' }}'"
              onmouseout="this.style.color='{{ $dark ? 'rgba(255,255,255,0.4)' : 'rgba(0,0,0,0.35)' }}'">
        Sign Out
      </button>
    </form>
  </div>
@else
  <a href="{{ route('login') }}"
     style="color:{{ $color }}; text-decoration:none; font-size:12px; letter-spacing:0.1em; text-transform:uppercase; font-weight:500; transition:opacity 0.2s ease;"
     onmouseover="this.style.opacity='0.7'"
     onmouseout="this.style.opacity='1'">
    Login
  </a>
@endauth