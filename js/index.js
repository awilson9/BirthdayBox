(function ($) {
  $(document).ready(function(){

    // hide .navbar first
    $(".dropdown").hide();

    // fade in .navbar
    $(function () {
        $(window).scroll(function () {

                 // set distance user needs to scroll before we start fadeIn
            if ($(this).scrollTop() > 500) {
                $('.dropdown').fadeIn();
                if($(window).width()>500) $('#header-button').fadeIn();
            } else {
                $('.dropdown').fadeOut();
                $('#header-button').fadeOut();
            }
        });
    });

});
  }(jQuery));