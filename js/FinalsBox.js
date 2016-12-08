"use strict"
$(document).ready(function(){
   $('#dessert-choice').change(function(){
   		var selected = $(this).find(":selected").text();
   		if(selected==="Cake"){
   			$('#cake-flavor').show("slow");
   		}
   		else{
   			$('#cake-flavor').hide("fast");
   		}
   })
   $('#yes-gift-card').click(function(){
   	if(this.checked){
  	 		if($('#no-gift-card')[0].checked){
  	 			$('#no-gift-card').prop("checked", false);
  	 		}
  	 	$('#gift-card-options').show("slow");
   	}
   	else{
   		$('#gift-card-options').hide("fast");
   		if(!$('#no-gift-card')[0].checked){
  	 			$('#no-gift-card').prop("checked", true);
  	 		}
   	}


   })
   $('#no-gift-card').click(function(){
   	if(this.checked){
   		if($('#yes-gift-card')[0].checked){
  	 			$('#yes-gift-card').prop("checked", false);
  	 		}
  	 		$('#gift-card-options').hide("fast");
   		}
   		else{
   			$('#gift-card-options').show("slow");
   			if(!$('#yes-gift-card')[0].checked){
  	 			$('#yes-gift-card').prop("checked", true);
  	 		}
   		}
   })
  $('#dessert-allergies').change(function(){
  	var proceed = true;
  
  	if($('#dessert-choice').val()===null){
  			proceed = false;
  			$('#dessert-choice').addClass('input-warning');
  	}
  	if($('#dessert-choice').val()==="Cake"){
  		if($('#cake-type').val()===null){
  			proceed = false;
  			$('#cake-type').addClass('input-warning');
  		}
  		if($('#frosting-type').val()===null){
  			proceed = false;
  			$('#frosting-type').addClass('input-warning');
  		}
  	}
  });
  $('#treat-2').change(function(){
  	var proceed = false;
  	if($('#treat-1').val()===null){
  		proceed = false;
  		$('#treat-1').addClass('input-warning');
  	}
  })
 $("#payment-form").submit(function(event) {
 			// Flag variable:
		var error = false;

		// disable the submit button to prevent repeated clicks:
		$('#submitBtn').attr("disabled", "disabled");

		// Get the values:
		var ccNum = $('.card-number').val(), cvcNum = $('.card-cvc').val(), expMonth = $('.card-expiry-month').val(), expYear = $('.card-expiry-year').val(), zip = $('.card-zip').val();

		// Validate the number:
		if (!Stripe.card.validateCardNumber(ccNum)) {
			error = true;
			reportError('The credit card number appears to be invalid.');
		}

		// Validate the CVC:
		if (!Stripe.card.validateCVC(cvcNum)) {
			error = true;
			reportError('The CVC number appears to be invalid.');
		}

		// Validate the expiration:
		if (!Stripe.card.validateExpiry(expMonth, expYear)) {
			error = true;
			reportError('The expiration date appears to be invalid.');
		}

		// Validate other form elements, if needed!

		// Check for errors:
		if (!error) {

			// Get the Stripe token:
			Stripe.card.createToken({
				number: ccNum,
				cvc: cvcNum,
				exp_month: expMonth,
				exp_year: expYear,
				address_zip: zip
			}, stripeResponseHandler);

		}

		// Prevent the form from submitting:
		return false;
    }); // form submission

 });//document ready

function stripeResponseHandler(status, response) {

	// Check for an error:
	if (response.error) {

		reportError(response.error.message);

	} else { // No errors, submit the form:

	  var f = $("#payment-form");

	  // Token contains id, last4, and card type:
	  var token = response['id'];

	  // Insert the token into the form so it gets submitted to the server
	  f.append("<input type='hidden' name='stripeToken' value='" + token + "' />");

	  var order_id = 0;
	  var dessert_info = $('#dessert-form').serialize();
	  var giftcard_info = $('#gift-card-form').serialize();

	  var delivery_info = $("#delivery_info").serialize();
	  var treat_info = $("#treat-form").serialize();
	 // var address = $('#autocomplete').val();
	  var personal_message = $("#personal_message").serialize();
	  var imgattr = $('#imageURL');
	  var imgURL = "";
	  if( imgattr.length) imgURL = imgattr[0].innerText;
	  else imgURL = "wisconsin.jpg";
	  //var apt = $('#apt').val();
	  //var email=$('#email-address').val();
	  //var date = $('#dp1').val();

	  $.post('./php/create_order_finals.php', dessert_info + '&' + giftcard_info + '&' + delivery_info + '&' + treat_info + '&' + personal_message +'&' + 'img=' + imgURL + '&' + f.serialize(), function(data){
	  	order_id = Number(data);
	  	processCharge(order_id);
	  }); 

	   function processCharge(id){
		f.append("<input type='hidden' name='orderID' value='" + order_id + "' />");
		$.post('./php/buy.php', f.serialize(), function(data){
	  			$('#payment-errors').text(data);
	  			if(data==="success") {
	  				sendSlack(id);
	  				sendEmail(id);
	  			}
	  			else{
	  				$('#submitBtn').prop('disabled', false);
	  			}
		});
	   }
	   function sendEmail(id){
	   		$.post('./php/email-order.php', 'orderID=' + id,function(data){
	   			window.onbeforeunload = null;
	   			window.location.replace("http://bdayb.com/success.html");
	   });
	   	}
	   	function sendSlack(id){
	   		$.post('./php/slack_order_integration.php', 'orderID=' + id,function(data){
	   });
	   	}
	}
}

function reportError(msg) {
 
    // Show the error in the form:
    $('#payment-errors').text(msg);
 
    // Re-enable the submit button:
    $('#submitBtn').prop('disabled', false);
 
    return false;
 
}
var nowTemp = new Date();
var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);
var w_start = new Date(2016, 11, 20, 0, 0, 0,0);

$('#dp1').datepicker({
  onRender: function(date) {
    var nw = now.valueOf();
    return (date.valueOf() < (now.valueOf()+172800000)||(date.valueOf()>w_start.valueOf())) ? 'disabled' : '';
  }})
  .on('changeDate', function(ev){
    $('#dp1').datepicker('hide');
  });