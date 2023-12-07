@extends('skeleton')

@section('breadcrumbs')
    @foreach ($breadcrumbs as $key => $items)
        <!--begin::Item-->
        <li class="breadcrumb-item text-gray-600">{{ __(ucwords(str_replace('_', ' ', $items))) }}
    @endforeach
@endsection

@section('content')
    <div id="kt_app_content_container" class="app-container container-fluid">
        <div class="card card-bordered">
            <div class="card-header ribbon ribbon-end">
                <div class="ribbon-label bg-primary">{{ ucwords(str_replace('.',' ',request()->route()->getName())) }}</div>
                <div class="card-title">{{ ucwords(str_replace('.',' ',request()->route()->getName())) }}</div>
            </div>
            <div class="card-body pt-6">
                @php
                    $fieldRequired = [];
                @endphp
                <form action="{{ url(Request::segment(1) . '/' . Request::segment(2)) }}" method="POST" id="formValidate"
                    enctype="multipart/form-data">
                    @method('PUT')
                    @csrf
                    @foreach ($forms as $form)
                        @if (isset($form['hidden']) && $form['hidden'] == true)
                            @continue
                        @endif
                        @php
                            if ($form['required']) {
                                $fieldRequired[$form['name']] = __($form['label']);
                            }
                        @endphp
                        <div class="form-group row mb-8">
                            <div class="col-xl-2 col-lg-2 col-form-label text-end">
                                <label
                                    class="{{ isset($form['required']) && $form['required'] == true ? 'required' : '' }} form-label">{{ __($form['label']) }}</label>
                            </div>
                            @component('_forms.' . $form['type'] . '.input', ['data' => $form])
                            @endcomponent
                        </div>
                    @endforeach
                    <div class="form-group row mb-8">
                        <div class="col-xl-2 col-lg-2 col-form-label text-end">
                            <label class="required form-label">{{ __('Roles') }}</label>
                        </div>
                        <div class="col-lg-9 col-xl-5">
                            <div class="pt-3 content">
                                @foreach ($roles as $key => $item)
                                    <div class="mb-3 form-check form-check-custom form-check-solid">
                                        <input class="form-check-input" type="checkbox" value="{{ $item->id }}"
                                            id="flexCheckDefault_{{ $key }}" name="roles[]"
                                            {{ in_array($item->id, $userRoles) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="flexCheckDefault_{{ $key }}">
                                            {{ $item->name }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                            @if ($errors->has('roles[]'))
                                <small id="form-error-{{ 'roles[]' }}" class="form-text text-danger">
                                    {{ $errors->first('roles[]') }}
                                </small>
                            @endif
                        </div>
                    </div>
                </form>
                @php
                    $fieldRequired['roles[]'] = 'Roles';
                @endphp
            </div>
            <div class="card-footer d-flex justify-content-between">
                <a href="{{ __(url(Request::segment(1))) }}" type="button" class="btn btn-light">
                    <i class="fas fa-arrow-left"></i>
                    {{ __('Cancel') }}
                </a>
                <button type="submit" id="kt_btn_1" class="btn btn-primary btn-submit">
                    <i class="fas fa-save"></i>
                    <span class="indicator-label">{{ __('Update') }}</span>
                    <span class="indicator-progress">{{ __('Please wait') }} ...
                        <span class="align-middle spinner-border spinner-border-sm ms-2"></span></span>
                </button>
            </div>
        </div>
    </div>
    <input type="hidden" id="filed-required" value='@json($fieldRequired)'>
@endsection
@section('customjs')
    <script src="{{ asset('assets/plugins/global/jquery-validate/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('assets/js/custom/components/form-validation.js') }}"></script>
@endsection
