@foreach ($menu as $value)

    @if ($value->user_group_id == $data->user_group_id)
        @php
            /*
             * Determine if ANY child of this top-level item matches
             * the current URL so we can auto-open the parent.
             */
            $isParentActive = false;

            foreach ($submenu as $s) {
                if ($s->menu_item_id != $value->menu_item_id) {
                    continue;
                }

                // Does this submenu link to the current page?
                if ($s->route && request()->is(ltrim($s->route, '/'))) {
                    $isParentActive = true;
                    break;
                }

                // Does any level-3 child match?
                foreach ($submenu2 as $s2) {
                    if ($s2->sub_menu_2 == $s->sub_menu_id && $s2->route && request()->is(ltrim($s2->route, '/'))) {
                        $isParentActive = true;
                        break 2;
                    }
                }
            }
        @endphp

        <li class="nav-item has-treeview {{ $isParentActive ? 'menu-open' : '' }}">

            <a href="#" class="nav-link {{ $isParentActive ? 'active' : '' }}">
                <i class="nav-icon fa-solid fa-gauge-high"></i>
                <p>
                    {{ $value->title }}
                    <i class="right fas fa-angle-left"></i>
                </p>
            </a>

            <ul class="nav nav-treeview">

                @foreach ($submenu as $submenus)
                    @if ($value->menu_item_id == $submenus->menu_item_id)
                        @php
                            $child2 = collect($submenu2)->where('sub_menu_2', $submenus->sub_menu_id);

                            // Is any level-3 item under this group active?
                            $isGroupActive = $child2->contains(function ($s2) {
                                return $s2->route && request()->is(ltrim($s2->route, '/'));
                            });
                        @endphp

                        {{-- HAS level-3 children --}}
                        @if ($child2->count() > 0)
                            <li class="nav-item has-treeview {{ $isGroupActive ? 'menu-open' : '' }}">

                                <a href="#" class="nav-link {{ $isGroupActive ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>
                                        {{ $submenus->sub_menu_name }}
                                        <i class="right fas fa-angle-left"></i>
                                    </p>
                                </a>

                                <ul class="nav nav-treeview">
                                    @foreach ($child2 as $submenus2)
                                        <li class="nav-item">
                                            <a href="{{ url($submenus2->route) }}"
                                                class="nav-link {{ $submenus2->route && request()->is(ltrim($submenus2->route, '/')) ? 'active' : '' }}">
                                                <i class="far fa-dot-circle nav-icon"></i>
                                                {{ $submenus2->sub_menu_name }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>

                            </li>

                            {{-- NO level-3 children --}}
                        @else
                            <li class="nav-item">
                                <a href="{{ url($submenus->route) }}"
                                    class="nav-link {{ $submenus->route && request()->is(ltrim($submenus->route, '/')) ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    {{ $submenus->sub_menu_name }}
                                </a>
                            </li>
                        @endif
                    @endif
                @endforeach

            </ul>

        </li>
    @endif

@endforeach
