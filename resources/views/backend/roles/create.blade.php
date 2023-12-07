@extends('skeleton')

@section('content')
    <div id="kt_app_content_container" class="app-container container-fluid">
        <div class="card card-flush h-md-100">
            <div class="card-body pt-6">
                <form action="{{ url(Request::segment(1)) }}" method="POST" id="createform">
                    @csrf
                    @foreach ($forms as $form)
                        <div class="form-group row mb-8">
                            <div class="col-xl-2 col-lg-2 col-form-label">
                                <label
                                    class="{{ isset($form['required']) && $form['required'] == true ? 'required' : '' }} form-label">{{ __($form['label']) }}</label>
                            </div>
                            @component('_forms.' . $form['type'] . '.input', ['data' => $form])
                            @endcomponent
                        </div>
                    @endforeach
                    <div class="accordion" id="kt_accordion_1">
                        @foreach ($privelages as $key => $values)
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="{{ str_replace(' ', '', $key) }}_1">
                                    <button class="accordion-button fs-4 fw-semibold collapsed" type="button"
                                        data-bs-toggle="collapse" data-bs-target="#{{ str_replace(' ', '', $key) }}_body"
                                        aria-expanded="false" aria-controls="{{ str_replace(' ', '', $key) }}_body">
                                        {{ $key }}
                                    </button>
                                </h2>
                                <div id="{{ str_replace(' ', '', $key) }}_body" class="accordion-collapse collapse"
                                    aria-labelledby="{{ str_replace(' ', '', $key) }}_1" data-bs-parent="#kt_accordion_1">
                                    <div class="accordion-body">
                                        <div class="mb-8 form-group row gy-5">
                                            <?php $no = 1; ?>
                                            @foreach ($values as $k => $items)
                                                <div class="col-lg-4">
                                                    <div class="mb-5 card card-stretch card-bordered">
                                                        <div class="card-header">
                                                            <h3 class="card-title">{{ $k }}</h3>
                                                        </div>
                                                        <div class="card-body">
                                                            <div class="mb-3 form-check form-check-custom form-check-solid">
                                                                <input class="form-check-input" type="checkbox"
                                                                    id="select-all" onclick="toggle(this)" />
                                                                <label class="form-check-label" for="select-all">
                                                                    {{ __('Check All') }}
                                                                </label>
                                                            </div>
                                                            @foreach ($items as $v)
                                                                <div
                                                                    class="mb-3 form-check form-check-custom form-check-solid">
                                                                    <input class="form-check-input" type="checkbox"
                                                                        value="{{ $v['value'] }}"
                                                                        id="flexCheckDefault_{{ $no }}"
                                                                        name="priveleges[]" />
                                                                    <label class="form-check-label"
                                                                        for="flexCheckDefault_{{ $no }}">
                                                                        {{ $v['name'] }}
                                                                    </label>
                                                                </div>
                                                                <?php $no++; ?>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </form>
            </div>
            <div class="card-footer d-flex justify-content-between">
                <a href="{{ __(url(Request::segment(1))) }}" type="button" class="btn btn-light">
                    <i class="fas fa-arrow-left"></i>
                    {{ __('Cancel') }}
                </a>
                <button type="button" id="kt_btn_1" class="btn btn-primary btn-submit"
                    onclick="$('#createform').submit()">
                    <i class="fas fa-save"></i>
                    <span class="indicator-label">{{ __('Submit') }}</span>
                    <span class="indicator-progress">{{ __('Please wait') }} ...
                        <span class="align-middle spinner-border spinner-border-sm ms-2"></span></span>
                </button>
            </div>
        </div>
    </div>
@endsection

@section('customjs')
    <script src="{{ asset('assets/plugins/global/jquery-validate/jquery.validate.min.js') }}"></script>
    <script>
        function toggle(source) {
            var parrent = source.closest('.card-body');
            var checkboxes = parrent.querySelectorAll('input[type="checkbox"]');
            for (var i = 0; i < checkboxes.length; i++) {
                if (checkboxes[i] != source)
                    checkboxes[i].checked = source.checked;
            }
        }

        $("form#createform").validate({
            ignore: '',
            errorElement: 'span',
            errorClass: 'is-invalid help-block help-block-error text-danger',
            focusInvalid: true,
            rules: {
                name: {
                    required: true
                },
                'priveleges[]': {
                    required: true
                }
            },
            messages: {
                name: 'Field Name is required !',
                'priveleges[]': 'Please select one ore more access!'
            },
            invalidHandler: function(event, validator) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Some fields is required !'
                }).then(() => {
                    const btn = $(".btn-submit");
                    btn.attr('data-kt-indicator', 'off');
                    btn.removeAttr('disabled');
                });
            },
            errorPlacement: function(error, element) {
                if (element.hasClass('fileupload-content')) {
                    error.insertAfter(element.closest('.fileupload-content'));
                } else if (element.hasClass('select2-hidden-accessible')) {
                    error.insertAfter(element.siblings('.select2-container')).focus();
                } else if (element.hasClass('thumbnail-image')) {
                    error.insertAfter(element.closest('.image-input'));
                } else if (element.hasClass('form-check-input')) {
                    error.insertBefore(element.closest('#kt_accordion_1'));
                } else {
                    error.insertAfter(element);
                }
            },
            submitHandler: function(form, e) {
                form.submit();
            }
        });
    </script>
@endsection
