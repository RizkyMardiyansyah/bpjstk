<style>
#container button:hover{
    color: var(--primary) !important;
}
.custom-width {
        width: 80% !important; /* Lebar 80% untuk tampilan mobile */
        margin: auto !important; /* Membuat elemen berada di tengah secara horizontal */
    }
    
    @media (min-width: 768px) and (max-width: 992px) {
        .custom-width {
            width: 50% !important; /* Lebar 50% untuk tampilan tab */
        }
    }
    
    @media (min-width: 992px) {
        .custom-width {
            width: 25% !important; /* Lebar 25% untuk tampilan desktop */
        }
    }
    
    .modal-dialog {
        display: flex; /* Mengatur container menjadi flex */
        justify-content: center; /* Mengatur rata tengah secara horizontal */
        align-items: center; /* Mengatur rata tengah secara vertikal */
        height: 100vh; /* Menetapkan tinggi sesuai dengan tinggi viewport */
    }

    .cancel{
        color: red !important;
        border: 1px solid red !important;
        border-radius: 5px;
    }
    .cancel:hover{
        background-color: red !important;
        color: white !important;
    }
    .card{
    border: 0px !important;
    box-shadow: 0px 12px 23px 0px rgba(62, 73, 84, 0.04);
  background-color: #ffffff;
}
.card span{
    color:var(--secondary);
}
.butonContainer button{
    margin-right: 10px;
    padding-left: 10px !important;
    padding-right: 10px !important;
}
    .accbtn{
        border: 1px solid var(--primary) !important;
        border-radius: 5px;
    }
    .accbtn:hover{
        background-color: var(--primary) !important;
       color: white !important;
    }
    .name{
        color: var(--primary);
    }
    .request{
        font-size: 14px;
    }
</style>
@extends(getTemplate() .'.panel.layouts.panel_layout')

@push('styles_top')
    <link rel="stylesheet" href="/assets/default/vendors/daterangepicker/daterangepicker.min.css">
@endpush

@section('content')
    <section>
        <h2 class="section-title">{{ trans('panel.meeting_statistics') }}</h2>

        <div class="activities-container mt-25 p-20 p-lg-35">
            <div class="row">
                <div class="col-4 d-flex align-items-center justify-content-center">
                    <div class="d-flex flex-column align-items-center text-center">
                        <img src="/assets/default/img/activity/49.svg" width="64" height="64" alt="">
                        <strong class="font-30 font-weight-bold mt-5">{{ $openReserveCount }}</strong>
                        <span class="font-16 text-dark-blue text-gray font-weight-500">{{ trans('panel.open_meetings') }}</span>
                    </div>
                </div>

                <div class="col-4 d-flex align-items-center justify-content-center">
                    <div class="d-flex flex-column align-items-center text-center">
                        <img src="/assets/default/img/activity/50.svg" width="64" height="64" alt="">
                        <strong class="font-30 font-weight-bold mt-5">{{ $totalReserveCount }}</strong>
                        <span class="font-16 text-dark-blue text-gray font-weight-500">{{ trans('panel.total_meetings') }}</span>
                    </div>
                </div>

                <div class="col-4 d-flex align-items-center justify-content-center">
                    <div class="d-flex flex-column align-items-center text-center">
                        <img src="/assets/default/img/activity/hours.svg" width="64" height="64" alt="">
                        <strong class="font-30 text-dark-blue font-weight-bold mt-5">{{ floor($activeHoursCount) }}</strong>
                        <span class="font-16 text-gray font-weight-500">{{ trans('panel.active_hours') }}</span>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <section class="mt-25">
        <h2 class="section-title">{{ trans('panel.filter_meetings') }}</h2>

        <div class="panel-section-card py-20 px-25 mt-20">
            <form action="/panel/meetings/reservation" method="get" class="row">
                <div class="col-12 col-lg-4">
                    <div class="row">
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label class="input-label">{{ trans('public.from') }}</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="dateInputGroupPrepend">
                                            <i data-feather="calendar" width="18" height="18" class="text-white"></i>
                                        </span>
                                    </div>
                                    <input type="text" name="from" autocomplete="off" class="form-control @if(!empty(request()->get('from'))) datepicker @else datefilter @endif"
                                           aria-describedby="dateInputGroupPrepend" value="{{ request()->get('from','') }}"/>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label class="input-label">{{ trans('public.to') }}</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="dateInputGroupPrepend">
                                            <i data-feather="calendar" width="18" height="18" class="text-white"></i>
                                        </span>
                                    </div>
                                    <input type="text" name="to" autocomplete="off" class="form-control @if(!empty(request()->get('to'))) datepicker @else datefilter @endif"
                                           aria-describedby="dateInputGroupPrepend" value="{{ request()->get('to','') }}"/>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-7">
                    <div class="row">
                        <div class="col-12 col-lg-4">
                            <div class="form-group">
                                <label class="input-label">{{ trans('public.day') }}</label>
                                <select class="form-control" id="day" name="day">
                                    <option value="all">{{ trans('public.all_days') }}</option>
                                    <option value="saturday" {{ (request()->get('day') === "saturday") ? 'selected' : '' }}>{{ trans('public.saturday') }}</option>
                                    <option value="sunday" {{ (request()->get('day') === "sunday") ? 'selected' : '' }}>{{ trans('public.sunday') }}</option>
                                    <option value="monday" {{ (request()->get('day') === "monday") ? 'selected' : '' }}>{{ trans('public.monday') }}</option>
                                    <option value="tuesday" {{ (request()->get('day') === "tuesday") ? 'selected' : '' }}>{{ trans('public.tuesday') }}</option>
                                    <option value="wednesday" {{ (request()->get('day') === "wednesday") ? 'selected' : '' }}>{{ trans('public.wednesday') }}</option>
                                    <option value="thursday" {{ (request()->get('day') === "thursday") ? 'selected' : '' }}>{{ trans('public.thursday') }}</option>
                                    <option value="friday" {{ (request()->get('day') === "friday") ? 'selected' : '' }}>{{ trans('public.friday') }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-12 col-lg-8">
                            <div class="row">
                                <div class="col-12 col-lg-6">
                                    <div class="form-group">
                                        <label class="input-label">{{ trans('webinars.contributor') }}</label>
                                        <select name="instructor_id" class="form-control select2">
                                            <option value="all">{{ trans('webinars.all_instructors') }}</option>

                                            @foreach($instructors as $instructor)
                                                <option value="{{ $instructor->id }}" @if(request()->get('instructor_id') == $instructor->id) selected @endif>{{ $instructor->full_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12 col-lg-6">
                                    <div class="form-group">
                                        <label class="input-label">{{ trans('public.status') }}</label>
                                        <select class="form-control" id="status" name="status">
                                            <option>{{ trans('public.all') }}</option>
                                            <option value="pending"
                                                {{ request()->get('status') === 'pending' ? 'selected' : '' }}>
                                                {{ trans('public.upcoming') }}</option>
                                            <option value="open" {{ (request()->get('status') === "open") ? 'selected' : '' }}>{{ trans('public.open') }}</option>
                                            <option value="finished" {{ (request()->get('status') === "finished") ? 'selected' : '' }}>{{ trans('public.finished') }}</option>
                                            <option value="canceled"
                                                {{ request()->get('status') === 'canceled' ? 'selected' : '' }}>
                                                {{ trans('public.canceled') }}</option>
                                            
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-1 d-flex align-items-center justify-content-end">
                    <button type="submit" class="btn btn-sm btn-primary w-100 mt-2">{{ trans('public.show_results') }}</button>
                </div>
            </form>
        </div>
    </section>


    <section class="mt-35">
        <div id="#sessionlist" class="d-flex align-items-start align-items-md-center justify-content-between flex-column flex-md-row">
            <h2 class="section-title">{{ trans('panel.meeting_list') }}</h2>

            <form action="/panel/meetings/reservation?{{ http_build_query(request()->all()) }}" class="d-flex align-items-center flex-row-reverse flex-md-row justify-content-start justify-content-md-center mt-20 mt-md-0">
                <label class="cursor-pointer mb-0 mr-10 text-gray font-14 font-weight-500" for="openMeetingResult">{{ trans('panel.show_only_open_meetings') }}</label>
                <div class="custom-control custom-switch">
                    <input type="checkbox" name="open_meetings" class="js-panel-list-switch-filter custom-control-input" id="openMeetingResult" {{ (request()->get('open_meetings', '') == 'on') ? 'checked' : '' }}>
                    <label class="custom-control-label" for="openMeetingResult"></label>
                </div>
            </form>
        </div>

        @if($reserveMeetings->count() > 0)

        <div  style="padding-left: 20px; padding-right:20px;">
            <div class="row">
                <div class="col-12">
                    <div class="responsive">
                            {{-- <table class="table text-center custom-table">
                                <thead>
                                <tr>
                                    <th>{{ trans('public.instructor') }}</th>
                                    <th class="text-center">{{ trans('update.meeting_type') }}</th>
                                    <th class="text-center">{{ trans('public.day') }}</th>
                                    <th class="text-center">{{ trans('public.date') }}</th>
                                    <th class="text-center">{{ trans('public.time') }}</th>
                                    <th class="text-center">{{ trans('public.paid_amount') }}</th>
                                    <th class="text-center">{{ trans('update.students_count') }}</th>
                                    <th class="text-center">{{ trans('public.status') }}</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody> --}}

                                @foreach($reserveMeetings as $ReserveMeeting)

                                <div class="card py-20 px-25 mt-20 request">
                                    <div class="d-flex align-items-center">
                                    
                                    <span class="d-block font-weight-500">  {{ trans('meeting.' . $ReserveMeeting->session_type) }} <b>{{ trans('update.' . $ReserveMeeting->meeting_type) }} </b>{{trans ('update.with') }} <b class="name">{{ $ReserveMeeting->meeting->creator->full_name }}</b></span>
                                    <div class="ml-auto">
                                    @switch($ReserveMeeting->status)
                                                @case(\App\Models\ReserveMeeting::$pending)
                                                    <span class="text-warning font-weight-500"><b>{{ trans('public.upcoming') }}</b></span>
                                                @break

                                                @case(\App\Models\ReserveMeeting::$open)
                                                    <span
                                                        class=" text-gray text-primary font-weight-500"><b>{{ trans('public.open') }}</b></span>
                                                @break

                                                @case(\App\Models\ReserveMeeting::$finished)
                                                    <span class="text-primary font-weight-500"><b>{{ trans('public.finished') }}</b></span>
                                                @break

                                                @case(\App\Models\ReserveMeeting::$canceled)
                                                    <span class="text-danger font-weight-500"><b>{{ trans('public.canceled') }}</b></span>
                                                @break
                                            @endswitch
                                    </div>
                                    </div>
                                    <div class="d-inline-flex align-items-center font-weight-500 mt-20">
                                        <img style="margin-right:5px;" src="/assets/default/img/icons/cLendar.svg">
                                        <span>{{ dateTimeFormat($ReserveMeeting->start_at, 'D') }}, {{ dateTimeFormat($ReserveMeeting->start_at, 'j M Y') }}</span>
                                        <span class="mx-10"> </span>
                                        <img style="margin-right:5px;" src="/assets/default/img/icons/cloack.svg">
                                        <span class="">{{ dateTimeFormat($ReserveMeeting->start_at, 'H:i') }}</span>
                                        <span class="mx-1">-</span>
                                        <span class="">{{ dateTimeFormat($ReserveMeeting->end_at, 'H:i') }}</span>
                                    </div>
                                    <div class="d-inline-flex align-items-center butonContainer mt-10 row">
                                        
                                        @if ($ReserveMeeting->status != \App\Models\ReserveMeeting::$finished)
                                        @if ($ReserveMeeting->status != \App\Models\ReserveMeeting::$canceled)
                                                    <input type="hidden"
                                                        class="js-meeting-password-{{ $ReserveMeeting->id }}"
                                                        value="{{ $ReserveMeeting->password }}">
                                                    <input type="hidden"
                                                        class="js-meeting-link-{{ $ReserveMeeting->id }}"
                                                        value="{{ $ReserveMeeting->link }}">

                                                            @if (getFeaturesSettings('agora_for_meeting') and $ReserveMeeting->meeting_type != 'in_person')
                                                            @if($ReserveMeeting->status == \App\Models\ReserveMeeting::$open)
                                                                <button type="button"
                                                                    data-item-id="{{ $ReserveMeeting->id }}"
                                                                    data-date="{{ dateTimeFormat($ReserveMeeting->start_at, 'j M Y H:i') }}"
                                                                    data-link="{{ $ReserveMeeting->session->getJoinLink() }}"
                                                                    class="js-join-meeting-session accbtn btn-transparent btn btn-sm d-block mt-10 text-primary">{{ trans('update.join_to_session') }}</button>
                                                            @endif
                                                        @endif


                                                        @if (
                                                            $ReserveMeeting->meeting_type != 'in_person' and
                                                                !empty($ReserveMeeting->link) and
                                                                $ReserveMeeting->status == \App\Models\ReserveMeeting::$open)
                                                            <button type="button"
                                                                data-reserve-id="{{ $ReserveMeeting->id }}"
                                                                class="js-join-reserve accbtn btn-transparent btn btn-sm d-block mt-10">{{ trans('footer.join') }}</button>
                                                        @endif
                                                        {{-- @if($ReserveMeeting->status == \App\Models\ReserveMeeting::$pending)
                                                        @if ($ReserveMeeting->meeting_type != 'in_person')
                                                            <button type="button" data-item-id="{{ $ReserveMeeting->id }}"  class="add-meeting-url accbtn btn-transparent btn btn-sm d-block mt-10">{{ trans('panel.create_link') }}</button>
                                                        @endif
                                                        @endif --}}


                                                        <button id="shareButton" type="button" class="accbtn btn-transparent btn btn-sm d-block mt-10" onclick="openCalendarModal()">{{ trans('public.add_to_calendar') }}</button>

                                                        <!-- Container Mengambang -->
                                                        <div class="modal fade" id="shareModal" role="dialog" aria-labelledby="shareModalLabel" aria-hidden="true">
                                                            <div class="modal-dialog modal-lg d-flex justify-content-center align-items-center custom-width" style="width: 25%;" role="document">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title" id="shareModalLabel">{{ trans('public.calendar') }}</h5>
                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                            <span aria-hidden="true">&times;</span>
                                                                        </button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <div id="container" class="floating-container">
                                                                            <!-- Tombol Google -->
                                                                            <div class="m-2">
                                                                            <button id="floating" onclick="window.open('{{ $ReserveMeeting->addToCalendarLink() }}', '_blank')" type="button" class="btn btn-sm ml-10">
                                                                                <img src="/assets/default/img/icons/googleCal.svg">
                                                                                {{ trans('public.googlecal') }}
                                                                            </button>
                                                                            </div>
                                                                            <!-- Tombol Outlook -->
                                                                            <div class="m-2">
                                                                            <button id="floating2" onclick="window.open('{{ $ReserveMeeting->addToCalendarLinkOutlook() }}', '_blank')" type="button" class="btn btn-sm ml-10">
                                                                                <img src="/assets/default/img/icons/outlookcal.svg">
                                                                                {{ trans('public.outlookcal') }}
                                                                            </button>
                                                                            </div>
                                                                            <!-- Tombol Ical -->
                                                                            <div class="m-2">
                                                                            <button id="floating3" onclick="window.open('{{ $ReserveMeeting->addToCalendarLinkIcal() }}', '_blank')" type="button" class="btn btn-sm ml-10">
                                                                                <img src="/assets/default/img/icons/ical.svg">
                                                                                {{ trans('public.ical') }}
                                                                            </button>
                                                                            </div >
                                                                            <!-- Tombol Yahoo -->
                                                                            <div class="m-2">
                                                                            <button id="floating4" onclick="window.open('{{ $ReserveMeeting->addToCalendarLinkYahoo() }}', '_blank')" type="button" class="btn btn-sm ml-10">
                                                                                <img src="/assets/default/img/icons/yahoocal.svg">
                                                                                {{ trans('public.yahoocal') }}
                                                                            </button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        
                                                            
                                                        
                                                        <button type="button"
                                                                data-user-id="{{ $ReserveMeeting->meeting->creator_id }}"
                                                                data-item-id="{{ $ReserveMeeting->id }}"
                                                                data-user-type="instructor"
                                                                class="contact-info accbtn btn-transparent btn btn-sm d-block mt-10">{{ trans('panel.contact_instructor') }}</button>

                                                                <button onclick="openRescheduleSession(
                                                                    '{{ $ReserveMeeting->id }}',
                                                                    '{{ $ReserveMeeting->user_id }}',
                                                                    '{{ $ReserveMeeting->session_type }}',
                                                                    '{{ $ReserveMeeting->meeting_type }}',
                                                                    '{{ $ReserveMeeting->meeting->creator->id }}'
                                                                )" 
                                                        type="button" 
                                                        class="accbtn btn-transparent btn btn-sm d-block mt-10">{{ trans('panel.reschedule') }}
                                                </button>
                                                <script>
                                                    function openRescheduleSession(reserveMeetingId, userId, sessiontype, meetingtype, instructorId) {
                                                        var url = '/users/' + instructorId + '/profile?tab=appointments&reschedule' + 
                                                                    '&reserve_meeting_id=' + reserveMeetingId + 
                                                                    '&sessiontype=' + sessiontype +
                                                                    '&meetingtype=' + meetingtype + 
                                                                    '&user_id=' + userId +
                                                                    '#booksession';
                                                
                                                        window.open(url, '_blank');
                                                    }
                                                </script>
                                                        {{-- Js Calender --}}
                                                        <script>
                                                            

                                                            document.addEventListener('DOMContentLoaded', function () {
                                                            document.getElementById('shareButton').addEventListener('click', function () {

                                                                // Tampilkan modal
                                                                $('#shareModal').modal('show');
                                                            });
                                                            
                                                                // Tambahkan event listener untuk menutup modal saat tombol di dalam modal diklik
                                                                document.getElementById('floating').addEventListener('click', function () {
                                                                        $('#shareModal').modal('hide');
                                                                    });
                                                        
                                                                    document.getElementById('floating2').addEventListener('click', function () {
                                                                        $('#shareModal').modal('hide');
                                                                    });
                                                        
                                                                    document.getElementById('floating3').addEventListener('click', function () {
                                                                        $('#shareModal').modal('hide');
                                                                    });
                                                                    document.getElementById('floating4').addEventListener('click', function () {
                                                                        $('#shareModal').modal('hide');
                                                                    });
                                                                    // Menambahkan event listener pada semua elemen dengan class 'floating-btn'
                                                                    var floatingButtons = document.querySelectorAll('.floating');
                                                                    floatingButtons.forEach(function (button) {
                                                                        button.addEventListener('click', hideFloatingContainer);
                                                                        $('#shareModal').modal('hide');
                                                                    });
                                                                    
                                                            });
                                                        </script>
                                                        

                                                            <script>
                                                                
                                                            </script>

                                                        
                                                        <button type="button" data-id="{{ $ReserveMeeting->id }}" class="cancel btn btn-sm js-cancel-meeting-reserve d-block btn-transparent mt-10 font-weight-normal">{{ trans('public.cancel') }}</button>
                                                        @endif
                                                        @endif
                                    </div>
                                                    
                                </div>

                               @endforeach

                                {{-- </tbody>
                            </table> --}}
                        </div>
                    </div>
                </div>
            </div>

            <div class="my-30">
                {{ $reserveMeetings->appends(request()->input())->links('vendor.pagination.panel') }}
            </div>
            
        @else
            @include(getTemplate() . '.includes.no-result',[
                'file_name' => 'meeting.png',
                'title' => trans('panel.meeting_no_result'),
                'hint' => nl2br(trans('panel.meeting_no_result_hint')),
            ])
        @endif
    </section>

    @include('web.default.panel.meeting.join_modal')
    @include('web.default.panel.meeting.meeting_create_session_modal')
@endsection

@push('scripts_bottom')
    <script>
        var instructor_contact_information_lang = '{{ trans('panel.instructor_contact_information') }}';
        var student_contact_information_lang = '{{ trans('panel.student_contact_information') }}';
        var email_lang = '{{ trans('public.email') }}';
        var phone_lang = '{{ trans('public.phone') }}';
        var location_lang = '{{ trans('update.location') }}';
        var close_lang = '{{ trans('public.close') }}';
        var finishReserveHint = '{{ trans('meeting.finish_reserve_modal_hint') }}';
        var finishReserveConfirm = '{{ trans('meeting.finish_reserve_modal_confirm') }}';
       
       
        var finishReserveTitle = '{{ trans('meeting.finish_reserve_modal_title') }}';
        
        var finishReserveSuccess = '{{ trans('meeting.finish_reserve_modal_success') }}';
        var finishReserveSuccessHint = '{{ trans('meeting.finish_reserve_modal_success_hint') }}';
        
        var finishReserveFail = '{{ trans('meeting.finish_reserve_modal_fail') }}';
        var finishReserveFailHint = '{{ trans('meeting.finish_reserve_modal_fail_hint') }}';

        var finishReserveCancel = '{{ trans('meeting.finish_reserve_modal_cancel') }}';
        var cancelReserveSuccess = '{{ trans('meeting.cancel_reserve_modal_success') }}';
        var cancelReserveConfirm = '{{ trans('meeting.cancel_reserve_modal_confirm') }}';
        var cancelReserveTitle = '{{ trans('meeting.cancel_reserve_modal_title') }}';
        var cancelReserveSuccessHint = '{{ trans('meeting.cancel_reserve_modal_success_hint') }}';

       

    </script>
    <script src="/assets/default/vendors/daterangepicker/daterangepicker.min.js"></script>
    <script src="/assets/default/js/panel/meeting/contact-info.min.js"></script>
    <script src="/assets/default/js/panel/meeting/reserve_meeting.min.js"></script>
@endpush
