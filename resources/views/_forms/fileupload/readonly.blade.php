<div class="col-lg-6 col-xl-6">
    <div class="card  overlay overflow-hidden">
        <div class="card-body p-0">
            <?php 
                $value = "#";
                if(isset($data['value'])) {
                    $value = url('storage/image/origin') . '/' . $data['value']['original_name'];
                }
            ?>
            <div class="overlay-wrapper">
                <img src="{{ $value }}" alt="" class="w-100 rounded preview"/>
                <figcaption class="font-size-lg figure-caption text-center">{{ $data['value']['description'] }}</figcaption>
            </div>
            <div class="overlay-layer bg-dark bg-opacity-25">
                <a href="{{ $value }}" target="_blank" class="btn btn-light-primary btn-shadow ms-2 preview-link">Image Preview</a>
            </div>
        </div>
    </div>
</div>