<?php
session_start();
header('Content-Type: application/json');
require_once("_access.php");
access([1,2,3,4,5,6,7,8,9,10,11,12]);
require_once("dbconnection.php");
$branch = $_REQUEST['branch'];
try{
  $query = "select count(order_no) as orders,max(staff.name) as driver_name,
            orders.driver_id as driver_id
            from staff
            left join orders on orders.driver_id = staff.id
            where orders.confirm = 7 group by orders.driver_id";
  $data = getData($con,$query);
  $success="1";
} catch(PDOException $ex) {
   $data=["error"=>$ex];
   $success="0";
}
print_r(json_encode(array("success"=>$success,"data"=>$data)));
?>