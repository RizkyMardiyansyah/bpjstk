@extends('admin.layouts.app')


@section('content')
  {{--    c11502	--}}
    <section class="section">
        <div class="section-header">
            <h1>{{ trans('admin/main.settings') }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="#">{{ trans('admin/main.dashboard') }}</a></div>
                <div class="breadcrumb-item">{{ trans('admin/main.settings') }}</div>
            </div>
        </div>

        <div class="section-body li">
            <h2 class="section-title">{{ trans('admin/main.overview') }}</h2>
            <p class="section-lead">
                {{ trans('admin/main.overview_hint') }} <br/>
            </p>

            <div class="row">
                @can('admin_settings_general')
                    <div class="col-lg-6">
                        <div class="card card-large-icons">
                            <div class="card-icon bg-primary text-white">
                                <i class="fas fa-cog"></i>
                            </div>
                            <div class="card-body">
                                <h4>{{ trans('admin/main.general_card_title') }}</h4>
                                <p>{{ trans('admin/main.general_card_hint') }}</p>
                                <a href="/admin/settings/general" class="card-cta">{{ trans('admin/main.change_setting') }}<i class="fas fa-chevron-right"></i></a>
                            </div>
                        </div>
                    </div>
                @endcan

                @can('admin_settings_financial')
                    <div class="col-lg-6">
                        <div class="card card-large-icons">
                            <div class="card-icon bg-primary text-white">
                                <i class="fas fa-dollar-sign"></i>
                            </div>
                            <div class="card-body">
                                <h4>{{ trans('admin/main.financial_card_title') }}</h4>
                                <p>{{ trans('admin/main.financial_card_hint') }}</p>
                                <a href="/admin/settings/financial" class="card-cta">{{ trans('admin/main.change_setting') }}<i class="fas fa-chevron-right"></i></a>
                            </div>
                        </div>
                    </div>
                @endcan

                @can('admin_settings_personalization')
                    <div class="col-lg-6">
                        <div class="card card-large-icons">
                            <div class="card-icon bg-primary text-white">
                                <i class="fas fa-wrench"></i>
                            </div>
                            <div class="card-body">
                                <h4>{{ trans('admin/main.personalization_card_title') }}</h4>
                                <p>{{ trans('admin/main.personalization_card_hint') }}</p>
                                <a href="/admin/settings/personalization/page_background" class="card-cta">{{ trans('admin/main.change_setting') }}<i class="fas fa-chevron-right"></i></a>
                            </div>
                        </div>
                    </div>
                @endcan

                @can('admin_settings_notifications')
                    <div class="col-lg-6">
                        <div class="card card-large-icons">
                            <div class="card-icon bg-primary text-white">
                                <i class="fas fa-bell"></i>
                            </div>
                            <div class="card-body">
                                <h4>{{ trans('admin/main.notifications_card_title') }}</h4>
                                <p>{{ trans('admin/main.notifications_card_hint') }}</p>
                                <a href="/admin/settings/notifications" class="card-cta">{{ trans('admin/main.change_setting') }}<i class="fas fa-chevron-right"></i></a>
                            </div>
                        </div>
                    </div>
                @endcan

                @can('admin_settings_seo')
                    <div class="col-lg-6">
                        <div class="card card-large-icons">
                            <div class="card-icon bg-primary text-white">
                                <i class="fas fa-globe"></i>
                            </div>
                            <div class="card-body">
                                <h4>{{ trans('admin/main.seo_card_title') }}</h4>
                                <p>{{ trans('admin/main.seo_card_hint') }}</p>
                                <a href="/admin/settings/seo" class="card-cta">{{ trans('admin/main.change_setting') }}<i class="fas fa-chevron-right"></i></a>
                            </div>
                        </div>
                    </div>
                @endcan
				{{--   c111ab1d-69da-4366-960a-4b0485e79502	 --}}
                @can('admin_settings_customization')
                    <div class="col-lg-6">
                        <div class="card card-large-icons">
                            <div class="card-icon bg-primary text-white">
                                <i class="fas fa-list-alt"></i>
                            </div>
                            <div class="card-body">
                                <h4>{{ trans('admin/main.customization_card_title') }}</h4>
                                <p>{{ trans('admin/main.customization_card_hint') }}</p>
                                <a href="/admin/settings/customization" class="card-cta text-primary">{{ trans('admin/main.change_setting') }}<i class="fas fa-chevron-right"></i></a>
                            </div>
                        </div>
                    </div>
                @endcan
                    @can('admin_pages')
                        <div class="col-lg-6">
                            <div class="card card-large-icons">
                                <div class="card-icon bg-primary text-white">
                                    <i class="fas fa-pager"></i>
                                </div>
                                <div class="card-body">
                                    <h4>{{ trans('admin/main.pages') }}</h4>
                                    <a href="{{getAdminPanelUrl() }}/pages" class="card-cta text-primary">{{ trans('admin/main.pages') }}<i class="fas fa-chevron-right"></i></a>
                                </div>
                            </div>
                        </div>
                    @endcan

                @can('admin_additional_pages')
                        <div class="col-lg-6">
                            <div class="card card-large-icons">
                                <div class="card-icon bg-primary text-white">
                                    <i class="fas fa-plus-circle"></i>
                                </div>
                                <div class="card-body">
                                    <h4>{{ trans('admin/main.additional_pages_title') }}</h4>
                                    <a href="{{getAdminPanelUrl() }}/additional_page/404" class="card-cta text-primary">{{ trans('admin/main.additional_pages_title') }}<i class="fas fa-chevron-right"></i></a>
                                </div>
                            </div>
                        </div>
                @endcan
                @can('admin_tags')
                        <div class="col-lg-6">
                            <div class="card card-large-icons">
                                <div class="card-icon bg-primary text-white">
                                    <i class="fas fa-tags"></i>
                                </div>
                                <div class="card-body">
                                    <h4>{{ trans('admin/main.tags') }}</h4>
                                    <a href="/admin/tags" class="card-cta text-primary">{{ trans('admin/main.tags') }}<i class="fas fa-chevron-right"></i></a>
                                </div>
                            </div>
                        </div>
                @endcan
                @can('admin_regions')
                        <div class="col-lg-6">
                            <div class="card card-large-icons">
                                <div class="card-icon bg-primary text-white">
                                    <i class="fas fa-map-marked"></i>
                                </div>
                                <div class="card-body">
                                    <h4>{{ trans('update.regions') }}</h4>
                                    <a href="{{getAdminPanelUrl() }}/regions/countries" class="card-cta text-primary">{{ trans('update.regions') }}<i class="fas fa-chevron-right"></i></a>
                                </div>
                            </div>
                        </div>
                @endcan
            </div>
        </div>
    </section>
@endsection
