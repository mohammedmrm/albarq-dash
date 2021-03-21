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
///--------------prices ------------
    $sql = 'select
            sum(invoice.dev_price) as dev_price,
            sum(total) as total,
            sum(if(invoice.confirm = 1,0,(total-invoice.dev_price))) as paid,
            count(*) as invoices
            from invoice
            inner join stores on stores.id = invoice.store_id
            inner join clients on clients.id= stores.client_id
            where invoice.date between "'.$start.'" and "'.$end.'"
            ';
           if($inserter >= 1){
             $sql .= " and invoice.staff_id =".$inserter;
            }
///--------------prices ------------
    $sql2 = 'select
            sum(driver_invoice.driver_price) as driver_price,
            sum(total) as total,
            sum(if(driver_invoice.confirm = 1,0,(total-driver_invoice.driver_price))) as received,
            count(*) as invoices
            from driver_invoice
            left join staff driver on driver.id = driver_invoice.staff_id
            left join staff on staff.id = driver_invoice.staff_id
            where driver_invoice.date between "'.$start.'" and "'.$end.'"
            ';
          if($inserter >= 1){
             $sql .= " and driver_invoice.staff_id =".$inserter;
          }



  $total1=getData($con,$sql);
  $total2=getData($con,$sql2);
  $total['paid'] = 0;
  $total['received'] = 0;
  $total['with_accounter'] = 0;
  $total['c_invoices'] = 0;
  $total['d_invoices'] = 0;

  $total['paid'] = $total1[0]['paid'];
  $total['received'] = $total2[0]['received'];
  $total['with_accounter'] = $total2[0]['received'] - $total1[0]['paid'];
  $total['c_invoices'] = $total1[0]['invoices'];
  $total['d_invoices'] = $total2[0]['invoices'];
  $success=1;
} catch(PDOException $ex) {
   $data=["error"=>$ex];
   $success="0";
}
echo (json_encode(array("success"=>$success,"total"=>$total,"clinet"=>$total1,"driver"=>$total2)));
?>