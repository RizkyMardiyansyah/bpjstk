@php use App\Models\Diary; @endphp
<div data-action="{{ getAdminPanelUrl() }}/diary/{{ !empty($diary) ? $diary->id .'/update' : 'store' }}"
     class="js-content-form quiz-form webinar-form">
    {{ csrf_field() }}
    <section>

        <div class="row">
            <div class="col-12 col-md-4">


                <div class="d-flex align-items-center justify-content-between">
                    <div class="">
                        <h2 class="section-title">{{ !empty($diary) ? (trans('public.edit').' ('. $diary->title .')') : trans('diary.new_diary') }}</h2>

                    </div>
                </div>

                <div class="form-group">
                    <label class="input-label">{{ trans('admin/pages/quiz.title') }}</label>
                    <input type="text" name="ajax[{{ !empty($diary) ? $diary->id : 'new' }}][title]"
                           value="{{ !empty($diary) ? $diary->title : old('title') }}"
                           class="js-ajax-title form-control " placeholder=""/>
                    <div class="invalid-feedback"></div>
                </div>

                <div class="form-group">
                    <label class="input-label">{{ trans('diary.theme') }}</label>
                    <input type="text" name="ajax[{{ !empty($diary) ? $diary->id : 'new' }}][theme]"
                           value="{{ !empty($diary) ? $diary->theme : old('theme') }}"
                           class="js-ajax-theme form-control " placeholder=""/>
                    <div class="invalid-feedback"></div>
                </div>

                <div class="form-group">
                    <label class="input-label">{{ trans('diary.t') }}</label>
                    <select name="ajax[{{ !empty($diary) ? $diary->id : 'new' }}][reference_type]"
                            class="form-control {{ !empty($diary) ? 'js-edit-content-reference_type' : '' }}">
                        @foreach(Diary::$referenceTypes as $referenceType)
                            <option
                                value="{{ $referenceType }}" {{ (!empty($diary) and $diary->reference_type == $referenceType) ? 'selected' : '' }}>{{ __('diary.'.$referenceType) }}</option>
                        @endforeach
                    </select>
                    <div class="invalid-feedback"></div>
                </div>


                <div class="form-group">
                    <label class="input-label">{{ trans('admin/main.description') }}</label>
                    <textarea type="text" name="ajax[{{ !empty($diary) ? $diary->id : 'new' }}][description]"
                           class="js-ajax-description form-control " placeholder="">{{ !empty($diary) ? $diary->theme : old('description') }}</textarea>
                    <div class="invalid-feedback"></div>
                </div>

                <div class="form-group">
                    <label class="input-label">{{ trans('diary.dated_at') }}</label>
                    <input type="datetime-local" name="ajax[{{ !empty($diary) ? $diary->id : 'new' }}][dated_at]"
                           value="{{ !empty($diary) ? $diary->dated_at : old('theme') }}"
                           class="js-ajax-dated_at form-control datepicker-time-view" placeholder=""/>
                    <div class="invalid-feedback"></div>
                </div>


            </div>
        </div>
    </section>


    <div class="mt-20 mb-20">
        <button type="button"
                class="js-submit-quiz-form btn btn-sm btn-primary">{{ !empty($diary) ? trans('public.save_change') : trans('public.create') }}</button>
    </div>
</div>


