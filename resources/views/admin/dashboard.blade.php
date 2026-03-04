@extends('layouts.admin')

@section('page_eyebrow', 'Overview')
@section('page_title', 'Dashboard')

@section('content')

{{-- ── Stat Cards ── --}}
<div class="row g-3 mb-4">

  <div class="col-6 col-lg-3">
    <div class="stat-card fade-up delay-1">
      <div class="stat-icon"><i class="fas fa-box"></i></div>
      <p style="font-size:11px; letter-spacing:0.15em; text-transform:uppercase; color:#7a7a72; margin-bottom:4px;">Products</p>
      <h2 style="font-family:'Cormorant Garamond',serif; font-size:2.5rem; font-weight:700; line-height:1; margin:0;">{{ $totalProducts }}</h2>
      <p style="font-size:11px; color:#7a7a72; margin-top:6px; margin-bottom:0;">Total in catalogue</p>
    </div>
  </div>

  <div class="col-6 col-lg-3">
    <div class="stat-card fade-up delay-2">
      <div class="stat-icon"><i class="fas fa-users"></i></div>
      <p style="font-size:11px; letter-spacing:0.15em; text-transform:uppercase; color:#7a7a72; margin-bottom:4px;">Customers</p>
      <h2 style="font-family:'Cormorant Garamond',serif; font-size:2.5rem; font-weight:700; line-height:1; margin:0;">{{ $totalCustomers }}</h2>
      <p style="font-size:11px; color:#7a7a72; margin-top:6px; margin-bottom:0;">Registered accounts</p>
    </div>
  </div>

  <div class="col-6 col-lg-3">
    <div class="stat-card fade-up delay-3">
      <div class="stat-icon"><i class="fas fa-shopping-bag"></i></div>
      <p style="font-size:11px; letter-spacing:0.15em; text-transform:uppercase; color:#7a7a72; margin-bottom:4px;">Orders</p>
      <h2 style="font-family:'Cormorant Garamond',serif; font-size:2.5rem; font-weight:700; line-height:1; margin:0;">{{ $totalOrders }}</h2>
      <p style="font-size:11px; color:#7a7a72; margin-top:6px; margin-bottom:0;">Total orders placed</p>
    </div>
  </div>

  <div class="col-6 col-lg-3">
    <div class="stat-card fade-up delay-4">
      <div class="stat-icon"><i class="fas fa-pound-sign"></i></div>
      <p style="font-size:11px; letter-spacing:0.15em; text-transform:uppercase; color:#7a7a72; margin-bottom:4px;">Revenue</p>
      <h2 style="font-family:'Cormorant Garamond',serif; font-size:2.5rem; font-weight:700; line-height:1; margin:0;">£{{ number_format($totalRevenue, 2) }}</h2>
      <p style="font-size:11px; color:#7a7a72; margin-top:6px; margin-bottom:0;">Total revenue</p>
    </div>
  </div>

</div>

{{-- ── Recent Orders + Recent Customers ── --}}
<div class="row g-3">

  {{-- Recent Orders --}}
  <div class="col-12 col-lg-7 fade-up delay-2">
    <div style="background:white; border:1px solid rgba(0,0,0,0.06);">
      <div style="padding:1.3rem 1.5rem; border-bottom:1px solid rgba(0,0,0,0.06); display:flex; align-items:center; justify-content:space-between;">
        <h3 style="font-family:'Cormorant Garamond',serif; font-size:1.3rem; font-weight:700; margin:0;">Recent Orders</h3>
        <span style="font-size:10px; letter-spacing:0.15em; text-transform:uppercase; color:#7a7a72;">Last 5</span>
      </div>
      <div style="overflow-x:auto;">
        <table class="deru-table">
          <thead>
            <tr>
              <th>Customer</th>
              <th>Amount</th>
              <th>Date</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            @forelse($recentOrders as $order)
            <tr>
              <td>
                <p style="font-weight:500; margin:0; font-size:13px;">{{ $order->user->name ?? 'Guest' }}</p>
                <p style="font-size:11px; color:#7a7a72; margin:0;">{{ $order->user->email ?? '' }}</p>
              </td>
              <td style="font-weight:600;">£{{ number_format($order->total, 2) }}</td>
              <td style="color:#7a7a72; font-size:12px;">{{ $order->created_at->format('d M Y') }}</td>
              <td><span class="badge-active">Paid</span></td>
            </tr>
            @empty
            <tr>
              <td colspan="4" style="text-align:center; color:#7a7a72; padding:2rem;">No orders yet.</td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>

  {{-- Recent Customers --}}
  <div class="col-12 col-lg-5 fade-up delay-3">
    <div style="background:white; border:1px solid rgba(0,0,0,0.06);">
      <div style="padding:1.3rem 1.5rem; border-bottom:1px solid rgba(0,0,0,0.06); display:flex; align-items:center; justify-content:space-between;">
        <h3 style="font-family:'Cormorant Garamond',serif; font-size:1.3rem; font-weight:700; margin:0;">New Customers</h3>
        <a href="{{ route('admin.customers.index') }}" style="font-size:10px; letter-spacing:0.15em; text-transform:uppercase; color:#c9a96e; text-decoration:none;">View All →</a>
      </div>
      <div style="padding:0.5rem 0;">
        @forelse($recentCustomers as $customer)
        <div style="display:flex; align-items:center; gap:12px; padding:12px 1.5rem; border-bottom:1px solid rgba(0,0,0,0.04);">
          <div style="width:36px; height:36px; background:#f1f0ec; display:flex; align-items:center; justify-content:center; flex-shrink:0; font-size:13px; font-weight:600; color:#7a7a72;">
            {{ strtoupper(substr($customer->name, 0, 1)) }}
          </div>
          <div style="min-width:0; flex:1;">
            <p style="font-size:13px; font-weight:500; margin:0; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $customer->name }}</p>
            <p style="font-size:11px; color:#7a7a72; margin:0; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $customer->email }}</p>
          </div>
          <span style="font-size:10px; color:#7a7a72; flex-shrink:0;">{{ $customer->created_at->diffForHumans() }}</span>
        </div>
        @empty
        <div style="text-align:center; color:#7a7a72; padding:2rem; font-size:13px;">No customers yet.</div>
        @endforelse
      </div>
    </div>
  </div>

</div>

@endsection