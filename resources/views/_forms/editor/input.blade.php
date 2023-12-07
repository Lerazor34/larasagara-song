<div class="col-lg-9 col-xl-{{ isset($data['column']) ? $data['column'] : '9' }}">
    <div>
        <div id="kt_docs_quill_basic" class="editor" data-name="{{ $data['name'] }}" style="min-height: 250px">
            <?php echo !empty($data['value']) ? $data['value'] : old($data['name']); ?>
        </div>
    </div>
    <input type="hidden" name="{{ $data['name'] }}" id="terget-editor" value="{{ !empty($data['value']) ? $data['value'] : old($data['name']) }}">
    @if ($errors->has($data['name']))
    <small id="form-error-{{ $data['name'] }}" class="form-text text-danger">
        {{ $errors->first($data['name']) }}
    </small>
    @endif
</div>

@section('customjs')
<script src="{{ asset('assets/js/custom/components/quil.js') }}"></script>
@endsection
