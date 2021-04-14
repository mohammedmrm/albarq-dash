<?php
session_start();
header('Content-Type: application/json');
error_reporting(0);
require_once("_access.php");
access([1,2,5,8]);
$start = $_REQUEST['h_start'];
$end = $_REQUEST['h_end'];
$inserter= $_REQUEST['h_inserter'];
if(empty($start)) {
    $start = '1000-10-10';
}
if(empty($end)) {
   $end = date('Y-m-d', strtotime(' + 1 day'));
}else{
   $end = date('Y-m-d', strtotime($end.' + 1 day'));
}
require_once("dbconnection.php");
try{
///--------------prices ------------
    $sql =  'select accounter_history.*, staff.name as name from accounter_history
            left join staff on staff.id = accounter_history.staff_id
            where accounter_history.date between "'.$start.'" and "'.$end.'"';
            if($inserter >= 1){
             $sql .= " and accounter_history.staff_id =".$inserter;
            }

  $data=getData($con,$sql);
  $success=1;
} catch(PDOException $ex) {
   $data=["error"=>$ex];
   $success="0";
}
echo (json_encode(array("success"=>$success,"data"=>$data)));
?>