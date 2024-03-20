<div class="li">
<ul class="nav nav-pills li" id="myTab3" role="tablist"
    style="
    margin-bottom: 4%;
"
>
    <li class="nav-item">
        <a class="nav-link {{ (request()->is(getAdminPanelUrl('/tags', false))) ? 'active' : '' }}" href="{{ getAdminPanelUrl() }}/tags">{{ trans('admin/main.list') }}</a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ (request()->is(getAdminPanelUrl('/tags/create', false))) ? 'active' : '' }}" href="{{ getAdminPanelUrl() }}/tags/create">{{ trans('admin/main.create') }}</a>
    </li>
</ul>
</div>
