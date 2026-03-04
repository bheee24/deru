@extends('layouts.admin')

@section('page_eyebrow', 'People')
@section('page_title', 'Customers')

@section('content')

<div class="fade-up delay-1">

  {{-- Header --}}
  <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:1.5rem; flex-wrap:wrap; gap:1rem;">
    <p style="font-size:13px; color:#7a7a72; margin:0;">{{ $customers->total() }} registered customers</p>

    {{-- Search --}}
    <form method="GET" action="{{ route('admin.customers.index') }}" style="display:flex; gap:0;">
      <input type="text" name="search" value="{{ request('search') }}"
             placeholder="Search by name or email..."
             class="deru-input-admin" style="width:260px; border-right:none;">
      <button type="submit" class="btn-deru btn-deru-sm" style="flex-shrink:0;">
        <i class="fas fa-search" style="font-size:10px;"></i>
      </button>
    </form>
  </div>

  {{-- Customers Table --}}
  <div style="background:white; border:1px solid rgba(0,0,0,0.06); overflow-x:auto;">
    <table class="deru-table">
      <thead>
        <tr>
          <th>Customer</th>
          <th>Registered</th>
          <th>Orders</th>
          <th>Total Spent</th>
          <th>Status</th>
          <th style="text-align:right;">Actions</th>
        </tr>
      </thead>
      <tbody>
        @forelse($customers as $customer)
        <tr>
          {{-- Name + Email --}}
          <td>
            <div style="display:flex; align-items:center; gap:12px;">
              <div style="width:36px; height:36px; background:#f1f0ec; display:flex; align-items:center; justify-content:center; flex-shrink:0; font-size:13px; font-weight:600; color:#7a7a72;">
                {{ strtoupper(substr($customer->name, 0, 1)) }}
              </div>
              <div>
                <p style="font-weight:500; margin:0; font-size:13px;">{{ $customer->name }}</p>
                <p style="font-size:11px; color:#7a7a72; margin:0;">{{ $customer->email }}</p>
              </div>
            </div>
          </td>
          <td style="color:#7a7a72; font-size:12px;">{{ $customer->created_at->format('d M Y') }}</td>
          <td style="font-weight:500;">{{ $customer->orders_count ?? 0 }}</td>
          <td style="font-weight:600;">£{{ number_format($customer->orders_sum_total ?? 0, 2) }}</td>
          <td>
            @if($customer->is_active)
              <span class="badge-active">Active</span>
            @else
              <span class="badge-disabled">Disabled</span>
            @endif
          </td>
          <td>
            <div style="display:flex; align-items:center; justify-content:flex-end; gap:8px;">

              {{-- View customer --}}
              <a href="{{ route('admin.customers.show', $customer) }}"
                 class="btn-deru btn-deru-sm btn-deru-outline" style="text-decoration:none;">
                <i class="fas fa-eye" style="font-size:9px;"></i> View
              </a>

              {{-- Toggle enable/disable --}}
              <form method="POST" action="{{ route('admin.customers.toggle', $customer) }}">
                @csrf
                @method('PATCH')
                @if($customer->is_active)
                  <button type="submit" class="btn-danger"
                          onclick="return confirm('Disable {{ addslashes($customer->name) }}? They will not be able to log in.')">
                    <i class="fas fa-ban" style="font-size:9px;"></i> Disable
                  </button>
                @else
                  <button type="submit" class="btn-deru btn-deru-sm"
                          style="background:rgba(34,197,94,0.1); color:#16a34a; border-color:rgba(34,197,94,0.3);"
                          onmouseover="this.style.background='#16a34a';this.style.color='white';this.style.borderColor='#16a34a';"
                          onmouseout="this.style.background='rgba(34,197,94,0.1)';this.style.color='#16a34a';this.style.borderColor='rgba(34,197,94,0.3)';">
                    <i class="fas fa-check" style="font-size:9px;"></i> Enable
                  </button>
                @endif
              </form>

            </div>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="6" style="text-align:center; color:#7a7a72; padding:3rem;">
            No customers found.
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  {{-- Pagination --}}
  @if($customers->hasPages())
  <div style="margin-top:1.5rem;">
    {{ $customers->links() }}
  </div>
  @endif

</div>

@endsection