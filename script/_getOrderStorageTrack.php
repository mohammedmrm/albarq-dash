<?php
session_start();
header('Content-Type: application/json');
require_once("_access.php");
access([1,2,3,5,7,8,9,15]);
require_once("dbconnection.php");
$id= $_REQUEST['id'];
$success=0;
try{
  $query = "select storage_tracking.*,storage.name as storage_name,if(storage_tracking.staff_id=-1,'شركه التوصيل السانده', staff.name) as staff_name,
  DATE_FORMAT(storage_tracking.date,'%Y-%m-%d') as date,DATE_FORMAT(storage_tracking.date,'%H:%i') as hour
  from storage_tracking
  left join staff on storage_tracking.staff_id = staff.id
  left join storage on staff.storage_id = storage.id
  where order_id=".$id." order by storage_tracking.date";
  $data = getData($con,$query);
  if(count($data) > 0){
  $success="1";
  }
} catch(PDOException $ex) {
   $data=["error"=>$ex];
   $success="0";
}
echo json_encode(array("success"=>$success,"data"=>$data));
?>