@extends('layouts.admin')

@section('page_eyebrow', 'Catalogue')
@section('page_title', isset($product) ? 'Edit Product' : 'Add Product')

@section('content')

<div class="fade-up delay-1" style="max-width:720px;">

  <a href="{{ route('admin.products.index') }}" style="font-size:11px; letter-spacing:0.1em; text-transform:uppercase; color:#7a7a72; text-decoration:none; display:inline-flex; align-items:center; gap:6px; margin-bottom:2rem;">
    <i class="fas fa-arrow-left" style="font-size:9px;"></i> Back to Products
  </a>

  <div style="background:white; border:1px solid rgba(0,0,0,0.06); padding:2rem;">

    <form method="POST"
          action="{{ isset($product) ? route('admin.products.update', $product) : route('admin.products.store') }}"
          enctype="multipart/form-data">
      @csrf
      @if(isset($product)) @method('PUT') @endif

      {{-- Name --}}
      <div style="margin-bottom:1.5rem;">
        <label class="deru-label-admin">Product Name</label>
        <input type="text" name="name" value="{{ old('name', $product->name ?? '') }}"
               placeholder="e.g. Classic 6-Panel Cap"
               class="deru-input-admin @error('name') border-red-400 @enderror" required>
        @error('name')
          <p style="font-size:11px; color:#dc2626; margin-top:5px;">{{ $message }}</p>
        @enderror
      </div>

      {{-- Price --}}
      <div style="margin-bottom:1.5rem;">
        <label class="deru-label-admin">Price (£)</label>
        <input type="number" name="price" step="0.01" min="0"
               value="{{ old('price', $product->price ?? '') }}"
               placeholder="0.00"
               class="deru-input-admin @error('price') border-red-400 @enderror" required>
        @error('price')
          <p style="font-size:11px; color:#dc2626; margin-top:5px;">{{ $message }}</p>
        @enderror
      </div>

      {{-- Summary --}}
      <div style="margin-bottom:1.5rem;">
        <label class="deru-label-admin">Brief Summary</label>
        <textarea name="summary" rows="3"
                  placeholder="A short description of the product..."
                  class="deru-input-admin @error('summary') border-red-400 @enderror"
                  style="resize:vertical;">{{ old('summary', $product->summary ?? '') }}</textarea>
        @error('summary')
          <p style="font-size:11px; color:#dc2626; margin-top:5px;">{{ $message }}</p>
        @enderror
      </div>

      {{-- Image --}}
      <div style="margin-bottom:2rem;">
        <label class="deru-label-admin">Product Image</label>

        {{-- Current image preview --}}
        @if(isset($product) && $product->image)
          <div style="margin-bottom:1rem;">
            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                 style="width:120px; height:120px; object-fit:cover; border:1px solid rgba(0,0,0,0.08);">
            <p style="font-size:11px; color:#7a7a72; margin-top:5px;">Current image — upload a new one to replace it.</p>
          </div>
        @endif

        <input type="file" name="image" accept="image/*"
               class="deru-input-admin @error('image') border-red-400 @enderror"
               style="padding:8px 14px;" id="imageInput">
        {{-- Preview new image before upload --}}
        <img id="imagePreview" src="#" alt="Preview"
             style="display:none; width:120px; height:120px; object-fit:cover; margin-top:10px; border:1px solid rgba(0,0,0,0.08);">
        @error('image')
          <p style="font-size:11px; color:#dc2626; margin-top:5px;">{{ $message }}</p>
        @enderror
      </div>

      {{-- Actions --}}
      <div style="display:flex; align-items:center; gap:1rem; padding-top:1rem; border-top:1px solid rgba(0,0,0,0.06);">
        <button type="submit" class="btn-deru">
          <i class="fas fa-save" style="font-size:10px;"></i>
          {{ isset($product) ? 'Update Product' : 'Save Product' }}
        </button>
        <a href="{{ route('admin.products.index') }}" class="btn-deru btn-deru-outline" style="text-decoration:none;">Cancel</a>
      </div>

    </form>
  </div>
</div>

@push('scripts')
<script>
  // Image preview
  document.getElementById('imageInput').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = (e) => {
      const preview = document.getElementById('imagePreview');
      preview.src = e.target.result;
      preview.style.display = 'block';
    };
    reader.readAsDataURL(file);
  });
</script>
@endpush

@endsection