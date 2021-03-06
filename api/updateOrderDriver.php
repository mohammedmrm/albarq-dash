<?php
session_start();
error_reporting(0);
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
require_once("_apiAccess2.php");
access();
require_once("../script/dbconnection.php");
require_once("../script/_httpRequest.php");
require_once("../config.php");

use Violin\Violin;
require_once('../validator/autoload.php');
$v = new Violin;
function validateDate($date, $format = 'Y-m-d')
{
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) == $date;
}

$v->addRuleMessage('isPhoneNumber', 'رقم هاتف غير صحيح ');

$v->addRule('isPhoneNumber', function($value, $input, $args) {
  if(preg_match("/^[0-9]{10,15}$/",$value) || empty($value)){
    $x=(bool) 1;
  }
    return $x;
});
$error = [];
$success = 0;
$manger = $_SESSION['userid'];

$id = $_REQUEST['id'];
$barcode = $_REQUEST['barcode'];
$driver_phone= $_REQUEST['driver_phone'];
;
if(!validateDate($date)){
  $date_err = "تاريخ غير صالح";
}else{
  $date_err = "";
}
if(empty($number)){
  $number = "1";
}
$v->validate([
    'id'           => [$id,    'required|int'],
    'barcode'      => [$barcode,'required|min(1)|max(100)'],
    'driver_phone' => [$driver_phone,'required|isPhoneNumber'],
]);

$response = [];
if($v->passes()) {
  try{
  $sql = 'update orders set remote_driver_phone='.$driver_phone.' where id ='.$id.' and bar_code='.$barcode;
  $result = setData($con,$sql);
  if($result > 0){
    $success = 1;
     ///---sync
     $sql = "select isfrom ,clients.sync_token as token,clients.sync_dns as dns,staff.phone as driver_phone,
             remote_id from orders
             left join clients on clients.id = orders.client_id
             left join staff on staff.id = orders.driver_id
             where orders.id=?";
     $order = getData($con,$sql,[$id]);
     if($order[0]['isfrom'] == 2){
       $response = httpPost($order[0]['dns'].'/api/updateOrderDriver.php',
            [
             'token'=>$order[0]['token'],
             'driver_phone'=>$order[0]['driver_phone'],
             'id'=>$order[0]['remote_id'],
             'barcode'=>$v,
            ]);
     }
  }
  }catch(PDOException $ex) {
   $error=["error"=>$ex];
   $success="0";
}
}else{
$error = [
           'id'=> implode($v->errors()->get('id')),
           'barcode'=>implode($v->errors()->get('order_no')),
           'driver_phone'=>implode($v->errors()->get('driver_phone')),
           ];
}
echo json_encode([$_REQUEST,'success'=>$success, 'error'=>$error]);
?>