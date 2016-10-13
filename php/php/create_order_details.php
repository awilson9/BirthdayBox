<?php


require('config.php');

$conn = mysqli_connect($servername, $username, $password, $database);

if(!$conn){
    die("Connection failed: " . mysqli_connect_error());
}

//product ids in database 
$Chocolate_Slice = 1;
$Chocolate_Cake = 2;
$Vanilla_Slice = 3;
$Vanilla_Cake = 4;
$Red_Slice = 5;
$Red_Cake = 6;
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


$price = 0;

$order_ID = $_POST['orderID'];


$has_a = $_POST['giftcards']['Amazon']['selected'];
$has_b = $_POST['giftcards']['Bookstore']['selected'];
$has_v = $_POST['giftcards']['Visa']['selected'];
$has_c = $_POST['giftcards']['Chipotle']['selected'];

$query = "";
$value = 0;
$gcID = 0;
if($has_a==="true"){
	$type = intval($_POST['giftcards']['Amazon']['type']);
	if($type==1){
		$gcID = $amazon_25;
	}
	else if($type==2){
		$gcID = $amazon_50;
	}
	else if($type==3){
		$gcID = $amazon_100;
	}

}
else if($has_c==="true"){
	$type = intval($_POST['giftcards']['Chipotle']['type']);
	if($type==1){
		$gcID = $chipotle_15;
	}
	else if($type==2){
		$gcID = $chipotle_25;
	}
}
else if($has_b==="true"){
	$type = intval($_POST['giftcards']['Bookstore']['type']);
	if($type==1){
		$gcID = $bs_25;
	}
	else if($type==2){
		$gcID = $bs_50;
	}
	else if($type==3){
		$gcID = $bs_100;
	}
}
else if($has_v==="true"){
	$type = intval($_POST['giftcards']['Visa']['type']);
	if($type==1){
		$gcID = $visa_25;
	}
	else if($type==2){
		$gcID = $visa_50;
	}
	else if($type==3){
		$gcID = $visa_100;
	}
}

	$unitpriceQuery = "SELECT product_price FROM PRODUCTS WHERE product_ID='$gcID' limit 1";
	$unitpriceObject = mysqli_query($conn, $unitpriceQuery) or die(mysqli_error());
	$unitprice = intval($unitpriceObject->fetch_object()->product_price);

	$price = $price + $unitprice;
	$query = "INSERT INTO ORDER_DETAILS (quantity, unit_price, order_FK, product_FK) VALUES ('1', '$unitprice', '$order_ID', '$gcID' )";
	if(mysqli_query($conn, $query)){
		}
		else{
		"Error: " . $query . "<br>" . mysqli_error($conn);
		}

//from form
$item = $_POST['item'];

$slice_id = 0;

//set slice id
if($item==="Vanilla Slice"){
	$slice_id = $Vanilla_Slice;
}
else if($item==="Vanilla Cake"){
	$slice_id =	$Vanilla_Cake;
}
else if($item==="Chocolate Slice"){
	$slice_id =	$Chocolate_Slice;
}
else if($item==="Chocolate Cake"){
	$slice_id = $Chocolate_Cake;
}
else if($item==="Red Velvet Slice"){
	$slice_id = $Red_Slice;
}
else{
	$slice_id = $Red_Cake;
}

$unitpriceQuery = "SELECT product_price FROM PRODUCTS WHERE product_ID='$slice_id' limit 1";
$unitpriceObject = mysqli_query($conn, $unitpriceQuery) or die(mysqli_error());
$unitprice = intval($unitpriceObject->fetch_object()->product_price);
$price = $price+$unitprice;

$queries[] =  "INSERT INTO ORDER_DETAILS (quantity, unit_price, order_FK, product_FK) VALUES ('1', '$unitprice', '$order_ID', '$slice_id' )";
$queries[] = "UPDATE ORDERS SET order_price='$price' WHERE order_ID='$order_ID' limit 1";


foreach($queries as $query){
	if(mysqli_query($conn, $query)){

	}
	else{
		"Error: " . $query . "<br>" . mysqli_error($conn);
	}
}







mysqli_close($conn);


?>