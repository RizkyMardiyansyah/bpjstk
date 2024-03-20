<style>
    .notification{
        border-bottom: 1px solid rgba(0, 0, 0, 0.6);
        margin-bottom: 20px;
    }
    .border{
        border: 1px solid rgba(0, 0, 0, 0.4);
        border-radius: 10px;
        padding: 10px;
        margin: 5px;
    }
</style>
<div class="row m-0">
    <h3 class="text-dark-blue mb-2">{{__('panel.notifications')}}</h3>
    <a href="/panel/notifications" class="view ml-auto">{{__("admin/main.view_more")}}</a>
</div>
<div class="bg-white noticeboard height rounded-sm panel-shadow py-10 py-md-20 px-10 px-md-30">
    

@if($unReadNotifications->count() > 0)
    @foreach($unReadNotifications->take(5) as $notification)
    <div class="border " onclick="redirect('/panel/notifications')">
        <div class="row align-items-center">
            <div class=" d-flex align-items-start col-12">
                @if(empty($notification->notificationStatus))
                    <span class="notification-badge badge badge-circle-danger mr-5 mt-5 d-flex align-items-center justify-content-center"></span>
                @endif
                

                <div class="col-6">
                    
                    <h3 class="notification-title font-16 font-weight-bold text-dark-blue">{{ $notification->title }}</h3>                   
                </div>
                <div class="col-6">
                    <span style="text-align: right" class="notification-time d-block font-12 text-gray mt-5">{{ dateTimeFormat($notification->created_at,'j M Y | H:i') }}</span>
                </div>
                
            </div>

            {{-- <div class="">
                <span class="font-weight-500 text-gray font-14">{!! truncate($notification->message, 150, true) !!}</span>
            </div> --}}

            {{-- <div class="col-12 col-lg-4 mt-10 mt-lg-0 text-right">
                <button type="button" data-id="{{ $notification->id }}" id="showNotificationMessage{{ $notification->id }}" class="js-show-message btn btn-border-white @if(!empty($notification->notificationStatus)) seen-at @endif">{{ trans('public.view') }}</button>
                <input type="hidden" class="notification-message" value="{!! $notification->message !!}">
            </div> --}}
        </div>
    </div>
@endforeach
@else

            @include(getTemplate() . '.includes.no-result-dashboard',[
                'file_name' => 'comment.png',
                'title' => trans('diary.notifications_no_result'),
                'hint' => nl2br(trans('diary.diary_no_result_hint')),
            ])

        @endif

    </div>
    <script>
        function redirect(url){
            window.location.href = url;
        }
    </script>

