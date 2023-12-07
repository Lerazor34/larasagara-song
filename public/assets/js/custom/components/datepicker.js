var DatePicker = function () {

    return {
        init: function (obj = null) {
            var element = $('.input-group.date.flatpickr');

            if (obj) {
                element = $(obj).find('.input-group.date.flatpickr');
            }

            element.each(function (e) {
                var input = $(this).find('input[type="text"]');
                var input_hidden = $(this).find('input[type="hidden"]');

                input.flatpickr({
                    dateFormat: $(this).attr('date-format'),
                    onChange: function (selectedDates, dateStr, instance) {
                        input_hidden.val(moment(selectedDates[0]).format('YYYY-MM-DD'));
                    }
                });
            });
        }

    };

}();

jQuery(document).ready(function () {
    DatePicker.init();
});
