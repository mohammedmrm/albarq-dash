<?php
ob_start();
session_start();
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, X-Requested-With");
error_reporting(0);
require_once("_apiAccess.php");
require_once("../config.php");
access();
$data=["No Data"];
$success="0";
$token = $_REQUEST['token'];
$f="";
$orders = $_REQUEST['orders'];
  if(count($orders) > 0){
    if(count($orders) > 0){
        $a = 0;
        foreach($orders as $order){
          if($a==0){
             $f .= " (order_no = '".$order['order_no']."' and customer_phone = '".$order['customer_phone']."' and to_city ='".$order['city']."' and
                    (DATE('".$order['date']."') < DATE_SUB(date, INTERVAL 7 DAY) or DATE('".$order['date']."') < DATE_SUB(date, INTERVAL 7 DAY))) ";
          }else{
             $f .= " or
                   (order_no = '".$order['order_no']."' and customer_phone = '".$order['customer_phone']."' and to_city ='".$order['city']."' and
                   (DATE('".$order['date']."') < DATE_SUB(date, INTERVAL 7 DAY) or DATE('".$order['date']."') < DATE_SUB(date, INTERVAL 7 DAY))) ";
          }
          $a++;
       }
       $f = " and ( ".$f." )";
    }
require_once("../script/dbconnection.php");
if(count($orders)<= 100){
  try{
    $query = "select
     confirm,
     customer_phone,
     to_city as city,
     order_no,
     date,
     orders.order_status_id as status,
     orders.price,
     new_price as received_price,
     discount
     from orders
     where confirm=1 and orders.client_id='".$clinetdata['id']."'  ".$f;
    $data = getData($con,$query);
    $success="1";
  } catch(PDOException $ex) {
     $data=["error"=>$ex];
     $success="0";
     $message ='Error contact the developer';
  }
}
}else{
     $data=[];
     $success="0";
     $message = "Max orders per request 100";
}
ob_end_clean();
echo json_encode([$query,"success"=>$success,"data"=>$data,'messgae'=>$message]);
?>