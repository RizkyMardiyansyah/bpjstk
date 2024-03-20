@php
    if (empty($authUser) and auth()->check()) {
        $authUser = auth()->user();
    }

    $navBtnUrl = null;
    $navBtnText = null;

    if(request()->is('forums*')) {
        $navBtnUrl = '/forums/create-topic';
        $navBtnText = trans('update.create_new_topic');
    } else {
        $navbarButton = getNavbarButton(!empty($authUser) ? $authUser->role_id : null, empty($authUser));

        if (!empty($navbarButton)) {
            $navBtnUrl = $navbarButton->url;
            $navBtnText = $navbarButton->title;
        }
    }

@endphp
@php
    $userLanguages = !empty($generalSettings['site_language']) ? [$generalSettings['site_language'] => getLanguages($generalSettings['site_language'])] : [];

    if (!empty($generalSettings['user_languages']) and is_array($generalSettings['user_languages'])) {
        $userLanguages = getLanguages($generalSettings['user_languages']);
    }

    $localLanguage = [];

    foreach($userLanguages as $key => $userLanguage) {
        $shortLanguageCode = substr($userLanguage, 0, 3);
    $localLanguage[localeToCountryCode($key)] = $shortLanguageCode;
    }

@endphp

<div id="navbarVacuum"></div>
<nav id="navbar" class="navbar navbar-expand-lg navbar-light">
    <div class="container-fluid li">
        <div class="d-flex align-items-center justify-content-between w-100">

            <a class="navbar-brand navbar-order d-flex align-items-center justify-content-center mr-auto {{ (empty($navBtnUrl) and empty($navBtnText)) ? 'ml-0' : '' }}" href="/">
                @if(!empty($generalSettings['logo']))
                    <img src="{{ $generalSettings['logo'] }}" class="img-cover" alt="site logo">
                @endif
            </a>

            <button style="border:0" class="navbar-toggler navbar-order" type="button" id="navbarToggle">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="mx-lg-10 d-none d-lg-flex flex-grow-1 navbar-toggle-content " id="navbarContent">
                <div class="navbar-toggle-header text-right d-lg-none">
                    <button class="btn-transparent" id="navbarClose">
                        <i data-feather="x" width="32" height="32"></i>
                    </button>
                </div>

                <ul class="navbar-nav mr-auto d-flex align-items-center">
                    @if(!empty($categories) and count($categories))
                        <li class="mr-lg-10">
                            <div class="menu-category no">
                                <ul>
                                    {{-- <li class="cursor-pointer user-select-none d-flex xs-categories-toggle">
                                        <i data-feather="grid" width="20" height="20" class="mr-10 d-none d-lg-block"></i>
                                        {{ trans('categories.categories') }}

                                        <ul class="cat-dropdown-menu">
                                            @foreach($categories as $category)
                                                <li>
                                                    <a href="{{ $category->getUrl() }}">
                                                        <div class="d-flex align-items-center">
                                                            <img src="{{ $category->icon }}" class="cat-dropdown-menu-icon mr-10" alt="{{ $category->title }} icon">
                                                            {{ $category->title }}
                                                        </div>

                                                        @if(!empty($category->subCategories) and count($category->subCategories))
                                                            <i data-feather="chevron-right" width="20" height="20" class="d-none d-lg-inline-block ml-10"></i>
                                                            <i data-feather="chevron-down" width="20" height="20" class="d-inline-block d-lg-none"></i>
                                                        @endif
                                                    </a>

                                                    @if(!empty($category->subCategories) and count($category->subCategories))
                                                        <ul class="sub-menu" data-simplebar @if((!empty($isRtl) and $isRtl)) data-simplebar-direction="rtl" @endif>
                                                            @foreach($category->subCategories as $subCategory)
                                                                <li>
                                                                    <a href="{{ $subCategory->getUrl() }}">
                                                                        @if(!empty($subCategory->icon))
                                                                            <img src="{{ $subCategory->icon }}" class="cat-dropdown-menu-icon mr-10" alt="{{ $subCategory->title }} icon">
                                                                        @endif

                                                                        {{ $subCategory->title }}
                                                                    </a>
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    @endif
                                                </li>
                                            @endforeach
                                        </ul>
                                    </li> --}}
                                </ul>
                            </div>
                        </li>
                    @endif

                    @if(!empty($navbarPages) and count($navbarPages))
                        @foreach($navbarPages as $navbarPage)
                            <li class="nav-item">
                                <a class="nav-link" href="{{ $navbarPage['link'] }}">{{ $navbarPage['title'] }}</a>
                            </li>
                        @endforeach
                    @endif
                </ul>
            </div>

            <div class="nav-icons-or-start-live navbar-order">

                {{-- @if(!empty($navBtnUrl))
                    <a href="{{ $navBtnUrl }}" class="d-none d-lg-flex btn btn-sm btn-primary nav-start-a-live-btn">
                        {{ $navBtnText }}
                    </a>

                    <a href="{{ $navBtnUrl }}" class="d-flex d-lg-none text-primary nav-start-a-live-btn font-14">
                        {{ $navBtnText }}
                    </a>
                @endif --}}

                <div class="  nav-notify-cart-dropdown top-navbar " style="display: flex !important;">

                    @if(!empty($localLanguage) and count($localLanguage) > 1)
                        <form action="/locale" method="post" class="mr-10"

                        >
                            {{ csrf_field() }}
                            <style>
                                @media screen and (min-width: 992px) {
                                    .responsive-select {
                                        margin-top: 7px;
                                    }
                                }
                            </style>
                            <input type="hidden" name="locale">
                            <div class="language-select responsive-select " >
                                <div id="localItems"
                                     data-selected-country="{{ localeToCountryCode(mb_strtoupper(app()->getLocale())) }}"
                                     data-countries='{{ json_encode($localLanguage) }}'
                                ></div>
                            </div>
                        </form>
                    @else
                        <div class="mr-0 mx-md-10"></div>
                    @endif

                    {{-- @include(getTemplate().'.includes.shopping-cart-dropdwon') --}}

                    <div class="mr-0 mx-md-10"></div>

                    @include(getTemplate().'.includes.notification-dropdown')


                    <div class="d-md-block">
                        @include('web.default.includes.top_nav.userTopBar')
                    </div>
                </div>
                <div>
                </div>




            </div>
        </div>
    </div>
</nav>

@push('scripts_bottom')
    <script src="/assets/default/js/parts/navbar.min.js"></script>
@endpush
@push('scripts_bottom')
    <link href="/assets/default/vendors/flagstrap/css/flags.css" rel="stylesheet">
    <script src="/assets/default/vendors/flagstrap/js/jquery.flagstrap.min.js"></script>
    <script src="/assets/default/js/parts/top_nav_flags.min.js"></script>
@endpush
