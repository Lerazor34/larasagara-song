<div class="col-lg-9 col-xl-{{ isset($data['column']) ? $data['column'] : '9' }}">
    <?php
    $image = asset('assets/media/avatars/blank.png');
    if (!empty(old($data['name']))) {
        $img = json_decode(old($data['name']));
    }
    ?>
    <div class="image-input image-input-empty thumbnail-input content" data-kt-image-input="true"
        style="background-image: url({{ $image }})" data-url="{{ url('api/file_upload') }}" data-bucket="avatar"
        data-path="avatar">
        <div class="image-input-wrapper w-125px h-125px"></div>
        <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-white shadow"
            data-kt-image-input-action="change" data-bs-toggle="tooltip" data-bs-dismiss="click" title="Ganti Foto">
            <i class="bi bi-pencil-fill fs-7"></i>
            @if (isset($data['value']))
                <input type="hidden" name="{{ $data['name'] }}" id="{{ $data['name'] }}" value="{{ $data['value'] }}"
                    class="thumbnail" />
            @else
                <input type="hidden" name="{{ $data['name'] }}" id="{{ $data['name'] }}"
                    value="{{ old($data['name']) }}" class="thumbnail" />
            @endif
            <input type="file" accept=".png, .jpg, .jpeg" />
        </label>
    </div>

    @if ($errors->has($data['name']))
        <small id="form-error-{{ $data['name'] }}" class="form-text text-danger">
            {{ $errors->first($data['name']) }}
        </small>
    @endif
</div>
