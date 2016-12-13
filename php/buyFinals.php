<?php

require_once('vendor/autoload.php');
require('config.php');

\Stripe\Stripe::setApiKey($livekey);



$conn = mysqli_connect($servername, $username, $password, $database);

if(!$conn){
    die("Connection failed: " . mysqli_connect_error());
}

$token = $_POST['stripeToken'];
$order_ID = $_POST['orderID'];
$amountQuery =  "SELECT order_price FROM ORDERS WHERE order_ID='$order_ID' limit 1";
$amountObject = mysqli_query($conn, $amountQuery)or die(mysql_error());
$amount = $amountObject->fetch_object()->order_price;

$descQuery = "SELECT receipt_description FROM receipt_description WHERE order_FK='$order_ID' limit 1";
$descObject = mysqli_query($conn, $descQuery) or die(mysql_error());
$desc = $descObject->fetch_object()->receipt_description;

$email = $_POST['email-address'];

if($description===""||$description==null){
    $description = "1 Birthday Box";
}

try {
    $charge = \Stripe\Charge::create(array(
        'amount' => $amount, // Amount in cents!
        'currency' => 'usd',
        'source' => $token,
        'description' => $desc,
        'receipt_email' => $email
    ));
} catch (\Stripe\Error\Card $e) {
	echo 'The charge was declined, please check the CVC and Zip Code again and make sure javascript is enabled';
}

if($charge->paid==true){
	$updateOrderQuery = "UPDATE ORDERS SET order_charged='1' WHERE order_ID='$order_ID' limit 1";
	mysqli_query($conn, $updateOrderQuery) or die(mysqli_error());
    echo "success";
}
mysqli_close($conn);

?>