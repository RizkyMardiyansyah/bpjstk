@extends(getTemplate() . '.layouts.app')

@push('styles_top')
    <link rel="stylesheet" href="/assets/default/vendors/select2/select2.min.css">
@endpush

@section('content')
    <div class="container">
        <div class="no-result default-no-result mt-50 d-flex align-items-center justify-content-center flex-column">
            <div class="no-result-logo">
                <img src="/assets/default/img/no-results/student.png" alt="">
            </div>
            <div class="d-flex align-items-center flex-column mt-30 text-center">
                <h2 class="text-dark-blue">Yout account is waiting for aproval.</h2>
                <p class="mt-1 text-center text-gray font-weight-500">We will send the notification to your registered mail
                    if your account has been aproved!</p>
            </div>
            <a href="/login" class="btn btn-sm btn-primary mt-25">Back to login</a>

        </div>

    </div>
@endsection

@push('scripts_bottom')
    <script src="/assets/default/vendors/select2/select2.min.js"></script>
@endpush
