<?php
session_start();
error_reporting(0);
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
require_once("_apiAccess.php");
access();
require_once("../script/dbconnection.php");
require_once("../script/_sendNoti.php"); 
require_once("../config.php");

use Violin\Violin;
require_once('../validator/autoload.php');
$v = new Violin;

$error = [];
$success = 0;

$remote_id = $_REQUEST['remote_id'];
$id = $_REQUEST['id'];
$message = $_REQUEST['message'];

$v->validate([
    'remote_id'   => [$remote_id,  'required|int'],
    'id' => [$id,'required|int'],
    'message'   => [$message,  'required|min(1)'],
]);

$response = [];
$sql ="select * from orders where id = ?";
$order = setData($con,$sql,[$id]);
if($v->passes()) {
  try{
    $sql="select * from orders where id=? and remote_id=?";
    $order=getData($con,$sql,[$id,$remote_id]);
    if(count($order)>0){
      $sql = 'insert into message (message,order_id,from_id,is_client) values (?,?,?,?)';
      $result = setData($con,$sql,[$message,$id,$userid,1]);
      if($result > 0){
        $success = 1;
        $sql = "select staff.token as s_token, clients.token as c_token,order_no from orders inner join staff
                on
                staff.id = orders.manager_id
                or
                staff.id = orders.driver_id
                inner join clients on clients.id = orders.client_id
                where orders.id = ?";
        $res =getData($con,$sql,[$id]);
        sendNotification([$res[0]['s_token'],$res[1]['s_token'],$res[0]['c_token']],[$order_id],'رساله جديد - '.$res[0]['order_no'],$message,"../orderDetails.php?o=".$order_id);

      }
    }
  }catch(PDOException $ex) {
   $error=["error"=>$ex];
   $success="0";
}
}else{
$error = [
           'remote_id'=>implode($v->errors()->get('remote_id')),
           'id'=>implode($v->errors()->get('id')),
           'message'=>implode($v->errors()->get('message')),
           ];
}
echo json_encode([$_REQUEST,'success'=>$success, 'error'=>$error]);
?>