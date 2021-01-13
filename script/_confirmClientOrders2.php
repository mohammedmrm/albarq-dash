<?php
session_start();
error_reporting(0);
header('Content-Type: application/json');
require_once("_access.php");
require_once("dbconnection.php");
require_once("_httpRequest.php");
access([1,5,2,7,8]);
$ids= $_REQUEST['ids'];
$stores= $_REQUEST['stores'];
$nos= $_REQUEST['order_no2'];
$customer_phone= $_REQUEST['customer_phone2'];
$success = 0;
$msg="";

if(count($ids)){
      try{
        $i=0;
         foreach($ids as $k=>$v){
           if($v > 0 && $stores[$i] > 0){
               $sql = "update orders set confirm=1,store_id=? , client_id=? , manager_id=?, date=? where id = ? and confirm=5
                       and ".$nos[$i]." not in (select order_no from orders where store_id='".$stores[$i]."' and customer_phone='".$customer_phone[$i]."' and order_no=".$nos[$i]." and confirm=1) as or2";
               $sql2 = "select * from stores where id=?";
               $st= getData($con,$sql,[$stores[$i]]);
               $client = $st[0]["client_id"];
               $data = setData($con,$sql2,[$stores[$i],$client,$_SESSION['userid'],date("Y-m-d"),$v]);
               $success="1";
               if($data == 1){
                $sql3 = "insert into tracking (order_id,order_status_id,note,staff_id) values(?,?,?,?)";
                setData($con,$sql3,[$v,1,"تأكيد الطلب",$_SESSION['userid']]);
               }
           }
           $i++;
         }
      } catch(PDOException $ex) {
         $data=["error"=>$ex];
         $success="0";
      }
}else{
  $msg = "فشل تأكيد الطلبيات";
  $success = 0;
}
echo json_encode([$sql2,$_REQUEST,'success'=>$success,'data'=>$data,'msg'=>$msg]);
?>