<?php
session_start();
error_reporting(0);
header('Content-Type: application/json');
require_once("_access.php");
require_once("../config.php");
access([1,2,3,5,7,8,9]);
require_once("dbconnection.php");
$store= $_REQUEST['store'];
$status = $_REQUEST['orderStatus'];
$start = trim($_REQUEST['start']);
$end = trim($_REQUEST['end']);
$driver = trim($_REQUEST['driver']);
$limit = 1000;
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
  $filter = " and orders.confirm = 7";

  if($client >= 1){
    $filter .= " and orders.client_id=".$client;
  }
  if($store >= 1){
    $filter .= " and orders.store_id=".$store;
  }
  $filter .= " and orders.driver_id=".$driver;

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
    $query .= " ".$filter;
  }

  $query .= ' order by orders.date DESC limit '.$limit;
  $data = getData($con,$query);
  $success="1";
} catch(PDOException $ex) {
   $data=["error"=>$ex];
   $success="0";
}
echo (json_encode(array($query,"success"=>$success,"data"=>$data,'pages'=>$pages,'page'=>$page+1,'orders'=>$ps[0]['count'])));
?>