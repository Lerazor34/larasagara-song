<div class="form-group row mb-8">
    <div class="col-xl-3 col-lg-3 col-form-label">
        <label class="{{ isset($data['required']) && $data['required'] == true ? 'required' : '' }} form-label">{{ __($data['label']) }}</label>
    </div>
    <div class="col-lg-9 col-xl-{{ isset($data['column']) ? '9' : '9' }}">
        <!--begin::Repeater-->
        <div id="kt_docs_repeater_basic">
            <!--begin::Form group-->
            <div class="form-group">
                <div data-repeater-list="kt_docs_repeater_basic">
                    <div data-repeater-item>
                        <div class="form-group row">
                            @foreach($data['items'] as $key => $items)
                            <div class="col-md-4">
                                @component('_forms.'.$items['type'], ['data' => $items])@endcomponent
                                {{-- <label class="form-label">{{ $items['label'] }}:</label>
                                <input type="email" class="form-control mb-2 mb-md-0" placeholder="Enter {{ $items['label'] }}" /> --}}
                            </div>
                            @endforeach
                            <div class="col-md-4">
                                <a href="javascript:;" data-repeater-delete class="btn btn-sm btn-light-danger mt-3 mt-md-8">
                                    <i class="la la-trash-o"></i>{{ __('Delete') }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--end::Form group-->

            <!--begin::Form group-->
            <div class="form-group mt-5">
                <a href="javascript:;" data-repeater-create class="btn btn-light-primary">
                    <i class="la la-plus"></i>{{ __('Tambah') }}
                </a>
            </div>
            <!--end::Form group-->
        </div>
        <!--end::Repeater-->


        @if ($errors->has($data['name']))
        <small id="form-error-{{$data['name']}}" class="form-text text-danger">
            {{ $errors->first($data['name']) }}
        </small>
        @endif
    </div>
</div>

@section('customjs')
<script>
    $(function(){
        $('#kt_docs_repeater_basic').repeater({
            initEmpty: false,

            defaultValues: {
                'text-input': 'foo'
            },

            show: function () {
                $(this).slideDown();
            },

            hide: function (deleteElement) {
                $(this).slideUp(deleteElement);
            }
        });
    });
</script>
@endsection