<style>
    .custom-width {
        width: 80% !important; /* Lebar 80% untuk tampilan mobile */
        margin: auto !important; /* Membuat elemen berada di tengah secara horizontal */
    }
    
    @media (min-width: 768px) and (max-width: 1199px) {
        .custom-width {
            width: 50% !important; /* Lebar 50% untuk tampilan tab */
        }
    }
    
    @media (min-width: 1200px) {
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
    
    .custom-table-wrapper {
    max-height: 400px; /* Sesuaikan tinggi maksimum yang diinginkan */
    overflow-y: auto;
}
</style>
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
                                    <th style="color:var(--secondary)" class="text-center align-top"><b>No</b></th>
                                    <th style="color:var(--secondary)" class="text-left align-top"><b>{{ trans('diary.title') }}</b></th>
                                    <th style="color:var(--secondary)" class="text-center align-top"><b>{{ trans('diary.reference_type') }}</b></th>
                                    <th style="color:var(--secondary)" class="text-center align-top"><b>{{ trans('public.date') }}</b></th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>

                                @foreach($diaries as $diary)
                                    <tr style="cursor: pointer;">
                                        <td onclick="window.location.href='/panel/diary/{{ $diary->id }}/edit'" class="text-align-center">
                                            <span>{{ ($loop->index + 1) + ($diaries->perPage() * ($diaries->currentPage() - 1)) }}</span>
                                        </td>
                                        <td onclick="window.location.href='/panel/diary/{{ $diary->id }}/edit'" class="text-left">
                                            <span class="d-block" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 250px;">
                                                {{ $diary->title }}
                                            </span>
                                        </td> 
                                        <td onclick="window.location.href='/panel/diary/{{ $diary->id }}/edit'" class="text-align-center">
                                            <span class="d-block">{{ __('diary.'.$diary->reference_type) }}</span>
                                        </td>                                      
                                        

                                        <td onclick="window.location.href='/panel/diary/{{ $diary->id }}/edit'" class="text-align-center align-items-center" style="width: 30%;">
                                            <div style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                                @if ($diary->dated_at)
                                                    <img style="margin-right: 1px;" src="/assets/default/img/icons/cLendar.svg">
                                                    {{ $diary->dated_at->format('d M Y') }}
                                                    <img style="margin-right: 1px; margin-left: 5px;" src="/assets/default/img/icons/cloack.svg">
                                                    {{ $diary->dated_at->format('h:i A') }}
                                                @else
                                                    N/A
                                                @endif
                                            </div>
                                        </td>
                                        
                                        <td class="text-center align-middle" style="width: 5%;">
                                            <div class="btn-group dropdown table-actions">
                                                <button id="share{{ $diary->id }}" class="webinar-actions btn-sm btn btn-primary">
                                                    <i class="fas fa-share"></i> {{ trans('public.share') }}
                                                </button>
                                                <button href="/panel/diary/{{ $diary->id }}" data-item-id="1" class=" btn-sm webinar-actions btn btn-danger delete-action">
                                                    {{ trans('public.delete') }}
                                                </button>
                                            </div>
                                            
                                        </td>
                                        <!-- Modal Kontainer Melayang -->
                                        <div class="modal fade" id="shareModal" role="dialog" aria-labelledby="shareModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-lg d-flex justify-content-center align-items-center custom-width" style="width: 25%;" role="document">
                                                  <div class="modal-content">
                                                    <div class="modal-header">
                                                      <h5 class="modal-title" id="shareModalLabel">{{ trans('public.share') }}</h5>
                                                      <buttcloon type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                          <span aria-hidden="true">&times;</span>
                                                      </button>
                                                  </div>
                                                <div class="modal-body">
                                                    <div id="container" class="floating-container">
                                                        
                                                        <a id="floating" href="javascript:void(0);" onclick="copyToClipboard('{{ request()->url() }}/{{ $diary->id }}/edit?readonly')"
                                                           class="btn btn-sm ml-10 ">
                                                           <img src="/assets/default/img/icons/copy.svg">
                                                            {{ trans('public.copy_to_clipboard') }}
                                                        </a>
                                                      
                                                        <!-- Tombol Share WhatsApp -->
                                                        <a id="floating2" href="whatsapp://send?text={{ request()->url() }}/{{ $diary->id }}/edit?readonly" target="_blank"
                                                           class="btn btn-sm ml-10 ">
                                                           <img src="/assets/default/img/icons/wa.svg">
                                                            {{ trans('public.share_to_wa') }}
                                                        </a>
                                                      
                                                        <!-- Tombol Share Email -->
                                                        <a id="floating3" href="mailto:?subject=Subject&body={{ request()->url() }}/{{ $diary->id }}/edit?readonly&text=Hai%20Chek%20My%20Learning%20Diary"
                                                           class="btn btn-sm ml-10 ">
                                                           <img src="/assets/default/img/icons/email.svg">
                                                            {{ trans('public.share_to_email') }}
                                                        </a>
                                                      
                                                        <!-- Tombol Share Telegram -->
                                                        <a id="floating4" href="https://t.me/share/url?url={{ request()->url() }}/{{ $diary->id }}/edit?readonly&text=Hai%20Chek%20My%20Learning%20Diary"
                                                           class="btn btn-sm ml-10 " target="_blank">
                                                           <img src="/assets/default/img/icons/telegram.svg">
                                                            {{ trans('public.share_to_telegram') }}
                                                        </a>
                                                      
                                                        <!-- Tombol Share Twitter -->
                                                        <a id="floating5" href="https://twitter.com/intent/tweet?url={{ request()->url() }}/{{ $diary->id }}/edit?readonly&text=Hai%20Chek%20My%20Learning%20Diary"
                                                           class="btn btn-sm ml-10 " target="_blank">
                                                           <img src="/assets/default/img/icons/twitter.svg">
                                                            {{ trans('public.share_to_twitter') }}
                                                        </a>
                                                      
                                                        <!-- Tombol Share Facebook -->
                                                        <a id="floating6" href="https://www.facebook.com/sharer/sharer.php?u={{ request()->url() }}/{{ $diary->id }}/edit?readonly&text=Hai%20Chek%20My%20Learning%20Diary"
                                                           class="btn btn-sm ml-10 " target="_blank">
                                                           <img src="/assets/default/img/icons/facebook.svg">
                                                            {{ trans('public.share_to_facebook') }}
                                                        </a>
                                                      </div>
                                                    
                                                </div>
                                            </div>
                                        </div>
                                    </div>
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
