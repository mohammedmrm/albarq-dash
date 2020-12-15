<?php
session_start();
header('Content-Type: application/json');
error_reporting(0);
require_once("_access.php");
access([1,2,3,4,5,6]);
require_once("dbconnection.php");
require_once("../config.php");
$company = 1;
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
try{
foreach ($ids as $id){
  if($id > 1){
    $f .= ' or orders.id = '.$id.' ';
  }
}
$f = ' ('.preg_replace('/^ or/', '', $f).') ';

$sql = "select
            orders.customer_phone as hp,
            if(orders.customer_name is null or orders.customer_name='','زبون',orders.customer_name) as name,
            cites.code as state,
            orders.note as rmk,
            orders.price as receiptAmt,
            clients.phone  as senderHp,
            CONCAT(orders.address , towns.name)  as locationDetails,
            orders.order_no as custReceiptNoOri,
            stores.name as senderName,
            orders.id as senderSystemCaseId,
            orders.qty as qty,
            towns.name as town,
            'عام' as items,
            if(to_city = 1,
                 if(orders.order_status_id=9,0,if(client_dev_price.price is null,(".$config['dev_b']." - discount),(client_dev_price.price - discount))),
                 if(orders.order_status_id=9,0,if(client_dev_price.price is null,(".$config['dev_o']." - discount),(client_dev_price.price - discount)))
              )
            + if(new_price > 500000 ,( (ceil(new_price/500000)-1) * ".$config['addOnOver500']." ),0)
            + if(weight > 1 ,( (weight-1) * ".$config['weightPrice']." ),0)
            + if(towns.center = 0 ,".$config['countrysidePrice'].",0)
            as shipmentCharge
            from orders
            left join cites on  cites.id = orders.to_city
            left join stores on  stores.id = orders.store_id
            left join towns on  towns.id = orders.to_town
            left join clients on  clients.id = orders.client_id
            left JOIN client_dev_price on client_dev_price.client_id = orders.client_id AND client_dev_price.city_id = orders.to_city
           where orders.confirm = 1 and ".$f." group by orders.id";
$result =getData($con,$sql);
if(count($res) == 1){
    $response = httpPost($res[0]['dns'].'IntegrationWs/ReceiveCasesOtherSystem/SYSBBL/'.$res[0]['token'],
    $result);
    $orders = json_decode($response);
}else{
  $msg = "يجب اختيار شركة التوصيل";
}
foreach ($orders as $id){
  if($id > 1){
    $f .= ' or orders.id = '.$id.' ';
  }
}
$f = ' ('.preg_replace('/^ or/', '', $f).') ';

$sql = "update orders set delivery_company_id=".$company." where ".$f;
setData($con,$sql);
}catch(PDOException $ex) {
   $result=["error"=>$ex];
   $msg ="Query Error";
}
function httpPost($url, $data)
{
    $postdata = json_encode($data);
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $postdata);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array('Accept: application/json','Content-Type: application/json'));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($curl);
    curl_close($curl);
    return $response;
}
echo json_encode([$orders,$result,"msg"=>$msg,"response"=>$response]);
?>