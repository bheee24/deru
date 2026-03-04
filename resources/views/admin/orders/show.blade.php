@extends('layouts.admin')

@section('page_eyebrow', 'Orders')
@section('page_title', $order->reference)

@section('content')

@php
  $statuses  = ['pending', 'processing', 'shipped', 'delivered', 'cancelled'];
  $colours = [
    'pending'    => ['bg'=>'rgba(201,169,110,0.12)', 'text'=>'#b8903a'],
    'processing' => ['bg'=>'rgba(59,130,246,0.1)',   'text'=>'#2563eb'],
    'shipped'    => ['bg'=>'rgba(16,185,129,0.1)',   'text'=>'#059669'],
    'delivered'  => ['bg'=>'rgba(16,185,129,0.15)',  'text'=>'#047857'],
    'cancelled'  => ['bg'=>'rgba(220,38,38,0.08)',   'text'=>'#dc2626'],
  ];
  $c = $colours[$order->status] ?? ['bg'=>'rgba(0,0,0,0.06)', 'text'=>'#7a7a72'];
@endphp

{{-- ── Flash ── --}}
@if(session('success'))
  <div style="background:rgba(201,169,110,0.1); border:1px solid rgba(201,169,110,0.25); color:#b8903a; padding:10px 16px; font-size:12px; margin-bottom:1.5rem; display:flex; align-items:center; gap:8px;">
    <i class="fas fa-check-circle"></i> {{ session('success') }}
  </div>
@endif

{{-- ── Page Header ── --}}
<div style="display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:1rem; margin-bottom:1.75rem;">
  <div style="display:flex; align-items:center; gap:12px; flex-wrap:wrap;">
    <span style="font-size:9px; font-weight:700; letter-spacing:0.12em; text-transform:uppercase;
                 padding:5px 12px; border-radius:9999px;
                 background:{{ $c['bg'] }}; color:{{ $c['text'] }};">
      {{ ucfirst($order->status) }}
    </span>
    <span style="font-size:12px; color:#7a7a72;">
      Placed {{ $order->created_at->format('d M Y \a\t H:i') }}
    </span>
  </div>
  <a href="{{ route('admin.orders.index') }}"
     style="font-size:11px; letter-spacing:0.12em; text-transform:uppercase; color:#7a7a72; text-decoration:none;">
    ← Back to Orders
  </a>
</div>

<div class="row g-4">

  {{-- ── Left: Items + Delivery ── --}}
  <div class="col-12 col-lg-8">

    {{-- Items --}}
    <div style="background:white; border:1px solid rgba(0,0,0,0.06); margin-bottom:1.25rem;">
      <div style="padding:1.1rem 1.5rem; border-bottom:1px solid rgba(0,0,0,0.06);">
        <h3 style="font-family:'Cormorant Garamond',serif; font-size:1.25rem; font-weight:700; margin:0;">
          Items Ordered
          <span style="font-size:0.9rem; font-weight:400; color:#7a7a72;">({{ $order->items->count() }})</span>
        </h3>
      </div>
      <table class="deru-table">
        <thead>
          <tr>
            <th>Product</th>
            <th>Price</th>
            <th>Qty</th>
            <th>Subtotal</th>
          </tr>
        </thead>
        <tbody>
          @foreach($order->items as $item)
          <tr>
            <td>
              <div style="display:flex; align-items:center; gap:10px;">
                @if($item->product_img)
                  <img src="{{ $item->product_img }}" style="width:44px; height:52px; object-fit:cover; background:#e8e5de; flex-shrink:0;">
                @else
                  <div style="width:44px; height:52px; background:#e8e5de; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                    <i class="fas fa-image" style="color:#c8c5be; font-size:12px;"></i>
                  </div>
                @endif
                <span style="font-size:13px; font-weight:500;">{{ $item->product_name }}</span>
              </div>
            </td>
            <td style="font-size:13px;">£{{ number_format($item->price, 2) }}</td>
            <td style="font-size:13px;">{{ $item->quantity }}</td>
            <td style="font-size:13px; font-weight:600;">£{{ number_format($item->subtotal, 2) }}</td>
          </tr>
          @endforeach
        </tbody>
      </table>
      {{-- Totals footer --}}
      <div style="padding:1rem 1.5rem; border-top:1px solid rgba(0,0,0,0.06); display:flex; flex-direction:column; align-items:flex-end; gap:4px;">
        <div style="display:flex; gap:3rem; font-size:13px;">
          <span style="color:#7a7a72;">Subtotal</span>
          <span>£{{ number_format($order->subtotal, 2) }}</span>
        </div>
        <div style="display:flex; gap:3rem; font-size:13px;">
          <span style="color:#7a7a72;">Shipping ({{ $order->shipping_method }})</span>
          <span style="{{ $order->shipping_cost == 0 ? 'color:#16a34a;' : '' }}">
            {{ $order->shipping_cost == 0 ? 'Free' : '£'.number_format($order->shipping_cost, 2) }}
          </span>
        </div>
        <div style="display:flex; gap:3rem; font-size:15px; font-weight:700; margin-top:4px; padding-top:8px; border-top:1px solid rgba(0,0,0,0.08);">
          <span>Total</span>
          <span>£{{ number_format($order->total, 2) }}</span>
        </div>
      </div>
    </div>

    {{-- Delivery --}}
    <div style="background:white; border:1px solid rgba(0,0,0,0.06); padding:1.5rem; margin-bottom:1.25rem;">
      <h3 style="font-family:'Cormorant Garamond',serif; font-size:1.25rem; font-weight:700; margin-bottom:1.25rem; padding-bottom:0.75rem; border-bottom:1px solid rgba(0,0,0,0.06);">
        <i class="fas fa-truck" style="color:#c9a96e; margin-right:8px; font-size:1rem;"></i> Delivery Details
      </h3>
      <div class="row g-3" style="font-size:13px;">
        <div class="col-6">
          <p style="font-size:10px; letter-spacing:0.12em; text-transform:uppercase; color:#7a7a72; margin-bottom:3px;">Full Name</p>
          <p style="font-weight:500; margin:0;">{{ $order->full_name }}</p>
        </div>
        <div class="col-6">
          <p style="font-size:10px; letter-spacing:0.12em; text-transform:uppercase; color:#7a7a72; margin-bottom:3px;">Email</p>
          <p style="font-weight:500; margin:0;">{{ $order->email }}</p>
        </div>
        <div class="col-6">
          <p style="font-size:10px; letter-spacing:0.12em; text-transform:uppercase; color:#7a7a72; margin-bottom:3px;">Phone</p>
          <p style="font-weight:500; margin:0;">{{ $order->phone ?? '—' }}</p>
        </div>
        <div class="col-6">
          <p style="font-size:10px; letter-spacing:0.12em; text-transform:uppercase; color:#7a7a72; margin-bottom:3px;">Shipping Method</p>
          <p style="font-weight:500; margin:0;">{{ $order->shipping_method }}</p>
        </div>
        <div class="col-12">
          <p style="font-size:10px; letter-spacing:0.12em; text-transform:uppercase; color:#7a7a72; margin-bottom:3px;">Delivery Address</p>
          <p style="font-weight:500; margin:0; line-height:1.6;">{{ $order->full_address }}</p>
        </div>
        @if($order->notes)
        <div class="col-12">
          <p style="font-size:10px; letter-spacing:0.12em; text-transform:uppercase; color:#7a7a72; margin-bottom:3px;">Order Notes</p>
          <p style="font-style:italic; color:#7a7a72; margin:0;">{{ $order->notes }}</p>
        </div>
        @endif
      </div>
    </div>

  </div>

  {{-- ── Right: Payment + Status Update ── --}}
  <div class="col-12 col-lg-4">

    {{-- Payment info --}}
    <div style="background:white; border:1px solid rgba(0,0,0,0.06); padding:1.5rem; margin-bottom:1.25rem;">
      <h3 style="font-family:'Cormorant Garamond',serif; font-size:1.25rem; font-weight:700; margin-bottom:1.25rem; padding-bottom:0.75rem; border-bottom:1px solid rgba(0,0,0,0.06);">
        <i class="fas fa-credit-card" style="color:#c9a96e; margin-right:8px; font-size:1rem;"></i> Payment
      </h3>
      @php
        $payRows = [
          ['Status',         '<span style="color:#047857;"><i class="fas fa-check-circle" style="margin-right:3px;"></i>Paid</span>'],
          ['Amount',         '<strong>£'.number_format($order->total, 2).'</strong>'],
          ['Card',           $order->card_summary],
          ['Transaction ID', '<span style="font-family:monospace; font-size:10px; color:#7a7a72; word-break:break-all;">'.$order->payment_intent_id.'</span>'],
          ['Reference',      '<span style="font-family:monospace; color:#c9a96e; font-weight:700;">'.$order->reference.'</span>'],
        ];
      @endphp
      @foreach($payRows as [$label, $val])
      <div style="display:flex; justify-content:space-between; align-items:flex-start; padding:8px 0; border-bottom:1px solid rgba(0,0,0,0.05); font-size:12px; gap:1rem;">
        <span style="color:#7a7a72; flex-shrink:0;">{{ $label }}</span>
        <span style="text-align:right;">{!! $val !!}</span>
      </div>
      @endforeach
    </div>

    {{-- Customer info --}}
    @if($order->user)
    <div style="background:white; border:1px solid rgba(0,0,0,0.06); padding:1.5rem; margin-bottom:1.25rem;">
      <h3 style="font-family:'Cormorant Garamond',serif; font-size:1.25rem; font-weight:700; margin-bottom:1.25rem; padding-bottom:0.75rem; border-bottom:1px solid rgba(0,0,0,0.06);">
        <i class="fas fa-user" style="color:#c9a96e; margin-right:8px; font-size:1rem;"></i> Customer
      </h3>
      <div style="display:flex; align-items:center; gap:12px; margin-bottom:1rem;">
        <div style="width:40px; height:40px; border-radius:50%; background:#f1f0ec; display:flex; align-items:center; justify-content:center; font-family:'Cormorant Garamond',serif; font-size:1.1rem; font-weight:700; color:#7a7a72; flex-shrink:0;">
          {{ strtoupper(substr($order->user->name, 0, 1)) }}
        </div>
        <div>
          <p style="font-size:13px; font-weight:600; margin:0;">{{ $order->user->name }}</p>
          <p style="font-size:11px; color:#7a7a72; margin:0;">{{ $order->user->email }}</p>
        </div>
      </div>
      <div style="font-size:12px; color:#7a7a72;">
        Member since {{ $order->user->created_at->format('M Y') }} ·
        {{ $order->user->orders()->count() }} {{ Str::plural('order', $order->user->orders()->count()) }}
      </div>
      <a href="{{ route('admin.customers.show', $order->user) }}"
         style="display:inline-block; margin-top:12px; font-size:11px; color:#c9a96e; text-decoration:none; letter-spacing:0.05em;">
        View Customer Profile →
      </a>
    </div>
    @endif

    {{-- ── Status Update ── --}}
    <div style="background:#0f0f0f; padding:1.5rem; border:1px solid rgba(255,255,255,0.06);">
      <h3 style="font-family:'Cormorant Garamond',serif; font-size:1.25rem; font-weight:700; color:white; margin-bottom:1.25rem; padding-bottom:0.75rem; border-bottom:1px solid rgba(255,255,255,0.08);">
        Update Status
      </h3>

      <form method="POST" action="{{ route('admin.orders.update', $order) }}">
        @csrf
        @method('PATCH')

        <div style="display:flex; flex-direction:column; gap:8px; margin-bottom:1.25rem;">
          @foreach($statuses as $status)
          @php $sc = $colours[$status] ?? ['bg'=>'rgba(255,255,255,0.06)', 'text'=>'rgba(255,255,255,0.5)']; @endphp
          <label style="display:flex; align-items:center; gap:10px; padding:10px 14px; border:1px solid rgba(255,255,255,0.08); cursor:pointer; transition:border-color 0.2s ease;"
                 id="status-label-{{ $status }}"
                 onclick="document.querySelectorAll('[id^=status-label]').forEach(el=>el.style.borderColor='rgba(255,255,255,0.08)'); this.style.borderColor='#c9a96e';">
            <input type="radio" name="status" value="{{ $status }}"
                   {{ $order->status === $status ? 'checked' : '' }}
                   style="accent-color:#c9a96e;">
            <span style="font-size:11px; font-weight:600; letter-spacing:0.1em; text-transform:uppercase; color:white;">
              {{ ucfirst($status) }}
            </span>
            <span style="margin-left:auto; font-size:9px; font-weight:700; letter-spacing:0.1em; text-transform:uppercase;
                         padding:3px 8px; border-radius:9999px;
                         background:{{ $sc['bg'] }}; color:{{ $sc['text'] }};">
              {{ ucfirst($status) }}
            </span>
          </label>
          @endforeach
        </div>

        <button type="submit"
                style="width:100%; padding:12px; background:#c9a96e; color:#0f0f0f; border:none; font-family:'Montserrat',sans-serif; font-size:11px; font-weight:700; letter-spacing:0.15em; text-transform:uppercase; cursor:pointer; transition:background 0.2s ease;"
                onmouseover="this.style.background='#b8903a'"
                onmouseout="this.style.background='#c9a96e'">
          Save Status
        </button>
      </form>
    </div>

  </div>
</div>

<script>
  // Highlight current status label on load
  document.addEventListener('DOMContentLoaded', () => {
    const checked = document.querySelector('input[name="status"]:checked');
    if (checked) {
      const label = checked.closest('label');
      if (label) label.style.borderColor = '#c9a96e';
    }
  });
</script>

@endsection