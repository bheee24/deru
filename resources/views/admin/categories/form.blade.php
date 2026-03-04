@extends('layouts.admin')

@section('page_eyebrow', 'Catalogue')
@section('page_title', isset($category) ? 'Edit Category' : 'Add Category')

@section('content')

<div class="fade-up delay-1" style="max-width:720px;">

  <a href="{{ route('admin.categories.index') }}" style="font-size:11px; letter-spacing:0.1em; text-transform:uppercase; color:#7a7a72; text-decoration:none; display:inline-flex; align-items:center; gap:6px; margin-bottom:2rem;">
    <i class="fas fa-arrow-left" style="font-size:9px;"></i> Back to Categories
  </a>

  <div style="background:white; border:1px solid rgba(0,0,0,0.06); padding:2rem;">

    <form method="POST"
          action="{{ isset($category) ? route('admin.categories.update', $category) : route('admin.categories.store') }}"
          enctype="multipart/form-data">
      @csrf
      @if(isset($category)) @method('PUT') @endif

      {{-- Name --}}
      <div style="margin-bottom:1.5rem;">
        <label class="deru-label-admin">Category Name</label>
        <input type="text" name="name" value="{{ old('name', $category->name ?? '') }}"
               placeholder="e.g. Beyond Ordinary"
               class="deru-input-admin @error('name') border-red-400 @enderror" required>
        @error('name')
          <p style="font-size:11px; color:#dc2626; margin-top:5px;">{{ $message }}</p>
        @enderror
        <p style="font-size:11px; color:#7a7a72; margin-top:5px;">The slug is auto-generated from the name.</p>
      </div>

      {{-- Tag --}}
      <div style="margin-bottom:1.5rem;">
        <label class="deru-label-admin">Tag <span style="font-weight:400; text-transform:none; letter-spacing:0;">(optional — displayed as a badge on the collection card)</span></label>
        <input type="text" name="tag" value="{{ old('tag', $category->tag ?? '') }}"
               placeholder="e.g. New, Hot, Limited, Best Seller"
               class="deru-input-admin @error('tag') border-red-400 @enderror">
        @error('tag')
          <p style="font-size:11px; color:#dc2626; margin-top:5px;">{{ $message }}</p>
        @enderror
      </div>

      {{-- Description --}}
      <div style="margin-bottom:1.5rem;">
        <label class="deru-label-admin">Description <span style="font-weight:400; text-transform:none; letter-spacing:0;">(optional)</span></label>
        <textarea name="description" rows="2"
                  placeholder="A short description shown on the collection card..."
                  class="deru-input-admin @error('description') border-red-400 @enderror"
                  style="resize:vertical;">{{ old('description', $category->description ?? '') }}</textarea>
        @error('description')
          <p style="font-size:11px; color:#dc2626; margin-top:5px;">{{ $message }}</p>
        @enderror
      </div>

      {{-- Sort Order --}}
      <div style="margin-bottom:1.5rem;">
        <label class="deru-label-admin">Sort Order <span style="font-weight:400; text-transform:none; letter-spacing:0;">(lower number = appears first)</span></label>
        <input type="number" name="sort_order" min="0"
               value="{{ old('sort_order', $category->sort_order ?? 0) }}"
               class="deru-input-admin @error('sort_order') border-red-400 @enderror"
               style="max-width:120px;">
        @error('sort_order')
          <p style="font-size:11px; color:#dc2626; margin-top:5px;">{{ $message }}</p>
        @enderror
      </div>

      {{-- Cover Image --}}
      <div style="margin-bottom:2rem;">
        <label class="deru-label-admin">Cover Image <span style="font-weight:400; text-transform:none; letter-spacing:0;">(displayed on the Collections section of the homepage)</span></label>

        @if(isset($category) && $category->cover_image)
          <div style="margin-bottom:1rem;">
            <img src="{{ asset('storage/' . $category->cover_image) }}" alt="{{ $category->name }}"
                 style="width:180px; height:120px; object-fit:cover; border:1px solid rgba(0,0,0,0.08);">
            <p style="font-size:11px; color:#7a7a72; margin-top:5px;">Current cover — upload a new one to replace it.</p>
          </div>
        @endif

        <input type="file" name="cover_image" accept="image/*"
               class="deru-input-admin @error('cover_image') border-red-400 @enderror"
               style="padding:8px 14px;" id="coverImageInput">
        <img id="coverPreview" src="#" alt="Preview"
             style="display:none; width:180px; height:120px; object-fit:cover; margin-top:10px; border:1px solid rgba(0,0,0,0.08);">
        @error('cover_image')
          <p style="font-size:11px; color:#dc2626; margin-top:5px;">{{ $message }}</p>
        @enderror
      </div>

      {{-- Actions --}}
      <div style="display:flex; align-items:center; gap:1rem; padding-top:1rem; border-top:1px solid rgba(0,0,0,0.06);">
        <button type="submit" class="btn-deru">
          <i class="fas fa-save" style="font-size:10px;"></i>
          {{ isset($category) ? 'Update Category' : 'Save Category' }}
        </button>
        <a href="{{ route('admin.categories.index') }}" class="btn-deru btn-deru-outline" style="text-decoration:none;">Cancel</a>
      </div>

    </form>
  </div>
</div>

@push('scripts')
<script>
  document.getElementById('coverImageInput').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = (e) => {
      const preview = document.getElementById('coverPreview');
      preview.src = e.target.result;
      preview.style.display = 'block';
    };
    reader.readAsDataURL(file);
  });
</script>
@endpush

@endsection