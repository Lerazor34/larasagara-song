<div class="col-lg-9 col-xl-9">
    <div class="row">
        <div class="col-lg-6">
            <div class="row">
                <div class="mb-3 col-lg-12">
                    <input type="file" name="{{ $data['name'] }}"
                        class="form-control fileupload @if ($errors->has($data['name'])) is-invalid @endif"
                        placeholder="{{ __(isset($data['placeholder'])) ? __($data['placeholder']) : __($data['label']) }}"
                        data-size="{{ isset($data['size']) ? $data['size'] : 5000000 }}" id="{{ $data['name'] }}_file"
                        accept="{{ isset($data['accept']) ? $data['accept'] : '*' }}" id="{{ $data['name'] }}_file">
                    @if ($errors->has($data['name']))
                        <small id="form-error-{{ $data['name'] }}" class="form-text text-danger">
                            {{ $errors->first($data['name']) }}
                        </small>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
