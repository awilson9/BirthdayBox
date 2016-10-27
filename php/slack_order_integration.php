<?php

    require('config.php');
    //open connection to mysql db
    $connection = mysqli_connect($servername, $username, $password, $database) or die("Error " . mysqli_error($connection));
    $order = 0;
    if( isset($_POST['orderID']) ){
        $order = intval($_POST['orderID']);
    }
    else{
        $order = intval($argv[1]);  
    }
    //fetch table rows from mysql db
    $sql = 
    "SELECT * FROM ORDERS WHERE order_ID = '$order'";
    $result = mysqli_query($connection, $sql) or die("Error in Selecting " . mysqli_error($connection));
    $imgURL = "";
    $fields = array();
    while($row =mysqli_fetch_assoc($result))
    { 
        foreach($row as $i => $item){
        if($i=="order_img"){
            $imgURL = "https://bdayb.com/php/files/".$item;
        }
        else if($i=="order_price"){
            $fields[] = array(
            "title" => $i,
            "value" => '$'. intval($item/100),
            "short" => false
            );
         }
        
        else{
        $fields[] = array(
            "title" => $i,
            "value" => $item,
            "short" => false
            );
         }
     }
    }

 $sql = 
    "SELECT product_FK, order_description FROM ORDER_DETAILS WHERE order_FK = '$order'";
    $result = mysqli_query($connection, $sql) or die("Error in Selecting " . mysqli_error($connection));
    $products = array();
    while($row =mysqli_fetch_assoc($result)){
        var_dump($row);
        if($row['order_description']!=""){
            $fields[] = array(
            "title" => "cake type",
            "value" => $row['order_description'],
            "short" => false
            );
        }
        else{
            $products[] = $row['product_FK'];
        }
    }
    
    if(count($products)!=0){
        foreach($products as $product){
             $sql = 
            "SELECT product_name, product_price FROM PRODUCTS WHERE product_ID = '$product'";
            $result = mysqli_query($connection, $sql) or die("Error in Selecting " . mysqli_error($connection));
            while($row =mysqli_fetch_assoc($result)){
                $fields[] = array(
                      "title" => "product name",
                      "value" => $row['product_name'],
                      "short" => false
                       );
                //gift cards all have product ID greater than 5, only want to display price for gift cards
                if(intval($product)>5){
                    $fields[] = array(
                      "title" => 'gift card price',
                      "value" => '$'. ($row['product_price']/100),
                      "short" => false
                       );
                }
            }
        }
    }


$attachment = array(
  'fallback' => 'Order Details', // A required markdown textfield that is displayed on devices that can't display Attachments
  'pretext' => 'Details :cake:',
  'color' => '#AE0000', // Can either be one of 'good', 'warning', 'danger', or any hex color code, but this is Pantheon Yellow
  'fields' => $fields,
  'image_url' => $imgURL
);
$message = "New Order"; 
$room = "orders"; 
$icon = ":smile:"; 
$data = "payload=" . json_encode(array(         
        "channel"       =>  "#{$room}",
        "text"          =>  $message,
        "icon_emoji"    =>  $icon,
        "attachments"   =>  array($attachment)
    ),JSON_PRETTY_PRINT);
var_dump($data);

$url = "https://hooks.slack.com/services/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";
         
 
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
$result = curl_exec($ch);
echo var_dump($result);
if($result === false)
{
    echo 'Curl error: ' . curl_error($ch);
}
 
curl_close($ch);


    //close the db connection
    mysqli_close($connection);
?>