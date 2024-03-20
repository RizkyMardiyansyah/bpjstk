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
    
    
    
        .pd{
            margin-top:20px;
            padding: 30px;
        }
        
        .option .checkbox-label {
            font-size: 14px;
            display: flex;
            align-items: center;
        }
        .option .checkbox-label label{
            padding: 0px;
            margin: 10px;
            margin-left: 5px;
        }
        .option .checkbox-label input{
            margin-left: 5px;
        }
    
        .a{
        color:var(--primary);
        }
    
        .temp {
        padding: 0px;
        width: 15px;
        height: 15px;
        border-radius: 50px;
        color:var(--primary);
        background: transparent;
        border: 1px solid var(--primary);
        transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out;
        box-shadow: none;
    }
    .temp:focus,
    .temp:active,
    .temp:hover{
        color: white;
        background: var(--primary);
    }
    .remove{
        border-top-left-radius: 0;
        border-bottom-left-radius: 0px;
        height: 40px;
        border: none;
        box-shadow: none;
        transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
    }
    .skills{
        border-top-right-radius: 0;
        border-bottom-right-radius: 0px;
        height: 40px;
    }
    .templateActive{
        color: white;
        background: var(--primary);
    }
    
    /* Optional styling untuk penataan */
    .skillsContainer {
    display: flex;
    
    }
    
    
    
    </style>
    <!-- Include Select2 CSS and JS -->
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>
    
    <div data-action="{{ !empty($diary) ? ('/panel/diary/'. $diary->id) : ('/panel/diary') }}" class="js-content-form quiz-form webinar-form" id="li">
    
      <section>
        @if(request()->has('readonly'))
        <h2 class="section-title after-line">{{ trans('diary.diary') }}</h2>
        @else
            <h2 class="section-title after-line">{{ !empty($diary) ? trans('public.edit') : trans('diary.new_diary') }}</h2>
        @endif
          
          
    <div class="card pd">
          <div class="row">
              <div class="col-12">
                  {{-- @if(request()->has('readonly'))
                  <script type="text/javascript">
                      // Disable all input
                      document.addEventListener('DOMContentLoaded', function () {
                          // sleep
                          const inputs = document.querySelectorAll('input, select, summernote');
                          inputs.forEach(function (input) {
                              input.setAttribute('disabled', 'disabled');
                          });
                      });
                      
                  </script>
                  @endif --}}
                  
            @if(request()->has('readonly'))
            <div class="form-group" style="display: flex; margin-bottom:0px;">
                
                <label style="margin-left:auto; color:black;" class="input-label"><b>{{ $diary->reference_type }}</b>{{' | '.$diary->dated_at->format('d M Y h:i A')}}</label>
                <input style="display: none;"  type="text" name="ajax[{{ !empty($diary) ? $diary->id : 'new' }}][dated_at]"  value="{{ !empty($diary) ? $diary->dated_at : old('dated_at', date('d m Y')) }}" class="js-ajax-title form-control"  />
                <div class="invalid-feedback"></div>
            </div>
                <div class="form-group title-form">
                      <label class="input-label"><b>{{$diary->title}}</b></label>
                      <input style="display: none" type="text" name="ajax[{{ !empty($diary) ? $diary->id : 'new' }}][title]" value="{{ !empty($diary) ? $diary->title : old('title') }}" class="js-ajax-title form-control" />
                      <div class="invalid-feedback"></div>
                </div>
                  
                
                    
                    <div class="form-group" >
                        {{-- <span style="color: black;" class="input-label">{{ trans('diary.reference_type').', '.$diary->reference_type }}</span> --}}
                        <select style="display: none;"  name="ajax[{{ !empty($diary) ? $diary->id : 'new' }}][reference_type]" class="form-control {{ !empty($banner) ? 'js-edit-content-locale' : '' }}" id="referenceTypeSelect">
                            <option value="" disabled selected style="display: none;">{{ trans('diary.choose_reference') }}</option>
                            <optgroup label="{{ trans('diary.information_sources') }}">
                                <option value="article" @if(mb_strtolower(!empty($diary) ? $diary->reference_type : 'new') == mb_strtolower("article")) selected @endif>{{ __('diary.article') }}</option>
                                <option value="book" @if(mb_strtolower(!empty($diary) ? $diary->reference_type : 'new') == mb_strtolower("book")) selected @endif>{{ __('diary.book') }}</option>
                                <option value="video" @if(mb_strtolower(!empty($diary) ? $diary->reference_type : 'new') == mb_strtolower("video")) selected @endif>{{ __('diary.video') }}</option>
                            </optgroup>
                            <optgroup label="{{ trans('diary.development_sources') }}">
                                <option value="coaching" @if(mb_strtolower(!empty($diary) ? $diary->reference_type : 'new') == mb_strtolower("coaching")) selected @endif> {{ __('diary.coaching') }}</option>
                                <option value="other" @if(mb_strtolower(!empty($diary) ? $diary->reference_type : 'new') == mb_strtolower("other")) selected @endif>{{ __('diary.other') }}</option>
                                <option value="mentoring" @if(mb_strtolower(!empty($diary) ? $diary->reference_type : 'new') == mb_strtolower("mentoring")) selected @endif> {{ __('diary.mentoring') }}</option>
                                <option value="training" @if(mb_strtolower(!empty($diary) ? $diary->reference_type : 'new') == mb_strtolower("training")) selected @endif> {{ __('diary.training') }}</option>
                            </optgroup>
                            {{-- @foreach($referenceTypes as $type)
                                <option value="{{ $type }}" @if(mb_strtolower(!empty($diary) ? $diary->reference_type : 'new') == mb_strtolower($type)) selected @endif>{{ __('diary.'.$type) }}</option>
                            @endforeach --}}
                        </select>
                        
                        
                        {{-- <select style="display: none;" name="ajax[{{ !empty($diary) ? $diary->id : 'new' }}][reference_type]" class="form-control {{ !empty($banner) ? 'js-edit-content-locale' : '' }}" id="referenceTypeSelect" >
                            @foreach(\App\Models\Diary::$referenceTypes as $type)
                                <option value="{{ $type }}" @if(mb_strtolower( !empty($diary) ? $diary->reference_type : 'new') == mb_strtolower($type)) selected @endif>{{ __('diary.'.$type) }}</option>
                            @endforeach
                        </select> --}}
                        <div class="invalid-feedback"></div>
                    </div>
                
                <div class="form-group option" style="font-size: 14px;">
                    <label class="input-label">{{ trans('diary.skills') }}</label>
                   
                    <span>
                        @php
                        $skillsArray = explode(',', $diary->skills);
                        @endphp
                        
                        <ul>
                            @foreach($skillsArray as $skill)
                                <li>{{ $skill }}</li>
                            @endforeach
                        </ul>   
                    </span>
                    <div class="row" style="display: none;">
                        <div class="col-12 col-md-6">
                            <div class="checkbox-label">
                                <input type="checkbox" name="selectedOptions[]" value="Analytical Problem Solving & Planing" id="option5" >
                                <label for="option5">Analytical Problem Solving & Planing</label>
                            </div>
                            <div class="checkbox-label">
                                <input type="checkbox" name="selectedOptions[]" value="Agile Leadership" id="option8" >
                                <label for="option8">Agile Leadership</label>
                            </div>
                            <div class="checkbox-label">
                                <input type="checkbox" name="selectedOptions[]" value="Driving Digital Innovation" id="option4" >
                                <label for="option4">Driving Digital Innovation</label>
                            </div>
                            <div class="checkbox-label">
                                <input type="checkbox" name="selectedOptions[]" value="Developing Capabilities" id="option7" >
                                <label for="option7">Developing Capabilities</label>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="checkbox-label">
                                <input type="checkbox" name="selectedOptions[]" value="Results Orientation & Execution" id="option1" >
                                <label for="option1">Results Orientation & Execution</label>
                            </div>
                            <div class="checkbox-label">
                                <input type="checkbox" name="selectedOptions[]" value="Stakeholder Orientation" id="option3" >
                                <label for="option3">Stakeholder Orientation</label>
                            </div>
                            <div class="checkbox-label">
                                <input type="checkbox" name="selectedOptions[]" value="Strategy & Business Acumen" id="option6" >
                                <label for="option6">Strategy & Business Acumen</label>
                            </div>
                            <div class="checkbox-label">
                                <input type="checkbox" name="selectedOptions[]" value="Synergistic Collaboration" id="option2" >
                                <label for="option2">Synergistic Collaboration</label>
                            </div>
                        </div>
                    </div>
                    <div class="invalid-feedback"></div>
                </div>
                <div class="skillsContainer form-group" id="skillsContainer" style="display: none;">
                    <input type="text" name="ajax[{{ !empty($diary) ? $diary->id : 'new' }}][skills]" value="{{ !empty($diary) ? $diary->skills : old('skills') }}" class="skills js-ajax-title form-control" id="skills" />
                    <button type="button" id="removeSkill" class="btn btn-sm btn-danger remove"><img src="/assets/default/img/icons/delete.svg"></button>
                    <div class="invalid-feedback"></div>
                </div>
                {{-- <label class="input-label">{{ trans('diary.select_template') }}</label>
                <div style="align-items: center" class="form-group d-flex">
                    <button style="" class="radio temp btn btn-sm btn-primary " id="buttonA" onclick="changeValue('template1')" disabled></button><span style="font-size: 14px; margin-left:5px; margin-right:15px;">{{ trans('diary.jurney') }}</span>
                    <button style="" class="radio temp btn btn-sm btn-primary " id="buttonB" onclick="changeValue('template2')" disabled></button><span style="font-size: 14px; margin-left:5px; margin-right:15px">{{ trans('diary.task') }}</span>
                </div> --}}
              </div>
        @else
            <div class="form-group title-form">
                <label class="input-label">{{ trans('diary.title') }} </label>
                <input type="text" name="ajax[{{ !empty($diary) ? $diary->id : 'new' }}][title]" value="{{ !empty($diary) ? $diary->title : old('title') }}" class="js-ajax-title form-control"/>
                <div class="invalid-feedback"></div>
            </div>        
            <div class="row">
                <div class="form-group col-12 col-md-6">
                    <label class="input-label">{{ trans('diary.dated_at') }}</label>
                    <input type="text" name="ajax[{{ !empty($diary) ? $diary->id : 'new' }}][dated_at]" id="datepicker" value="{{ !empty($diary) ? $diary->dated_at : old('dated_at', date('Y-m-d')) }}" class="js-ajax-title form-control" />
                    <div class="invalid-feedback"></div>
                </div>
                <div class="form-group col-12 col-md-6">
                    <label class="input-label">{{ trans('diary.reference_type') }}</label>
                    <select name="ajax[{{ !empty($diary) ? $diary->id : 'new' }}][reference_type]" class="form-control {{ !empty($banner) ? 'js-edit-content-locale' : '' }}" id="referenceTypeSelect">
                        <option value="" disabled selected style="display: none;">{{ trans('diary.choose_reference') }}</option>
                        <optgroup label="{{ trans('diary.information_sources') }}">
                            <option value="article" @if(mb_strtolower(!empty($diary) ? $diary->reference_type : 'new') == mb_strtolower("article")) selected @endif>{{ __('diary.article') }}</option>
                            <option value="book" @if(mb_strtolower(!empty($diary) ? $diary->reference_type : 'new') == mb_strtolower("book")) selected @endif>{{ __('diary.book') }}</option>
                            <option value="video" @if(mb_strtolower(!empty($diary) ? $diary->reference_type : 'new') == mb_strtolower("video")) selected @endif>{{ __('diary.video') }}</option>
                        </optgroup>
                        <optgroup label="{{ trans('diary.development_sources') }}">
                            <option value="coaching" @if(mb_strtolower(!empty($diary) ? $diary->reference_type : 'new') == mb_strtolower("coaching")) selected @endif> {{ __('diary.coaching') }}</option>
                            <option value="other" @if(mb_strtolower(!empty($diary) ? $diary->reference_type : 'new') == mb_strtolower("other")) selected @endif>{{ __('diary.other') }}</option>
                            <option value="mentoring" @if(mb_strtolower(!empty($diary) ? $diary->reference_type : 'new') == mb_strtolower("mentoring")) selected @endif> {{ __('diary.mentoring') }}</option>
                            <option value="training" @if(mb_strtolower(!empty($diary) ? $diary->reference_type : 'new') == mb_strtolower("training")) selected @endif> {{ __('diary.training') }}</option>
                        </optgroup>
                        {{-- @foreach($referenceTypes as $type)
                            <option value="{{ $type }}" @if(mb_strtolower(!empty($diary) ? $diary->reference_type : 'new') == mb_strtolower($type)) selected @endif>{{ __('diary.'.$type) }}</option>
                        @endforeach --}}
                    </select>
                    
                    <div class="invalid-feedback"></div>
                </div>
            </div>
            <div class="form-group option">
                <label class="input-label">{{ trans('diary.skills') }}</label>
                <div class="row">
                    <div class="col-12 col-md-6">
                        <div class="checkbox-label">
                            <input type="checkbox" name="selectedOptions[]" value="Analytical Problem Solving & Planing" id="option5">
                            <label for="option5">Analytical Problem Solving & Planing</label>
                        </div>
                        <div class="checkbox-label">
                            <input type="checkbox" name="selectedOptions[]" value="Agile Leadership" id="option8">
                            <label for="option8">Agile Leadership</label>
                        </div>
                        <div class="checkbox-label">
                            <input type="checkbox" name="selectedOptions[]" value="Driving Digital Innovation" id="option4">
                            <label for="option4">Driving Digital Innovation</label>
                        </div>
                        <div class="checkbox-label">
                            <input type="checkbox" name="selectedOptions[]" value="Developing Capabilities" id="option7">
                            <label for="option7">Developing Capabilities</label>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="checkbox-label">
                            <input type="checkbox" name="selectedOptions[]" value="Results Orientation & Execution" id="option1">
                            <label for="option1">Results Orientation & Execution</label>
                        </div>
                        <div class="checkbox-label">
                            <input type="checkbox" name="selectedOptions[]" value="Stakeholder Orientation" id="option3">
                            <label for="option3">Stakeholder Orientation</label>
                        </div>
                        <div class="checkbox-label">
                            <input type="checkbox" name="selectedOptions[]" value="Strategy & Business Acumen" id="option6">
                            <label for="option6">Strategy & Business Acumen</label>
                        </div>
                        <div class="checkbox-label">
                            <input type="checkbox" name="selectedOptions[]" value="Synergistic Collaboration" id="option2">
                            <label for="option2">Synergistic Collaboration</label>
                        </div>
                    </div>
                </div>
                <div class="invalid-feedback"></div>
            </div>
          
            <div class="skillsContainer form-group" id="skillsContainer" style="display: none;">
                <input type="text" name="ajax[{{ !empty($diary) ? $diary->id : 'new' }}][skills]" value="{{ !empty($diary) ? $diary->skills : old('skills') }}" class="skills js-ajax-title form-control" id="skills" readonly/>
                <button type="button" id="removeSkill" class="btn btn-sm btn-danger remove"><img src="/assets/default/img/icons/delete.svg"></button>
                <div class="invalid-feedback"></div>
            </div>
            <label class="input-label">{{ trans('diary.select_template') }}</label>
            <div style="align-items: center" class="form-group d-flex">
                <button style="" class="radio temp btn btn-sm btn-primary " id="buttonA" onclick="changeValue('template1')"></button><span style="font-size: 14px; margin-left:5px; margin-right:15px;">{{ trans('diary.jurney') }}</span>
                <button style="" class="radio temp btn btn-sm btn-primary " id="buttonB" onclick="changeValue('template2')"></button><span style="font-size: 14px; margin-left:5px; margin-right:15px">{{ trans('diary.task') }}</span>
            </div>
            </div>
        
        @endif
            <div class="form-group col-12 not" >
                <label class="input-label">{{ trans('diary.description') }}</label>
                <textarea style="display: flex !important;" id="summernote" name="ajax[{{ !empty($diary) ? $diary->id : 'new' }}][description]" class="js-ajax-description form-control">{{ !empty($diary) ? $diary->description : old('description') }}
                    <p id="result"></p>
                </textarea>
                <div class="invalid-feedback"></div>
            </div>

                        @php
                        $hideButton = strpos(request()->fullUrl(), 'create') !== false;
                        
                        @endphp

                        @if ($hideButton)
                        <div class="form-group col-12">
                            <label style="display: none" class="input-label">{{ trans('diary.feedback') }}</label>
                            <textarea rows="5" style="display: none !important;" name="ajax[{{ !empty($diary) ? $diary->id : 'new' }}][feedback]" class="js-ajax-description form-control">{{ !empty($diary) ? $diary->feedback : old('feedback') }}</textarea>
                            <div class="invalid-feedback"></div>
                        </div>
                        @elseif (!$hideButton)
                        @if (empty($diary->feedback))
                        <div class="form-group col-12">
                            <label style="display: none" class="input-label">{{ trans('diary.feedback') }}</label>
                            <textarea rows="10" style="display: none !important;" name="ajax[{{ !empty($diary) ? $diary->id : 'new' }}][feedback]" class="js-ajax-description form-control auto-scroll" id="feedbackTextarea" readonly>{{ !empty($diary) ? $diary->feedback : old('feedback') }}</textarea>
                            <div class="invalid-feedback"></div>
                        </div>
                        @elseif  (!empty($diary->feedback))
                        <div class="form-group col-12">
                            <label class="input-label">{{ trans('diary.feedback') }}</label>
                            <textarea rows="10" style="display: flex !important;" name="ajax[{{ !empty($diary) ? $diary->id : 'new' }}][feedback]" class="js-ajax-description form-control auto-scroll" id="feedbackTextarea" readonly>{{ !empty($diary) ? $diary->feedback : old('feedback') }}</textarea>
                            <div class="invalid-feedback"></div>
                        </div>
                        @endif
                        
                        @if ($diary->user_id!==$authUser->id)
                        <div class="form-group col-12">
                            <label class="input-label">{{ trans('diary.addfeedback') }}</label>
                            <textarea id="newFeedbackInput" rows="5" class="form-control"></textarea>
                            {{-- <input type="text" id="newFeedbackInput" class="form-control"> --}}
                        </div>
                        @endif
                        @endif
                        


            
            {{-- @dd($diary->user_id, $authUser->id); --}}
                
            @if (!empty($diary))
                @if(request()->has('readonly'))
                    @if ($diary->user_id!==$authUser->id)
                        <div style="padding-right: 15px;" class="ml-auto">
                            <button type="button" id="addFeedbackBtn" class="js-submit-sent-form btn btn-sm btn-primary">{{trans('diary.send_feedback') }}</button>
                        </div> 
                    @endif   
                @endif        
            @endif
            
          </div>
      </section>
         
      <script>
        document.addEventListener('DOMContentLoaded', function () {
            var addFeedbackBtn = document.getElementById('addFeedbackBtn');
            var feedbackTextarea = document.getElementById('feedbackTextarea');
            var newFeedbackInput = document.getElementById('newFeedbackInput');
    
            addFeedbackBtn.addEventListener('click', function () {
                var newFeedback = newFeedbackInput.value.trim();
                if (newFeedback !== '') {
                    // Mendapatkan tanggal dan waktu saat ini
                    var currentDate = new Date();
                    var formattedDateTime = formatDateTime(currentDate); // Menggunakan fungsi formatDateTime untuk memformat tanggal dan waktu
    
                    // Membuat teks untuk tanggal, waktu, dan nama pengguna
                    var dateTimeText = '[' + formattedDateTime + ']';
                    var userFullName = '<?php echo $authUser->full_name; ?>';
    
                    var currentFeedback = feedbackTextarea.value.trim();
                    if (currentFeedback !== '') {
                        // Jika ada feedback sebelumnya, tambahkan spasi dan kemudian feedback baru di bawahnya
                        feedbackTextarea.value = currentFeedback + '\n\n' + dateTimeText + ' ' + userFullName + ' : ' + newFeedback;
                    } else {
                        // Jika tidak ada feedback sebelumnya, langsung tambahkan feedback baru
                        feedbackTextarea.value = dateTimeText + ' ' + userFullName + ': ' + newFeedback;
                    }
                    // Kosongkan input feedback baru
                    newFeedbackInput.value = '';
                }
            });
        });
    
        // Fungsi untuk memformat tanggal dan waktu menjadi "d M Y h:i A" (AM/PM)
        function formatDateTime(date) {
            var months = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
            var day = date.getDate();
            var monthIndex = date.getMonth();
            var year = date.getFullYear();
            var hours = date.getHours();
            var ampm = hours >= 12 ? 'PM' : 'AM';
            hours = hours % 12;
            hours = hours ? hours : 12; // Jam 0 menjadi 12 AM
            var minutes = date.getMinutes();
            return day + ' ' + months[monthIndex] + ' ' + year + ' ' + hours + ':' + (minutes < 10 ? '0' : '') + minutes + ' ' + ampm;
        }

        document.addEventListener('DOMContentLoaded', function() {
        var textarea = document.querySelector('.auto-scroll');
        textarea.scrollTop = textarea.scrollHeight;
    });
    </script>
    
    
      <div id="btncontainer" class="mt-20 mb-20 row" style="padding: 10px; padding-top:0px">
          @if(!request()->has('readonly'))
          @if((empty($diary))||($diary->user_id==$authUser->id))
        <div style="padding-left: 5px;">
            <button type="button" class="js-submit-quiz-form btn btn-sm btn-primary">{{ !empty($diary) ? trans('public.save_change') : trans('public.create') }}</button>
        </div>
        {{-- <div style="padding-left: 5px;">
            <button type="button" class="js-edit-quiz-form btn btn-sm btn-primary">{{ !empty($diary) ? trans('public.save_change') : trans('public.create') }}</button>
        </div> --}}
          @endif
          @endif
          
    
          <!-- Tombol Share -->
          @if((!request()->has('readonly'))&&(!empty($diary)))
            @if ($diary->user_id==$authUser->id)
                <div class="ml-2">
                <button type="button" id="shareButton" class="js-submit-share-form btn btn-sm btn-primary">{{ trans('public.share') }}</button>
                </div>
            @endif
          @endif
          {{-- <button type="button" id="shareButton" class="s-submit-quiz-form btn btn-sm ml-10  btn-primary">
              {{ trans('public.share') }}
          </button> --}}
          <!-- Container Mengambang -->
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
                              <a id="floating" href="javascript:void(0);" onclick="copyToClipboard('{{ request()->url() }}/?readonly')" class="btn btn-sm ml-10 ">
                                  <img src="/assets/default/img/icons/copy.svg">
                                  {{ trans('public.copy_to_clipboard') }}
                              </a>
                              <!-- Tombol Share WhatsApp -->
                              <a id="floating" href="whatsapp://send?text={{ request()->url() }}?readonly" target="_blank" class="btn btn-sm ml-10 ">
                                  <img src="/assets/default/img/icons/wa.svg">
                                  {{ trans('public.share_to_wa') }}
                              </a>
                              <!-- Tombol Share Email -->
                              <a id="floating2" href="mailto:?subject=Subject&body={{ request()->url() }}?readonly" class="btn btn-sm ml-10 ">
                                  <img src="/assets/default/img/icons/email.svg">
                                  {{ trans('public.share_to_email') }}
                              </a>
                              <!-- Tombol Share Telegram -->
                              <a id="floating3" href="https://t.me/share/url?url={{ request()->url() }}?readonly" class="btn btn-sm ml-10 " target="_blank">
                                  <img src="/assets/default/img/icons/telegram.svg">
                                  {{ trans('public.share_to_telegram') }}
                              </a>
                              <!-- Tombol Share Twitter -->
                              <a id="floating4" href="https://twitter.com/intent/tweet?url={{ request()->url() }}?readonly" class="btn btn-sm ml-10 " target="_blank">
                                  <img src="/assets/default/img/icons/twitter.svg">
                                  {{ trans('public.share_to_twitter') }}
                              </a>
                              <!-- Tombol Share Facebook -->
                              <a id="floating5" href="https://www.facebook.com/sharer/sharer.php?u={{ request()->url() }}?readonly" class="btn btn-sm ml-10 " target="_blank">
                                  <img src="/assets/default/img/icons/facebook.svg">
                                  {{ trans('public.share_to_facebook') }}
                              </a>
                          </div>
                      </div>
                  </div>
              </div>
          </div>
    
          @if(!empty($diary))
          <div class="ml-auto">
          <a  href="/panel/diary" class="btn btn-sm ml-10 cancel-accordion {{ request()->has('readonly') ? 'btn-primary' : 'btn-danger '}} ">
              {{ trans('public.close') }}
          </a>
          </div>
          @endif
    
      </div>
    </div>
    </div>
    
    
<script>

                
        // Inisialisasi Flatpickr
        // Ambil elemen input
        var datepicker = document.getElementById("datepicker");

        // Buat instance Flatpickr dengan opsi yang diinginkan
        var picker = flatpickr(datepicker, {
            enableTime: true, // Mengaktifkan waktu
            dateFormat: "Y-m-d H:i", // Format tanggal dan waktu yang ditampilkan pada input
            altFormat: "d M Y h:i K", // Format tanggal alternatif untuk ditampilkan di input
            altInput: true, // Mengaktifkan input alternatif
            time_24hr: false, // Mengatur format waktu menjadi 12 jam (false) atau 24 jam (true)
            defaultDate: "today", // Mengatur tanggal dan waktu default ke saat ini
            theme: "material_blue", // Set tema (misalnya, material_blue, material_red, dll.)
            // Anda dapat menyesuaikan Flatpickr lebih lanjut dengan opsi tambahan jika diperlukan
        });

        // Ambil waktu saat ini
        var now = new Date();

        // Atur waktu saat ini sebagai nilai default input
        picker.setDate(now);

    
        $(document).ready(function () {
        // Function to update checkboxes based on skills data
        function updateCheckboxesFromSkillsData() {
            var skillsData = $('#skills').val(); // Get skills data
            if (skillsData) {
                var selectedSkills = skillsData.split(','); // Split skills data into an array (tanpa spasi)
                // Check each checkbox based on the skills data
                selectedSkills.forEach(function(skill) {
                    // Trim whitespace dari skill
                    skill = skill.trim();
                    // Periksa dan centang checkbox dengan nilai yang sesuai
                    $('input[type="checkbox"][value="' + skill + '"]').prop('checked', true);
                });
            }
        }
    
        // Panggil fungsi awal untuk mengatur centang checkbox
        updateCheckboxesFromSkillsData();
    
        // Dengarkan perubahan pada checkbox
        $('input[type="checkbox"]').change(function () {
            var selectedSkills = [];
            $('input[type="checkbox"]:checked').each(function () {
                selectedSkills.push($(this).val());
            });
    
           
            // Hapus nilai 'undefined' jika ada
            selectedSkills = selectedSkills.filter(skill => skill !== 'undefined');
             // Hapus nilai 'undefined' jika ada
             selectedSkills = selectedSkills.filter(skill => skill !== 'on');
    
            // Simpan kembali nilai yang telah diproses ke dalam input #skills
            $('#skills').val(selectedSkills.join(', '));
        });
    });
    
    
    
        document.addEventListener('DOMContentLoaded', function () {
            var referenceTypeSelect = document.getElementById('referenceTypeSelect');
            var skills = document.querySelector('.option');
            var skillsContainer = document.getElementById('skillsContainer');
            var readonlyskills = document.getElementById('readonlyskills');
    
            function handleSkillsVisibility() {
                var selectedValue = referenceTypeSelect.value.toLowerCase();
                var createparam = new URLSearchParams(window.location.search).has('create');
                var readonlyParam = new URLSearchParams(window.location.search).has('readonly');
    
                if (selectedValue === 'other' && !readonlyParam) {
                    skills.style.display = 'block';
                    
                    if (!createparam) {
                        readonlyskills.style.display = 'block';
                    }
                } else {
                    skills.style.display = 'block';
                    
                    if (createparam) {
                        readonlyskills.style.display = 'block';
                    }
                }
            }
    
            referenceTypeSelect.addEventListener('change', function () {
                handleSkillsVisibility();
            });
    
            handleSkillsVisibility();
        });
    </script>
    
    
    
    
    
    
    
    
    
    
    <!-- Script to handle dropdown selection and remove skill -->
    <script>
        $(document).ready(function () {
            $('#selectedOptions').change(function () {
                var selectedValue = $(this).val();
                var skillsInput = $('#skills');
                var currentSkills = skillsInput.val();
    
                if (!currentSkills.includes(selectedValue)) {
                    if (currentSkills === '') {
                        skillsInput.val(selectedValue);
                    } else {
                        skillsInput.val(currentSkills + ' , ' + selectedValue);
                    }
                }
            });
    
            $('#removeSkill').click(function () {
                var skillsInput = $('#skills');
                var currentSkills = skillsInput.val();
    
                // Mendapatkan nilai terakhir dari input "skills" (sebelum koma terakhir)
                var lastCommaIndex = currentSkills.lastIndexOf(',');
                var newSkills = currentSkills.substring(0, lastCommaIndex);
    
                skillsInput.val(newSkills);
            });
        });
    </script>
    
    <script>
      document.addEventListener('DOMContentLoaded', function () {
          document.getElementById('shareButton').addEventListener('click', function () {
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
    
                // Menambahkan event listener pada semua elemen dengan class 'floating-btn'
      var floatingButtons = document.querySelectorAll('.floating');
      floatingButtons.forEach(function (button) {
          button.addEventListener('click', hideFloatingContainer);
          $('#shareModal').modal('hide');
      });
      
      });
    
      function hideFloatingContainer() {
          var floatingContainer = document.getElementById('container');
          floatingContainer.setAttribute('aria-hidden', 'false');
    
          var shareModal = document.getElementById('shareModal');
          shareModal.style.display = 'none';
      }
    
      // Menambahkan event listener pada semua elemen dengan class 'floating-btn'
      var floatingButtons = document.querySelectorAll('.floating');
      floatingButtons.forEach(function (button) {
          button.addEventListener('click', hideFloatingContainer);
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
    
      // Variabel untuk menyimpan nilai
      var myVariable = '';
      var template1 = '<h2>My Learning Diary</h2><br><table class="table table-bordered"><tbody><tr><td><p>Hari ini saya belajar </p><p><br></p><p><br></p><p><br></p><p><br></p><p><br></p><p><br></p><p><br></p><p><br></p><p><br></p></td></tr></tbody></table><p><br></p>';
      var template2 = '<h2>Action Plan</h2><br><table class="table table-bordered"><tbody><tr><td><b>Name</b></td><td><b>Summary</b>&nbsp;</td><td><b>Start Date</b></td></td><td><b>Due Date</b></td></td><td><b>Status</b></td></tr><tr><td>[Fill the name to-do list]</td><td>[Fill this coloum with your summary activity]</td><td>[Fill in the Start Date with the date it will start Ex: 1 jan 2024]</td></td><td>[Fill in the Do Date with the date it will be done Ex: 30 jan 2024]</td></td><td>[Fill the status for your activities Ex: To Do/On Progress/Done]</td></td></tr><tr><td><br></td><td><br></td><td><br></td></tr></tbody></table><p><br></p>';
      // Fungsi untuk mengubah nilai variabel
      function changeValue(value) {
          if (value === "template1") value = template1;
          if (value === "template2") value = template2;
          myVariable = value;
          $('#result').html(myVariable);
          console.log('Nilai variabel sekarang: ' + myVariable);
    
          // Menampilkan nilai variabel dalam elemen span
          document.getElementById('variableValue').textContent = myVariable;
      }
    </script>
    
    
    @if(request()->has('readonly'))
        <script>
            $(document).ready(function () {
                $('#summernote').summernote('disable')({
                    height: 500,
                    toolbar: [
                        ['style', ['style','bold', 'underline', 'italic']],
                        ['font', ['fontname', 'fontsize', 'color']],
                        ['para', ['ul', 'ol', 'paragraph', 'table']],
                        ['picture', ['picture']],
                        ['link', ['link']],
                        ['view', ['fullscreen']]
                    ],
                    fontNames: ['Arial', 'Times New Roman', 'Poppins', 'Helvetica', 'Tahoma', 'Verdana', 'Impact'],
                    fontSizeUnits: ['px', 'pt', 'em', '%'],
                    styleTags: ['p', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6'],
                    colors: [
                        ['#000000', '#424242', '#636363', '#9c9c94', '#cecdcd', '#eaeaea', '#f5f5f5'],
                        ['#c1c1c1', '#6c6c6c', '#878787', '#b3b3b3', '#d1d0d0', '#e6e6e6', '#f0f0f0'],
                        ['#e68181', '#e6b981', '#e4e181', '#b1e489', '#81e4ac', '#81d9e4', '#81b0e4', '#b581e4']
                    ]
                });
            });
      </script>
        @else
    <script>
            $(document).ready(function () {
          $('#summernote').summernote({
              height: 500,
              toolbar: [
                  ['style', ['style','bold', 'underline', 'italic']],
                  ['font', ['fontname', 'fontsize', 'color']],
                  ['para', ['ul', 'ol', 'paragraph', 'table']],
                  ['picture', ['picture','link','fullscreen']],
                //   ['link', []],
                //   ['view', []]
              ],
              fontNames: ['Arial', 'Times New Roman', 'Poppins', 'Helvetica', 'Tahoma', 'Verdana', 'Impact'],
              fontSizeUnits: ['px', 'pt', 'em', '%'],
              styleTags: ['p', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6'],
              colors: [
                  ['#000000', '#424242', '#636363', '#9c9c94', '#cecdcd', '#eaeaea', '#f5f5f5'],
                  ['#c1c1c1', '#6c6c6c', '#878787', '#b3b3b3', '#d1d0d0', '#e6e6e6', '#f0f0f0'],
                  ['#e68181', '#e6b981', '#e4e181', '#b1e489', '#81e4ac', '#81d9e4', '#81b0e4', '#b581e4']
              ]
          });
      }); 
    </script>
        @endif
        <script>
            document.getElementById('send-feedback-btn').addEventListener('click', function() {
                // Ambil data dari formulir atau tempat lain jika diperlukan
                var title = document.getElementById('title-input').value;
                var description = document.getElementById('description-input').value;
                var referenceType = document.getElementById('reference-type-input').value;
                var skills = document.getElementById('skills-input').value;
                var datedAt = document.getElementById('dated-at-input').value;
                var feedback = document.getElementById('feedback-input').value;
        
                // Kirim data ke server menggunakan AJAX
                var xhr = new XMLHttpRequest();
                xhr.open('POST', '/panel/diary/store', true);
                xhr.setRequestHeader('Content-Type', 'application/json');
                xhr.onreadystatechange = function() {
                    if (xhr.readyState === 4 && xhr.status === 200) {
                        // Tanggapan dari server
                        var response = JSON.parse(xhr.responseText);
                        if (response.code === 200) {
                            // Redirect atau tindakan lain sesuai kebutuhan
                            window.location.href = response.redirect_url;
                        } else {
                            // Tangani kesalahan jika perlu
                            console.error('Error:', response.errors);
                        }
                    }
                };
                var data = JSON.stringify({
                    title: title,
                    description: description,
                    reference_type: referenceType,
                    skills: skills,
                    dated_at: datedAt,
                    feedback: feedback
                    // Tambahkan bidang lain jika ada
                });
                xhr.send(data);
            });
        </script>
        
    
    
    @if(!request()->has('readonly'))
    <script>
      var changesSaved = false;
    
      // Fungsi untuk menampilkan konfirmasi jika ada perubahan yang belum disimpan
      function showConfirmation() {
          if (!changesSaved) {
              return "Changes have not been saved.";
          }
      }
    
      // Menangkap perubahan pada textarea
      $('#summernote').on('summernote.change', function () {
          changesSaved = false;
      });
    
      // Menangkap perubahan pada input teks
      $('input').on('input', function () {
          changesSaved = false;
      });
    
      // Menangkap klik tombol simpan
      $('.js-submit-quiz-form').on('click', function () {
          changesSaved = true;
      });

      // Menangkap perubahan pada select
      $('select').on('change', function () {
          changesSaved = false;
      });
    
      // Menangkap klik tombol close
      $('.cancel-accordion').on('click', function () {
          var confirmationMessage = showConfirmation();
          if (confirmationMessage) {
              var userConfirmed = confirm(confirmationMessage);
              if (!userConfirmed) {
                  return false;
              }
          }
      });
    
    //   // Menangkap perubahan pada summernote
    //   $('#summernote').on('summernote.blur', function () {
    //       changesSaved = true;
    //   });
    
    //   // Menangkap perubahan pada input teks
    //   $('input').on('change', function () {
    //       changesSaved = false;
    //   });
    
      
    </script>
    <!-- Tambahkan kode ini di bagian head atau sebelum penutup tag body -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    
    <script>
        $(document).ready(function () {
            // Tambahkan kelas 'buttonActive' saat tombol A diklik
            $('#buttonA').on('click', function () {
                $('#buttonA').addClass('templateActive');
                $('#buttonB').removeClass('templateActive');
            });
    
            // Tambahkan kelas 'buttonActive' saat tombol B diklik
            $('#buttonB').on('click', function () {
                $('#buttonB').addClass('templateActive');
                $('#buttonA').removeClass('templateActive');
            });
        });
    </script>
    
    @endif
    
    @push('scripts_bottom')
    <script src="/assets/vendors/summernote/summernote-bs4.min.js"></script>
    @endpush
    
    @push('scripts_bottom')
    <script>
         var saveSuccessLang = '{{ trans('diary.diary_created') }}';
        var sentSuccessLang = '{{ trans('diary.sent') }}';
      var videoDemoPathPlaceHolderBySource = {
          upload: '{{ trans('update.file_source_upload_placeholder') }}',
          youtube: '{{ trans('update.file_source_youtube_placeholder') }}',
          vimeo: '{{ trans('update.file_source_vimeo_placeholder') }}',
          external_link: '{{ trans('update.file_source_external_link_placeholder') }}',
      }
    </script>
    @endpush