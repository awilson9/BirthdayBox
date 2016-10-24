//'use strict'

function reportError(msg) {
 
    // Show the error in the form:
    $('#payment-errors').text(msg);
 
    // Re-enable the submit button:
    $('#submitBtn').prop('disabled', false);
 
    return false;
 
}

$(document).ready(function() {
 
    // Watch for a form submission:
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
 
}); // dank me me ready.

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
	  var delivery_info = $("#delivery_info").serialize();
	  var address = $('#autocomplete').val();
	  var personal_message = $("#personal_message").serialize();
	  var imgattr = $('#imageURL');
	  var imgURL = "";
	  if( imgattr.length) imgURL = imgattr[0].innerText;
	  else imgURL = "wisconsin.jpg";
	  var apt = $('#apt').val();
	  var email=$('#email-address').val();
	  var date = $('#dp1').val();

	  $.post('./php/create_order.php', 'address='+ address +'&date=' + date +'&' +'apt=' +apt + '&' +delivery_info+'&'+ personal_message +'&' + 'img=' + imgURL + '&' + f.serialize(), function(data){
	  	order_id = Number(data);
	  	createOrderDetails(order_id);
	  }); 

	  function createOrderDetails(id){
	  	order.orderID = id;
	  	var recursiveDecoded = decodeURIComponent( $.param( order ) );
	  	$.post('./php/create_order_details.php', recursiveDecoded, function(data){
	  		processCharge(id);
	  });

	}
	   function processCharge(id){
		f.append("<input type='hidden' name='orderID' value='" + order_id + "' />");
		$.post('./php/buy.php', f.serialize()+'&email=' + email, function(data){
	  			$('#payment-errors').text(data);
	  			if(data==="success") sendEmail(id);
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
	}
}
