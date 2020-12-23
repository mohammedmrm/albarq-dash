<?php
session_start();
error_reporting(0);
header('Content-Type: application/json');
require_once("_access.php");
access([1,2,3,4,5,6,7,8,9,10,11,12]);
require_once("dbconnection.php");
require_once("../config.php");

$branch = $_REQUEST['branch'];
$to_branch = $_REQUEST['to_branch'];
$city = $_REQUEST['city'];
$town= $_REQUEST['town'];
$customer = $_REQUEST['customer'];
$order = $_REQUEST['order_no'];
$store= $_REQUEST['store'];
$invoice= $_REQUEST['invoice'];
$driver_invoice= $_REQUEST['driver_invoice'];
$status = $_REQUEST['orderStatus'];
$storageStatus = $_REQUEST['storageStatus'];
$callcenter = $_REQUEST['callcenter'];
$driver = $_REQUEST['driver'];
$repated = $_REQUEST['repated'];
$confirm = $_REQUEST['confirm'];
$start = trim($_REQUEST['start']);
$end = trim($_REQUEST['end']);
$limit = trim($_REQUEST['limit']);
if(empty($limit)){
  $limit = 10;
}
$sort ="";
$page = trim($_REQUEST['p']);
if(empty($page) || $page <=0){
  $page =1;
}
$total = [];
$money_status = trim($_REQUEST['money_status']);
if(empty($end)) {
$end =date('Y-m-d', strtotime(' + 1 day'));
}
try{

  $count = "select count(*) as count from orders2";

  $query = "select orders2.*, cites.name as city,order_status.status as status_name from orders2
            left join cites on  cites.id = orders2.to_city
            left join order_status on  order_status.id = orders2.order_status_id
            ";

  $where ="where ";
  if($driver >= 1){
   $filter .= " and orders2.driver_id =".$driver;
  }
  $sort = " order by orders2.date DESC ";

  if($city >= 1){
    $filter .= " and to_city=".$city;
  }

  if($invoice == 1){
    $filter .= " and orders2.invoice_id =0";
  }else if($invoice == 2){
    $filter .= " and orders2.invoice_id =1";
  }
  //--------
  if($driver_invoice == 1){
    $filter .= " and orders2.driver_invoice_id =0 ";
  }else if($driver_invoice == 2){
    $filter .= " and orders2.driver_invoice_id =1 ";
  }
  if(!empty($customer)){
    $filter .= " and (customer_phone like '%".$customer."%')";
  }
  if(!empty($order)){
    $filter .= " and orders2.order_no = '".$order."'";
  }
  ///-----------------status
  $s = "";
  if(count($status) > 0){
    foreach($status as $stat){
      if($stat > 0){
        $s .= " or orders2.order_status_id=".$stat;
      }
    }
  }
  $s = preg_replace('/^ or/', '', $s);
   if($s != ""){
    $s = " and (".$s." )";
    $filter .= $s;
  }
  //---------------------end of status
  function validateDate($date, $format = 'Y-m-d')
    {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) == $date;
    }
  if(validateDate($start) && validateDate($end)){
      $filter .= " and orders2.date between '".$start."' AND '".$end."'";
     }
  if($filter != ""){
    $filter = preg_replace('/^ and/', '', $filter);
    $filter = $where." ".$filter;
    $count .= " ".$filter;
    $query .= " ".$filter;
  }

  $count = getData($con,$count);
  $orders2 = $count[0]['count'];
  $pages= ceil($count[0]['count'] / $limit);
  $lim = " limit ".(($page-1) * $limit).",".$limit;

  $query .= $sort.$lim;
  $data = getData($con,$query);
  $success="1";
} catch(PDOException $ex) {
   $data=["error"=>$ex];
   $success="0";
}
try{

 $sqlt = "select
          sum(new_price) as income,

          sum( dev_price ) as dev,

          sum(client_price) as client_price,
          count(orders2.order_no) as orders
          from orders2
          ";

if($filter != ""){
    $filter = preg_replace('/^ and/', '', $filter);
    $sqlt .= " ".$filter;
}
$total = getData($con,$sqlt);
$total[0]['orders2'] = $orders2;
if($store >=1){
 $total[0]['store'] = $data[0]['store_name'];
}else{
 $total[0]['store'] = '<span class="text-danger">لم يتم تحديد صفحة</span>';
}
  $success="1";
} catch(PDOException $ex) {
   $total=["error"=>$ex];
   $success="0";
}
echo json_encode(array($driver_invoice,$query,"success"=>$success,"data"=>$data,'total'=>$total,"pages"=>$pages,"page"=>$page));
?>