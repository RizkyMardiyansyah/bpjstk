<div class="li">
<ul class="nav nav-pills li" id="myTab3" role="tablist"
    style="
    margin-bottom: 4%;
"
>
    @can('admin_regions_countries')
        <li class="nav-item">
            <a class="nav-link {{ (request()->is(getAdminPanelUrl('/regions/countries', false))) ? 'active' : '' }}" href="{{ getAdminPanelUrl() }}/regions/countries">{{ trans('update.countries') }}</a>
        </li>
    @endcan()

    @can('admin_regions_provinces')
        <li class="nav-item">
            <a class="nav-link {{ (request()->is(getAdminPanelUrl('/regions/provinces', false))) ? 'active' : '' }}" href="{{ getAdminPanelUrl() }}/regions/provinces">{{ trans('update.provinces') }}</a>
        </li>
    @endcan()

    @can('admin_regions_cities')
        <li class="nav-item">
            <a class="nav-link {{ (request()->is(getAdminPanelUrl('/regions/cities', false))) ? 'active' : '' }}" href="{{ getAdminPanelUrl() }}/regions/cities">{{ trans('update.cities') }}</a>
        </li>
    @endcan()

    @can('admin_regions_districts')
        <li class="nav-item">
            <a class="nav-link {{ (request()->is(getAdminPanelUrl('/regions/districts', false))) ? 'active' : '' }} " href="{{ getAdminPanelUrl() }}/regions/districts">{{ trans('update.districts') }}</a>
        </li>
    @endcan()
</ul>
</div>