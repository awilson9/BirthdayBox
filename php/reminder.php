<?php
require('config.php');

$conn = mysqli_connect($servername, $username, $password, $database);

if(!$conn){
    die("Connection failed: " . mysqli_connect_error());
}

$email = $_POST['reminder_email'];
$date = $_POST['reminder_date'];
$u_date = strtotime($date);
$send_date_u = $u_date - 604800;
$dateTime = new DateTime("@$send_date_u");
$send_date = date_format($dateTime, 'm/d/Y');

$query = "INSERT INTO REMINDERS (REMINDER_EMAIL, REMINDER_BDAY) VALUES ('$email', '$date')";
if(mysqli_query($conn, $query)){
	shell_exec('echo php email-reminder.php ' . $email . ' ' .$date . '| at 10:00 AM ' . $send_date); 
	//$command = "cd ../ at " + "2:10 PM" +"\nphp -f email-reminder.php " . $email . " " . $date + "\n^d";
	//echo shell_exec($command);
}else{
	echo "Error: " . $query . "<br>" . mysqli_error($conn);
}


mysqli_close($conn);

?>