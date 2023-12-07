<div class="col-lg-9 col-xl-{{ isset($data['column']) ? $data['column'] : '9' }}">
    <input
        type="text"
        class="form-control"
        placeholder="{{ __(isset($data['placeholder'])) ? __($data['placeholder']) : __($data['label']) }}"
        value="{{ isset($data['value']) ? $data['value'] : old($data['name']) }}"
        readonly
    >
</div>
