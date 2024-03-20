<section class="mt-2">
    <div class="d-flex align-items-start align-items-md-center justify-content-between flex-column flex-md-row">
        <h2 class="section-title">{{ trans('update.current_courses') }}</h2>
    </div>

    @if(!empty($sales) and !$sales->isEmpty())
        @foreach($sales->take(2) as $sale)
            @php
                $item = !empty($sale->webinar) ? $sale->webinar : $sale->bundle;

                $lastSession = !empty($sale->webinar) ? $sale->webinar->lastSession() : null;
                $nextSession = !empty($sale->webinar) ? $sale->webinar->nextSession() : null;
                $isProgressing = false;

                if(!empty($sale->webinar) and $sale->webinar->start_date <= time() and !empty($lastSession) and $lastSession->date > time()) {
                    $isProgressing = true;
                }
            @endphp

            @if(!empty($item))
                <div class="row mt-2">
                    <div class="col-12">
                        <div style="height:100%" class="webinar-card webinar-list d-flex">
                            

                            <div class="webinar-card-body w-100 d-flex flex-column">
                                <div class="d-flex align-items-center justify-content-between">
                                    <a href="{{ $item->getUrl() }}">
                                        <h3 class="height2 font-weight-bold font-14 text-dark-blue">
                                            {{ $item->title }}

                                        </h3>
                                        
                                        
                                                <span class="stat-title">{{ $item->teacher->full_name }}</span>
                                   
                                        
                                    </a>
                                </div>

                                @include(getTemplate() . '.includes.webinar.rate2',['rate' => $item->getRate()])

                                <div class="d-flex align-items-center justify-content-between flex-wrap mt-auto">

                                    @if(!empty($sale->gift_id) and $sale->buyer_id == $authUser->id)
                                        <div class="d-flex align-items-start flex-column mt-2 mr-15">
                                            <span class="stat-title">{{ trans('update.gift_status') }}:</span>

                                            @if(!empty($sale->gift_date) and $sale->gift_date > time())
                                                <span class="stat-value text-warning">{{ trans('public.pending') }}</span>
                                            @else
                                                <span class="stat-value text-primary">{{ trans('update.sent') }}</span>
                                            @endif
                                        </div>
                                    @else
                                        
                                    @endif

                                    @if(!empty($sale->gift_id))
                                        <div class="d-flex align-items-start flex-column mt-2 mr-15">
                                            <span class="stat-title">{{ trans('update.gift_receive_date') }}:</span>
                                            <span class="stat-value">{{ (!empty($sale->gift_date)) ? dateTimeFormat($sale->gift_date, 'j M Y H:i') : trans('update.instantly') }}</span>
                                        </div>
                                    @else
                                    
                                    @endif

                                

                                    <div style="width: 100%; margin-top:-30px !important">
                            
                                        @if(!empty($sale->webinar))
                                            
                                                @if($item->start_date > time())
                                                    <span class="badge "></span>
                                                @elseif($item->isProgressing())
                                                    <span class="badge "></span>
                                                @else
                                                    <span class="badge "></span>
                                                @endif
                        
                                            @php
                                                $percent = $item->getProgress();
    
                                                if($item->isWebinar()){
                                                    if($item->isProgressing()) {
                                                        $progressTitle = trans('public.course_learning_passed',['percent' => $percent]);
                                                    } else {
                                                        $progressTitle = $item->sales_count .'/'. $item->capacity .' '. trans('quiz.students');
                                                    }
                                                } else {
                                                       $progressTitle = trans('public.course_learning_passed',['percent' => $percent]);
                                                }
                                            @endphp
    
                                            @if(!empty($sale->gift_id) and $sale->buyer_id == $authUser->id)
                                                {{--  --}}
                                            @else
                                                <div class="progress cursor-pointer" data-toggle="tooltip" data-placement="top" title="{{ $progressTitle }}">
                                                    <span class="progress-bar" style="width: {{ $percent }}%"></span>
                                                </div>
                                            @endif
                                        @else
                                            <span class="badge badge-secondary">{{ trans('update.bundle') }}</span>
                                        @endif
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        @endforeach
    @else

    <div class="bg-white noticeboard rounded-sm panel-shadow py-10 py-md-20 px-15 px-md-30 height1 mt-2">       
            @include(getTemplate() . '.includes.no-result-dashboard',[
            'file_name' => 'student.png',
            'title' => trans('panel.no_result_purchases') ,
            'hint' => trans('panel.no_result_purchases_hint') ,
            'btn' => ['url' => '/classes?sort=newest','text' => trans('panel.start_learning')]
        ])
    </div>
    @endif
</section>