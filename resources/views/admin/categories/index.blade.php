@extends('layouts.admin')

@section('page_eyebrow', 'Catalogue')
@section('page_title', 'Categories')

@section('content')

<div class="fade-up delay-1">

  <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:1.5rem; flex-wrap:wrap; gap:1rem;">
    <p style="font-size:13px; color:#7a7a72; margin:0;">{{ $categories->count() }} categories</p>
    <a href="{{ route('admin.categories.create') }}" class="btn-deru btn-deru-sm" style="text-decoration:none;">
      <i class="fas fa-plus" style="font-size:10px;"></i> Add Category
    </a>
  </div>

  <div style="background:white; border:1px solid rgba(0,0,0,0.06); overflow-x:auto;">
    <table class="deru-table">
      <thead>
        <tr>
          <th>Category</th>
          <th>Slug</th>
          <th>Tag</th>
          <th>Products</th>
          <th>Sort</th>
          <th style="text-align:right;">Actions</th>
        </tr>
      </thead>
      <tbody>
        @forelse($categories as $category)
        <tr>
          {{-- Cover + Name --}}
          <td>
            <div style="display:flex; align-items:center; gap:12px;">
              @if($category->cover_image)
                <img src="{{ asset('storage/' . $category->cover_image) }}" alt="{{ $category->name }}"
                     style="width:56px; height:40px; object-fit:cover; flex-shrink:0; background:#f1f0ec;">
              @else
                <div style="width:56px; height:40px; background:#f1f0ec; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                  <i class="fas fa-image" style="color:#d0cec8; font-size:12px;"></i>
                </div>
              @endif
              <div>
                <p style="font-weight:500; margin:0; font-size:13px;">{{ $category->name }}</p>
                @if($category->description)
                  <p style="font-size:11px; color:#7a7a72; margin:0; max-width:200px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $category->description }}</p>
                @endif
              </div>
            </div>
          </td>
          <td style="color:#7a7a72; font-size:12px; font-family:monospace;">{{ $category->slug }}</td>
          <td>
            @if($category->tag)
              <span style="background:rgba(201,169,110,0.1); color:#c9a96e; font-size:10px; font-weight:600; letter-spacing:0.05em; padding:3px 10px; border-radius:9999px;">
                {{ $category->tag }}
              </span>
            @else
              <span style="color:#d0cec8;">—</span>
            @endif
          </td>
          <td style="font-weight:500;">{{ $category->products_count }}</td>
          <td style="color:#7a7a72;">{{ $category->sort_order }}</td>
          <td>
            <div style="display:flex; align-items:center; justify-content:flex-end; gap:8px;">
              <a href="{{ route('admin.categories.edit', $category) }}" class="btn-deru btn-deru-sm btn-deru-outline" style="text-decoration:none;">
                <i class="fas fa-pen" style="font-size:9px;"></i> Edit
              </a>
              <form method="POST" action="{{ route('admin.categories.destroy', $category) }}"
                    onsubmit="return confirm('Delete {{ addslashes($category->name) }}? Products will become uncategorised.')">
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
          <td colspan="6" style="text-align:center; color:#7a7a72; padding:3rem;">
            No categories yet. <a href="{{ route('admin.categories.create') }}" style="color:#c9a96e;">Create your first →</a>
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>

</div>

@endsection