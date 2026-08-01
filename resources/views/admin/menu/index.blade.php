@extends('layouts.app')
@section('title', 'Menu Hierarchy')
@section('page-title', 'Menu Hierarchy')
@section('breadcrumb')
    <li class="breadcrumb-item active">Menu Hierarchy</li>
@endsection

@section('content')
    <div class="row g-3">
        {{-- Add Menu Form --}}
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header"><i class="bi bi-plus-circle me-2"></i>Add Menu Item</div>
                <div class="card-body">
                    <form action="{{ route('menus.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Title <span class="text-danger">*</span></label>
                            <input type="text" name="TITLE" class="form-control @error('TITLE') is-invalid @enderror"
                                placeholder="e.g. Sales Module" maxlength="100" value="{{ old('TITLE') }}">
                            @error('TITLE')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Child ID <span class="text-danger">*</span></label>
                            <input type="text" name="child_id"
                                class="form-control @error('child_id') is-invalid @enderror" placeholder="e.g. MENU_005"
                                maxlength="15" value="{{ old('child_id') }}" style="text-transform:uppercase;">
                            @error('child_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Item Type</label>
                            <select name="ITEM_TYPE" class="form-select">
                                <option value="0" {{ old('ITEM_TYPE') == '0' ? 'selected' : '' }}>0 — Root</option>
                                <option value="1" {{ old('ITEM_TYPE', '1') == '1' ? 'selected' : '' }}>1 — Menu
                                </option>
                                <option value="2" {{ old('ITEM_TYPE') == '2' ? 'selected' : '' }}>2 — Sub Menu</option>
                                <option value="3" {{ old('ITEM_TYPE') == '3' ? 'selected' : '' }}>3 — Page</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Parent ID</label>
                            <select name="PARENT_ID" class="form-select">
                                <option value="">— Root (no parent) —</option>
                                @foreach ($menus as $m)
                                    <option value="{{ $m->child_id }}"
                                        {{ old('PARENT_ID') == $m->child_id ? 'selected' : '' }}>
                                        {{ $m->child_id }} — {{ $m->TITLE }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Object Name</label>
                            <input type="text" name="OBJECT_NAME" class="form-control" placeholder="e.g. SalesModule"
                                maxlength="30" value="{{ old('OBJECT_NAME') }}">
                        </div>
                        <div class="row g-2 mb-3">
                            <div class="col-6">
                                <label class="form-label">File Name</label>
                                <input type="text" name="FILE_NAME" class="form-control" placeholder="sales.html"
                                    maxlength="256" value="{{ old('FILE_NAME') }}">
                            </div>
                            <div class="col-6">
                                <label class="form-label">Directory</label>
                                <input type="text" name="DIR" class="form-control" placeholder="/modules/sales/"
                                    maxlength="256" value="{{ old('DIR') }}">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="DESCRIPTION" class="form-control" rows="2" placeholder="Brief description..." maxlength="512">{{ old('DESCRIPTION') }}</textarea>
                        </div>
                        <div class="row g-2 mb-3">
                            <div class="col-6">
                                <label class="form-label">Sort By</label>
                                <input type="number" name="SORT_BY" class="form-control" value="{{ old('SORT_BY', 0) }}"
                                    min="0">
                            </div>
                            <div class="col-6">
                                <label class="form-label">Is Active</label>
                                <select name="is_active" class="form-select">
                                    <option value="Y" {{ old('is_active', 'Y') == 'Y' ? 'selected' : '' }}>Y — Active
                                    </option>
                                    <option value="N" {{ old('is_active') == 'N' ? 'selected' : '' }}>N — Inactive
                                    </option>
                                </select>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary w-100"><i class="bi bi-save me-1"></i>Save Menu
                            Item</button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Menu Table --}}
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span><i class="bi bi-diagram-3 me-2"></i>All Menu Items
                        <span
                            class="badge bg-secondary-subtle text-secondary border border-secondary-subtle ms-1">{{ $menus->count() }}</span>
                    </span>
                    <div class="d-flex gap-2">
                        <a href="{{ route('menus.tree') }}" class="btn btn-sm btn-outline-secondary"><i
                                class="bi bi-diagram-2 me-1"></i>Tree View</a>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-hover datatable" style="width:100%">
                        <thead class="table-light">
                            <tr>
                                <th>Title</th>
                                <th>Child ID</th>
                                <th>Type</th>
                                <th>Parent</th>
                                <th>Object</th>
                                <th>Sort</th>
                                <th>Active</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($menus as $menu)
                                <tr>
                                    <td class="fw-500">{{ $menu->title }}</td>
                                    <td><code class="id-tag">{{ $menu->child_id ?? null }}</code></td>
                                    <td>
                                        @php $typeLabels = [0=>'Root',1=>'Menu',2=>'Sub Menu',3=>'Page']; @endphp
                                        <span
                                            class="badge bg-info-subtle text-info border border-info-subtle">{{ $typeLabels[$menu->ITEM_TYPE] ?? $menu->ITEM_TYPE }}</span>
                                    </td>
                                    <td><code class="id-tag">{{ $menu->parent_id ?: '-' }}</code></td>
                                    <td class="text-muted">{{ $menu->object_name ?: '-' }}</td>
                                    <td>{{ $menu->SORT_BY }}</td>
                                    <td>
                                        @if ($menu->is_active === 'Y')
                                            <span
                                                class="badge bg-success-subtle text-success border border-success-subtle">Active</span>
                                        @else
                                            <span
                                                class="badge bg-secondary-subtle text-secondary border border-secondary-subtle">Inactive</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex gap-1">
                                            <a href="{{ route('menus.edit', $menu->child_id ?? null) }}"
                                                class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
                                            <form action="{{ route('menus.destroy', $menu->child_id ?? null) }}"
                                                method="POST" onsubmit="return confirm('Delete this menu item?')">
                                                @csrf @method('DELETE')
                                                <button class="btn btn-sm btn-outline-danger"><i
                                                        class="bi bi-trash"></i></button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
