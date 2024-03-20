@extends(getTemplate() .'.panel.layouts.panel_layout')

@push('styles_top')
    <link rel="stylesheet" href="/assets/default/vendors/daterangepicker/daterangepicker.min.css">
    <link rel="stylesheet" href="/assets/default/vendors/select2/select2.min.css">
@endpush

@section('content')
    {{-- <section>
        <h2 class="section-title">{{ trans('diary.diary_statistic') }}</h2>
        <div class="activities-container shadow-sm rounded-lg mt-25 p-20 p-lg-35">
            <div class="row">
                <div class="col-6 col-md-3 d-flex align-items-center justify-content-center">
                    <div class="d-flex flex-column align-items-center text-center">
                        <img src="/assets/default/img/activity/58.svg" width="64" height="64" alt="">
                        <strong class="font-30 font-weight-bold text-secondary mt-5">{{$diariesBookCount}}</strong>
                        <span class="font-16 text-gray font-weight-500">{{ trans('diary.total_diary') }}</span>
                    </div>
                </div>

                <div class="col-6 col-md-3 d-flex align-items-center justify-content-center">
                    <div class="d-flex flex-column align-items-center text-center">
                        <img src="/assets/default/img/activity/88.svg" width="64" height="64" alt="">
                        <strong class="font-30 font-weight-bold text-secondary mt-5">{{$diariesVideoCount}}</strong>
                        <span class="font-16 text-gray font-weight-500">{{ trans('diary.total_video') }}</span>
                    </div>
                </div>

                <div class="col-6 col-md-3 mt-30 mt-md-0 d-flex align-items-center justify-content-center mt-5 mt-md-0">
                    <div class="d-flex flex-column align-items-center text-center">
                        <img src="/assets/default/img/activity/45.svg" width="64" height="64" alt="">
                        <strong class="font-30 font-weight-bold text-secondary mt-5">{{$diariesArticleCount}}</strong>
                        <span class="font-16 text-gray font-weight-500">{{ trans('diary.total_article') }}</span>
                    </div>
                </div>

                <div class="col-6 col-md-3 mt-30 mt-md-0 d-flex align-items-center justify-content-center mt-5 mt-md-0">
                    <div class="d-flex flex-column align-items-center text-center">
                        <img src="/assets/default/img/activity/45.svg" width="64" height="64" alt="">
                        <strong class="font-30 font-weight-bold text-secondary mt-5">{{$diariesOtherCount}}</strong>
                        <span class="font-16 text-gray font-weight-500">{{ trans('diary.total_other') }}</span>
                    </div>
                </div>


            </div>
        </div>
    </section> --}}

    <section class="mt-25">
        <h2 class="section-title">{{ trans('diary.diary_statistic') }}</h2>

        <div class="panel-section-card py-20 px-25 mt-20">
            <form action="/panel/diary" method="get" class="row">
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
                                    <input type="text" name="from" autocomplete="off" class="form-control @if(!empty(request()->get('from'))) datepicker @else datefilter @endif" aria-describedby="dateInputGroupPrepend" value="{{ request()->get('from','') }}"/>
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
                                    <input type="text" name="to" autocomplete="off" class="form-control @if(!empty(request()->get('to'))) datepicker @else datefilter @endif" aria-describedby="dateInputGroupPrepend" value="{{ request()->get('to','') }}"/>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-6" >
                    <div class="row">
                        <div class="col-12 col-md-6">
                        <div class="form-group">
                            <label class="input-label">{{ trans('admin/main.search') }}</label>
                            <input type="text" name="search_text" class="form-control" value="{{ request()->get('search_text','') }}"/>
                        </div>
                        </div>
                        <div class="col-12 col-md-6">
                        <div class="form-group">
                            <label class="input-label">{{ trans('admin/main.type') }}</label>
                            <select class="form-control" id="reference_type" name="reference_type">
                                <option value="">{{ trans('diary.all_diary') }}</option>
                                @foreach(\App\Models\Diary::$referenceTypes as $referenceType)
                                    <option value="{{ $referenceType }}" @if(request()->get('reference_type') == $referenceType) selected @endif>{{ __('diary.'.$referenceType) }}</option>
                                @endforeach
                            </select>
                        </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-2 d-flex align-items-center justify-content-end">
                    <button type="submit" class="btn btn-sm btn-primary w-100 mt-2">{{ trans('public.show_results') }}</button>
                </div>
            </form>
        </div>
    </section>

    <section class="mt-35">


        @if($diaries->count() > 0)

            <div class="panel-section-card py-20 px-25 mt-20">
                <div class="row">
                    <div class="col-12 ">
                        <div class="table-responsive">
                            <table class="table text-center custom-table table-striped">
                                <thead>
                                    <tr>
                                        <th class="text-center align-top">No</th>
                                        <th class="text-left align-top">{{ trans('diary.title') }}</th>
                                        <th class="text-center align-top">{{ trans('diary.reference_type') }}</th>
                                        <th class="text-center align-top">{{ trans('public.date') }}</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($diaries as $diary)
                                    <tr style="cursor: pointer;">
                                        <td class="text-center">
                                            <span>{{ $loop->iteration }}</span>
                                        </td>
                                        <td class="text-left">
                                            <span onclick="window.location.href='/panel/diary/{{ $diary->id }}/edit'" class="d-block">{{ $diary->title }}</span>
                                        </td>                                        
                                        <td class="text-center align-middle">{{ __('diary.'.$diary->reference_type) }}</td>
                                        <td class="text-center align-middle align-items-center" style="width:30%">
                                            @if ($diary->dated_at)
                                            <img style="margin-right:1px;" src="/assets/default/img/icons/cLendar.svg">
                                            {{ $diary->dated_at->format('d M Y') }}
                                            <img style="margin-right:1px; margin-left:5px;" src="/assets/default/img/icons/cloack.svg">
                                            {{ $diary->dated_at->format('h:i A') }}
                                            @else
                                            N/A
                                            @endif
                                        </td>
                                        <td class="text-center align-middle" style="width: 5%;">
                                            <div class="btn-group dropdown table-actions">
                                                <button type="button" class="btn-transparent dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i data-feather="more-vertical" height="20"></i>
                                                </button>
                                                <div class="dropdown-menu font-weight-normal">
                                                    <!-- Tindakan Bagikan -->
                                                    <a href="javascript:void(0);" onclick="copyToClipboard('{{ request()->url() }}/{{ $diary->id }}/edit?readonly')" class="webinar-actions d-block mt-10"><i class="fas fa-share"></i> {{ trans('public.share') }}</a>
                                                    <!-- Tindakan Edit -->
                                                    <a href="/panel/diary/{{ $diary->id }}/edit" class="webinar-actions d-block mt-10"><i class="fas fa-edit"></i> {{ trans('public.edit') }}</a>
                                                    <!-- Tindakan Hapus -->
                                                    <a href="/panel/diary/{{ $diary->id }}" data-item-id="1" class="webinar-actions d-block mt-10 delete-action"><i class="fas fa-trash-alt"></i> {{ trans('public.delete') }}</a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                    </div>
                </div>
            </div>
        @else

            @include(getTemplate() . '.includes.no-result',[
                'file_name' => 'quiz.png',
                'title' => trans('diary.diary_no_result'),
                'hint' => nl2br(trans('diary.diary_no_result_hint')),
                'btn' => ['url' => '/panel/diary/create','text' => trans('diary.create_a_diary')]
            ])

        @endif

    </section>

    <div class="my-30">
        {{ $diaries->appends(request()->input())->links('vendor.pagination.panel') }}
    </div>
@endsection

@push('scripts_bottom')
    <script src="/assets/default/vendors/daterangepicker/daterangepicker.min.js"></script>
    <script src="/assets/default/vendors/select2/select2.min.js"></script>

    <script src="/assets/default/js/panel/diary_list.min.js"></script>
@endpush
<script>
     document.addEventListener('DOMContentLoaded', function () {
    @foreach($diaries as $diary)

        document.getElementById('share{{ $diary->id }}').addEventListener('click', function () {
            // Setel konten yang ingin Anda salin ke clipboard
            var contentToCopy = 'Konten yang ingin disalin';

            // Setel nilai dari input field di dalam modal
            document.getElementById('container').value = contentToCopy;

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

            document.getElementById('floating5').addEventListener('click', function () {
                $('#shareModal').modal('hide');
            });

            document.getElementById('floating6').addEventListener('click', function () {
                $('#shareModal').modal('hide');
            });
        @endforeach
    });

    // Fungsi untuk menyalin ke clipboard (sesuaikan dengan kebutuhan)
  function copyToClipboard(text) {
    // Menambahkan ?readonly setelah URL
    var readonlyText = text + (text.includes('?') ? '&readonly' : '?readonly');
    
      var textarea = document.createElement('textarea');
      textarea.value = text;
      document.body.appendChild(textarea);
      textarea.select();
      document.execCommand('copy');
      document.body.removeChild(textarea);
      alert('Link copied to clipboard!');
  }
    
</script>
