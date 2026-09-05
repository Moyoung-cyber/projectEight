jQuery(function ($) {
    $(document).ready(function () {
        $(".primary-menu li.menu-dropdown > a").each(function () {
            if (!$(this).find('.dropdown-btn').length) {
                $(this).append('<span class="dropdown-btn"><i class="fa-solid fa-chevron-down"></i></span>');
            }
        });


        $('.dropdown-btn').on('click', function (event) {
            event.preventDefault();
            event.stopPropagation();
            $(this).parent().parent().toggleClass('open').first().siblings().removeClass('open');
            $(this).parent().parent().find("ul").parent().find("ul.sub-menu").first().slideToggle();
            $(this).parent().parent().siblings().find("ul.sub-menu").slideUp().parent().removeClass('open');
            $(this).toggleClass('transform-90');
            $(this).parent().parent().siblings().find('.dropdown-btn').removeClass('transform-90');
        });
    });
    $('.primary-menu li').has('ul').addClass('menu-dropdown');
});