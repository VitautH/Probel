/**
 * Created by myhelp on 07.10.2014.
 */
$(window).scroll(function() {
    if ($(".navbar").offset().top > 50) {
        $(".navbar-fixed-top").addClass("top-nav-collapse");
        $("#menu-header_menu").addClass("mini");
    } else {
        $(".navbar-fixed-top").removeClass("top-nav-collapse");
        $("#menu-header_menu").removeClass("mini");
    }
});