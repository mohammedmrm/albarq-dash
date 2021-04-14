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
            sum(invoice.dev_price) as dev_price,
            sum(total) as total,
            sum(if(invoice.confirm = 1,0,(total-invoice.dev_price))) as paid,
            count(*) as invoices
            from invoice
            inner join stores on stores.id = invoice.store_id
            inner join clients on clients.id= stores.client_id
            where invoice.date between "'.$start.'" and "'.$end.'"
            and invoice.staff_id ='.$inserter;
  $total = getData($con,$sql);
  $query = "update invoice
           inner join stores on stores.id = invoice.store_id
           inner join clients on stores.client_id = clients.id
           left join staff on staff.id = invoice.staff_id
           set confirm=1
           ";

    function validateDate($date, $format = 'Y-m-d')
    {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) == $date;
    }
    if(validateDate($start) && validateDate($end)){
      $filter = "where invoice.date between '".$start."' AND '".$end."'";
    }

    if($client >= 1){
       $filter .= " and stores.client_id =".$client;
    }
    if($branch >= 1){
       $filter .= " and clients.branch_id =".$branch;
    }
    if($store >= 1){
       $filter .= " and invoice.store_id =".$store;
    }
    if($inserter >= 1){
       $filter .= " and invoice.staff_id =".$inserter;
    }
    $query .=  $filter;
    $data = setData($con,$query);
    if($data > 0){
      $sql = "insert into accounter_history (staff_id,invoices,paid) values (?,?,?)";
      setData($con,$sql,[$inserter,$total[0]['invoices'],$total[0]['paid']]);
    }
    $success="1";
} catch(PDOException $ex) {
   $data=["error"=>$ex];
   $success="0";
}
echo (json_encode(array($query,"success"=>$success,"data"=>$data)));
?>