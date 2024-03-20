<style>
    .notification {
        border-bottom: 1px solid rgba(0, 0, 0, 0.6);
        margin-bottom: 20px;
    }

    .border {
        border: 1px solid rgba(0, 0, 0, 0.4);
        border-radius: 10px;
        padding: 10px;
        margin: 5px;
    }
    .border:hover{
        box-shadow: 0px 12px 23px 0px rgba(62, 73, 84, 0.04);
        border-color: var(--primary) !important;
    }
</style>
<div class="row m-0">
    <h3 class="text-dark-blue mb-2">{{__('diary.diary')}}</h3>
    <a href="/panel/diary/create" class="view ml-auto">{{__("diary.create_a_diary")}}</a>
</div>
<div class="bg-white noticeboard rounded-sm panel-shadow py-10 py-md-20 px-15 px-md-30 height">


    @if($authUser->getDiaries()->limit(5)->latest()->get()->count() > 0)
    @foreach($authUser->getDiaries()->limit(5)->latest()->get() as $diary)

    <div class="d-flex align-items-center justify-content-between border" onclick="redirect('{{ route('diary.edit', $diary->id) }}')">
        <div>
            <h6 class="js-noticeboard-title font-weight-500 text-secondary font-12">{{ truncate($diary->title,50) }}</h6>
            <div class="font-12 text-gray mt-0">
                <span class="">
                    {{ $diary->dated_at->format('d M Y') }}
                </span>
            </div>
        </div>

        <div>

        </div>
    </div>

    @endforeach
    @else

    @include(getTemplate() . '.includes.diary-result-dashboard',[
    'file_name' => 'quiz.png',
    'title' => trans('diary.diary_no_result_hint'),
    'hint' => nl2br(trans('diary.diary_no_result')),
    // 'btn' => ['url' => '/panel/diary/create','text' => trans('diary.create_a_diary')]
    ])

    @endif

</div>

<script>
    function redirect(url) {
        window.location.href = url;
    }
</script>
