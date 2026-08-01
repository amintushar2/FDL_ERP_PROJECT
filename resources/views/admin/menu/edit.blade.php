@extends('layouts.app')
@section('title', 'Edit Menu')
@section('page-title', 'Edit Menu')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('menus.index') }}">Menu Hierarchy</a></li>
    <li class="breadcrumb-item active">Edit Menu</li>
@endsection

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="card">
                <div class="card-header"><i class="bi bi-pencil me-2"></i>Edit Menu Item</div>
                <div class="card-body">
                    <form action="{{ route('menus.update', $menu->child_id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label class="form-label">Child ID</label>
                            <input type="text" class="form-control" value="{{ $menu->child_id }}" disabled>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Title <span class="text-danger">*</span></label>
                            <input type="text" name="TITLE" class="form-control @error('TITLE') is-invalid @enderror"
                                maxlength="100" value="{{ old('TITLE', $menu->title) }}">
                            @error('TITLE')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Item Type</label>
                            <select name="ITEM_TYPE" class="form-select">
                                <option value="0" {{ old('item_type', $menu->item_type) == '0' ? 'selected' : '' }}>0 -
                                    Root</option>
                                <option value="1" {{ old('item_type', $menu->item_type) == '1' ? 'selected' : '' }}>1 -
                                    Menu</option>
                                <option value="2" {{ old('item_type', $menu->item_type) == '2' ? 'selected' : '' }}>2 -
                                    Sub Menu</option>
                                <option value="3" {{ old('item_type', $menu->item_type) == '3' ? 'selected' : '' }}>3 -
                                    Page</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Parent ID</label>
                            <select name="PARENT_ID" class="form-select">
                                <option value="">Root (no parent)</option>
                                @foreach ($menus as $m)
                                    @continue($m->child_id === $menu->child_id)
                                    <option value="{{ $m->child_id }}"
                                        {{ old('parent_id', $menu->parent_id) == $m->child_id ? 'selected' : '' }}>
                                        {{ $m->child_id }} - {{ $m->title }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Object Name</label>
                            <input type="text" name="OBJECT_NAME" class="form-control" maxlength="30"
                                value="{{ old('OBJECT_NAME', $menu->OBJECT_NAME) }}">
                        </div>

                        <div class="row g-2 mb-3">
                            <div class="col-md-6">
                                <label class="form-label">File Name</label>
                                <input type="text" name="FILE_NAME" class="form-control" maxlength="256"
                                    value="{{ old('file_name', $menu->file_name) }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Directory</label>
                                <input type="text" name="DIR" class="form-control" maxlength="256"
                                    value="{{ old('dir', $menu->dir) }}">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="DESCRIPTION" class="form-control" rows="2" maxlength="512">{{ old('description', $menu->description) }}</textarea>
                        </div>

                        <div class="row g-2 mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Sort By</label>
                                <input type="number" name="SORT_BY" class="form-control"
                                    value="{{ old('SORT_BY', $menu->SORT_BY) }}" min="0">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Is Active</label>
                                <select name="IS_ACTIVE" class="form-select">
                                    <option value="Y"
                                        {{ old('IS_ACTIVE', $menu->IS_ACTIVE) == 'Y' ? 'selected' : '' }}>Y - Active
                                    </option>
                                    <option value="N"
                                        {{ old('IS_ACTIVE', $menu->IS_ACTIVE) == 'N' ? 'selected' : '' }}>N - Inactive
                                    </option>
                                </select>
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Update
                                Menu</button>
                            <a href="{{ route('menus.index') }}" class="btn btn-outline-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
