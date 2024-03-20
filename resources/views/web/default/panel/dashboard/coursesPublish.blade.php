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
    <h3 class="text-dark-blue mb-2">{{__('update.courses_published')}}</h3>
    <a href="/panel/webinars" class="view ml-auto">{{__("admin/main.view_more")}}</a>
</div>

    <div class="bg-white noticeboard rounded-sm panel-shadow py-10 py-md-20 px-15 px-md-30 height">
       
        @if($courses->count() > 0)
        @foreach($courses->take(5) as $diary)
        
        <div class="border  ">
            <div class="row align-items-center">
                <div class=" d-flex align-items-start col-12">
                    @if(empty($notification->notificationStatus))
                        <span class="notification-badge badge badge-circle-danger mr-5 mt-5 d-flex align-items-center justify-content-center"></span>
                    @endif
    
                    <div class="col-6">
                        <h3 class="notification-title font-16 font-weight-bold text-dark-blue">{{ $diary->title }}</h3>
                        
                    </div>
                    <div class="col-6">
                        @if ($diary->status=="pending")
                        <span style="text-align: right;" class="notification-time d-block font-16 font-weight-bold text-warning mt-5">{{$diary->status }}</span>
                             
                         @elseif($diary->status=="active")
                         <span style="text-align: right;" class="notification-time d-block font-16 font-weight-bold text-primary mt-5">{{$diary->status }}</span>
                         
                         @else
                         <span style="text-align: right;" class="notification-time d-block  font-16 font-weight-bold text-danger mt-5">{{$diary->status }}</span>   
                        @endif
                    
                    </div>
                </div>
            </div>
        </div>
        @endforeach
        @else

            @include(getTemplate() . '.includes.no-result-dashboard',[
                'file_name' => 'webinar.png',
                'title' => trans('panel.you_not_have_any_webinar'),
                'hint' =>  trans('panel.no_result_hint') ,

            ])

        @endif

    </div>


