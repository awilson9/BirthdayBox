<?php
 
if(isset($_POST['email'])) {
 
     
 
    // EDIT THE 2 LINES BELOW AS REQUIRED
 
    $email_to = "contact@bdayb.com";
 
    $email_subject = "From Website";
 
     
 
     
 
    function died($error) {
 
        // your error code can go here
 
        echo "We are very sorry, but there were error(s) found with the form you submitted. ";
 
        echo "These errors appear below.<br /><br />";
 
        echo $error."<br /><br />";
 
        echo "Please go back and fix these errors.<br /><br />";
 
        die();
 
    }
 
     
 
    // validation expected data exists
 
    if(!isset($_POST['name']) ||
 
        !isset($_POST['email']) ||
 
        !isset($_POST['text'])) {
 
        died('We are sorry, but there appears to be a problem with the form you submitted.');       
 
    }
 
     
 
    $name = $_POST['name']; // required
 
    $email_from = $_POST['email']; // required

    $comments = $_POST['text']; // required
 
     
 
    $error_message = "";
 
    $email_exp = '/^[A-Za-z0-9._%-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,4}$/';
 
  if(!preg_match($email_exp,$email_from)) {
 
    $error_message .= 'The Email Address you entered does not appear to be valid.<br />';
 
  }
 
    $string_exp = "/^[A-Za-z .'-]+$/";
 
  if(!preg_match($string_exp,$name)) {
 
    $error_message .= 'The Name you entered does not appear to be valid.<br />';
 
  }
 
  if(strlen($comments) < 2) {
 
    $error_message .= 'The Comments you entered do not appear to be valid.<br />';
 
  }
 
  if(strlen($error_message) > 0) {
 
    died($error_message);
 
  }
 
    $email_message = "Form details below.\n\n";
 
     
 
    function clean_string($string) {
 
      $bad = array("content-type","bcc:","to:","cc:","href");
 
      return str_replace($bad,"",$string);
 
    }
 
     
 
    $email_message .= "Name: ".clean_string($name)."\n";
 
    $email_message .= "Email: ".clean_string($email_from)."\n";
 
    $email_message .= "Comments: ".clean_string($comments)."\n";
 
     
 
     
 
// create email headers
 
$headers = 'From: '.$email_from."\r\n".
 
'Reply-To: '.$email_from."\r\n" .
 
'X-Mailer: PHP/' . phpversion();
 
@mail($email_to, $email_subject, $email_message, $headers); 

$fields = array(
  array(
    "title" => 'Name',
    "value" => $name,
    "short" => false
    ),
  array(
    "title" => 'Email',
    "value" => $email_from,
    "short" => false
    ),
  array(
    "title" => 'Message',
    "value" => $comments,
    "short" => false
    )

  );

$attachment = array(
  'fallback' => 'Message Details', // A required markdown textfield that is displayed on devices that can't display Attachments
  'pretext' => 'Details :email:',
  'color' => '#AE0000', // Can either be one of 'good', 'warning', 'danger', or any hex color code, but this is Pantheon Yellow
  'fields' => $fields,

);


$message = "New Message"; 
$room = "contact"; 
$icon = ":memo:"; 
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
 
 
 
<!-- include your own success html here -->
 
 
 

 
 
 
<?php
 
}
 
?>