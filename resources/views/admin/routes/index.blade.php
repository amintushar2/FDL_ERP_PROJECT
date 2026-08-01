@extends('layouts.app')

@section('title', 'Route Entry')
@section('page-title', 'Route Entry')

@section('breadcrumb')
    <li class="breadcrumb-item active">Route Entry</li>
@endsection

@section('content')

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <div class="row g-3">

        {{-- LEFT FORM --}}
        <div class="col-lg-4">

            <div class="card">

                <div class="card-header">
                    <i class="bi bi-plus-circle me-2"></i>
                    Add Route
                </div>

                <div class="card-body">

                    <form action="{{ route('routes.store') }}" method="POST">

                        @csrf

                        {{-- ROUTE ID --}}
                        <div class="mb-3">

                            <label class="form-label">
                                Route ID
                            </label>

                            <input type="text" name="ROUTE_ID" class="form-control" required maxlength="30"
                                placeholder="HRM_EMP_ENTRY" style="text-transform:uppercase;">

                        </div>

                        {{-- ROUTE PATH --}}
                        <div class="mb-3">

                            <label class="form-label">
                                Route Path
                            </label>

                            <input type="text" name="ROUTE_PATH" class="form-control" required maxlength="256"
                                placeholder="hrm/employee-entry">

                        </div>

                        {{-- MAIN MENU --}}
                        <div class="mb-3">

                            <label class="form-label">
                                Main Menu
                            </label>

                            <select name="MENU_ID" id="MENU_ID" class="form-select" required>

                                <option value="">
                                    Select Main Menu
                                </option>

                                @foreach ($menus as $menu)
                                    <option value="{{ $menu->child_id }}" data-parent="{{ $menu->parent_id }}">

                                        {{ $menu->child_id }}
                                        -
                                        {{ $menu->title }}

                                    </option>
                                @endforeach

                            </select>

                        </div>

                        {{-- SUB MENU --}}
                        <div class="mb-3">

                            <label class="form-label">
                                Sub Menu
                            </label>

                            <select name="SUB_MENU_ID" id="SUB_MENU_ID" class="form-select">

                                <option value="">
                                    Select Sub Menu
                                </option>

                                @foreach ($menus as $menu)
                                    <option value="{{ $menu->parent_id }}">

                                        {{ $menu->parent_id }}

                                    </option>
                                @endforeach

                            </select>

                        </div>

                        {{-- PARENT ROUTE --}}
                        <div class="mb-3">

                            <label class="form-label">
                                Parent Route
                            </label>

                            <select name="SUB_MENU_1" id="SUB_MENU_1" class="form-select">

                                <option value="">
                                    Select Parent Route
                                </option>

                                @foreach ($routes as $route)
                                    <option value="{{ $route->route_id }}">

                                        {{ $route->route_id }}
                                        -
                                        {{ $route->component }}

                                    </option>
                                @endforeach

                            </select>

                        </div>

                        {{-- SUB MENU 2 --}}
                        <div class="mb-3">

                            <label class="form-label">
                                Sub Menu Level 2
                            </label>

                            <input type="text" name="SUB_MENU_2" class="form-control" maxlength="30"
                                placeholder="Optional">

                        </div>

                        {{-- COMPONENT --}}
                        <div class="mb-3">

                            <label class="form-label">
                                Component
                            </label>

                            <input type="text" name="COMPONENT" class="form-control" maxlength="100"
                                placeholder="EmployeeEntry">

                        </div>

                        {{-- SCREEN TITLE --}}
                        <div class="mb-3">

                            <label class="form-label">
                                Screen Name
                            </label>

                            <input type="text" name="SUB_MENU_NAME" class="form-control" maxlength="200"
                                placeholder="Employee Entry">

                        </div>

                        {{-- DESCRIPTION --}}
                        <div class="mb-3">

                            <label class="form-label">
                                Description
                            </label>

                            <textarea name="DESCRIPTION" class="form-control" rows="2"></textarea>

                        </div>

                        {{-- ACTIVE --}}
                        <div class="mb-3">

                            <label class="form-label">
                                Is Active
                            </label>

                            <select name="IS_ACTIVE" class="form-select">

                                <option value="Y">
                                    Y - Active
                                </option>

                                <option value="N">
                                    N - Inactive
                                </option>

                            </select>

                        </div>

                        {{-- SAVE BUTTON --}}
                        <button class="btn btn-primary w-100">

                            <i class="bi bi-save me-1"></i>

                            Save Route

                        </button>

                    </form>

                </div>

            </div>

        </div>

        {{-- RIGHT TABLE --}}
        <div class="col-lg-8">

            <div class="card">

                <div class="card-header">

                    <i class="bi bi-signpost me-2"></i>

                    All Routes

                    <span class="badge bg-secondary ms-1">

                        {{ $routes->count() }}

                    </span>

                </div>

                <div class="card-body">

                    <div class="table-responsive">

                        <table class="table table-bordered table-hover align-middle">

                            <thead class="table-light">

                                <tr>

                                    <th>Route ID</th>

                                    <th>Route Path</th>

                                    <th>Main Menu</th>

                                    <th>Sub Menu</th>

                                    <th>Parent Route</th>

                                    <th>Screen Name</th>

                                    <th>Status</th>

                                    <th width="60">Action</th>

                                </tr>

                            </thead>

                            <tbody>

                                @forelse($routes as $route)
                                    <tr>

                                        <td>
                                            <code>{{ $route->route_id }}</code>
                                        </td>

                                        <td>
                                            {{ $route->route_path }}
                                        </td>

                                        <td>
                                            {{ $route->menu_child_id }}
                                        </td>

                                        <td>
                                            {{ $route->sub_menu_id }}
                                        </td>

                                        <td>
                                            {{ $route->sub_menu_1 }}
                                        </td>

                                        <td>
                                            {{ $route->sub_menu_name }}
                                        </td>

                                        <td>

                                            @if ($route->is_active == 'Y')
                                                <span class="badge bg-success">
                                                    Active
                                                </span>
                                            @else
                                                <span class="badge bg-danger">
                                                    Inactive
                                                </span>
                                            @endif

                                        </td>

                                        <td>

                                            <form action="{{ route('routes.destroy', $route->route_id) }}" method="POST"
                                                onsubmit="return confirm('Delete Route ?')">

                                                @csrf
                                                @method('DELETE')

                                                <button class="btn btn-sm btn-danger">

                                                    <i class="bi bi-trash"></i>

                                                </button>

                                            </form>

                                        </td>

                                    </tr>

                                @empty

                                    <tr>

                                        <td colspan="8" class="text-center text-muted">

                                            No routes found

                                        </td>

                                    </tr>
                                @endforelse

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

        </div>

    </div>

    {{-- AUTO MENU SETUP --}}
    <script>
        document.getElementById('MENU_ID').addEventListener('change', function() {

            let selected = this.options[this.selectedIndex];

            let parentId = selected.getAttribute('data-parent');

            document.getElementById('SUB_MENU_ID').value = parentId;

        });
    </script>

@endsection
