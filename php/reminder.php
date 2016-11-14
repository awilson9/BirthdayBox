<?php
require('config.php');

$conn = mysqli_connect($servername, $username, $password, $database);

if(!$conn){
    die("Connection failed: " . mysqli_connect_error());
}

$email = $_POST['reminder-email'];
$date = time();

$query = "INSERT INTO REMINDERS (REMINDER_EMAIL, REMINDER_BDAY) VALUES ('$email', '$date')";
if(mysqli_query($conn, $query)){
	$command = "php email-reminder.php " . $email . " " . $date . " | at " . $date;
	$output = shell_exec($command);
}else{
	echo "Error: " . $query . "<br>" . mysqli_error($conn);
}

mysqli_close($conn);

?>