<div class="col-lg-9 col-xl-{{ isset($data['column']) ? $data['column'] : '9' }}">
    <div class="pt-3">
        @php
            $name = $data['options']['index'];
            $value = $data['options']['value'];
        @endphp
        @foreach ($data['data'] as $key => $item)
            <div class="mb-3 form-check form-check-custom form-check-solid">
                <input class="form-check-input" type="checkbox" value="{{ $item->$value }}"
                    id="flexCheckDefault_{{ $key }}" name="{{ $data['name'] . '[' . $key . ']' }}">
                <label class="form-check-label" for="flexCheckDefault_{{ $key }}">
                    {{ $item->$name }}
                </label>
            </div>
        @endforeach
    </div>
    @if ($errors->has($data['name']))
        <small id="form-error-{{ $data['name'] }}" class="form-text text-danger">
            {{ $errors->first($data['name']) }}
        </small>
    @endif
</div>
