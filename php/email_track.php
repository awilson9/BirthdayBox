<?php

$email_content = $_POST['email'];
$email_to = "orders@bdayb.com";
$headers = "From: website \r\n Reply-To: \r\n X-Mailer: PHP/" . phpversion();
$email_subject = "email entered in website:";
$email_message = "email: " . $email_content . " entered into website";
@mail($email_to, $email_subject, $email_message, $headers); 

?>