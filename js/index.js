/*In case they want the fade in scroll bar again...
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
  */


  $(document).ready(function(){
    if($(window).width()<500){
        $('#header-button').toggle();
        $('#mobile-button').toggle();
    }
    });


$('#bottom-button').on('click', function(){
  $('.wrap, #bottom-button').toggleClass('active');
  
  return false;
});

var nowTemp = new Date();
var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);
var w_start = new Date(2016, 11, 20, 0, 0, 0,0);
var w_end = new Date(2017, 0, 20,0,0,0,0);
var sem_end = new Date(2017, 4, 15, 0, 0,0, 0);
$('#dp-index').datepicker({
  onRender: function(date) {
    var nw = now.valueOf();                
    return (date.valueOf() < (now.valueOf()+604800000)||(date.valueOf()>w_start.valueOf()&&date.valueOf()<w_end.valueOf())||(date.valueOf()>sem_end.valueOf())) ? 'disabled' : '';
  }})
  .on('changeDate', function(ev){
    $('#dp-index').datepicker('hide');
  });