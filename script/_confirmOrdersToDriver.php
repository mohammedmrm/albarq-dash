<?php
session_start();
header('Content-Type: application/json');
require_once("_access.php");
access([1,2,3,4,5,6,7,8,9,10,11,12]);
require_once("dbconnection.php");
$driver = $_REQUEST['driver'];
$start = trim($_REQUEST['start']);
$end = trim($_REQUEST['end']);
if(!empty($end)) {
   $end .=":59";
}else{
   $end =date('Y-m-d', strtotime(' + 1 day'));
   $end .=" 23:59:59";
}
if(!empty($start)) {
   $start .=":00";
}
try{
  $query = "update orders set confirm = 1 where confirm=7 and orders.date between '".$start."' and '".$end."'
            and orders.driver_id=?";
  $data = setData($con,$query,[$driver]);
  if($data > 0){
    $success="1";
  }
} catch(PDOException $ex) {
   $data=["error"=>$ex];
   $success="0";
}
print_r(json_encode(array("success"=>$success,"data"=>$data)));
?>