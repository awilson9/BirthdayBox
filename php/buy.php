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
$email = $_POST['email'];


try {
    $charge = \Stripe\Charge::create(array(
        'amount' => $amount, // Amount in cents!
        'currency' => 'usd',
        'source' => $token,
        'description' => 'description'
    ));
} catch (\Stripe\Error\Card $e) {
	echo 'the charge was declined, please try again and make sure javascript is enabled';
}

if($charge->paid==true){
	$updateOrderQuery = "UPDATE ORDERS SET order_charged='1' WHERE order_ID='$order_ID' limit 1";
	mysqli_query($conn, $updateOrderQuery) or die(mysqli_error());
}
mysqli_close($conn);

?>