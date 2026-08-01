{{-- resources/views/setup/partials/sidebar.blade.php --}}
<div class="setup-sidebar">
    <div class="setup-sb-label">SETUP</div>
    @php
        $setupNav = [
            ['route' => 'setup.department.index', 'label' => 'Department', 'path' => 'M1 2h14v12H1zM1 6h14M5 6v8'],
            [
                'route' => 'setup.section.index',
                'label' => 'Section',
                'path' => 'M8 5a3 3 0 1 0 0-6 3 3 0 0 0 0 6zM2 8h3M11 8h3M8 2v3M8 11v3',
            ],
            [
                'route' => 'setup.designation.index',
                'label' => 'Designation',
                'path' => 'M8 2a3 3 0 1 0 0 6 3 3 0 0 0 0-6zM2 14c0-3.3 2.7-5 6-5s6 1.7 6 5',
            ],
            ['route' => 'setup.floor.index', 'label' => 'Floor', 'path' => 'M1 14h14M3 14V7l5-5 5 5v7'],
            ['route' => 'setup.line.index', 'label' => 'Line', 'path' => 'M2 5h12M2 8h12M2 11h12'],
            [
                'route' => 'setup.shift.index',
                'label' => 'Shift',
                'path' => 'M8 2a6 6 0 1 0 0 12A6 6 0 0 0 8 2zM8 5v3l2 2',
            ],
            [
                'route' => 'setup.religion.index',
                'label' => 'Religion',
                'path' => 'M8 2l1.5 4.5H14l-3.7 2.7 1.4 4.3L8 11l-3.7 2.5 1.4-4.3L2 6.5h4.5z',
            ],
            [
                'route' => 'setup.emp-type.index',
                'label' => 'Employee Type',
                'path' =>
                    'M11 14v-2a3 3 0 0 0-3-3H4a3 3 0 0 0-3 3v2M6 9a3 3 0 1 0 0-6 3 3 0 0 0 0 6zM15 14v-2a3 3 0 0 0-2-2.8M11 3a3 3 0 0 1 0 5.8',
            ],
        ];
    @endphp
    @foreach ($setupNav as $nav)
        @php $routeBase = str_replace('.index', '.*', $nav['route']); @endphp
        <a href="{{ route($nav['route']) }}" class="setup-nav-link {{ request()->routeIs($routeBase) ? 'active' : '' }}">
            <svg width="15" height="15" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.8">
                <path d="{{ $nav['path'] }}" />
            </svg>
            {{ $nav['label'] }}
        </a>
    @endforeach
</div>
