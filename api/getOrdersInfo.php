<?php
ob_start();
session_start();
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, X-Requested-With");
error_reporting(0);
require_once("_apiAccess.php");
require_once("../config.php");
access();
$data=["No Data"];
$success="0";
$token = $_REQUEST['token'];
$orders = $_REQUEST['bar_codes'];
  if(count($orders) > 0){
    if(count($orders) > 0){
        $a = 0;
        foreach($orders as $id){
          if($a==0){
             $f = " orders.id =".$id;
          }else{
            $f .= " or orders.id =".$id;
          }
          $a++;
       }
       $f = " and ( ".$f." )";
    }
  require_once("../script/dbconnection.php");
if(count($orders)<= 100){
  try{
    $query = "select
     orders.id as bar_code,
     confirm,
     order_no,
     orders.order_status_id as status,
     orders.price,
     new_price as received_price,
     discount,
     staff.name as driver_name,
     tracking.note as status_note ,
     staff.phone as driver_phone,
      if(to_city = 1,
           if(orders.order_status_id=9,0,if(client_dev_price.price is null,(".$config['dev_b']." - discount),(client_dev_price.price - discount))),
           if(orders.order_status_id=9,0,if(client_dev_price.price is null,(".$config['dev_o']." - discount),(client_dev_price.price - discount)))
        )
      + if(new_price > 500000 ,( (ceil(new_price/500000)-1) * ".$config['addOnOver500']." ),0)
      + if(weight > 1 ,( (weight-1) * ".$config['weightPrice']." ),0)
      + if(towns.center = 0 ,".$config['countrysidePrice'].",0)
      as delivery_price
    from orders
    left join staff on staff.id = orders.driver_id
    left join (
      select max(id) as last_id,order_id from tracking group by order_id
    ) a on a.order_id = orders.id
    left join tracking on a.last_id = tracking.id
    left join towns on  towns.id = orders.to_town
    left JOIN client_dev_price on client_dev_price.client_id = orders.client_id AND client_dev_price.city_id = orders.to_city
    where orders.client_id='".$clinetdata['id']."'  ".$f;
    $data = getData($con,$query);
    $i =0;
    foreach($data as $order){
      $sql = "select order_status_id as status, note,date from tracking where order_id=?";
      $tracking = getData($con,$sql,[$order['bar_code']]);
      $data[$i]['tracking']=$tracking;
      $i++;
    }
    $success="1";
  } catch(PDOException $ex) {
     $data=["error"=>$ex];
     $success="0";
     $message ='Error contact the developer';
  }
}
}else{
     $data=[];
     $success="0";
     $message = "Max orders per request 100";
}
ob_end_clean();
echo json_encode(["success"=>$success,"data"=>$data,'messgae'=>$message]);
?>