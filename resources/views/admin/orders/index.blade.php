@extends('layouts.admin')

@section('page_eyebrow', 'Management')
@section('page_title', 'Orders')

@section('content')

{{-- ── Stat Strip ── --}}
<div class="row g-3 mb-4">

  @php
    $statCards = [
      ['label' => 'Total Orders',  'value' => $stats['total'],                       'icon' => 'fa-shopping-bag', 'color' => '#0f0f0f'],
      ['label' => 'Revenue',       'value' => '£'.number_format($stats['revenue'],2), 'icon' => 'fa-pound-sign',  'color' => '#c9a96e'],
      ['label' => 'Pending',       'value' => $stats['pending'],                      'icon' => 'fa-clock',       'color' => '#b8903a'],
      ['label' => 'Processing',    'value' => $stats['processing'],                   'icon' => 'fa-cog',         'color' => '#2563eb'],
      ['label' => 'Shipped',       'value' => $stats['shipped'],                      'icon' => 'fa-truck',       'color' => '#059669'],
      ['label' => 'Delivered',     'value' => $stats['delivered'],                    'icon' => 'fa-check-circle','color' => '#047857'],
    ];
  @endphp

  @foreach($statCards as $i => $card)
  <div class="col-6 col-md-4 col-lg-2">
    <div class="stat-card fade-up" style="animation-delay:{{ $i * 0.05 }}s; opacity:0;">
      <p style="font-size:9px; letter-spacing:0.18em; text-transform:uppercase; color:#7a7a72; margin-bottom:6px;">{{ $card['label'] }}</p>
      <h3 style="font-family:'Cormorant Garamond',serif; font-size:1.8rem; font-weight:700; color:{{ $card['color'] }}; line-height:1; margin:0;">{{ $card['value'] }}</h3>
    </div>
  </div>
  @endforeach

</div>

{{-- ── Filters ── --}}
<div style="background:white; border:1px solid rgba(0,0,0,0.06); padding:1.25rem 1.5rem; margin-bottom:1rem; display:flex; gap:1rem; flex-wrap:wrap; align-items:center;">
  <form method="GET" action="{{ route('admin.orders.index') }}" style="display:flex; gap:0.75rem; flex-wrap:wrap; flex:1; align-items:center;">

    <input type="text" name="search" value="{{ request('search') }}"
           placeholder="Search ref, name or email..."
           style="padding:8px 12px; border:1px solid rgba(0,0,0,0.15); font-family:'Montserrat',sans-serif; font-size:12px; outline:none; min-width:220px; flex:1;">

    <select name="status"
            style="padding:8px 12px; border:1px solid rgba(0,0,0,0.15); font-family:'Montserrat',sans-serif; font-size:12px; outline:none; background:white; cursor:pointer;">
      <option value="">All Statuses</option>
      @foreach(['pending','processing','shipped','delivered','cancelled'] as $s)
        <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
      @endforeach
    </select>

    <button type="submit"
            style="padding:8px 20px; background:#0f0f0f; color:white; border:none; font-family:'Montserrat',sans-serif; font-size:11px; font-weight:600; letter-spacing:0.12em; text-transform:uppercase; cursor:pointer;">
      Filter
    </button>

    @if(request()->hasAny(['search','status']))
      <a href="{{ route('admin.orders.index') }}"
         style="padding:8px 16px; font-size:11px; color:#7a7a72; text-decoration:none; letter-spacing:0.1em; text-transform:uppercase;">
        Clear
      </a>
    @endif
  </form>
</div>

{{-- ── Orders Table ── --}}
<div style="background:white; border:1px solid rgba(0,0,0,0.06);">

  @if(session('success'))
    <div style="padding:10px 1.5rem; background:rgba(201,169,110,0.1); border-bottom:1px solid rgba(201,169,110,0.2); font-size:12px; color:#b8903a; display:flex; align-items:center; gap:8px;">
      <i class="fas fa-check-circle"></i> {{ session('success') }}
    </div>
  @endif

  <div style="overflow-x:auto;">
    <table class="deru-table">
      <thead>
        <tr>
          <th>Reference</th>
          <th>Customer</th>
          <th>Items</th>
          <th>Total</th>
          <th>Card</th>
          <th>Date</th>
          <th>Status</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        @forelse($orders as $order)
        <tr>
          <td>
            <a href="{{ route('admin.orders.show', $order) }}"
               style="font-family:monospace; font-size:12px; font-weight:600; color:#c9a96e; text-decoration:none;">
              {{ $order->reference }}
            </a>
          </td>
          <td>
            <p style="font-weight:500; margin:0; font-size:13px;">{{ $order->full_name }}</p>
            <p style="font-size:11px; color:#7a7a72; margin:0;">{{ $order->email }}</p>
          </td>
          <td style="font-size:12px; color:#7a7a72;">
            {{ $order->items->count() }} {{ Str::plural('item', $order->items->count()) }}
          </td>
          <td style="font-weight:700; font-size:13px;">£{{ number_format($order->total, 2) }}</td>
          <td style="font-size:12px; color:#7a7a72;">{{ $order->card_summary }}</td>
          <td style="font-size:12px; color:#7a7a72; white-space:nowrap;">
            {{ $order->created_at->format('d M Y') }}<br>
            <span style="font-size:10px;">{{ $order->created_at->format('H:i') }}</span>
          </td>
          <td>
            @php
              $colours = [
                'pending'    => ['bg'=>'rgba(201,169,110,0.12)', 'text'=>'#b8903a'],
                'processing' => ['bg'=>'rgba(59,130,246,0.1)',   'text'=>'#2563eb'],
                'shipped'    => ['bg'=>'rgba(16,185,129,0.1)',   'text'=>'#059669'],
                'delivered'  => ['bg'=>'rgba(16,185,129,0.15)',  'text'=>'#047857'],
                'cancelled'  => ['bg'=>'rgba(220,38,38,0.08)',   'text'=>'#dc2626'],
              ];
              $c = $colours[$order->status] ?? ['bg'=>'rgba(0,0,0,0.06)', 'text'=>'#7a7a72'];
            @endphp
            <span style="font-size:9px; font-weight:700; letter-spacing:0.12em; text-transform:uppercase;
                         padding:4px 10px; border-radius:9999px;
                         background:{{ $c['bg'] }}; color:{{ $c['text'] }};">
              {{ ucfirst($order->status) }}
            </span>
          </td>
          <td>
            <a href="{{ route('admin.orders.show', $order) }}"
               style="font-size:11px; color:#c9a96e; text-decoration:none; letter-spacing:0.05em; white-space:nowrap;">
              View →
            </a>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="8" style="text-align:center; color:#7a7a72; padding:3rem; font-size:13px;">
            No orders found.
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  {{-- Pagination --}}
  @if($orders->hasPages())
    <div style="padding:1rem 1.5rem; border-top:1px solid rgba(0,0,0,0.06); display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:1rem;">
      <p style="font-size:12px; color:#7a7a72; margin:0;">
        Showing {{ $orders->firstItem() }}–{{ $orders->lastItem() }} of {{ $orders->total() }} orders
      </p>
      <div style="display:flex; gap:4px;">
        @if($orders->onFirstPage())
          <span style="padding:6px 12px; font-size:12px; color:rgba(0,0,0,0.25); border:1px solid rgba(0,0,0,0.1);">← Prev</span>
        @else
          <a href="{{ $orders->previousPageUrl() }}" style="padding:6px 12px; font-size:12px; color:#0f0f0f; border:1px solid rgba(0,0,0,0.15); text-decoration:none;">← Prev</a>
        @endif
        @if($orders->hasMorePages())
          <a href="{{ $orders->nextPageUrl() }}" style="padding:6px 12px; font-size:12px; color:#0f0f0f; border:1px solid rgba(0,0,0,0.15); text-decoration:none;">Next →</a>
        @else
          <span style="padding:6px 12px; font-size:12px; color:rgba(0,0,0,0.25); border:1px solid rgba(0,0,0,0.1);">Next →</span>
        @endif
      </div>
    </div>
  @endif

</div>

@endsection