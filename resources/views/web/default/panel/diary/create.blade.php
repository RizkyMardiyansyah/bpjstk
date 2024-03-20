@extends(getTemplate() .'.panel.layouts.panel_layout')

@push('styles_top')
    <link href="/assets/default/vendors/sortable/jquery-ui.min.css"/>
@endpush

@section('content')
    <div class="">

        @include(getTemplate() . '.panel.diary.form')
        @endsection

        @push('scripts_bottom')
            <script>
                var saveSuccessLang = '{{ trans('webinars.success_store') }}';
                var diarySectionLang = '{{ trans('quiz.diary_section') }}';
            </script>

            <script src="/assets/default/vendors/sortable/jquery-ui.min.js"></script>
            <script src="/assets/default/js/panel/quiz.min.js"></script>
            <script src="/assets/default/js/panel/webinar_content_locale.min.js"></script>
    @endpush
