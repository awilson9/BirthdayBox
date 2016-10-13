$('.trigger-menu').click(function(){
		$('.dropdown-content').slideToggle();
		$('#close-menu').toggle();
		if($('.dropdown-content').is(":visible") ){
					$('#menu-icon').animate({rotate: '-90deg'},1000);
		}
		else		$('#menu-icon').animate({rotate: '90deg'},1000);
});

$('#about-link').click(function(){
	$('.dropdown-content').slideToggle();
})

$('#review-link').click(function(){
	$('.dropdown-content').slideToggle();
})