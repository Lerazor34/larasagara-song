var Thumbnail = function () {
    return {
        init: function (obj = null) {
            var element = $('.thumbnail-input');

            if (obj) {
                element = obj.find('.thumbnail-input');
            }

            element.each(function (e) {
                var obj = $(this);
                var value = obj.find("input[type='hidden']").val();
                if (value != '') {
                    value = JSON.parse(value);
                    var imageUrl = assetDir + 'storage/avatar/' + value.filename;
                    obj.find('.image-input-wrapper').css('background-image', 'url(' + imageUrl + ')');
                    if (obj.hasClass('image-input-empty')) {
                        obj.removeClass('image-input-empty');
                        obj.addClass('image-input-changed');
                    }
                }

                obj.find("input[type='file']").change(function (e) {
                    var input = $(this).get(0).files;
                    var uri = obj.data('url');


                    if (obj.data('bucket') != '') {
                        uri += '?bucket=' + obj.data('bucket');
                    }

                    Thumbnail.spinner.start(obj);
                    Thumbnail.upload(input, uri, function (data, error) {
                        Thumbnail.spinner.stop(obj);
                        if (error) {
                            Swal.fire({
                                text: error.statusText,
                                icon: "error",
                                buttonsStyling: false,
                                confirmButtonText: "Yes",
                                customClass: {
                                    confirmButton: "btn fw-bold btn-primary",
                                }
                            })
                            return;
                        }

                        if (data.length > 0) {
                            obj.find('input[type="hidden"]').val(JSON.stringify(data[0]));
                        }
                    });
                });
            });
        },
        processFile: function (files) {
            var formdata = new FormData();
            for (var index = 0; index < files.length; index++) {
                formdata.append(files[index].name, files[index]);
            }

            return formdata;
        },


        upload: function (file, url, cb = null) {
            var data = Thumbnail.processFile(file);
            var token = $('meta[name="token"]').attr('content');
            Object.assign(data, {'_token': csrfToken});
            $.ajax({
                type: 'POST',
                url: url,
                headers: {
                    'Authorization': 'Bearer ' + token
                },
                data: data,
                cache: false,
                contentType: false,
                processData: false,
            }).done(function (response) {
                if (cb) {
                    cb(response, null);
                }
            }).fail(function (error) {
                if (cb) {
                    cb(null, error);
                }

            });
        },
        spinner: function () {
            return {
                start: function (obj = null) {
                    obj.find('span.spinner').addClass('show');

                },
                stop: function (obj = null) {
                    obj.find('span.spinner').removeClass('show');
                }

            }

        }()

    };

}();

jQuery(document).ready(function () {
    Thumbnail.init();
});