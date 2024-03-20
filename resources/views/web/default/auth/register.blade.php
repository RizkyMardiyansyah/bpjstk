{{-- <style>
    .input-group-text{
        background-color: white !important;
        border: 1px solid #ced4da !important; /* Warna border */
       
        padding: .375rem .75rem; /* Padding */
        transition: border-color .15s ease-in-out, box-shadow .15s ease-in-out; /* Animasi transisi */
    }
</style> --}}
@extends(getTemplate().'.layouts.app')

@push('styles_top')
    <link rel="stylesheet" href="/assets/default/vendors/select2/select2.min.css">
@endpush

@section('content')
    @php
        $registerMethod = getGeneralSettings('register_method') ?? 'mobile';
        $showOtherRegisterMethod = getFeaturesSettings('show_other_register_method') ?? false;
        $showCertificateAdditionalInRegister = getFeaturesSettings('show_certificate_additional_in_register') ?? false;
        $selectRolesDuringRegistration = getFeaturesSettings('select_the_role_during_registration') ?? null;
    @endphp

    <div class="container">
        <div class="row login-container">
            <div class="col-12 col-md-6 pl-0">
                <img src="{{ getPageBackgroundSettings('register') }}" class="img-cover" alt="Login">
            </div>
            <div class="col-12 col-md-6">
                <div class="login-card">
                    <h1 class="font-20 font-weight-bold">{{ trans('auth.signup') }}</h1>

                    <form method="post" action="/register" class="mt-35">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">

                        @if(!empty($selectRolesDuringRegistration) and count($selectRolesDuringRegistration))
                            <div class="form-group">
                                <label class="input-label">{{ trans('financial.account_type') }}</label>

                                <div class="d-flex align-items-center wizard-custom-radio mt-5">
                                    <div class="wizard-custom-radio-item flex-grow-1">
                                        <input type="radio" name="account_type" value="user" id="role_user" class="" checked>
                                        <label class="font-12 cursor-pointer px-15 py-10" for="role_user">{{ trans('update.role_user') }}</label>
                                    </div>
                                    @foreach($selectRolesDuringRegistration as $selectRole)
                                        <div class="wizard-custom-radio-item flex-grow-1">
                                            <input type="radio" name="account_type" value="{{ $selectRole }}" id="role_{{ $selectRole }}" class="">
                                            <label class="font-12 cursor-pointer px-15 py-10" for="role_{{ $selectRole }}">{{ trans('update.role_'.$selectRole) }}</label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        @if($registerMethod == 'mobile')
                            @include('web.default.auth.register_includes.mobile_field')

                            @if($showOtherRegisterMethod)
                                @include('web.default.auth.register_includes.email_field',['optional' => true])
                            @endif
                        @else
                            @include('web.default.auth.register_includes.email_field')

                            @if($showOtherRegisterMethod)
                                @include('web.default.auth.register_includes.mobile_field',['optional' => true])
                            @endif
                        @endif

                        <div class="form-group">
                            <label class="input-label" for="full_name">{{ trans('auth.full_name') }}:</label>
                            <input name="full_name" type="text" value="{{ old('full_name') }}" class="form-control @error('full_name') is-invalid @enderror">
                            @error('full_name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="input-label" for="password">{{ trans('auth.password') }}:</label>
                            <input name="password" type="password"
                                   class="form-control @error('password') is-invalid @enderror" id="password"
                                   aria-describedby="passwordHelp">
                            @error('password')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        <div class="form-group ">
                            <label class="input-label" for="confirm_password">{{ trans('auth.retype_password') }}:</label>
                            <input name="password_confirmation" type="password"
                                   class="form-control @error('password_confirmation') is-invalid @enderror" id="confirm_password"
                                   aria-describedby="confirmPasswordHelp">
                            @error('password_confirmation')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        
                        {{-- @if($showCertificateAdditionalInRegister)
                            <div class="form-group" id="certificateAdditionalGroup" style="display: none;">
                                <label class="input-label" for="certificate_additional">{{ trans('update.certificate_additional') }}</label>
                                <input name="certificate_additional" id="certificate_additional" class="form-control @error('certificate_additional') is-invalid @enderror"/>
                                @error('certificate_additional')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                        @endif --}}
                        @if($showCertificateAdditionalInRegister)
                        <div class="form-group" id="certificateAdditionalGroup" style="display: none;">
                            <label class="input-label" for="experience">{{ trans('auth.experience') }}:</label>
                            <div class="row m-0" style="width: 100%;">
                                <div class="col-6 pr-1 p-0">
                                    {{-- <label class="input-label" for="experience_year">{{ trans('auth.year') }}:</label> --}}
                                    <div class="input-group">
                                        <input name="experience_year" type="number" value="{{ old('experience_year') }}" class="form-control @error('experience_year') is-invalid @enderror">
                                        <div class="input-group-append">
                                          <span class="input-group-text" style="background-color: white !important; border: 1px solid #ececec !important; transition: border-color .15s ease-in-out, box-shadow .15s ease-in-out !important;">{{ trans('auth.year') }}</span>
                                        </div>
                                    </div>
                                      
                                    @error('experience_year')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="col-6 pl-1 p-0">
                                    {{-- <label class="input-label" for="experience_month">{{ trans('auth.month') }}:</label> --}}
                                    <div class="input-group">
                                        <input name="experience_month" type="number" value="{{ old('experience_month') }}" class="form-control @error('experience_month') is-invalid @enderror">
                                        <div class="input-group-append">
                                          <span class="input-group-text" style="background-color: white !important; border: 1px solid #ececec !important; transition: border-color .15s ease-in-out, box-shadow .15s ease-in-out !important;">{{ trans('auth.month') }}</span>
                                    </div>
                                    </div>
                                    {{-- <input name="experience_month" type="text" value="{{ old('experience_month') }}" class="form-control @error('experience_month') is-invalid @enderror"> --}}
                                    @error('experience_month')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group" id="expertiseGroup" style="display: none;">
                            <label class="input-label" for="expertise">{{ trans('auth.expertise') }}:</label>
                            <input name="expertise" type="text" value="{{ old('expertise') }}" class="form-control @error('expertise') is-invalid @enderror">
                            @error('expertise')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="form-group" id="linkedinGroup" style="display: none;">
                            <label class="input-label" for="linkedin">{{ trans('auth.linkedin') }}:</label>
                            <input name="linkedin" type="text" value="{{ old('linkedin') }}" class="form-control @error('linkedin') is-invalid @enderror">
                            @error('linkedin')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="form-group" id="reasonGroup" style="display: none;">
                            <label class="input-label" for="reason">{{ trans('auth.reason') }}:</label>
                            <textarea name="reason" class="form-control @error('reason') is-invalid @enderror" rows="5" cols="50">{{ old('reason') }}</textarea>
                            @error('reason')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    @endif


                        @if(getFeaturesSettings('timezone_in_register'))
                            @php
                                $selectedTimezone = getGeneralSettings('default_time_zone');
                            @endphp

                            <div class="form-group" style="opacity: 0%; height:0px">
                                <input name="timezone" class="form-control" value="Asia/Jakarta"/>
                            </div>
                            {{-- <div class="form-group">
                                                                                          
                                {{-- <label class="input-label">{{ trans('update.timezone') }}</label>
                                <select value="Asia/Jakarta" name="timezone" class="form-control select2" data-allow-clear="false">
                                    <option value="" {{ empty($user->timezone) ? 'selected' : '' }} disabled>{{ trans('public.select') }}</option>
                                    @foreach(getListOfTimezones() as $timezone)
                                        <option value="{{ $timezone }}" @if($selectedTimezone == $timezone) selected @endif>{{ $timezone }}</option>
                                    @endforeach
                                </select>
                                @error('timezone')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror 
                            </div> --}}
                        @endif

                        @if(!empty($referralSettings) and $referralSettings['status'])
                            {{-- <div class="form-group ">
                                <label class="input-label" for="referral_code">{{ trans('financial.referral_code') }}:</label>
                                <input name="referral_code" type="text"
                                       class="form-control @error('referral_code') is-invalid @enderror" id="referral_code"
                                       value="{{ !empty($referralCode) ? $referralCode : old('referral_code') }}"
                                       aria-describedby="confirmPasswordHelp">
                                @error('referral_code')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div> --}}
                        @endif

                        @if(!empty(getGeneralSecuritySettings('captcha_for_register')))
                            @include('web.default.includes.captcha_input')
                        @endif

                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" name="term" value="1" {{ (!empty(old('term')) and old('term') == '1') ? 'checked' : '' }} class="custom-control-input @error('term') is-invalid @enderror" id="term">
                            <label class="custom-control-label font-14" for="term">{{ trans('auth.i_agree_with') }}
                                <a href="pages/terms" target="_blank" class="text-secondary font-weight-bold font-14">{{ trans('auth.terms_and_rules') }}</a>
                            </label>

                            @error('term')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        @error('term')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror

                        <button type="submit" class="btn btn-primary btn-block mt-20">{{ trans('auth.signup') }}</button>
                    </form>

                    <div class="text-center mt-20">
                        <span class="text-secondary">
                            {{ trans('auth.already_have_an_account') }}
                            <a href="/login" class="text-secondary font-weight-bold">{{ trans('auth.login') }}</a>
                        </span>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts_bottom')
    <script src="/assets/default/vendors/select2/select2.min.js"></script>
    <script src="/assets/default/vendors/select2/select2.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var accountTypeRadios = document.querySelectorAll('input[name="account_type"]');
            var certificateAdditionalGroup = document.getElementById('certificateAdditionalGroup');
            var expertiseGroup = document.getElementById('expertiseGroup');
            var linkedinGroup = document.getElementById('linkedinGroup');
            var reasonGroup = document.getElementById('reasonGroup');;

            function toggleCertificateAdditional() {
                var selectedAccountType = document.querySelector('input[name="account_type"]:checked').value;
                if (selectedAccountType === 'teacher') {
                    certificateAdditionalGroup.style.display = 'block';
                   
                    expertiseGroup.style.display = 'block';
                    linkedinGroup.style.display = 'block';
                    reasonGroup.style.display = 'block';
                } else {
                    certificateAdditionalGroup.style.display = 'none';
                    expertiseGroup.style.display = 'none';
                    linkedinGroup.style.display = 'none';
                    reasonGroup.style.display = 'none';
                }
            }

            // Initial check and toggle on page load
            toggleCertificateAdditional();

            // Add event listener to each radio button
            accountTypeRadios.forEach(function (radio) {
                radio.addEventListener('change', toggleCertificateAdditional);
            });
        });
    </script>
@endpush
