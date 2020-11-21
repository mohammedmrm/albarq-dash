<?php
session_start();
error_reporting(0);
header('Content-Type: application/json');
require_once("_access.php");
require_once("_httpRequest.php");
access([1,5,2,7,8]);
$id= trim($_REQUEST['id']);
$success = 0;
$msg="";
require_once("dbconnection.php");
use Violin\Violin;
require_once('../validator/autoload.php');
$v = new Violin;

$v->validate([
    'order_id'    => [$id,'required|int']
    ]);

if($v->passes()){
         $sql = "update orders set confirm=1,manager_id=?,date=? where id = ? and confirm=5";
         $result = setData($con,$sql,[$_SESSION['userid'],date("Y-m-d"),$id]);
         if($result > 0){
            $success = 1;
            $sql = "insert into tracking (order_id,order_status_id,note,staff_id) values(?,?,?,?)";
            setData($con,$sql,[$id,1,"تأكيد الطلب",$_SESSION['userid']]);
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

           $order = getData($con,$sql,[$id]);
           if($order[0]['isfrom'] == 2){
             $response = httpPost($order[0]['dns'].'/api/updateOrderConfirm.php',
                  [
                   'token'=>$order[0]['token'],
                   'comfirm'=>1,
                   'barcode'=>$order[0]['id'],
                   'id'=>$order[0]['remote_id'],
              ]);
           }
         }else{
            $msg = "فشل التأكيد, قد يكون مؤكد مسبقاً";
         }
}else{
  $msg = "فشل التأكيد";
  $success = 0;
}
echo json_encode([$sql,$_SESSION['user_details']['branch_id'],'success'=>$success, 'msg'=>$msg,'response'=>$response]);
?>