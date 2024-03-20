<div class="li">
<ul class="nav nav-pills" id="myTab3" role="tablist"
    style="
    margin-bottom: 4%;
"
>
    @can('admin_additional_pages_404')
        <li class="nav-item">
            <a class="nav-link {{ (request()->is(getAdminPanelUrl('/additional_page/404', false))) ? 'active' : '' }}" href="{{ getAdminPanelUrl() }}/additional_page/404">{{ trans('admin/main.error_404') }}</a>
        </li>
    @endcan()

    @can('admin_additional_pages_contact_us')
        <li class="nav-item">
            <a class="nav-link {{ (request()->is(getAdminPanelUrl('/additional_page/contact_us', false))) ? 'active' : '' }}" href="{{ getAdminPanelUrl() }}/additional_page/contact_us">{{ trans('admin/main.contact_us') }}</a>
        </li>
    @endcan()

    @can('admin_additional_pages_footer')
        <li class="nav-item">
            <a class="nav-link {{ (request()->is(getAdminPanelUrl('/additional_page/footer', false))) ? 'active' : '' }}" href="{{ getAdminPanelUrl() }}/additional_page/footer">{{ trans('admin/main.footer') }}</a>
        </li>
    @endcan()

    @can('admin_additional_pages_navbar_links')
        <li class="nav-item">
            <a class="nav-link {{ (request()->is(getAdminPanelUrl('/additional_page/navbar_links', false))) ? 'active' : '' }}" href="{{ getAdminPanelUrl() }}/additional_page/navbar_links">{{ trans('admin/main.top_navbar') }}</a>
        </li>
    @endcan()
</ul>
</div>