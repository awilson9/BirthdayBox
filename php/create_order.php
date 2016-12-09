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
$age = intval($_POST['age']);
$name = $_POST['cardholder-name'];
$email = $_POST['email-address'];
$allergy = $_POST['allergy'];



$query = "INSERT INTO ORDERS (order_address, order_apt, order_phone, order_first, order_last, order_message, order_img, order_age, order_bday, order_name, order_email, order_date, order_allergy) VALUES ('$address', '$apt_no', '$phone', '$fname', '$lname', '$message', '$img', '$age', '$date', '$name', '$email', now(), '$allergy')";


if(mysqli_query($conn, $query)){
	$last_ID = mysqli_insert_id($conn);
	echo $last_ID;
}else{
	"Error: " . $query . "<br>" . mysqli_error($conn);
}

mysqli_close($conn);


?>