@extends('layouts.admin')

@section('page_eyebrow', 'Catalogue')
@section('page_title', 'Manage Products')

@section('content')

<div class="fade-up delay-1">

  {{-- Header row --}}
  <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:1.5rem; flex-wrap:wrap; gap:1rem;">
    <p style="font-size:13px; color:#7a7a72; margin:0;">{{ $products->total() }} products in catalogue</p>
    <a href="{{ route('admin.products.create') }}" class="btn-deru btn-deru-sm" style="text-decoration:none;">
      <i class="fas fa-plus" style="font-size:10px;"></i> Add Product
    </a>
  </div>

  {{-- Products Table --}}
  <div style="background:white; border:1px solid rgba(0,0,0,0.06); overflow-x:auto;">
    <table class="deru-table">
      <thead>
        <tr>
          <th>Product</th>
          <th>Price</th>
          <th>Summary</th>
          <th>Added</th>
          <th style="text-align:right;">Actions</th>
        </tr>
      </thead>
      <tbody>
        @forelse($products as $product)
        <tr>
          {{-- Image + Name --}}
          <td>
            <div style="display:flex; align-items:center; gap:12px;">
              @if($product->image)
                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                     style="width:48px; height:48px; object-fit:cover; flex-shrink:0; background:#f1f0ec;">
              @else
                <div style="width:48px; height:48px; background:#f1f0ec; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                  <i class="fas fa-image" style="color:#d0cec8; font-size:14px;"></i>
                </div>
              @endif
              <span style="font-weight:500;">{{ $product->name }}</span>
            </div>
          </td>
          <td style="font-weight:600;">£{{ number_format($product->price, 2) }}</td>
          <td style="color:#7a7a72; max-width:220px;">
            <span style="display:block; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; max-width:220px;">
              {{ $product->summary ?? '—' }}
            </span>
          </td>
          <td style="color:#7a7a72; font-size:12px;">{{ $product->created_at->format('d M Y') }}</td>
          <td>
            <div style="display:flex; align-items:center; justify-content:flex-end; gap:8px;">
              <a href="{{ route('admin.products.edit', $product) }}" class="btn-deru btn-deru-sm btn-deru-outline" style="text-decoration:none;">
                <i class="fas fa-pen" style="font-size:9px;"></i> Edit
              </a>
              <form method="POST" action="{{ route('admin.products.destroy', $product) }}"
                    onsubmit="return confirm('Delete {{ addslashes($product->name) }}? This cannot be undone.')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-danger">
                  <i class="fas fa-trash" style="font-size:9px;"></i> Delete
                </button>
              </form>
            </div>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="5" style="text-align:center; color:#7a7a72; padding:3rem;">
            No products yet. <a href="{{ route('admin.products.create') }}" style="color:#c9a96e;">Add your first product →</a>
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  {{-- Pagination --}}
  @if($products->hasPages())
  <div style="margin-top:1.5rem;">
    {{ $products->links() }}
  </div>
  @endif

</div>

@endsection