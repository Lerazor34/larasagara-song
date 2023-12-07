var Fileupload = function () {
    var processUpload = function () {
        $(`.fileupload`).on('change', function (evt) {
            const data = $(this);
            const content = data.closest('.fileupload-content');
            const DataSize = data.data('size');
            const file = data[0].files[0];
            const { size } = file;
            if (size > DataSize) {
                const maxSize = sizeConverter(DataSize);
                Swal.fire({
                    icon: 'error',
                    title: 'File size is too large!',
                    text: `Max File size is ${maxSize}`
                }).then((value) => {
                    data.val(null);
                    // content.find('img.preview').removeAttr("src");
                });
                return false;
            }

            // let reader = new FileReader();
            // reader.onload = (e) => {
            //     content.find('img.preview').attr('src', e.target.result);
            //     content.find('a.preview-link').attr('href', e.target.result);
            // }

            // reader.readAsDataURL(this.files[0]);
        });
    }

    var sizeConverter = function formatBytes(bytes, decimals = 2) {
        if (!+bytes) return '0 Bytes'

        const k = 1024
        const dm = decimals < 0 ? 0 : decimals
        const sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB']

        const i = Math.floor(Math.log(bytes) / Math.log(k))

        return `${parseFloat((bytes / Math.pow(k, i)).toFixed(dm))} ${sizes[i]}`
    }

    return {
        init: function () {
            processUpload();
        }
    };
}();

jQuery(document).ready(function () {
    Fileupload.init();
});