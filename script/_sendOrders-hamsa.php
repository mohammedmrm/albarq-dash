<?php
session_start();
header('Content-Type: application/json');
//error_reporting(0);
require_once("_access.php");
access([1,2,3,4,5,6]);
require_once("dbconnection.php");
require_once("../config.php");
$company = $_REQUEST['company'];
$store = $_REQUEST['apistore'];
$ids = $_REQUEST['ids'];
if($company > 0){
  $msg ="";
}else{
  $msg = "يجب تحديد شركه التوصيل";
}
$response = 0;
$data=[];
$sql ="select * from companies where id=?";
$res= getData($con,$sql,[$company]);
$f=0;
foreach ($ids as $id){
  if($id > 1){
    $f .= ' or orders.id = '.$id.' ';
  }
}
$f = ' ('.preg_replace('/^ or/', '', $f).') ';

$sql = "select orders.*,
            orders.customer_phone as hp,
            orders.customer_name as name,
            cites.code as state,orders.note as rmk,
            date_format(orders.date,'%Y-%m-%d') as dat,
            orders.price as receiptAmt,
            orders.address as locationDetails,
            orders.order_no as custReceiptNoOri,
            cites.name as city,
            towns.name as town
            from orders
            left join cites on  cites.id = orders.to_city
            left join towns on  towns.id = orders.to_town
            left join clients on  clients.id = orders.client_id
           where orders.confirm = 1 and ".$f." group by orders.id";
$result =getData($con,$sql);
if(count($res) == 1){
    $response = httpPost($res[0]['dns'].'/IntegrationWs/createCases/'.$res[0]['token'],['store'=>$store,'orders'=>$result]);
    $response = json_decode($response, true);
    foreach($response['data'] as $k=>$val){
        if(isset($val['barcode'])){
          $sql = "update orders set bar_code = ?, delivery_company_id=?,remote_driver_phone=?, remote_confirm=0 where id=? ";
          $update = setData($con,$sql,[$val['barcode'],$company,$val['driver_phone'],$val['id']]);
        }
      }
}else{
  $msg = "يجب اختيار شركة التوصيل";
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
echo json_encode([$_REQUEST,"msg"=>$msg,"response"=>$response]);
?>