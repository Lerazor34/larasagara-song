$(function () {
    $(".menu-item:not(.menu-accordion) > a").each(function () {
        var href = window.location.href.replace(/\?.*/g, "");
        if ($(this).attr('href') == href || href.indexOf($(this).attr('href') + '/') > -1) {
            $(this).addClass('active');
            $(this).parent().parents('.menu-accordion').addClass('here show');
        }
    })
});
