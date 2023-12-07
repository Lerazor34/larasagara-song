<div class="col-lg-9 col-xl-{{ isset($data['column']) ? $data['column'] : '9' }}">
    <div>
        <div id="kt_docs_quill_basic" class="editor" data-name="{{ $data['name'] }}" style="min-height: 250px">
            {{ !empty($data['value']) ? $data['value'] : old($data['name']) }}
        </div>
    </div>
</div>

@section('customjs')
<script src="{{ asset('assets/js/custom/components/quil.js') }}"></script>
@endsection
