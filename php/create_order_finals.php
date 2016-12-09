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

$query = "INSERT INTO ORDERS (order_address, order_apt, order_phone, order_first, order_last, order_message, order_img, order_bday, order_name, order_email, order_date, order_allergy) VALUES ('$address', '$apt_no','$phone', '$fname', '$lname', '$message', '$img', '$date', '$name', '$email', now(), '$allergy')";



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


$hasgc = $_POST['hasGC'];
$gcID = 0;
$price = 2500;
if($hasgc==="true"){
	$type = $_POST['type'];
	$amount = $_POST['amount'];
	if($type==="S"&&$amount==="10"){
		$gcID = $sb_10;
	}
	else if($type==="S"){
		$gcID = $sb_25;
	}
	else if($type==="B"&&$amount==="10"){
		$gcID = $bs_10;
	}
	else if($type==="B"){
		$gcID = $bs_25;
	}
	else if($type==="C"&&$amount==="10"){
		$gcID = $chipotle_10;
	}
	else if($type==="C"){
		$gcID = $chipotle_25;
	}

	$unitpriceQuery = "SELECT product_price FROM PRODUCTS WHERE product_ID='$gcID' limit 1";
	$unitpriceObject = mysqli_query($conn, $unitpriceQuery) or die(mysqli_error());
	$unitprice = intval($unitpriceObject->fetch_object()->product_price);
	$price = $price + $unitprice;
	$query = "INSERT INTO ORDER_DETAILS (product_FK, order_FK, unit_price) VALUES ('$gcID', '$id', '$unitprice' )";
	if(mysqli_query($conn, $query)){
		}
		else{
		"Error: " . $query . "<br>" . mysqli_error($conn);
		}
}


$item = $_POST['dessert'];
$slice_id = 0;
$description = "";
//set slice id
if($item==="Donuts"){
	$slice_id = $Donut;
}
else if($item==="Cookies"){
	$slice_id =	$Cookie;
}
else{
	$slice_id =	$Cake;
	$description = $_POST['cake-type'] . " " . $_POST['frosting-type'];

}

$queries[] =  "INSERT INTO ORDER_DETAILS (product_FK, order_FK, order_description, unit_price) VALUES ('$slice_id', '$id', '$description')";
$queries[] = "UPDATE ORDERS SET order_price='$price' WHERE order_ID='$id' limit 1";

//todo treat options (once figured out)


foreach($queries as $query){
	if(mysqli_query($conn, $query)){

	}
	else{
		"Error: " . $query . "<br>" . mysqli_error($conn);
	}
}



echo $id;
mysqli_close($conn);

?>

