<?php
ob_start();
session_start();
header("Access-Control-Allow-Headers");
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
error_reporting(0);
require_once("_apiAccess.php");
access();
$data=["No Data"];
$success="0";
$token = $_REQUEST['token'];
$orders = $_REQUEST['bar_codes'];
  if(count($orders) > 0){
    if(count($orders) > 0){
        $a = 0;
        foreach($orders as $id){
          if($a==0){
             $f = " orders.id =".$id;
          }else{
            $f .= " or orders.id =".$id;
          }
          $a++;
       }
       $f = " and ( ".$f." )";
    }
  require_once("../script/dbconnection.php");
  try{
    $query = "select
     orders.id as bar_code,
     confirm,
     order_status_id as status,
     price,
     new_price as received_price,
     discount,
     staff.name as driver_name,
     staff.phone as driver_phone
    from orders
    left join staff on staff.id = orders.driver_id
    where client_id='".$clinetdata['id']."'  ".$f;
    $data = getData($con,$query);
    $success="1";
  } catch(PDOException $ex) {
     $data=["error"=>$ex];
     $success="0";
  }
}
ob_end_clean();
echo json_encode(["success"=>$success,"data"=>$data,'messgae'=>""]);
?>