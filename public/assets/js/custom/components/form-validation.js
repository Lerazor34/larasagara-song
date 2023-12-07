$(function() {
    const listColumns = $("input#filed-required").val();
    const columns = JSON.parse(listColumns);
    const isrequired = {}
    const messages = {}
    if (columns) {
        for (const key in columns) {
            isrequired[key] = {
                required: true
            }
            messages[key] = `Field ${columns[key]} is required !`
        }
    }

    $("form#formValidate").validate({
        ignore: '',
        errorElement: 'span',
        errorClass: 'is-invalid help-block help-block-error text-danger',
        focusInvalid: true,
        rules: isrequired,
        messages: messages,
        invalidHandler: function(event, validator) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Some fields is required !'
            }).then(() => {
                const btn = $(".btn-submit");
                btn.attr('data-kt-indicator', 'off');
                btn.removeAttr('disabled');
            });
        },
        errorPlacement: function(error, element) {
            if (element.hasClass('fileupload-content')) {
                error.insertAfter(element.closest('.fileupload-content'));
            } else if (element.hasClass('select2-hidden-accessible')) {
                error.insertAfter(element.siblings('.select2-container')).focus();
            } else if (element.hasClass('thumbnail-image')) {
                error.insertAfter(element.closest('.image-input'));
            } else if (element.hasClass('form-check-input')) {
                error.insertAfter(element.closest('.content'));
            } else if (element.hasClass('email')) {
                error.insertAfter(element.closest('.content'));
            } else if (element.hasClass('password')) {
                error.insertAfter(element.closest('.content'));
            } else if (element.hasClass('thumbnail')) {
                error.insertAfter(element.closest('.content'));
            } else {
                error.insertAfter(element);
            }
        },
        submitHandler: function(form, e) {
            form.submit();
        }
    });
});