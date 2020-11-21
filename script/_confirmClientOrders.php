<?php
session_start();
error_reporting(0);
header('Content-Type: application/json');
require_once("_access.php");
require_once("dbconnection.php");
require_once("_httpRequest.php");
access([1,5,2,7,8]);
$ids= $_REQUEST['ids'];
$success = 0;
$msg="";

if(count($ids)){
      try{
         $sql = "update orders set confirm=1,manager_id=?, date=? where id = ? and confirm=5";
         foreach($ids as $v){
           $data = setData($con,$sql,[$_SESSION['userid'],date("Y-m-d"),$v]);
           $success="1";
           $sql = "insert into tracking (order_id,order_status_id,note,staff_id) values(?,?,?,?)";
           setData($con,$sql,[$v,1,"تأكيد الطلب",$_SESSION['userid']]);

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
           $order = getData($con,$sql,[$v]);
           if($order[0]['isfrom'] == 2){
             $response = httpPost($order[0]['dns'].'/api/updateOrderConfirm.php',
                  [
                   'token'=>$order[0]['token'],
                   'confirm'=>1,
                   'barcode'=>$order[0]['id'],
                   'id'=>$order[0]['remote_id'],
              ]);
           }
         }
      } catch(PDOException $ex) {
         $data=["error"=>$ex];
         $success="0";
      }
}else{
  $msg = "فشل تأكيد الطلبيات";
  $success = 0;
}
echo json_encode(['success'=>$success, 'msg'=>$msg,'response'=>$response]);
?>