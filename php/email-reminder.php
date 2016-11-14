<?php 

$email_to = $argv[1];
$email_subject = "Order Reminder From Birthday Box";
$email_from = "orders@bdayb.com";
$email_message = "Hello,\n\nThank you for your interest in Birthday Box. This is an order reminder for a birthday on " . $argv[2] . "\nClick on the following link to complete your order: https://bdayb.com/order.html\n\nHave a great day,\n The Birthday Box Team"; 
$headers = 'From: '.$email_from."\r\n".
 
'Reply-To: '.$email_from."\r\n" .
 
'X-Mailer: PHP/' . phpversion();
 
@mail($email_to, $email_subject, $email_message, $headers);
?>