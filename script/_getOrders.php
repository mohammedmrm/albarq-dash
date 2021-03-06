<?php
session_start();
error_reporting(0);
header('Content-Type: application/json');
require_once("_access.php");
require_once("../config.php");
access([1,2,3,5,7,8,9]);
require_once("dbconnection.php");
$branch = $_REQUEST['branch'];
$to_branch = $_REQUEST['to_branch'];
$city = $_REQUEST['city'];
$town = $_REQUEST['town'];
$customer = $_REQUEST['customer'];
$order = $_REQUEST['order_no'];
$client= $_REQUEST['client'];
$store= $_REQUEST['store'];
$status = $_REQUEST['orderStatus'];
$start = trim($_REQUEST['start']);
$end = trim($_REQUEST['end']);
$limit = trim($_REQUEST['limit']);
$page = trim($_REQUEST['p']);
$BOrO  = trim($_REQUEST['BOrO']);
$remote_confirm  = trim($_REQUEST['remote_confirm']);

$assignStatus= trim($_REQUEST['assignStatus']);
$money_status = trim($_REQUEST['money_status']);
if(!empty($end)) {
   $end .=" 23:59:59";
}else{
   $end =date('Y-m-d', strtotime(' + 1 day'));
   $end .=" 23:59:59";
}
if(!empty($start)) {
   $start .=" 00:00:00";
}
try{
  $count = "select count(*) as count from orders ";
  $query = "select orders.*,DATE_FORMAT(orders.date,'%Y-%m-%d') as date,
            clients.name as client_name,clients.phone as client_phone,stores.name as store_name,
            cites.name as city,towns.name as town,branches.name as branch_name,
            if(companies.name is null , '/',companies.name) as dev_comp_name,
            if(to_city = 1,
                 if(order_status_id=9,0,if(client_dev_price.price is null,(".$config['dev_b']." - discount),(client_dev_price.price - discount))),
                 if(order_status_id=9,0,if(client_dev_price.price is null,(".$config['dev_o']." - discount),(client_dev_price.price - discount)))
            )
            + if(new_price > 500000 ,( (ceil(new_price/500000)-1) * ".$config['addOnOver500']." ),0)
            + if(weight > 1 ,( (weight-1) * ".$config['weightPrice']." ),0)
            + if(towns.center = 0 ,".$config['countrysidePrice'].",0)
            as dev_price,
            if(order_status_id=9,0,discount) as discount
            from orders left join
            clients on clients.id = orders.client_id
            left join cites on  cites.id = orders.to_city
            left join towns on  towns.id = orders.to_town
            left join stores on  stores.id = orders.store_id
            left join companies on  companies.id = orders.delivery_company_id
            left join branches on  branches.id = orders.to_branch
            left JOIN client_dev_price on client_dev_price.client_id = orders.client_id AND client_dev_price.city_id = orders.to_city

            ";
  $where = "where ";
  if($_SESSION['role'] != 1 && $_SESSION['role'] != 5){
   $where = "where ((from_branch = '".$_SESSION['user_details']['branch_id']."' or to_branch = '".$_SESSION['user_details']['branch_id']."')
         or (orders.to_city in (select city_id from branch_cities where branch_cities.branch_id ='".$_SESSION['user_details']['branch_id']."'))) and ";
  }
  $filter = " and orders.confirm = 1";
  if($branch >= 1){
   $filter .= " and from_branch =".$branch;
  }
  if($to_branch >= 1){
   $filter .= " and to_branch =".$to_branch;
  }
  if( is_array($city)){
  ///-----------------status
    $s = "";
    if(count($city) > 0){
      foreach($city as $cit){
        if($cit > 0){
          $s .= " or orders.to_city=".$cit;
        }
      }
    }
    $s = preg_replace('/^ or/', '', $s);
     if($s != ""){
      $s = " and (".$s." )";
      $filter .= $s;
    }
  //---------------------end of status
  }else{
    if($city >= 1){
      $filter .= " and to_city=".$city;
    }
  }
  if(!empty($remote_confirm) && $remote_confirm >= 0 && $remote_confirm != 'all'){
    $filter .= " and orders.remote_confirm=".$remote_confirm;
  }
  if(($money_status == 1 || $money_status == 0) && $money_status !=""){
    $filter .= " and money_status='".$money_status."'";
  }
  if($town >= 1){
    $filter .= " and to_town=".$town;
  }  
  if($client >= 1){
    $filter .= " and orders.client_id=".$client;
  }
  if($store >= 1){
    $filter .= " and orders.store_id=".$store;
  }
  if(!empty($customer)){
    $filter .= " and (customer_name like '%".$customer."%' or
                      customer_phone like '%".$customer."%')";
  }
  if(!empty($order)){
    $filter .= " and order_no = '".$order."'";
  }
  if($assignStatus == 1){
     $filter .= " and orders.delivery_company_id = 0";
  }else if($assignStatus == 2){
    $filter .= " and orders.delivery_company_id > 0";
  }
  if($BOrO== 1){
     $filter .= " and orders.to_city = 1";
  }else if($BOrO == 2){
    $filter .= " and orders.to_city > 1";
  }
  //-----------------status
  if($status == 4){
    $filter .= " and (order_status_id =".$status." or order_status_id = 6 or order_status_id = 5)";
  }else if($status == 9){
    $filter .= " and (order_status_id =".$status." or order_status_id =11 or order_status_id = 6 or order_status_id = 5)";
  }else  if($status >= 1){
    $filter .= " and order_status_id =".$status;
  }
  //---------------------end of status

  function validateDate($date, $format = 'Y-m-d H:i:s')
    {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) == $date;
    }
  if(validateDate($start) && validateDate($end)){
      $filter .= " and orders.date between '".$start."' AND '".$end."'";
     }
  if($filter != ""){
    $filter = preg_replace('/^ and/', '', $filter);
    $filter = $where." ".$filter;
    $count .= " ".$filter;
    $query .= " ".$filter;
  }
  if($page != 0){
    $page = $page -1;
  }
  $query .= ' order by orders.date DESC limit '.($page * $limit).",".$limit;
  $data = getData($con,$query);
  $ps = getData($con,$count);
  $pages= ceil($ps[0]['count']/$limit);
  $success="1";
} catch(PDOException $ex) {
   $data=["error"=>$ex];
   $success="0";
}
/*if($success == '1'){
  foreach($data as $k=>$v){
    if($v['with_dev'] == 1){
      $data[$k]['with_dev'] = "نعم";
    }else{
      $data[$k]['with_dev'] = "لا";
    }
    if($v['money_status'] == 1){
      $data[$k]['money_status'] = "مدفوع";
    }else{
      $data[$k]['money_status'] = "غير مدفوع";
    }
  }
}*/
echo (json_encode(array($query,"success"=>$success,"data"=>$data,'pages'=>$pages,'page'=>$page+1,'orders'=>$ps[0]['count'])));
?>