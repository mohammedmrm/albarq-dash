<?php
session_start();
header('Content-Type: application/json');
error_reporting(0);
require_once("_access.php");
access([1,2,5,8]);
$client = $_REQUEST['client'];
$store = $_REQUEST['store'];
$start = $_REQUEST['start'];
$end = $_REQUEST['end'];
$branch= $_REQUEST['branch'];
$inserter= $_REQUEST['inserter'];
$branch_price = !$_REQUEST['branch_price'] ? 0 :$_REQUEST['branch_price'] ;
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

 $sql = 'select
            sum(driver_invoice.driver_price) as driver_price,
            sum(total) as total,
            sum(if(driver_invoice.confirm = 1,0,(total-driver_invoice.driver_price))) as received,
            count(*) as invoices
            from driver_invoice
            left join staff driver on driver.id = driver_invoice.staff_id
            left join staff on staff.id = driver_invoice.staff_id
            where driver_invoice.date between "'.$start.'" and "'.$end.'"
            and driver_invoice.staff_id ='.$inserter;
  $total = getData($con,$sql); 
  $query = "update driver_invoice
           left join staff driver on driver.id = driver_invoice.driver_id
           left join staff on staff.id = driver_invoice.staff_id
           set confirm=1
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
    $data = setData($con,$query);
    if($data > 0){
      $sql = "insert into accounter_history (staff_id,driver_invoices,received) values (?,?,?)";
      setData($con,$sql,[$inserter,$total[0]['invoices'],$total[0]['received']]);
    }
    $success="1";
} catch(PDOException $ex) {
   $data=["error"=>$ex];
   $success="0";
}
echo (json_encode(array($query,"success"=>$success,"data"=>$data)));
?>