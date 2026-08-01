@extends('layouts.app')
@section('title', 'Route Permissions')
@section('page-title', 'Route Permissions')
@section('breadcrumb')
    <li class="breadcrumb-item active">Route Permissions</li>
@endsection

@section('content')
    @php
        $level2Permissions = $permissions->filter(fn($p) => empty($p->SUB_MENU_2));
    @endphp

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <div class="row g-3">
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header"><i class="bi bi-plus-circle me-2"></i>Add Submenu Permission</div>
                <div class="card-body">
                    <form action="{{ route('permissions.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">Group</label>
                            <select name="USER_GROUP_ID" class="form-select" required>
                                <option value="">Select group</option>
                                @foreach ($groups as $group)
                                    <option value="{{ $group->user_group_id }}"
                                        {{ old('user_group_id') == $group->USER_GROUP_ID ? 'selected' : '' }}>
                                        {{ $group->user_group_id }} - {{ $group->group_title }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Menu Item</label>
                            <select name="MENU_ITEM_ID" class="form-select" required>
                                <option value="">Select menu</option>
                                @foreach ($menus as $menu)
                                    <option value="{{ $menu->child_id }}"
                                        {{ old('menu_item_id') == $menu->child_id ? 'selected' : '' }}>
                                        {{ $menu->child_id }} - {{ $menu->title }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Route</label>
                            <select name="SUB_MENU_ID" class="form-select" onchange="fillRouteFields(this)" required>
                                <option value="">Select route</option>
                                @foreach ($routes as $route)
                                    <option value="{{ $route->route_id }}" data-route="{{ $route->ROUTE_PATH }}"
                                        data-name="{{ $route->component ?: $route->route_id }}"
                                        data-menu="{{ $route->menu_child_id }}"
                                        {{ old('SUB_MENU_ID') == $route->route_id ? 'selected' : '' }}>
                                        {{ $route->route_id }} - {{ $route->component ?: $route->route_path }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Level</label>
                            <select name="LEVEL" id="permissionLevel" class="form-select" onchange="toggleParentSelect()">
                                <option value="2" {{ old('LEVEL') == '2' ? 'selected' : '' }}>Submenu Level 2</option>
                                <option value="3" {{ old('LEVEL') == '3' ? 'selected' : '' }}>Submenu Level 3</option>
                            </select>
                        </div>

                        <div class="mb-3" id="parentSubmenuWrap" style="display:none;">
                            <label class="form-label">Parent Submenu 2</label>
                            <select name="SUB_MENU_2" class="form-select">
                                <option value="">Select parent</option>
                                @foreach ($level2Permissions as $parent)
                                    <option value="{{ $parent->sub_menu_id }}"
                                        {{ old('SUB_MENU_2') == $parent->sub_menu_id ? 'selected' : '' }}>
                                        {{ $parent->user_group_id }} - {{ $parent->sub_menu_name }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="form-text">For level 3, this stores the parent route ID in SUB_MENU_2.</div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Submenu Name</label>
                            <input type="text" name="SUB_MENU_NAME" id="newSubmenuName" class="form-control"
                                value="{{ old('sub_menu_name') }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Route Path</label>
                            <input type="text" name="ROUTE" id="newRoutePath" class="form-control"
                                value="{{ old('ROUTE') }}">
                        </div>

                        <input type="hidden" name="SUB_MENU_1" value="{{ old('SUB_MENU_1') }}">

                        <div class="mb-3">
                            <label class="form-label">Enabled</label>
                            <select name="ENABLED" class="form-select">
                                <option value="Y" {{ old('ENABLED', 'Y') == 'Y' ? 'selected' : '' }}>Y - Enabled
                                </option>
                                <option value="N" {{ old('ENABLED') == 'N' ? 'selected' : '' }}>N - Disabled</option>
                            </select>
                        </div>

                        <button class="btn btn-primary w-100"><i class="bi bi-save me-1"></i>Save Permission</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <i class="bi bi-list-check me-2"></i>Assigned Submenus
                    <span
                        class="badge bg-secondary-subtle text-secondary border border-secondary-subtle ms-1">{{ $permissions->count() }}</span>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Group</th>
                                    <th>Menu</th>
                                    <th>Route ID</th>
                                    <th>Level</th>
                                    <th>Name / Route</th>
                                    <th>Enabled</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($permissions as $permission)
                                    @php
                                        $updateFormId = 'permission-update-' . $loop->iteration;
                                    @endphp
                                    <tr>
                                        <td><code>{{ $permission->USER_GROUP_ID }}</code></td>
                                        <td>
                                            <select name="MENU_ITEM_ID" class="form-select form-select-sm"
                                                form="{{ $updateFormId }}">
                                                @foreach ($menus as $menu)
                                                    <option value="{{ $menu->CHILD_ID }}"
                                                        {{ $permission->MENU_ITEM_ID == $menu->CHILD_ID ? 'selected' : '' }}>
                                                        {{ $menu->CHILD_ID }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td><code>{{ $permission->SUB_MENU_ID }}</code></td>
                                        <td>
                                            @if ($permission->SUB_MENU_2)
                                                <span class="badge bg-info-subtle text-info border border-info-subtle">Level
                                                    3</span>
                                                <input type="text" name="SUB_MENU_2"
                                                    class="form-control form-control-sm mt-1"
                                                    value="{{ $permission->SUB_MENU_2 }}" form="{{ $updateFormId }}">
                                            @else
                                                <span
                                                    class="badge bg-primary-subtle text-primary border border-primary-subtle">Level
                                                    2</span>
                                                <input type="hidden" name="SUB_MENU_2" value=""
                                                    form="{{ $updateFormId }}">
                                            @endif
                                            <input type="hidden" name="SUB_MENU_1"
                                                value="{{ $permission->SUB_MENU_1 }}" form="{{ $updateFormId }}">
                                        </td>
                                        <td>
                                            <input type="text" name="SUB_MENU_NAME"
                                                class="form-control form-control-sm mb-1"
                                                value="{{ $permission->SUB_MENU_NAME }}" required
                                                form="{{ $updateFormId }}">
                                            <input type="text" name="ROUTE" class="form-control form-control-sm"
                                                value="{{ $permission->ROUTE }}" form="{{ $updateFormId }}">
                                        </td>
                                        <td>
                                            <select name="ENABLED" class="form-select form-select-sm"
                                                form="{{ $updateFormId }}">
                                                <option value="Y"
                                                    {{ $permission->ENABLED == 'Y' ? 'selected' : '' }}>Y</option>
                                                <option value="N"
                                                    {{ $permission->ENABLED == 'N' ? 'selected' : '' }}>N</option>
                                            </select>
                                        </td>
                                        <td>
                                            <div class="d-flex gap-1">
                                                <form id="{{ $updateFormId }}"
                                                    action="{{ route('permissions.update', [$permission->USER_GROUP_ID, $permission->SUB_MENU_ID]) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                </form>
                                                <button class="btn btn-sm btn-outline-primary"
                                                    form="{{ $updateFormId }}">Update</button>
                                                <form
                                                    action="{{ route('permissions.destroy', [$permission->USER_GROUP_ID, $permission->SUB_MENU_ID]) }}"
                                                    method="POST"
                                                    onsubmit="return confirm('Delete this submenu permission?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-sm btn-outline-danger">Delete</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-muted py-4">No submenu permissions
                                            found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function fillRouteFields(select) {
            const selected = select.options[select.selectedIndex];
            const name = selected.dataset.name || '';
            const route = selected.dataset.route || '';
            const menu = selected.dataset.menu || '';

            document.getElementById('newSubmenuName').value = name;
            document.getElementById('newRoutePath').value = route;

            const menuSelect = document.querySelector('select[name="MENU_ITEM_ID"]');
            if (menuSelect && menu) {
                menuSelect.value = menu;
            }
        }

        function toggleParentSelect() {
            const level = document.getElementById('permissionLevel').value;
            document.getElementById('parentSubmenuWrap').style.display = level === '3' ? 'block' : 'none';
        }

        document.addEventListener('DOMContentLoaded', toggleParentSelect);
    </script>
@endpush
