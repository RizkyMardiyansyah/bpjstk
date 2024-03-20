@php
    $userId = isset($_GET['user_id']) ? $_GET['user_id'] : null;
    $meetingId = isset($_GET['reserve_meeting_id']) ? $_GET['reserve_meeting_id'] : null;
    $creatorId = isset($_GET['creator_id']) ? $_GET['creator_id'] : null;
    $sessiontype = isset($_GET['sessiontype']) ? $_GET['sessiontype'] : null;
    $meetingtype = isset($_GET['meetingtype']) ? $_GET['meetingtype'] : null;
@endphp

{{-- @dd($sessiontype, $meetingtype); --}}


@if (!empty($meeting) and !empty($meeting->meetingTimes) and $meeting->meetingTimes->count() > 0)
    @push('styles_top')
        <link rel="stylesheet" href="/assets/vendors/wrunner-html-range-slider-with-2-handles/css/wrunner-default-theme.css">
    @endpush

    <div class="mt-40" >
        <h3 class="font-16 font-weight-bold text-dark-blue">{{ trans('site.view_available_times') }}</h3>

        <div class="mt-35">
            <div class="row align-items-center justify-content-center">
                <input type="hidden" id="inlineCalender" class="form-control" value="">
                <div class="inline-reservation-calender"></div>
            </div>
        </div>
    </div>

    <div class="pick-a-time d-none" id="PickTimeContainer" data-user-id="{{ $user['id'] }}">

        

        <div class="d-flex align-items-center my-40 rounded-lg border px-10 py-5">
            <div class="appointment-timezone-icon">
                <img src="/assets/default/img/icons/timezone.svg" alt="appointment timezone">
            </div>
            <div class="ml-15">
                <div class="font-16 font-weight-bold text-dark-blue">{{ trans('update.note') }}:</div>
                <p class="font-14 font-weight-500 text-gray">
                    {{ trans('update.appointment_timezone_note_hint', ['timezone' => $meetingTimezone . ' ' . toGmtOffset($meetingTimezone)]) }}
                </p>
            </div>
        </div>


        {{-- Cashback Alert --}}
        @include('web.default.includes.cashback_alert', [
            'itemPrice' => $meeting->amount,
            'classNames' => 'mt-0 mb-40',
            'itemType' => 'meeting',
        ])


        <div class="loading-img d-none text-center">
            <img src="/assets/default/img/loading.gif" width="80" height="80">
        </div>

        <form action="{{ !$meeting->disabled ? '/meetings/reserve' : '' }}" method="post" id="PickTimeBody"
            class="d-none">
            
            {{-- <input type="hidden" name="creatorId" value="$creatorId">
            <input type="hidden" name="meetingId" value="$reserveMeetingId"> --}}
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="day" id="selectedDay" value="">
            <input type="hidden" name="is_reverse" value="{{ empty($is_reverse) ? false : $is_reverse }}">
            <h3 class="font-16 font-weight-bold text-dark-blue">
                @if ($meeting->disabled)
                    {{ trans('public.unavailable') }}
                @else
                    {{ trans('site.pick_a_time') }}
                    @if (!empty($meeting) and !empty($meeting->discount) and !empty($meeting->amount) and $meeting->amount > 0)
                        <span class="badge badge-danger text-white font-12">{{ $meeting->discount }}%
                            {{ trans('public.off') }}</span>
                    @endif
                @endif
            </h3>

            <div class="d-flex flex-column mt-10">
                @if ($meeting->disabled)
                    <span class="font-14 text-gray">{{ trans('public.unavailable_description') }}</span>
                @else
                    <span class="d-block font-14 text-gray font-weight-500">
                        {{ trans('site.instructor_hourly_charge') }}

                        @if (!empty($meeting->amount) and $meeting->amount > 0)
                            @if (!empty($meeting->discount))
                                <span
                                    class="text-decoration-line-through">{{ handlePrice($meeting->amount, true, true, false, null, true) }}</span>
                                <span
                                    class="text-primary">{{ handlePrice($meeting->amount - ($meeting->amount * $meeting->discount) / 100, true, true, false, null, true) }}</span>
                            @else
                                <span
                                    class="text-primary">{{ handlePrice($meeting->amount, true, true, false, null, true) }}</span>
                            @endif
                        @else
                            <span class="text-primary">{{ trans('public.free') }}</span>
                        @endif
                    </span>

                    @if ($meeting->in_person)
                        <span class="d-block font-14 text-gray font-weight-500">
                            {{ trans('update.instructor_hourly_charge_in_person_amount') }}

                            @if (!empty($meeting->in_person_amount) and $meeting->in_person_amount > 0)
                                @if (!empty($meeting->discount))
                                    <span
                                        class="text-decoration-line-through">{{ handlePrice($meeting->in_person_amount, true, true, false, null, true) }}</span>
                                    <span
                                        class="text-primary">{{ handlePrice($meeting->in_person_amount - ($meeting->in_person_amount * $meeting->discount) / 100, true, true, false, null, true) }}</span>
                                @else
                                    <span
                                        class="text-primary">{{ handlePrice($meeting->in_person_amount, true, true, false, null, true) }}</span>
                                @endif
                            @else
                                <span class="text-primary">{{ trans('public.free') }}</span>
                            @endif
                        </span>
                    @endif
                    @if ($meeting->group_meeting)
                        <span
                            class="d-block font-14 text-gray font-weight-500">{{ trans('update.instructor_conducts_group_meetings', ['min' => $meeting->online_group_min_student, 'max' => $meeting->online_group_max_student]) }}</span>
                    @endif

                @endif

                <span class="font-14 text-gray mt-5 selected_date font-weight-500">{{ trans('site.selected_date') }}:
                    <span></span></span>
            </div>

            @if (!$meeting->disabled)
                <div id="availableTimes" class="d-flex flex-wrap align-items-center mt-25">

                </div>

                <div class="js-time-description-card d-none mt-25 rounded-sm border p-10">

                </div>

                <div class="mt-25 d-none js-finalize-reserve">
                    <h3 class="font-16 font-weight-bold text-dark-blue">{{ trans('update.finalize_your_meeting') }}
                    </h3>
                    <span
                        class="selected-date-time font-14 text-gray font-weight-500">{{ trans('update.meeting_time') }}:
                        <span></span></span>

                    <div class="mt-15">
                        <span class="font-16 font-weight-500 text-dark-blue">{{ trans('update.meeting_type') }}</span>

                        <div class="d-flex align-items-center mt-5">
                            @php
                            $inperson = strpos(request()->fullUrl(), 'in_person') !== false;
                            @endphp
                            @if ($inperson)                           
                            <div  class="meeting-type-reserve position-relative">
                                <input checked type="radio" name="meeting_type" id="meetingTypeInPerson" value="in_person">
                                <label for="meetingTypeInPerson">{{ trans('update.in_person') }}</label>
                            </div>
                            @else
                            <div class="meeting-type-reserve position-relative">
                                <input type="radio" name="meeting_type" id="meetingTypeInPerson" value="in_person">
                                <label for="meetingTypeInPerson">{{ trans('update.in_person') }}</label>
                            </div>
                            @endif

                            @php
                            $online = strpos(request()->fullUrl(), 'online') !== false;
                            @endphp
                            @if ($online)
                            <div class="meeting-type-reserve position-relative">
                                <input checked type="radio" name="meeting_type" id="meetingTypeOnline" value="online">
                                <label for="meetingTypeOnline">{{ trans('update.online') }}</label>
                            </div>
                            @else
                            <div class="meeting-type-reserve position-relative">
                                <input type="radio" name="meeting_type" id="meetingTypeOnline" value="online">
                                <label for="meetingTypeOnline">{{ trans('update.online') }}</label>
                            </div>
                            @endif
                        </div>
                    </div>
                    <div class="mt-15">
                        <span class="font-16 font-weight-500 text-dark-blue">{{ trans('meeting.type_session') }}</span>
                        <div class="d-flex align-items-center mt-5">
                            @foreach (['mentoring', 'coaching', 'training'] as $sessionType)
                                @php
                            
                                    $isChecked = $sessionType === $meeting->session_type;
                                    
                                    $type = strpos(request()->fullUrl(), $sessionType) !== false;
                            
                                @endphp
                                @if($type)
                                <div class="{{ (empty($user->type_of_sessions) or !is_array($user->type_of_sessions) or !in_array($sessionType, $user->type_of_sessions)) ? 'meeting-type-reserve type position-relative disabled' : 'meeting-type-reserve type position-relative' }}">
                                    <input checked type="radio" name="session_type" id="{{ ucfirst($sessionType) }}" value="{{ $sessionType }}"
                                    {{ (empty($user->type_of_sessions) or !is_array($user->type_of_sessions) or !in_array($sessionType, $user->type_of_sessions)) ? 'disabled' : ''}}>
                                    <label
                                        for="{{ ucfirst($sessionType) }}">{{ trans('meeting.' . $sessionType) }}</label>
                                </div>
                                @else
                                <div class="{{ (empty($user->type_of_sessions) or !is_array($user->type_of_sessions) or !in_array($sessionType, $user->type_of_sessions)) ? 'meeting-type-reserve type position-relative disabled' : 'meeting-type-reserve type position-relative' }}">
                                    <input type="radio" name="session_type" id="{{ ucfirst($sessionType) }}" value="{{ $sessionType }}"
                                    {{ (empty($user->type_of_sessions) or !is_array($user->type_of_sessions) or !in_array($sessionType, $user->type_of_sessions)) ? 'disabled' : ''}}>
                                    <label
                                        for="{{ ucfirst($sessionType) }}">{{ trans('meeting.' . $sessionType) }}</label>
                                </div>
                                @endif
                            @endforeach
                            {{-- <div class="meeting-type-reserve type position-relative">
                                <input type="radio" name="session_type" id="sessionTypementoring" value="Mentoring">
                                <label for="sessionTypementoring">{{ trans('meeting.mentoring') }}</label>
                            </div> --}}
                            {{-- <div class="d-flex align-items-center">
                                <div class="{{ (empty($user->type_of_sessions) or !is_array($user->type_of_sessions) or !in_array('coaching', $user->type_of_sessions)) ? 'meeting-type-reserve type position-relative disabled' : 'meeting-type-reserve type position-relative' }}">
                                    <input type="radio" name="session_type" value="coaching" id="coaching"
                                        {{ (!empty($user->type_of_sessions) and is_array($user->type_of_sessions) and in_array('coaching', $user->type_of_sessions)) ? 'checked="checked"' : ''}}
                                        class="custom-control-input">
                                    <label for="coaching">{{ trans('meeting.coaching') }}</label>
                                </div>
                            
                                <div class="{{ (empty($user->type_of_sessions) or !is_array($user->type_of_sessions) or !in_array('mentoring', $user->type_of_sessions)) ? 'meeting-type-reserve type position-relative disabled' : 'meeting-type-reserve type position-relative' }}">
                                    <input type="radio" name="session_type" value="mentoring" id="mentoring"
                                        {{ (!empty($user->type_of_sessions) and is_array($user->type_of_sessions) and in_array('mentoring', $user->type_of_sessions)) ? 'checked="checked"' : ''}}
                                        class="custom-control-input">
                                    <label for="mentoring">{{ trans('meeting.mentoring') }}</label>
                                </div>
                            
                                <div class="{{ (empty($user->type_of_sessions) or !is_array($user->type_of_sessions) or !in_array('training', $user->type_of_sessions)) ? 'meeting-type-reserve type position-relative disabled' : 'meeting-type-reserve type position-relative' }}">
                                    <input type="radio" name="session_type" value="training" id="training"
                                        {{ (!empty($user->type_of_sessions) and is_array($user->type_of_sessions) and in_array('training', $user->type_of_sessions)) ? 'checked="checked"' : ''}}
                                        class="custom-control-input">
                                    <label for="training">{{ trans('meeting.training') }}</label>
                                </div>
                            </div> --}}
                            
                        </div>
                    </div>
                </div>

                    @if ($meeting->group_meeting)
                        <div class="js-group-meeting-switch d-none align-items-center mt-20">
                            <label class="mb-0 mr-10 text-gray font-14 font-weight-500 cursor-pointer"
                                for="withGroupMeetingSwitch">{{ trans('update.group_meeting') }}</label>
                            <div class="custom-control custom-switch">
                                <input type="checkbox" name="with_group_meeting" class="custom-control-input"
                                    id="withGroupMeetingSwitch">
                                <label class="custom-control-label" for="withGroupMeetingSwitch"></label>
                            </div>
                        </div>

                        <div class="js-group-meeting-options d-none mt-15">
                            <div class="row">
                                <div class="col-12 col-lg-4">
                                    <div class="form-group">
                                        <input type="hidden" id="online_group_max_student"
                                            value="{{ $meeting->online_group_max_student }}">
                                        <input type="hidden" id="in_person_group_max_student"
                                            value="{{ $meeting->in_person_group_max_student }}">
                                        <label for="studentCountRange"
                                            class="form-label">{{ trans('update.participates') }}:</label>
                                        <div class="range" id="studentCountRange" data-minLimit="1">
                                            <input type="hidden" name="student_count" value="1">
                                            <div class="invalid-feedback"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="js-online-group-amount d-none font-14 font-weight-500 mt-15">
                                <span class="text-gray d-block">{{ trans('update.online') }}
                                    {{ trans('update.group_meeting_hourly_rate_per_student', ['amount' => handlePrice($meeting->online_group_amount, true, true, false, null, true)]) }}</span>
                                <span
                                    class="text-danger mt-5 d-block">{{ trans('update.group_meeting_student_count_hint', ['min' => $meeting->online_group_min_student, 'max' => $meeting->online_group_max_student]) }}</span>
                                <span
                                    class="text-danger mt-5 d-block">{{ trans('update.group_meeting_max_student_count_hint', ['max' => $meeting->online_group_max_student]) }}</span>
                            </div>

                            @if ($meeting->in_person)
                                <div class="js-in-person-group-amount d-none font-14 font-weight-500 mt-15">
                                    <span class="text-gray d-block">{{ trans('update.in_person') }}
                                        {{ trans('update.group_meeting_hourly_rate_per_student', ['amount' => handlePrice($meeting->in_person_group_amount, true, true, false, null, true)]) }}</span>
                                    <span
                                        class="text-danger mt-5 d-block">{{ trans('update.group_meeting_student_count_hint', ['min' => $meeting->in_person_group_min_student, 'max' => $meeting->in_person_group_max_student]) }}</span>
                                    <span
                                        class="text-danger mt-5 d-block">{{ trans('update.group_meeting_max_student_count_hint', ['max' => $meeting->in_person_group_max_student]) }}</span>
                                </div>
                            @endif

                        </div>
                    @endif
                </div>
                {{-- @dd($userId) --}}

                <input type="hidden" name="userId" value="{{ $userId }}">
                <input type="hidden" name="meetingId" value="{{ $meetingId }}">
                
                
               

                <div class="js-reserve-description d-none form-group mt-30">
                    @php
                        $hideButton = strpos(request()->fullUrl(), 'reschedule') !== false;
                        @endphp

                        @if (!$hideButton)
                        <label class="input-label">{{ trans('public.description') }}</label>
                        @else
                        <label class="input-label">{{ trans('update.reason') }}</label>
                        
                        @endif
                    
                    
                    <textarea name="description" class="form-control" rows="5"
                        placeholder="{{ trans('update.reserve_time_description_placeholder') }}"></textarea>
                </div>

                <div  class="js-reserve-btn d-none align-items-center justify-content-end mt-30">
                    
                    <button id="book" type="button" class="js-submit-form btn btn-primary">{{ trans('meeting.reserve_appointment') }}</button>
                    @php
                        $authid = strpos(request()->fullUrl(), 'user_id=' . $authUser->id) !== false;
                    @endphp

                    @if (!$authid)
                        <button id="reschedule" type="button" class="js-submit-res-form btn btn-primary">{{ trans('panel.reschedule') }}</button>
                    @else
                        <button id="reschedule" type="button" class="js-submit-res2-form btn btn-primary">{{ trans('panel.reschedule') }}</button>                        
                    @endif

                    
                </div>


                
                
                
            @endif
        </form>
    </div>
    

<script>
    // Ambil URL saat ini
    var currentUrl = window.location.href;

    // Periksa apakah URL mengandung kata 'reschedule'
    if (currentUrl.includes('reschedule')) {
        // Jika 'reschedule' ditemukan dalam URL, sembunyikan elemen dengan ID 'book' dan tampilkan elemen dengan ID 'reschedule'
        document.getElementById('book').style.display = 'none';
        document.getElementById('reschedule').style.display = 'block';
    } else {
        // Jika 'reschedule' tidak ditemukan dalam URL, tampilkan elemen dengan ID 'book' dan sembunyikan elemen dengan ID 'reschedule'
        document.getElementById('book').style.display = 'block';
        document.getElementById('reschedule').style.display = 'none';
    }
</script>

    
    



    @push('scripts_bottom')
        <script src="/assets/vendors/wrunner-html-range-slider-with-2-handles/js/wrunner-jquery.js"></script>
    @endpush
@else
    @include(getTemplate() . '.includes.no-result', [
        'file_name' => 'meet.png',
        'title' => trans('site.instructor_not_available'),
        'hint' => '',
    ])

@endif


@push('scripts_bottom')
    <script>
        var saveSuccessLang = '{{ trans('webinars.success_store') }}';
        var zoomJwtTokenInvalid = '{{ trans('webinars.zoom_jwt_token_invalid') }}';
        var hasZoomApiToken = '{{ (!empty($authUser->zoomApi) and !empty($authUser->zoomApi->api_key) and !empty($authUser->zoomApi->api_secret)) ? 'true' : 'false' }}';
        var editChapterLang = '{{ trans('public.edit_chapter') }}';
    </script>

    <script src="/assets/default/js/panel/webinar.min.js"></script>
    <script src="/assets/default/js/panel/webinar_content_locale.min.js"></script>
@endpush

