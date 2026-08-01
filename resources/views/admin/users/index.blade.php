@extends('layouts.app')
@section('title', 'User Management')
@section('page-title', 'User Management')
@section('breadcrumb')
    <li class="breadcrumb-item active">Users</li>
@endsection

@section('content')
    <div class="row g-3">
        {{-- Create User Form --}}
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header"><i class="bi bi-person-plus me-2"></i>Create Oracle User</div>
                <div class="card-body">
                    <form action="{{ route('users.store') }}" method="POST">
                        @csrf
                        <p class="section-label">Identity</p>
                        <div class="mb-3">
                            <label class="form-label">User ID <span class="text-danger">*</span></label>
                            <input type="text" name="USER_ID" class="form-control @error('USER_ID') is-invalid @enderror"
                                placeholder="e.g. JOHN_DOE" maxlength="30" value="{{ old('USER_ID') }}"
                                style="text-transform:uppercase;">
                            @error('USER_ID')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="row g-2 mb-3">
                            <div class="col-6">
                                <label class="form-label">Employee ID <span class="text-danger">*</span></label>
                                <input type="text" name="EMPLOYEE_ID"
                                    class="form-control @error('EMPLOYEE_ID') is-invalid @enderror" placeholder="EMP001"
                                    maxlength="15" value="{{ old('EMPLOYEE_ID') }}">
                                @error('EMPLOYEE_ID')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-6">
                                <label class="form-label">Mobile</label>
                                <input type="text" name="USER_MOBILE" class="form-control" placeholder="01600000000"
                                    maxlength="11" value="{{ old('USER_MOBILE') }}">
                            </div>
                        </div>

                        <p class="section-label">Access & Role</p>
                        <div class="mb-3">
                            <label class="form-label">User Group <span class="text-danger">*</span></label>
                            <select name="USER_GROUP_ID" class="form-select @error('user_group_id') is-invalid @enderror">
                                <option value="">— select group —</option>
                                @foreach ($groups as $g)
                                    <option value="{{ $g->user_group_id }}"
                                        {{ old('USER_GROUP_ID') == $g->user_group_id ? 'selected' : '' }}>
                                        {{ $g->user_group_id }}
                                        — {{ $g->group_title }}</option>
                                @endforeach
                            </select>
                            @error('user_group_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">User Role <span class="text-danger">*</span></label>
                            <input type="text" name="USER_ROLE"
                                class="form-control @error('USER_ROLE') is-invalid @enderror"
                                placeholder="e.g. SALES_MANAGER" maxlength="100" value="{{ old('USER_ROLE') }}">
                            @error('USER_ROLE')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="row g-2 mb-3">
                            <div class="col-6">
                                <label class="form-label">User Type</label>
                                <input type="text" name="USER_TYPE" class="form-control" placeholder="INTERNAL"
                                    maxlength="100" value="{{ old('USER_TYPE') }}">
                            </div>
                            <div class="col-6">
                                <label class="form-label">Company ID</label>
                                <input type="text" name="COMPANY_ID" class="form-control" placeholder="COMP_001"
                                    maxlength="30" value="{{ old('COMPANY_ID') }}">
                            </div>
                        </div>

                        <p class="section-label">Credentials</p>
                        <div class="mb-3">
                            <label class="form-label">Initial Password <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="password" name="INITIAL_PASSWORD" id="passInput"
                                    class="form-control @error('INITIAL_PASSWORD') is-invalid @enderror"
                                    placeholder="Set password" maxlength="30">
                                <button class="btn btn-outline-secondary" type="button" onclick="togglePw()"><i
                                        class="bi bi-eye" id="pwIcon"></i></button>
                            </div>
                            @error('INITIAL_PASSWORD')
                                <div class="text-danger" style="font-size:12px;">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="row g-2 mb-3">
                            <div class="col-6">
                                <label class="form-label">Status</label>
                                <select name="USER_STATUS" class="form-select">
                                    <option value="ACTIVE"
                                        {{ old('USER_STATUS', 'ACTIVE') == 'ACTIVE' ? 'selected' : '' }}>Active
                                    </option>
                                    <option value="INACTIVE" {{ old('USER_STATUS') == 'INACTIVE' ? 'selected' : '' }}>
                                        Inactive
                                    </option>
                                    <option value="LOCKED" {{ old('USER_STATUS') == 'LOCKED' ? 'selected' : '' }}>Locked
                                    </option>
                                </select>
                            </div>
                            <div class="col-6">
                                <label class="form-label">Credit Limit</label>
                                <input type="number" name="CREDTI_LIMIT" class="form-control" placeholder="0"
                                    min="0" value="{{ old('CREDTI_LIMIT', 0) }}">
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary w-100"><i class="bi bi-person-plus me-1"></i>Create
                            User</button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Users Table --}}
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span><i class="bi bi-people me-2"></i>All Users
                        <span
                            class="badge bg-secondary-subtle text-secondary border border-secondary-subtle ms-1">{{ $users->count() }}</span>
                    </span>
                    <a href="{{ route('user-menu.index') }}" class="btn btn-sm btn-primary"><i
                            class="bi bi-toggles me-1"></i>Manage Menu Permissions</a>
                    <a href="{{ route('user-company.index') }}" class="btn btn-sm btn-primary"><i
                            class="bi bi-toggles me-1"></i> Company Permissions </a>
                </div>
                <div class="card-body">
                    <table class="table table-hover datatable" style="width:100%">
                        <thead class="table-light">
                            <tr>
                                <th></th>
                                <th>User ID</th>
                                <th>Employee</th>
                                <th>Group</th>
                                <th>Role</th>
                                <th>Status</th>
                                <th>Mobile</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                @php
                                    $colors = ['#0d6efd', '#198754', '#fd7e14', '#dc3545', '#6610f2'];
                                    $color = $colors[abs(crc32($user->USER_ID)) % 5];
                                @endphp
                                <tr>
                                    <td>
                                        <div
                                            style="width:30px;height:30px;border-radius:50%;background:{{ $color }};color:#fff;display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:600;">
                                            {{ strtoupper(substr($user->USER_ID, 0, 2)) }}
                                        </div>
                                    </td>
                                    <td class="fw-500"><code class="id-tag">{{ $user->user_id }}</code></td>
                                    <td class="text-muted">{{ $user->employee_id }}</td>
                                    <td><span
                                            class="badge bg-primary-subtle text-primary border border-primary-subtle">{{ $user->user_group_id }}</span>
                                    </td>
                                    <td style="font-size:12px;">{{ $user->user_role }}</td>
                                    <td>
                                        @if ($user->user_status === 'U')
                                            <span
                                                class="badge bg-success-subtle text-success border border-success-subtle">Active</span>
                                        @elseif($user->user_status === 'L')
                                            <span
                                                class="badge bg-danger-subtle text-danger border border-danger-subtle">Locked</span>
                                        @else
                                            <span
                                                class="badge bg-secondary-subtle text-secondary border border-secondary-subtle">Inactive</span>
                                        @endif
                                    </td>
                                    <td style="font-size:12px;" class="text-muted">{{ $user->user_mobile ?: '—' }}</td>
                                    <td>
                                        <div class="d-flex gap-1">
                                            <a href="{{ route('user-menu.index', ['user' => $user->USER_ID]) }}"
                                                class="btn btn-sm btn-outline-primary" title="Manage Permissions"><i
                                                    class="bi bi-key"></i></a>
                                            <a href="{{ route('users.edit', $user->user_id) }}"
                                                class="btn btn-sm btn-outline-secondary"><i class="bi bi-pencil"></i></a>
                                            <form action="{{ route('users.destroy', $user->user_id) }}" method="POST"
                                                onsubmit="return confirm('Delete this user?')">
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
        function togglePw() {
            const input = document.getElementById('passInput');
            const icon = document.getElementById('pwIcon');
            if (input.type === 'password') {
                input.type = 'text';
                icon.className = 'bi bi-eye-slash';
            } else {
                input.type = 'password';
                icon.className = 'bi bi-eye';
            }
        }
    </script>
@endpush
