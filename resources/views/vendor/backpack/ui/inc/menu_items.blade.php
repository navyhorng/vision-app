<li class="nav-item">
    <a class="nav-link" href="{{ backpack_url('') }}">
        <i class="la la-home nav-icon"></i>
        {{ trans('backpack::base.dashboard') }}
    </a>
</li>

<x-backpack::menu-item title="Scan Requests" icon="la la-search" :link="backpack_url('scan-request')" />
<x-backpack::menu-item title="Scan Results" icon="la la-clipboard-check" :link="backpack_url('scan-result')" />
<x-backpack::menu-item title="User Products" icon="la la-box" :link="backpack_url('user-product')" />

@if (backpack_user()->hasRole('admin'))
    <x-backpack::menu-dropdown title="Admin" icon="la la-user-shield">
        <x-backpack::menu-dropdown-item title="Users" icon="la la-users" :link="backpack_url('user')" />
        <x-backpack::menu-dropdown-item title="Roles" icon="la la-user-tag" :link="backpack_url('role')" />
        <x-backpack::menu-dropdown-item title="Permissions" icon="la la-user-lock" :link="backpack_url('permission')" />
        <x-backpack::menu-dropdown-item title="Logs" icon="la la-file-alt" link="{{ route('admin.logs') }}" />
    </x-backpack::menu-dropdown>
@endif


