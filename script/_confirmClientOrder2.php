<?php
session_start();
error_reporting(0);
header('Content-Type: application/json');
require_once("_access.php");
require_once("_httpRequest.php");
access([1,5,2,7,8]);
$id= trim($_REQUEST['id']);
$store= trim($_REQUEST['store']);
$order_no= trim($_REQUEST['order_no']);
$customer_phone= trim($_REQUEST['customer_phone']);
$success = 0;
$msg="";
require_once("dbconnection.php");
use Violin\Violin;
require_once('../validator/autoload.php');
$v = new Violin;

$v->validate([
    'order_id'    => [$id,'required|int'],
    'store'       => [$store,'required|int']
    ]);

if($v->passes()){
         try {
         $sql = "select * from stores where id=?";
         $st= getData($con,$sql,$store);
         $client = $st[0]["client_id"];
         $sql = "update orders set confirm=1,store_id=?,client_id=?,manager_id=?,date=? where id = ? and confirm=5
         and ".$order_no." not in (select order_no from orders where store_id='".$store."' and customer_phone='".$customer_phone."' and order_no=".$order_no." and confirm=1) as  as or2";
         $result = setData($con,$sql,[$store,$client,$_SESSION['userid'],date("Y-m-d"),$id]);
         if($result > 0){
            $success = 1;
            $sql = "insert into tracking (order_id,order_status_id,note,staff_id) values(?,?,?,?)";
            setData($con,$sql,[$id,1,"تأكيد الطلب",$_SESSION['userid']]);
         }else{
            $msg = "فشل التأكيد, قد يكون مؤكد مسبقاً";
         }
         } catch(PDOException $ex) {
           $data=["error"=>$ex];
           $success="0";
        }
}else{
  $msg = "فشل التأكيد";
  $success = 0;
}
echo json_encode([$_REQUEST,$_SESSION['user_details']['branch_id'],'success'=>$success, 'msg'=>$msg,'response'=>$response]);
?>