@can('view_crmenquery_list')
    <li class="nav-item {{ request()->routeIs('admin.crmenquery.*')  ? 'active' : '' }}">
        <a
            href="{{ route('admin.crmenquery.index') }}"
            data-href="{{ route('admin.crmenquery.index') }}"
            class="nav-link">
            <i class="link-icon" data-feather="heart"></i>
            <span class="link-title">Leads Setting</span>
        </a>
</li>
@endcan
