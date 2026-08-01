@extends('layouts.app')
@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('breadcrumb')
    <li class="breadcrumb-item active">Dashboard</li>
@endsection

@section('content')
    <div class="row g-3 mb-4">
        @php
            $stats = [
                [
                    'label' => 'Menu Items',
                    'val' => $menuCount,
                    'icon' => 'bi-layout-sidebar',
                    'bg' => 'bg-primary-subtle',
                    'ic' => 'text-primary',
                ],
                [
                    'label' => 'Routes',
                    'val' => $routeCount,
                    'icon' => 'bi-signpost-split',
                    'bg' => 'bg-info-subtle',
                    'ic' => 'text-info',
                ],
                [
                    'label' => 'User Groups',
                    'val' => $groupCount,
                    'icon' => 'bi-people',
                    'bg' => 'bg-success-subtle',
                    'ic' => 'text-success',
                ],
                [
                    'label' => 'Users',
                    'val' => $userCount,
                    'icon' => 'bi-person-badge',
                    'bg' => 'bg-warning-subtle',
                    'ic' => 'text-warning',
                ],
                [
                    'label' => 'Permissions',
                    'val' => $permissionCount,
                    'icon' => 'bi-key',
                    'bg' => 'bg-danger-subtle',
                    'ic' => 'text-danger',
                ],
                [
                    'label' => 'Group Access',
                    'val' => $groupMenuCount,
                    'icon' => 'bi-toggles',
                    'bg' => 'bg-purple-subtle',
                    'ic' => 'text-purple',
                ],
            ];
        @endphp
        @foreach ($stats as $s)
            <div class="col-6 col-md-4 col-xl-2">
                <div class="stat-card">
                    <div class="d-flex align-items-start justify-content-between mb-2">
                        <div class="stat-icon {{ $s['bg'] }} {{ $s['ic'] }}">
                            <i class="bi {{ $s['icon'] }}"></i>
                        </div>
                    </div>
                    <div class="stat-val">{{ $s['val'] }}</div>
                    <div class="stat-lbl">{{ $s['label'] }}</div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="row g-3">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span><i class="bi bi-clock-history me-2 text-muted"></i>Recent Users</span>
                    <a href="{{ route('users.index') }}" class="btn btn-sm btn-outline-primary">View all</a>
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>User ID</th>
                                <th>Employee</th>
                                <th>Group</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentUsers as $u)
                                <tr>
                                    <td><code class="id-tag">{{ $u->user_id }}</code></td>
                                    <td>{{ $u->employee_id }}</td>
                                    <td><span
                                            class="badge bg-primary-subtle text-primary border border-primary-subtle">{{ $u->user_group_id }}</span>
                                    </td>
                                    <td>
                                        @if ($u->user_status === 'U')
                                            <span
                                                class="badge bg-success-subtle text-success border border-success-subtle">Active</span>
                                        @elseif($u->user_status === 'L')
                                            <span
                                                class="badge bg-danger-subtle text-danger border border-danger-subtle">Locked</span>
                                        @else
                                            <span
                                                class="badge bg-secondary-subtle text-secondary border border-secondary-subtle">Inactive</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-3">No users yet</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span><i class="bi bi-layout-sidebar me-2 text-muted"></i>Menu Hierarchy</span>
                    <a href="{{ route('menus.index') }}" class="btn btn-sm btn-outline-primary">Manage</a>
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Title</th>
                                <th>Child ID</th>
                                <th>Type</th>
                                <th>Active</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentMenus as $m)
                                <tr>
                                    <td class="fw-500">{{ $m->title }}</td>
                                    <td><code class="id-tag">{{ $m->child_id }}</code></td>
                                    <td><span
                                            class="badge bg-secondary-subtle text-secondary border border-secondary-subtle">{{ $m->ITEM_TYPE }}</span>
                                    </td>
                                    <td>
                                        @if ($m->is_active === 'Y')
                                            <span class="badge bg-success-subtle text-success">Active</span>
                                        @else
                                            <span class="badge bg-secondary-subtle text-secondary">Inactive</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-3">No menus yet</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
