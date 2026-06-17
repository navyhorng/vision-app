<li class="nav-item">
    <a class="nav-link" href="{{ backpack_url('') }}">
        <i class="la la-home nav-icon"></i>
        {{ trans('backpack::base.dashboard') }}
    </a>
</li>

<x-backpack::menu-item title="Products" icon="la la-box" :link="backpack_url('product')" />
<x-backpack::menu-item title="OCR Results" icon="la la-file-text" :link="backpack_url('ocr-result')" />

@if (backpack_user()->hasRole('admin'))
    <x-backpack::menu-dropdown title="Admin" icon="la la-user-shield">
        <x-backpack::menu-dropdown-item title="Users" icon="la la-users" :link="backpack_url('user')" />
        <x-backpack::menu-dropdown-item title="Roles" icon="la la-user-tag" :link="backpack_url('role')" />
        <x-backpack::menu-dropdown-item title="Permissions" icon="la la-user-lock" :link="backpack_url('permission')" />
    </x-backpack::menu-dropdown>
@endif
