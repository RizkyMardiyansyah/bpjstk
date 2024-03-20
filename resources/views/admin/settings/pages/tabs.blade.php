<div class="li">
<ul class="nav nav-pills li" id="myTab3" role="tablist"
    style="
    margin-bottom: 4%;
"
>
    @can('admin_pages_list')
        <li class="nav-item">
            <a class="nav-link {{ (request()->is(getAdminPanelUrl('/pages', false))) ? 'active' : '' }}" href="{{ getAdminPanelUrl() }}/pages">{{ trans('admin/main.lists') }}</a>
        </li>
    @endcan()

    @can('admin_pages_create')
        <li class="nav-item">
            <a class="nav-link {{ (request()->is(getAdminPanelUrl('/pages/create', false))) ? 'active' : '' }}" href="{{ getAdminPanelUrl() }}/pages/create">{{ trans('admin/main.new_page') }}</a>
        </li>
    @endcan()
</ul>
</div>
