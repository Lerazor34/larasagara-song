<div class="col-lg-9 col-xl-{{ isset($data['column']) ? $data['column'] : '9' }}">
    <select
        class="form-select @if($errors->has($data['name'])) is-invalid @endif"
        data-control="select2" data-placeholder="{{ __('Select') }} {{ $data['name'] }}"
        name="{{ __($data['name']) }}[]"
        multiple
    >
        <option></option>
        @foreach($data['options'] as $key => $items)
            <option value="{{ __($key) }}" {{ isset($data['value']) && $data['value'] == $key || old($data['name']) == $key ? 'selected' : '' }}>{{ __($items) }}</option>
        @endforeach
    </select>
    @if ($errors->has($data['name']))
    <small id="form-error-{{$data['name']}}" class="form-text text-danger">
        {{ $errors->first($data['name']) }}
    </small>
    @endif
</div>
