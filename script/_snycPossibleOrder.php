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
$company = $_REQUEST['company'];
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
function httpPost($url, $data)
{
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($curl);
    curl_close($curl);
    return $response;
}
try{
  $sql ="select * from companies where id=?";
  $company = getData($con,$sql,[$company])
  $query = "select orders.id as order_id, order_no,customer_phone, to_city as city, date
            from orders ";
  $where = "where bar_code > 0 and ";
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
    $query .= " ".$filter;
  }
  if($page != 0){
    $page = $page -1;
  }
  $query .= ' order by orders.date DESC limit '.($page * $limit).",".$limit;
  $data = getData($con,$query);
   $COUNT = 0;
   $response = httpPost($val['dns'].'api/syncPossibleOrder.php',['token'=>$val['token'],"orders"=>$data]);
   $response = json_decode($response,true);
   if($response["success"] == 1){
      foreach($response['data'] as $order){
        $sql = "update orders set id=LAST_INSERT_ID(id),
                                  order_status_id=? ,
                                  remote_confirm=? ,
                                  remote_driver_phone=?,
                                  new_price = ?
                                  where to_city=? and customer_phone=? and order_no=?";
       $res = setDataWithLastID($con,$sql,[$order['status'],$order['confirm'],$order['driver_phone'],$order['received_price'],$order['city'],$order['customer_phone'],$order['order_no']]);
       if($res > 1){
         $tracking = "insert into tracking (order_id,order_status_id,note,staff_id) values(?,?,?,?)";
         $addTrack = setData($con,$tracking,[$res,$order['status'],'تم تحديث الطلب بالمزامنه !',1]);
         $COUNT++;
       }
      }
    }
  $success="1";
} catch(PDOException $ex) {
   $data=["error"=>$ex];
   $success="0";
}

echo (json_encode(array("r"=>$response,"updated"=>$COUNT,"success"=>$success,"data"=>$data)));
?>