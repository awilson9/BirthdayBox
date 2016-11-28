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



$fields = array(
  array(
    "title" => 'Date',
    "value" => $date,
    "short" => false
    ),
  array(
    "title" => 'Email',
    "value" => $email,
    "short" => false
    ),
 

  );

$attachment = array(
  'fallback' => 'Reminder', // A required markdown textfield that is displayed on devices that can't display Attachments
  'pretext' => 'Details :tada:',
  'color' => '#AE0000', 
  'fields' => $fields,

);


$message = "New Reminder"; 
$room = "contact"; 
$icon = ":tada:"; 
$data = json_encode(array(         
        "channel"       =>  "#{$room}",
        "text"          =>  $message,
        "icon_emoji"    =>  $icon,
        "attachments"   =>  array($attachment)
    ),JSON_PRETTY_PRINT);


$url = "https://hooks.slack.com/services/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";
         
 
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch, CURLOPT_POSTFIELDS, array('payload'=> $data));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
$result = curl_exec($ch);
echo var_dump($result);
if($result === false)
{
    echo 'Curl error: ' . curl_error($ch);
}
 
curl_close($ch);



?>