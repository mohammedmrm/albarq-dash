<?php
session_start();
//error_reporting(0);
header('Content-Type: application/json');
require_once("_access.php");
require_once("_httpRequest.php"); 
access([1]);
$id= $_REQUEST['id'];
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
     try{
         $sql = "update orders set confirm = 99 where id=?";
         $result = setData($con,$sql,[$id]);
         if($result > 0){
            $success = 1;
            $sql = "delete from tracking where order_id = ?";
            $result = setData($con,$sql,[$id]);
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
                     'confirm'=>99,
                     'barcode'=>$order[0]['id'],
                     'id'=>$order[0]['remote_id'],
                ]);
             }
         }else{
            $msg = "فشل الحذف";
         }
     }catch(PDOException $ex) {
         $msg=["error"=>$ex];
         $success="0";
      }
}else{
  $msg = "فشل الحذف";
  $success = 0;
}
echo json_encode(['success'=>$success, 'msg'=>$msg]);
?>