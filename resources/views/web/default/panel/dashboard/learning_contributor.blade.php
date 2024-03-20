

@push('styles_top')
    <link rel="stylesheet" href="/assets/default/vendors/swiper/swiper-bundle.min.css">
    <link rel="stylesheet" href="/assets/default/vendors/owl-carousel2/owl.carousel.min.css">
@endpush
<div class="flex-fill">
    <div class="flex-fill">
        <section class="">
            <div class="d-flex align-items-start align-items-md-center justify-content-between flex-column flex-md-row">
                <h1 class="section-title">{{ trans('panel.dashboard') }}</h1>
    
                {{-- @if(!$authUser->isUser())
                    <div
                        class="d-flex align-items-center flex-row-reverse flex-md-row justify-content-start justify-content-md-center mt-20 mt-md-0">
                        <label class="mb-0 mr-10 cursor-pointer text-gray font-14 font-weight-500"
                               for="iNotAvailable">{{ trans('panel.i_not_available') }}</label>
                        <div class="custom-control custom-switch">
                            <input type="checkbox" name="disabled" @if($authUser->offline) checked
                                   @endif class="custom-control-input" id="iNotAvailable">
                            <label class="custom-control-label" for="iNotAvailable"></label>
                        </div>
                    </div>
                @endif --}}
            </div>
    
            {{-- @if(!$authUser->financial_approval and !$authUser->isUser())
                <div class="p-15 mt-20 p-lg-20 not-verified-alert font-weight-500 text-dark-blue rounded-sm panel-shadow">
                    {{ trans('panel.not_verified_alert') }}
                    <a href="/panel/setting/step/7" class="text-decoration-underline">{{ trans('panel.this_link') }}</a>.
                </div>
            @endif --}}
    
            <div class=" bg-white dashboard-banner-container position-relative px-30 px-ld-35 py-30 panel-shadow rounded-sm">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="font-30 text-primary line-height-1">
                            <span class="d-block">{{ trans('panel.hi') }}, {{ $authUser->full_name }}</span>
                            <span class="font-16 text-secondary font-weight-bold">{{__('panel.teacher')}}</span>
                        </h2>
                    </div>
                    <div>
                        <span class="font-16 text-secondary font-weight-bold">{{ $currentDate }}</span>
                    </div>
                </div>
            </div>
            
        </section>

    
</div>
{{-- <!-- Stretching this row to 4 columns -->
<h3 class="text-gray-800">{{__('panel.teacher')}}</h3> --}}

<!-- This is the first row -->
<div class="row ml-4 mr-4 mt-4  justify-content-between align-items-center">
    <h3 class="text-dark-blue">{{__('admin/main.session')}}</h3>
    <a href="/panel/meetings/requests" class="view ml-auto">{{__("admin/main.view_more")}}</a>
</div>
<div class="flex-fill row ml-2 mb-2 mr-2">
    @php
        $stats = [
            [
                'title' =>  __('meeting.coaching'),
                'value' => floor($coachingMeetingHours) . ' ' . __('home.hours'),
            ],
            [
                'title' => __('meeting.mentoring'),
                'value' => floor($mentoringMeetingHours) . ' ' . __('home.hours'),
            ],
            [
                'title' => __('meeting.training'),
                'value' => floor($trainingMeetingHours) . ' ' . __('home.hours'),
            ],
            // [
            //     'title' => __('update.mentoring_type_reverse'),
            //     'value' => floor($reverseMeetingHours) . ' ' . __('home.hours'),
            // ],
        ];
    @endphp
    
    @foreach($stats as $stat)

    <div class="flex-fill  col-md-4 col-sm-12  ">
        <div class="mt-2">
            <x-card-summary-stats title="{{ $stat['title'] }}" value="{{ $stat['value'] }}" icon="{{ $stat['title'] }}.svg" />
        </div>
    </div>

    

    
    
        {{-- <a class=" mt-2 col-lg-3 col-md-6 col-sm-12">
            <x-card-summary-stats title="{{ $stat['title'] }}" value="{{ $stat['value'] }}" icon="{{ $stat['title'] }}.svg" />
        </a> --}}
    @endforeach


<!-- This is the second row -->


    {{-- <div class="flex-fill col-lg-6 col-md-6 col-sm-12">
        <div class="row m-0 mt-4 justify-content-between align-items-center">
            <h3 class="text-gray-800">{{__('update.courses_published')}}</h3>
            <a href="/panel/webinars" class="btn btn-primary ml-auto">{{__("admin/main.view_more")}}</a>
            
        </div>


        <div class="mt-2">
            <x-card-summary  value="{{ $myWebinarsCount}} {{ trans('panel.classes') }} " icon="coursus.svg" />
        </div>
    </div> --}}
    <div class="flex-fill col-lg-6 col-md-6 col-sm-12">
        <div class="row m-0 mt-4 justify-content-between align-items-center">
            <h3 class="text-dark-blue">{{__('admin/main.total_students')}}</h3>
            {{-- <a href="/panel/webinars" class="view ml-auto">{{__("admin/main.view_more")}}</a> --}}
        </div>

        <div class="mt-2">
            <x-card-summary-stats3  value="{{ $totalCount }} {{ trans('admin/main.students') }}" icon="student.svg" />
        </div>
    </div>
    <div class="flex-fill col-lg-6 col-md-6 col-sm-12">
        <div class="mt-4">
            @include('web.default.panel.dashboard.noticeboard2')
        </div>
    </div>


    <div class="flex-fill col-sm-12 col-md-6 col-lg-6">
    <div class="mt-4">
        @include('web.default.panel.dashboard.diary')
    </div>
    </div>


    {{-- <div class="  flex-fill col-sm-12 col-md-6 col-lg-6">
        <div class="mt-4">
            @include('web.default.panel.dashboard.assignment')
        </div>
    </div> --}}
    {{-- <div class="  flex-fill col-sm-12 col-md-6 col-lg-6">
        <div class="mt-4">
            @include('web.default.panel.dashboard.topOrganization')
        </div>
    </div> --}}
    <div class="  flex-fill col-sm-12 col-md-6 col-lg-6">
        <div class="mt-4">
            @include('web.default.panel.dashboard.topRank')
        </div>
    </div>
    <div class="flex-fill col-sm-12">
        <div class="mt-4 mt-2">
            @include('web.default.panel.dashboard.diaryChart')
        </div>
    </div>
        
</div>
{{-- <div style="margin-top:-80px;">
<!-- Include Swiper JS -->
<link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />

<!-- Swiper -->
<section class="home-sections container">
    <div class="d-flex justify-content-between">
        <div>
            <h2 class="section-title">{{ trans('home.best_sellers') }}</h2>
            <p class="section-hint">{{ trans('home.best_sellers_hint') }}</p>
        </div>

        <a href="/classes?sort=bestsellers" class="btn btn-primary ml-auto">{{ trans('home.view_all') }}</a>
    </div>

    <div class="mt-10 position-relative">
        <div class="swiper-container best-sales-webinars-swiper px-12">
            <div class="swiper-wrapper py-20">
                @foreach($bestSaleWebinars as $bestSaleWebinar)
                    <div class="swiper-slide">
                        @include('web.default.includes.webinar.grid-card',['webinar' => $bestSaleWebinar])
                    </div>
                @endforeach
            </div>

            <!-- Add Pagination -->
            <div  class="swiper-pagination best-sales-webinars-swiper-pagination"></div>
        </div>
    </div>
</section>

<!-- Swiper JS -->
<script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>

<!-- Initialize Swiper -->
<script>
    var swiper = new Swiper('.best-sales-webinars-swiper', {
        spaceBetween: 20,
        pagination: {
            el: '.best-sales-webinars-swiper-pagination',
            clickable: true,
        },
        breakpoints: {
            320: {
                slidesPerView: 1,
            },
            768: {
                slidesPerView: 2,
            },
            1024: {
                slidesPerView: 3,
            },
        },
    });
</script>
</div> --}}





