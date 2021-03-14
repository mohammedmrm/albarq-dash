<?php
session_start();
header('Content-Type: application/json');
require_once("_access.php");
access([1,2,3,4,5,6,7,8,9,10,11,12]);
require_once("dbconnection.php");
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
  $query = "select count(order_no) as orders,if(max(staff.name) is null,'بدون مندوب', max(staff.name))  as driver_name,
            orders.driver_id as driver_id
            from staff
            right join orders on orders.driver_id = staff.id
            where orders.confirm = 7 and orders.date between '".$start."' and '".$end."' group by orders.driver_id";

  $data = getData($con,$query);
  $success="1";
} catch(PDOException $ex) {
   $data=["error"=>$ex];
   $success="0";
}
print_r(json_encode(array($query,"success"=>$success,"data"=>$data)));
?>