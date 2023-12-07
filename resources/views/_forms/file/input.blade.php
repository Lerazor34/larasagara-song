<div class="col-lg-9 col-xl-{{ isset($data['column']) ? $data['column'] : '9' }}">
    <!--begin::Dropzone-->
    <div class="dropzone dropzone-queue mb-2 fileupload" id="file_{{ $data['name'] }}">
        <!--begin::Controls-->
        @if(isset($data['value']))
        <input type="hidden" name="{{ $data['name'] }}" value="{{ $data['value'] }}"/>
        @else
        <input type="hidden" name="{{ $data['name'] }}" value="{{ old($data['name']) }}"/>
        @endif
        <div class="dropzone-panel mb-lg-0 mb-2">
            <a class="dropzone-select btn btn-sm btn-primary me-2">{{ __('Lampirkan Berkas') }}</a>
            <a class="dropzone-remove-all btn btn-sm btn-light-primary">{{ ('Hapus Semua') }}</a>
        </div>
        <!--end::Controls-->

        <!--begin::Items-->
        <div class="dropzone-items wm-200px">
            <div class="dropzone-item" style="display:none">
                <!--begin::File-->
                <div class="dropzone-file">
                    <div class="dropzone-filename" title="some_image_file_name.jpg">
                        <span data-dz-name>{{ __('some_image_file_name.jpg') }}</span>
                        <strong>(<span data-dz-size>{{ __('340kb') }}</span>)</strong>
                    </div>

                    <div class="dropzone-error" data-dz-errormessage></div>
                </div>
                <!--end::File-->

                <!--begin::Progress-->
                <div class="dropzone-progress">
                    <div class="progress">
                        <div
                            class="progress-bar bg-primary"
                            role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0" data-dz-uploadprogress>
                        </div>
                    </div>
                </div>
                <!--end::Progress-->

                <!--begin::Toolbar-->
                <div class="dropzone-toolbar">
                    <span class="dropzone-delete" data-dz-remove><i class="bi bi-x fs-1"></i></span>
                </div>
                <!--end::Toolbar-->
            </div>
        </div>
        <!--end::Items-->
    </div>
    <!--end::Dropzone-->
    <!--begin::Hint-->
    <span class="form-text text-muted">{{ isset($data['options']) ? $data['options'] : '' }}</span>

    @if ($errors->has($data['name']))
    <small id="form-error-{{$data['name']}}" class="form-text text-danger">
        {{ $errors->first($data['name']) }}
    </small>
    @endif
    <!--end::Hint-->
</div>
<!--end::Input group-->
@section('customjs')
<script>
    $(function(){
        // set the dropzone container id
        const id = "#file_{{ $data['name'] }}";
        const dropzone = document.querySelector(id);
        var value = dropzone.querySelector(`input[name="{{ $data['name'] }}"]`).value;
        // set the preview element template
        var previewNode = dropzone.querySelector(".dropzone-item");
        previewNode.id = "";
        var previewTemplate = previewNode.parentNode.innerHTML;
        previewNode.parentNode.removeChild(previewNode);

        var myDropzone = new Dropzone(id, { // Make the whole body a dropzone
            method: 'POST',
            url: hostUrl + '/api/file_upload', // Set the url for your upload script location
            headers: {
                'Authorization': 'Bearer {{session('bearer_token')}}'
            },
            parallelUploads: 20,
            maxFilesize: 1, // Max filesize in MB
            previewTemplate: previewTemplate,
            previewsContainer: id + " .dropzone-items", // Define the container to display the previews
            clickable: id + " .dropzone-select", // Define the element that should be used as click trigger to select files.
            init: function () {
                this.on("success", function (file, response) {
                    if (this.files.length > 1) {
                        this.removeFile(this.files[0]);
                    }
                    if (response.length > 0) {
                        dropzone.querySelector("input[type='hidden']").value = JSON.stringify(response[0])
                    }
                });
            }
        });

        myDropzone.on("removedfile", function (file) {
            dropzone.querySelector("input[type='hidden']").value = '';
        });

        if (value != '') {
            value = JSON.parse(value);
            var mockFile = { name: value.name, size: 12345 };
            myDropzone.options.addedfile.call(myDropzone, mockFile);
            myDropzone.options.thumbnail.call(myDropzone, mockFile, value.bucket + '/' + value.filename);
            myDropzone.options.complete.call(myDropzone, mockFile);
            myDropzone.files.push(mockFile);
            console.log(mockFile)

            $(".dropzone-item").addClass("d-block");
        }

        myDropzone.on("addedfile", function (file) {
            // Hookup the start button
            const dropzoneItems = dropzone.querySelectorAll('.dropzone-item');
            dropzoneItems.forEach(dropzoneItem => {
                dropzoneItem.style.display = '';
            });
        });

        // Update the total progress bar
        myDropzone.on("totaluploadprogress", function (progress) {
            const progressBars = dropzone.querySelectorAll('.progress-bar');
            progressBars.forEach(progressBar => {
                progressBar.style.width = progress + "%";
            });
        });

        myDropzone.on("sending", function (file) {
            // Show the total progress bar when upload starts
            const progressBars = dropzone.querySelectorAll('.progress-bar');
            progressBars.forEach(progressBar => {
                progressBar.style.opacity = "1";
            });
        });

        // Hide the total progress bar when nothing"s uploading anymore
        myDropzone.on("complete", function (progress) {
            const progressBars = dropzone.querySelectorAll('.dz-complete');

            setTimeout(function () {
                progressBars.forEach(progressBar => {
                    progressBar.querySelector('.progress-bar').style.opacity = "0";
                    progressBar.querySelector('.progress').style.opacity = "0";
                });
            }, 300);
        });

        // myDropzone.on("error", function(file, message) {
        //     alert(message);
        //     this.removeFile(file);
        // });
    });
</script>
@endsection
