$(function () {
    $(".app-page-loader").removeClass("d-block");

    $(".btn-submit").on("click", function () {
        $('#formValidate').submit();
        const btn = $(this);
        btn.attr('data-kt-indicator', 'on');
        btn.attr('disabled', true);
    });

    if ($('body').attr('notification_success')) {
        toastr.success($('body').attr('notification_message'))
    }

    if ($('body').attr('notification_error')) {
        let errorList = JSON.parse($('body').attr('notification_data'));
        for (i = 0; i < errorList.length; i++) {
            toastr.error(errorList[i], "Opss Error !")
        }
    }

    // DELETE ROWS FROM DETAIL PAGE
    $(".delete-row").on("click", function (e) {
        e.preventDefault();
        const url = $(this).data('remote');
        console.log(url);
        const message = $(this).data('message');
        const tableName = $(this).data('tablename');
        Swal.fire({
            text: message,
            icon: "warning",
            showCancelButton: true,
            buttonsStyling: false,
            confirmButtonText: "Yes Delete!",
            cancelButtonText: "No Cancel",
            customClass: {
                confirmButton: "btn fw-bold btn-danger",
                cancelButton: "btn fw-bold btn-active-light-primary"
            }
        }).then(function (result) {
            if (result.value) {
                $.ajax({
                    url: url,
                    type: "POST",
                    headers: {
                        'Authorization': `Bearer ${personalToken}`
                    },
                    success: function (resp) {
                        Swal.fire({
                            text: "Rows successfully deleted !",
                            icon: "success",
                            buttonsStyling: false,
                            confirmButtonText: "Yes",
                            customClass: {
                                confirmButton: "btn fw-bold btn-primary",
                            }
                        }).then(function () {
                            window.location.href = `${hostUrl}/${tableName}`;
                        });
                    }
                });
            } else if (result.dismiss === 'cancel') {
                Swal.fire({
                    text: "Rows Not Deleted.",
                    icon: "error",
                    buttonsStyling: false,
                    confirmButtonText: "Yes",
                    customClass: {
                        confirmButton: "btn fw-bold btn-primary",
                    }
                });
            }
        });
        console.log(123);
    });
});
