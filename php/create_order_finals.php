<?php
require('config.php');



$conn = mysqli_connect($servername, $username, $password, $database);

if(!$conn){
    die("Connection failed: " . mysqli_connect_error());
}

$address = $_POST['address'];
$apt_no = intval($_POST['apt']);
$phone = $_POST['phone-number'];
$fname= $_POST['fname_deliv'];
$lname = $_POST['lname_deliv'];
$message = $_POST['message'];
$date = $_POST['date'];
$img = $_POST['img'];
$name = $_POST['cardholder-name'];
$email = $_POST['email-address'];
$allergy = $_POST['allergies'];
$comment = $_POST['comments'];

$query = "INSERT INTO ORDERS (order_address, order_apt, order_phone, order_first, order_last, order_message, order_img, order_bday, order_name, order_email, order_date, order_allergy, order_comment) VALUES ('$address', '$apt_no','$phone', '$fname', '$lname', '$message', '$img', '$date', '$name', '$email', now(), '$allergy', '$comment')";



if(mysqli_query($conn, $query)){

}else{
	"Error: " . $query . "<br>" . mysqli_error($conn);
}

$id = mysqli_insert_id($conn);



//product ids in database 
$Donut = 1;
$Cookie = 3;
$Cake = 5;
$bs_25 =7;
$bs_50 =8;
$bs_100 =9;
$amazon_25 = 10;
$amazon_50 = 11;
$amazon_100 = 12;
$visa_25=13;
$visa_50=14;
$visa_100 =15;
$chipotle_15 = 16;
$chipotle_25 = 17;
$chipotle_50 = 18;
$bs_10 = 19;
$chipotle_10 = 20;
$sb_10 = 21;
$sb_25 = 22;
$treat = 23;

$hasgc = $_POST['hasGC'];
$gcID = 0;
$price = 2500;
$gc_description = "";
$unitprice = 0;
if($hasgc==="true"){
	$type = $_POST['type'];
	$amount = $_POST['amount'];
	if($type==="S"&&$amount==="10"){
		$gcID = $sb_10;
		$gc_description = $gc_description . " and 1 $10 Starbucks Gift Card";
		$unitprice = 1000;
	}
	else if($type==="S"){
		$gcID = $sb_25;
		$gc_description = $gc_description . " and 1 $25 Starbucks Gift Card";
		$unitprice = 2500;
	}
	else if($type==="B"&&$amount==="10"){
		$gcID = $bs_10;
		$gc_description = $gc_description . " and 1 $10 Bookstore Gift Card";
		$unitprice = 1000;
	}
	else if($type==="B"){
		$gcID = $bs_25;
		$gc_description = $gc_description . " and 1 $25 Bookstore Gift Card";
		$unitprice = 2500;
	}
	else if($type==="C"&&$amount==="10"){
		$gcID = $chipotle_10;
		$gc_description = $gc_description . " and 1 $10 Chipotle Gift Card";
		$unitprice = 1000;
	}
	else if($type==="C"){
		$gcID = $chipotle_25;
		$gc_description = $gc_description . " and 1 $25 Chipotle Gift Card";
		$unitprice = 2500;
	}

	
	$price = $price + $unitprice;
	
	
	$query = "INSERT INTO ORDER_DETAILS (product_FK, order_FK, unit_price) VALUES ('$gcID', '$id', '$unitprice')";
	if(mysqli_query($conn, $query)){
		}
		else{
		"Error: " . $query . "<br>" . mysqli_error($conn);
		}
	
}


$item = $_POST['dessert'];
$slice_id = 0;
$description = "";
$receipt_description = "1 Finals Box with ";
//set slice id
if($item==="Donuts"){
	$slice_id = $Donut;
	$receipt_description = $receipt_description . "Donuts,";
}
else if($item==="Cookies"){
	$slice_id =	$Cookie;
	$receipt_description = $receipt_description . "Cookie Sandwiches,";
}
else{
	$slice_id =	$Cake;
	$description = $_POST['frosting-type'] . " frosting " . $_POST['cake-type'] . " Cake,";
	$receipt_description = $receipt_description . "a " . $description;

}
$treat_description = " " . $_POST['treat-1'] . ", " . $_POST['treat-2'] . ", " . $_POST['treat-3'] . ","; 

$receipt_description = $receipt_description . $treat_description . $gc_description;

$query = "INSERT INTO ORDER_DETAILS (product_FK, order_FK, order_description) VALUES ('$slice_id', '$id', '$description')";
if(mysqli_query($conn, $query)){

	}
	else{
		"Error: " . $query . "<br>" . mysqli_error($conn);
	}
	

	

$query = "UPDATE ORDERS SET order_price='$price' WHERE order_ID='$id' limit 1";
if(mysqli_query($conn, $query)){

	}
	else{
		"Error: " . $query . "<br>" . mysqli_error($conn);
	}



	$query = "INSERT INTO ORDER_DETAILS (product_FK, order_FK, order_description) VALUES ('$treat', '$id', '$treat_description')";
if(mysqli_query($conn, $query)){

	}
	else{
		"Error: " . $query . "<br>" . mysqli_error($conn);
	}

$query = "INSERT INTO receipt_description (receipt_description, order_FK) VALUES ('$receipt_description', '$id')"; 
if(mysqli_query($conn, $query)){

	}
	else{
		"Error: " . $query . "<br>" . mysqli_error($conn);
	}
echo $id;




mysqli_close($conn);

?>

