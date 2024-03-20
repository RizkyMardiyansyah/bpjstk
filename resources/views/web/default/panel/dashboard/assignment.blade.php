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
    <h3 class="text-dark-blue mb-2">{{__('update.your_students_assignments')}}</h3>
    <a href="/panel/assignments/my-courses-assignments" class="view ml-auto">{{__("admin/main.view_more")}}</a>
</div>
    <div class="bg-white noticeboard height rounded-sm panel-shadow py-10 py-md-20 px-15 px-md-30">
        

        @if($assignments->count() > 0)
        @foreach($assignments->take(5) as $assignment)
            
                <div class="d-flex align-items-center justify-content-between border">
                    <div class="col-10">
                        <h4 class="js-noticeboard-title font-weight-500 text-secondary">{{ truncate($assignment->title,50) }}</h4>
                    </div>
                    <div class="col-2">
                        

                            <div class="btn-group dropdown table-actions">
                                <button type="button" class="btn-transparent dropdown-toggle"
                                        data-toggle="dropdown" aria-haspopup="true"
                                        aria-expanded="false">
                                    <i data-feather="more-vertical" height="20"></i>
                                </button>

                                <div class="dropdown-menu menu-lg">
                                    <a href="/panel/assignments/{{ $assignment->id }}/students?status=pending" target="_blank"
                                       class="webinar-actions d-block mt-10 font-weight-normal">{{ trans('update.pending_review') }}</a>

                                    <a href="/panel/assignments/{{ $assignment->id }}/students" target="_blank"
                                       class="webinar-actions d-block mt-10 font-weight-normal">{{ trans('update.all_assignments') }}</a>
                                </div>
                            </div>                        
                    </div>

                    <div>

                    </div>
                </div>
        @endforeach
        @else
            @include(getTemplate() . '.includes.no-result-dashboard',[
                'file_name' => 'meeting.png',
                'title' => trans('update.my_assignments_no_result'),
                'hint' => nl2br(trans('update.my_assignments_no_result_hint')),
            ])
        @endif

    </div>
       
            