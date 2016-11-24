<?php
	
	require('config.php');
	require('../PHPMailer-master/class.phpmailer.php');



	$conn = mysqli_connect($servername, $username, $password, $database);
	
	if(!$conn){
	    die("Connection failed: " . mysqli_connect_error());
	}
	$order = 0;
	if( isset($_POST['orderID']) ){
	$order = intval($_POST['orderID']);

}
else{
	$order = intval($argv[1]);
}
	$query = "SELECT * FROM ORDERS WHERE order_ID = '$order'";
	$result= mysqli_query($conn, $query)
    or  die(mysqli_error());
    $item_query = "SELECT * FROM ORDER_DETAILS WHERE order_FK = '$order'";
    $item_results = mysqli_query($conn, $item_query)
    or  die(mysqli_error());


    $row_order = mysqli_fetch_assoc($result);
    $size = $row_order['order_size'];
    $giftcard = "No Giftcards";
    $item = "";
   
    
    while($row= mysqli_fetch_array($item_results)){
    	if(intval($row['product_FK'])==7){
    		$giftcard = "$25 University Bookstore Gift Card";
    	}
    		else if(intval($row['product_FK'])==8){
    			$giftcard = "$50 University Bookstore Gift Card";
    	}
    		else if(intval($row['product_FK'])==9){
    			$giftcard = "$100 University Bookstore Gift Card";
    	}
    		else if(intval($row['product_FK'])==10){
    			$giftcard = "$25 Amazon Gift Card";
    	}
    		else if(intval($row['product_FK'])==11){
    			$giftcard = "$50 Amazon Gift Card";
    	}
    		else if(intval($row['product_FK'])==12){
				$giftcard = "$100 Amazon Gift Card";
    	}
    		else if(intval($row['product_FK'])==13){
    			$giftcard = "$25 Visa Gift Card";
    	}
    		else if(intval($row['product_FK'])==14){
				$giftcard = "$50 Visa Gift Card";
    	}
    		else if(intval($row['product_FK'])==15){
    		$giftcard = "$100 Visa Gift Card";
    	}	
    		else if(intval($row['product_FK'])==16){
    		$giftcard = "$15 Chipotle Gift Card";
    	}
    		else if(intval($row['product_FK'])==17){
    		$giftcard = "$25 Chipotle Gift Card";
    	}
	    	else if(intval($row['product_FK'])==18){
    		$giftcard = "$50 Chipotle Gift Card";
    	}
    		else if(intval($row['product_FK'])==1){
    			if($size=="Individual"){
    				$item = "2 Doughnuts";
    			}
    			else if($size=="Double"){
    				$item = "4 Doughnuts";
    			}
    			else if($size=="Group"){
    				$item = "6 Doughnuts";
    			}
    	}
    		else if(intval($row['product_FK'])==3){
    		if($size=="Individual"){
    				$item = "2 Cookies";
    			}
    			else if($size=="Double"){
    				$item = "4 Cookies";
    			}
    			else if($size=="Group"){
    				$item = "6 Cookies";
    			}
    	}
    		else if(intval($row['product_FK'])==5){
    			$split = explode(" ", $row['order_description']);
    			if($size=="Individual"){
    				$item = "1 " . $split[0] . " Cake " . $split[1] . " Frosting";
    			}
    			else if($size=="Double"){
  	 				$item = "2 " . $split[0] . " Cakes " . $split[1] . " Frosting";			
      			}
    			else if($size=="Group"){
    				$item = "3 " . $split[0] . " Cakes " . $split[1] . " Frosting";
    			}
    	}

    }
	
	$mail = new PHPMailer();
	$mail->IsSendmail();
	$mail->SetFrom($row_order['order_email'],$row_order['order_name']);
	$mail->AddAddress('orders@bdayb.com', "BdayB");
	$mail->Subject = "New Order";
		$img = "files/".$row_order['order_img'];

	$mail->AddAttachment($img);

	$mail->AddEmbeddedImage("../img/BdayBMadisonRed.png", "logo");

	$price = "$" . $row_order['order_price']/100; 
	$email_message  = 
	"<div style='height:500px;'>
		<img height='150px' width='150px' src='cid:logo'>
		<h2 style='align-content:center'>".$price . " at Birthday Box" . "</h2>
	</div>"
	
	.
	"<table style='width:100%'>
		<tr>
			<td style='margin-left:20%'>" 
				. $row_order['order_date']. 
			"</td>
			<td style='align:right;margin-right:20%'>
				#" . $order .
			"</td>
		</tr>

		<tr>
			<td style='margin-left:20%'>
				1. Delivery Date: 
			</td>" .
			"<td style='align:right;margin-right:20%'>" .
				 $row_order['order_bday'] . 
			"</td>" .
		"</tr>
		<tr>		
			<td style='margin-left:20%'>
				2. Desert Flavor: 
			</td>
			<td style='align:right;margin-right:20%'>"
				. $item 
			."</td>
		</tr>" . 
			
		"<tr>
			<td style='margin-left:20%'>
				3. Gift Cards:" . 
			"</td>
			<td style='align:right;margin-right:20%'>"
				. $giftcard . 
			"</td>
		</tr>				
		<tr>			
			<td style='margin-left:20%'>
				4. Message: 
			</td>
			<td style='align:right;margin-right:20%'>" 
				. $row_order['order_message'] . 
			"</td>
		</tr>
		<tr>
			<td style='margin-left:20%'>
				5. Recipient Info: 
			</td>
			<td style='align:right;margin-right:20%'>" . 
				$row_order['order_first'] . " " . $row_order['order_last'] . " " . $row_order['order_phone'] . 
			"</td>
		</tr>
		<tr>
			<td style='margin-left:20%'>
				6. Recipient Address: 
			</td>
			<td style='align:right;margin-right:20%'>" 
				. $row_order['order_address'] . " apartment " . $row_order['order_apt'] . 
			"</td>
		</tr>
		<tr>
			<td style='margin-left:20%'>
				7. Purchaser name: 
			</td>
			<td style='align:right;margin-right:20%'>"
			 . $row_order['order_name'] .
			 "</td>
		</tr>
		<tr>
			<td style='margin-left:20%'>
				8. Allergies:
			</td>
			<td style = 'align:right;margin-right:20%'>"
			. $row_order['order_allergy'] . 
			"</td>
	</table>";


	$mail->MsgHTML($email_message);
	if(!$mail->Send()){
		echo "Mailer Error: " . $mail->ErrorInfo; 
	}
	else{
		echo "Message sent!";
	}
	
	$mail = new PHPMailer();
	$mail->IsSendmail();
	$mail->SetFrom('orders@bdayb.com', "BdayB");
	$mail->AddAddress($row_order['order_email'],$row_order['order_name']);
	$mail->Subject = "New Order";
		$img = "files/".$row_order['order_img'];

	$mail->AddAttachment($img);

	$mail->AddEmbeddedImage("../img/BdayBMadisonRed.png", "logo");

	$price = "$" . $row_order['order_price']/100; 
	$email_message  = 
	"<div style='height:500px;'>
		<img height='150px' width='150px' src='cid:logo'>
		<h2 style='align-content:center'>".$price . " at Birthday Box" . "</h2>
	</div>"
	
	.
	"<table style='width:100%'>
		<tr>
			<td style='margin-left:20%'>" 
				. $row_order['order_date']. 
			"</td>
			<td style='align:right;margin-right:20%'>
				#" . $order .
			"</td>
		</tr>

		<tr>
			<td style='margin-left:20%'>
				1. Delivery Date: 
			</td>" .
			"<td style='align:right;margin-right:20%'>" .
				 $row_order['order_bday'] . 
			"</td>" .
		"</tr>
		<tr>		
			<td style='margin-left:20%'>
				2. Desert Flavor: 
			</td>
			<td style='align:right;margin-right:20%'>"
				. $item 
			."</td>
		</tr>" . 
			
		"<tr>
			<td style='margin-left:20%'>
				3. Gift Cards:" . 
			"</td>
			<td style='align:right;margin-right:20%'>"
				. $giftcard . 
			"</td>
		</tr>				
		<tr>			
			<td style='margin-left:20%'>
				4. Message: 
			</td>
			<td style='align:right;margin-right:20%'>" 
				. $row_order['order_message'] . 
			"</td>
		</tr>
		<tr>
			<td style='margin-left:20%'>
				5. Recipient Info: 
			</td>
			<td style='align:right;margin-right:20%'>" . 
				$row_order['order_first'] . " " . $row_order['order_last'] . " " . $row_order['order_phone'] . 
			"</td>
		</tr>
		<tr>
			<td style='margin-left:20%'>
				6. Recipient Address: 
			</td>
			<td style='align:right;margin-right:20%'>" 
				. $row_order['order_address'] . " apartment " . $row_order['order_apt'] . 
			"</td>
		</tr>
		<tr>
			<td style='margin-left:20%'>
				7. Purchaser name: 
			</td>
			<td style='align:right;margin-right:20%'>"
			 . $row_order['order_name'] .
			 "</td>
		</tr>
		<tr>
			<td style='margin-left:20%'>
				8. Allergies:
			</td>
			<td style = 'align:right;margin-right:20%'>"
			. $row_order['order_allergy'] . 
			"</td>
	</table>";

		
	$mail->MsgHTML($email_message);
	if(!$mail->Send()){
		echo "Mailer Error: " . $mail->ErrorInfo; 
	}
	else{
		echo "Message sent!";
	}
	
	mysqli_close($conn);
?>