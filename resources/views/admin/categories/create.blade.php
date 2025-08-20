@extends('admin.layout')

@section('title', 'Create Category')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Create New Category</h2>
        <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back to Categories
        </a>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Category Information</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.categories.store') }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="name" class="form-label">Category Name <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('name') is-invalid @enderror" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name') }}" 
                                   required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">This will be used as the display name for the category.</div>
                        </div>

                        <div class="mb-3">
                            <label for="slug" class="form-label">URL Slug</label>
                            <input type="text" 
                                   class="form-control @error('slug') is-invalid @enderror" 
                                   id="slug" 
                                   name="slug" 
                                   value="{{ old('slug') }}">
                            @error('slug')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Leave empty to auto-generate from name. Used in URLs.</div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" 
                                      name="description" 
                                      rows="4">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Optional description for the category.</div>
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" 
                                       type="checkbox" 
                                       id="is_active" 
                                       name="is_active" 
                                       value="1" 
                                       {{ old('is_active', true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    Active Category
                                </label>
                            </div>
                            <div class="form-text">Only active categories will be visible on the website.</div>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Create Category
                            </button>
                            <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Help & Tips</h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <h6><i class="fas fa-lightbulb me-2"></i>Category Tips</h6>
                        <ul class="mb-0 ps-3">
                            <li>Choose descriptive names that customers will understand</li>
                            <li>Slugs are used in URLs and should be SEO-friendly</li>
                            <li>Inactive categories won't appear on your store</li>
                            <li>You can organize products better with clear categories</li>
                        </ul>
                    </div>

                    <div class="card mt-3">
                        <div class="card-body">
                            <h6>Category Preview</h6>
                            <div class="border rounded p-3 bg-light">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-tag text-primary me-2"></i>
                                    <span id="preview-name" class="fw-bold">Category Name</span>
                                </div>
                                <small class="text-muted" id="preview-url">URL: /categories/category-slug</small>
                                <p class="mt-2 mb-0" id="preview-description">Category description will appear here...</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const nameInput = document.getElementById('name');
    const slugInput = document.getElementById('slug');
    const descriptionInput = document.getElementById('description');
    const previewName = document.getElementById('preview-name');
    const previewUrl = document.getElementById('preview-url');
    const previewDescription = document.getElementById('preview-description');

    function generateSlug(text) {
        return text
            .toLowerCase()
            .replace(/[^a-z0-9 -]/g, '')
            .replace(/\s+/g, '-')
            .replace(/-+/g, '-')
            .trim('-');
    }

    function updatePreview() {
        const name = nameInput.value || 'Category Name';
        const slug = slugInput.value || generateSlug(nameInput.value) || 'category-slug';
        const description = descriptionInput.value || 'Category description will appear here...';

        previewName.textContent = name;
        previewUrl.textContent = `URL: /categories/${slug}`;
        previewDescription.textContent = description;
    }

    // Auto-generate slug when name changes
    nameInput.addEventListener('input', function() {
        if (!slugInput.value) {
            slugInput.value = generateSlug(this.value);
        }
        updatePreview();
    });

    // Update preview when slug changes
    slugInput.addEventListener('input', updatePreview);
    
    // Update preview when description changes
    descriptionInput.addEventListener('input', updatePreview);

    // Initial preview update
    updatePreview();
});
</script>
@endpush
