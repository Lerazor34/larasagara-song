<div class="col-lg-9 col-xl-{{ isset($data['column']) ? $data['column'] : '9' }}">
    @php
        $img = isset($data['value']) ? json_decode($data['value']) : [];
    @endphp
    <div class="card">
        <div class="card-body p-0">
            <div class="overlay-wrapper">
                <img src="{{ asset('storage/avatar/' . $img->filename) }}" alt="" class="w-100 rounded" />
            </div>
        </div>
    </div>
</div>
