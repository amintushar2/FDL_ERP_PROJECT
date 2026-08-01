@extends('layouts.app')
@section('title', 'User Groups')
@section('page-title', 'User Groups')
@section('breadcrumb')
    <li class="breadcrumb-item active">Groups</li>
@endsection

@section('content')
    <div class="row g-3">
        {{-- Create Group Form --}}
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header"><i class="bi bi-collection me-2"></i>Create User Group</div>
                <div class="card-body">
                    <form action="{{ route('groups.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Group ID <span class="text-danger">*</span></label>
                            <input type="text" name="USER_GROUP_ID"
                                class="form-control @error('USER_GROUP_ID') is-invalid @enderror" placeholder="e.g. ADMIN"
                                maxlength="10" value="{{ old('USER_GROUP_ID') }}" style="text-transform:uppercase;">
                            @error('USER_GROUP_ID')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Group Title <span class="text-danger">*</span></label>
                            <input type="text" name="GROUP_TITLE"
                                class="form-control @error('GROUP_TITLE') is-invalid @enderror"
                                placeholder="e.g. Administrators" maxlength="30" value="{{ old('GROUP_TITLE') }}">
                            @error('GROUP_TITLE')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="GROUP_DESCRIPTION" class="form-control" rows="3" placeholder="Optional description">{{ old('GROUP_DESCRIPTION') }}</textarea>
                        </div>
                        <button type="submit" class="btn btn-primary w-100"><i class="bi bi-plus-circle me-1"></i>Create
                            Group</button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Groups Table --}}
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span><i class="bi bi-collection me-2"></i>All Groups
                        <span
                            class="badge bg-secondary-subtle text-secondary border border-secondary-subtle ms-1">{{ $groups->count() }}</span>
                    </span>
                    <a href="{{ route('group-menu.index') }}" class="btn btn-sm btn-primary"><i
                            class="bi bi-toggles me-1"></i>Manage Menu Access</a>
                </div>
                <div class="card-body">
                    <table class="table table-hover datatable" style="width:100%">
                        <thead class="table-light">
                            <tr>
                                <th>Group ID</th>
                                <th>Title</th>
                                <th>Description</th>
                                <th>Users</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($groups as $group)
                                <tr>
                                    <td class="fw-500"><code class="id-tag">{{ $group->user_group_id }}</code></td>
                                    <td>{{ $group->group_title }}</td>
                                    <td style="font-size:12px;" class="text-muted">{{ $group->group_description ?: '—' }}
                                    </td>
                                    <td><span
                                            class="badge bg-info-subtle text-info border border-info-subtle">{{ $group->users()->count() }}</span>
                                    </td>
                                    <td>
                                        <div class="d-flex gap-1">
                                            <form action="{{ route('groups.destroy', $group->user_group_id) }}"
                                                method="POST"
                                                onsubmit="return confirm('Delete this group? This will affect {{ $group->users()->count() }} users.')">
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

@push('scripts')
    <script>
        $('.datatable').DataTable({
            pageLength: 25,
            order: [
                [0, 'asc']
            ],
            columnDefs: [{
                orderable: false,
                targets: [3, 4]
            }]
        });
    </script>
@endpush
