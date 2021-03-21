<?php
session_start();
header('Content-Type: application/json');
error_reporting(0);
require_once("_access.php");
access([1,2,5,8]);
$driver = $_REQUEST['driver'];
$start = $_REQUEST['start'];
$end = $_REQUEST['end'];
$inserter= $_REQUEST['inserter'];
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
  $query = "select driver_invoice.*,date_format(driver_invoice.date,'%Y-%m-%d') as in_date,
           staff.name as staff_name,
           driver.name as driver_name,
           driver.phone as driver_phone
           from driver_invoice
           left join staff driver on driver.id = driver_invoice.driver_id
           left join staff on staff.id = driver_invoice.staff_id
           ";

    function validateDate($date, $format = 'Y-m-d')
    {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) == $date;
    }
    if(validateDate($start) && validateDate($end)){
      $filter = "where driver_invoice.date between '".$start."' AND '".$end."'";
    }

    if($driver >= 1){
       $filter .= " and driver_invoice.driver_id =".$driver;
    }
    if($inserter >= 1){
       $filter .= " and driver_invoice.staff_id =".$inserter;
    }
    $query .=  $filter;
    $query .=  " order by driver_invoice.date DESC";
///--------------prices ------------
    $sql = 'select
            sum(driver_invoice.driver_price) as driver_price,
            sum(total) as total,
            sum(if(driver_invoice.confirm = 1,0,(total-driver_invoice.driver_price))) as with_accounter,
            count(*) as invoices
            from driver_invoice
            left join staff driver on driver.id = driver_invoice.staff_id
            left join staff on staff.id = driver_invoice.staff_id
            where driver_invoice.date between "'.$start.'" and "'.$end.'"
            ';

          if($driver >= 1){
             $sql .= " and driver_invoice.driver_id =".$driver;
          }
          if($inserter >= 1){
             $sql .= " and driver_invoice.staff_id =".$inserter;
          }

$total[0] =[
 'invoices'=>0,
 'driver_price'=>0,
 'total'=>0,
 'with_accounter'=>0,
];
if($_SESSION['role'] == 1){
 $total=getData($con,$sql);
}
    $data = getData($con,$query);
    $success="1";
} catch(PDOException $ex) {
   $data=["error"=>$ex];
   $success="0";
}
echo (json_encode(array($query,"success"=>$success,"data"=>$data,"total"=>$total)));
?>