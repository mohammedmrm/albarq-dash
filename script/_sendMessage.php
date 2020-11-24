<?php
session_start();
error_reporting(0);
header('Content-Type: application/json');
require_once("_access.php");
access([1,2,3,4,5,9,8,7]);
require_once("_sendNoti.php");
require_once("dbconnection.php");
require_once("_crpt.php");
require_once("_httpRequest.php");

use Violin\Violin;
require_once('../validator/autoload.php');
$v = new Violin;


$success = 0;
$error = [];
$message  = $_REQUEST['message'];
$order_id = $_REQUEST['order_id'];


$v->addRuleMessages([
    'required' => 'الحقل مطلوب',
    'int'      => 'فقط الارقام مسموع بها',
    'min'      => 'قصير جداً',
    'max'      => 'مسموح ب {value} رمز كحد اعلى ',
]);

$v->validate([
    'message'    => [$message,    'required|min(1)|max(500)'],
    'order_id'   => [$order_id,    'required|int'],
]);

if($v->passes()) {
  $sql = 'insert into message (message,order_id,from_id) values
                             (?,?,?)';
  $result = setData($con,$sql,[$message,$order_id,$_SESSION['userid']]);
  if($result > 0){
    try{
    $success = 1;
    $sql = "select staff.token as s_token, clients.token as c_token,order_no from orders inner join staff
            on
            staff.id = orders.manager_id
            or
            staff.id = orders.driver_id
            inner join clients on clients.id = orders.client_id
            where orders.id = ?";

    $res =getData($con,$sql,[$order_id]);
    sendNotification([$res[0]['s_token'],$res[1]['s_token'],$res[0]['c_token']],[$order_id],'رساله جديد - '.$res[0]['order_no'],$message,"../orderDetails.php?o=".$order_id);

           //--- snyc
           $sql = "select
                   isfrom ,
                   clients.sync_token as token,
                   clients.sync_dns as dns,
                   orders.id as id,
                   orders.remote_id as remote_id
                   from orders
                   inner join clients on clients.id = orders.client_id
                   where orders.id=?";
           $order = getData($con,$sql,[$order_id]);
           if($order[0]['isfrom'] == 2 && $order[0]['remote_id'] > 1){
             $response = httpPost($order[0]['dns'].'/api/shareMessageToClinet.php',
                  [
                   'token'=>$order[0]['token'],
                   'message'=>$message,
                   'barcode'=>$order[0]['id'],
                   'id'=>$order[0]['remote_id'],
              ]);
           }else{
             $sql = "select
                     companies.sync_token as token,
                     companies.dns as dns, orders.id as id,
                     orders.bar_code as bar_code
                     from orders
                     left join companies on orders.delivery_company_id = companies.id
                     where orders.id=?";
             $order = getData($con,$sql,[$order_id]);
             $response = httpPost($order[0]['dns'].'/api/shareMessageToCompany.php',
                  [
                   'token'=>$order[0]['token'],
                   'message'=>$message,
                   'remote_id'=>$order[0]['id'],
                   'id'=>$order[0]['bar_code'],
              ]);
           }
    }catch(PDOException $ex) {
       $error=["error"=>$ex];
       $success="0";
    }
  }

}else{
  $error = [
           'message'=> implode($v->errors()->get('message')),
           'order_id'=>implode($v->errors()->get('order_id')),
           ];
}
echo json_encode(['success'=>$success,'error'=>$error,'response'=>$response]);
?>