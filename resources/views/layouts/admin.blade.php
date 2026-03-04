<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>DERU Admin — @yield('page_title', 'Dashboard')</title>

  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;0,700;1,400&family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">

  <!-- Tailwind CDN -->
  <script src="https://cdn.tailwindcss.com"></script>

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

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
    body { font-family: 'Montserrat', sans-serif; background: #f1f0ec; color: #0f0f0f; }

    /* ── Sidebar ── */
    .admin-sidebar {
      position: fixed; top: 0; left: 0; bottom: 0;
      width: 260px; background: #0f0f0f;
      display: flex; flex-direction: column;
      z-index: 100; transition: transform 0.3s ease;
    }
    .sidebar-logo {
      padding: 2rem 1.8rem 1.5rem;
      border-bottom: 1px solid rgba(255,255,255,0.06);
    }
    .sidebar-nav { flex: 1; padding: 1.5rem 0; overflow-y: auto; }
    .nav-section-label {
      font-size: 9px; letter-spacing: 0.3em; text-transform: uppercase;
      color: rgba(255,255,255,0.25); padding: 0 1.8rem;
      margin: 1.5rem 0 0.5rem;
    }
    .nav-item {
      display: flex; align-items: center; gap: 12px;
      padding: 11px 1.8rem; font-size: 12px; font-weight: 500;
      letter-spacing: 0.05em; color: rgba(255,255,255,0.45);
      text-decoration: none; transition: all 0.2s ease;
      border-left: 2px solid transparent;
      position: relative;
    }
    .nav-item:hover {
      color: white; background: rgba(255,255,255,0.04);
      border-left-color: rgba(201,169,110,0.4);
    }
    .nav-item.active {
      color: #c9a96e; background: rgba(201,169,110,0.08);
      border-left-color: #c9a96e;
    }
    .nav-item i { width: 16px; text-align: center; font-size: 13px; }
    .nav-badge {
      margin-left: auto; background: #c9a96e; color: #0f0f0f;
      font-size: 9px; font-weight: 700; padding: 2px 7px;
      border-radius: 9999px;
    }
    .sidebar-footer {
      padding: 1.5rem 1.8rem;
      border-top: 1px solid rgba(255,255,255,0.06);
    }

    /* ── Main Content ── */
    .admin-main {
      margin-left: 260px;
      min-height: 100vh;
      display: flex; flex-direction: column;
    }

    /* ── Topbar ── */
    .admin-topbar {
      background: white; padding: 0 2.5rem;
      height: 64px; display: flex; align-items: center;
      justify-content: space-between;
      border-bottom: 1px solid rgba(0,0,0,0.06);
      position: sticky; top: 0; z-index: 50;
    }

    /* ── Page Content ── */
    .admin-content { padding: 2.5rem; flex: 1; }

    /* ── Cards ── */
    .stat-card {
      background: white; padding: 1.8rem;
      border: 1px solid rgba(0,0,0,0.06);
      transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .stat-card:hover {
      transform: translateY(-3px);
      box-shadow: 0 8px 30px rgba(0,0,0,0.08);
    }
    .stat-icon {
      width: 44px; height: 44px;
      display: flex; align-items: center; justify-content: center;
      background: rgba(201,169,110,0.1); color: #c9a96e;
      font-size: 16px; margin-bottom: 1rem;
    }

    /* ── Tables ── */
    .deru-table { width: 100%; border-collapse: collapse; }
    .deru-table th {
      font-size: 9px; letter-spacing: 0.2em; text-transform: uppercase;
      color: #7a7a72; font-weight: 600; padding: 12px 16px;
      border-bottom: 1px solid rgba(0,0,0,0.08); text-align: left;
      background: #f9f8f6;
    }
    .deru-table td {
      padding: 14px 16px; font-size: 13px;
      border-bottom: 1px solid rgba(0,0,0,0.05);
      vertical-align: middle;
    }
    .deru-table tr:hover td { background: #faf9f7; }
    .deru-table tr:last-child td { border-bottom: none; }

    /* ── Badges ── */
    .badge-active {
      background: rgba(34,197,94,0.1); color: #16a34a;
      font-size: 10px; font-weight: 600; letter-spacing: 0.05em;
      padding: 3px 10px; border-radius: 9999px;
    }
    .badge-disabled {
      background: rgba(239,68,68,0.1); color: #dc2626;
      font-size: 10px; font-weight: 600; letter-spacing: 0.05em;
      padding: 3px 10px; border-radius: 9999px;
    }
    .badge-user {
      background: rgba(201,169,110,0.1); color: #c9a96e;
      font-size: 10px; font-weight: 600; letter-spacing: 0.05em;
      padding: 3px 10px; border-radius: 9999px;
    }

    /* ── Buttons ── */
    .btn-deru {
      display: inline-flex; align-items: center; gap: 7px;
      background: #0f0f0f; color: white; border: 1px solid #0f0f0f;
      font-family: 'Montserrat', sans-serif; font-size: 11px;
      font-weight: 600; letter-spacing: 0.15em; text-transform: uppercase;
      padding: 10px 20px; cursor: pointer; text-decoration: none;
      transition: all 0.25s ease; border-radius: 0;
    }
    .btn-deru:hover { background: #c9a96e; border-color: #c9a96e; color: #0f0f0f; }
    .btn-deru-sm {
      font-size: 10px; padding: 7px 14px;
    }
    .btn-deru-outline {
      background: transparent; color: #0f0f0f; border-color: rgba(0,0,0,0.2);
    }
    .btn-deru-outline:hover { background: #0f0f0f; color: white; border-color: #0f0f0f; }
    .btn-danger {
      background: transparent; color: #dc2626; border: 1px solid rgba(220,38,38,0.3);
      font-family: 'Montserrat', sans-serif; font-size: 10px; font-weight: 600;
      letter-spacing: 0.1em; text-transform: uppercase; padding: 7px 14px;
      cursor: pointer; text-decoration: none; transition: all 0.25s ease;
    }
    .btn-danger:hover { background: #dc2626; color: white; border-color: #dc2626; }

    /* ── Form inputs ── */
    .deru-input-admin {
      width: 100%; background: #f9f8f6;
      border: 1px solid rgba(0,0,0,0.1); border-radius: 0;
      font-family: 'Montserrat', sans-serif; font-size: 13px;
      padding: 11px 14px; color: #0f0f0f; transition: border-color 0.2s ease;
      outline: none;
    }
    .deru-input-admin:focus { border-color: #c9a96e; background: white; }
    .deru-label-admin {
      display: block; font-size: 10px; font-weight: 600;
      letter-spacing: 0.2em; text-transform: uppercase;
      color: #7a7a72; margin-bottom: 7px;
    }

    /* ── Mobile sidebar toggle ── */
    .sidebar-toggle { display: none; }
    @media (max-width: 991px) {
      .admin-sidebar { transform: translateX(-100%); }
      .admin-sidebar.open { transform: translateX(0); }
      .admin-main { margin-left: 0; }
      .sidebar-toggle { display: block; }
    }

    /* ── Animations ── */
    @keyframes fadeUp {
      from { opacity: 0; transform: translateY(16px); }
      to   { opacity: 1; transform: translateY(0); }
    }
    .fade-up { opacity: 0; animation: fadeUp 0.5s ease forwards; }
    .delay-1 { animation-delay: 0.1s; }
    .delay-2 { animation-delay: 0.2s; }
    .delay-3 { animation-delay: 0.3s; }
    .delay-4 { animation-delay: 0.4s; }
  </style>

  @stack('styles')
</head>
<body>

{{-- ── SIDEBAR ── --}}
<aside class="admin-sidebar" id="adminSidebar">

  {{-- Logo --}}
  <div class="sidebar-logo">
    <a href="{{ route('admin.dashboard') }}" style="text-decoration:none;">
      <span style="font-family:'Cormorant Garamond',serif; font-size:1.6rem; font-weight:700; letter-spacing:0.25em; color:white;">DERU</span>
      <span style="display:block; font-size:9px; letter-spacing:0.3em; text-transform:uppercase; color:rgba(255,255,255,0.25); margin-top:2px;">Admin Panel</span>
    </a>
  </div>

  {{-- Navigation --}}
  <nav class="sidebar-nav">
    <span class="nav-section-label">Overview</span>
    <a href="{{ route('admin.dashboard') }}" class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
      <i class="fas fa-chart-line"></i> Dashboard
    </a>

    <span class="nav-section-label">Catalogue</span>
    <a href="{{ route('admin.products.index') }}" class="nav-item {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
      <i class="fas fa-box"></i> Manage Products
    </a>
    <a href="{{ route('admin.categories.index') }}" class="nav-item {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
      <i class="fas fa-tags"></i> Categories
    </a>

    <span class="nav-section-label">People</span>
    <a href="{{ route('admin.customers.index') }}" class="nav-item {{ request()->routeIs('admin.customers.*') ? 'active' : '' }}">
      <i class="fas fa-users"></i> Customers
    </a>

    <span class="nav-section-label">Store</span>
    <a href="/" target="_blank" class="nav-item">
      <i class="fas fa-store"></i> View Store
      <i class="fas fa-external-link-alt" style="font-size:9px; margin-left:auto; opacity:0.4;"></i>
    </a>
  </nav>

  {{-- Footer --}}
  <div class="sidebar-footer">
    <div style="display:flex; align-items:center; gap:10px; margin-bottom:1rem;">
      <div style="width:32px; height:32px; background:rgba(201,169,110,0.15); display:flex; align-items:center; justify-content:center; flex-shrink:0;">
        <i class="fas fa-user" style="font-size:12px; color:#c9a96e;"></i>
      </div>
      <div style="min-width:0;">
        <p style="font-size:12px; font-weight:600; color:white; margin:0; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ auth()->user()->name }}</p>
        <p style="font-size:10px; color:rgba(255,255,255,0.3); margin:0; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ auth()->user()->email }}</p>
      </div>
    </div>
    <form method="POST" action="{{ route('logout') }}">
      @csrf
      <button type="submit" style="width:100%; background:rgba(255,255,255,0.05); border:1px solid rgba(255,255,255,0.08); color:rgba(255,255,255,0.45); font-family:'Montserrat',sans-serif; font-size:10px; letter-spacing:0.15em; text-transform:uppercase; padding:9px; cursor:pointer; transition:all 0.2s ease; display:flex; align-items:center; justify-content:center; gap:8px;"
              onmouseover="this.style.background='rgba(220,38,38,0.1)';this.style.borderColor='rgba(220,38,38,0.3)';this.style.color='#dc2626';"
              onmouseout="this.style.background='rgba(255,255,255,0.05)';this.style.borderColor='rgba(255,255,255,0.08)';this.style.color='rgba(255,255,255,0.45)';">
        <i class="fas fa-sign-out-alt"></i> Sign Out
      </button>
    </form>
  </div>

</aside>

{{-- ── MAIN ── --}}
<div class="admin-main">

  {{-- Topbar --}}
  <header class="admin-topbar">
    <div style="display:flex; align-items:center; gap:1rem;">
      <button class="sidebar-toggle" id="sidebarToggle" style="background:none; border:none; font-size:1.2rem; cursor:pointer; color:#0f0f0f;">
        <i class="fas fa-bars"></i>
      </button>
      <div>
        <p style="font-size:10px; letter-spacing:0.2em; text-transform:uppercase; color:#7a7a72; margin:0;">@yield('page_eyebrow', 'Admin')</p>
        <h1 style="font-family:'Cormorant Garamond',serif; font-size:1.5rem; font-weight:700; margin:0; line-height:1.2;">@yield('page_title', 'Dashboard')</h1>
      </div>
    </div>
    <div style="display:flex; align-items:center; gap:1rem;">
      <span style="font-size:11px; color:#7a7a72; letter-spacing:0.05em;">{{ now()->format('D, d M Y') }}</span>
      <a href="/" target="_blank" class="btn-deru btn-deru-sm btn-deru-outline" style="text-decoration:none;">
        <i class="fas fa-store" style="font-size:10px;"></i> Store
      </a>
    </div>
  </header>

  {{-- Page Content --}}
  <main class="admin-content">

    {{-- Flash messages --}}
    @if(session('success'))
      <div style="background:rgba(34,197,94,0.08); border:1px solid rgba(34,197,94,0.2); color:#16a34a; padding:12px 16px; font-size:12px; letter-spacing:0.04em; margin-bottom:1.5rem; display:flex; align-items:center; gap:10px;">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
      </div>
    @endif
    @if(session('error'))
      <div style="background:rgba(220,38,38,0.08); border:1px solid rgba(220,38,38,0.2); color:#dc2626; padding:12px 16px; font-size:12px; letter-spacing:0.04em; margin-bottom:1.5rem; display:flex; align-items:center; gap:10px;">
        <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
      </div>
    @endif

    @yield('content')
  </main>

</div>

{{-- Mobile overlay --}}
<div id="sidebarOverlay" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.5); z-index:99;"
     onclick="closeSidebar()"></div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
  const sidebar = document.getElementById('adminSidebar');
  const overlay = document.getElementById('sidebarOverlay');

  function openSidebar()  { sidebar.classList.add('open'); overlay.style.display = 'block'; }
  function closeSidebar() { sidebar.classList.remove('open'); overlay.style.display = 'none'; }

  document.getElementById('sidebarToggle')?.addEventListener('click', openSidebar);
</script>

@stack('scripts')
</body>
</html>