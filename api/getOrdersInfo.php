<?php
session_start();
header('Content-Type: application/json');
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
    $query = "select confirm, order_status_id as status from orders where client_id='".$clinetdata['id']."'  ".$f;
    $data = getData($con,$query);
    $success="1";
  } catch(PDOException $ex) {
     $data=["error"=>$ex];
     $success="0";
  }
}
echo json_encode(["success"=>$success,"data"=>$data,'messgae'=>""]);
?>