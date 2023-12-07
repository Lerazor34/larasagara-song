<div class="col-lg-9 col-xl-{{ isset($data['column']) ? $data['column'] : '9' }}">
    <textarea name="{{ $data['name'] }}" class="form-control @if($errors->has($data['name'])) is-invalid @endif" cols="30" rows="5">{{ isset($data['value']) ? $data['value'] : old($data['name']) }}</textarea>

    @if ($errors->has($data['name']))
    <small id="form-error-{{$data['name']}}" class="form-text text-danger">
        {{ $errors->first($data['name']) }}
    </small>
    @endif
</div>
