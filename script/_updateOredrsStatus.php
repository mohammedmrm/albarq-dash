<?php
session_start();
error_reporting(0);
header('Content-Type: application/json');
require_once("_access.php");
access([1,2,5,7,8]);
require_once("dbconnection.php");
require_once("_sendNoti.php");

$ids = $_REQUEST['ids'];
$statues = $_REQUEST['statuses'];
$reason = $_REQUEST['reason'];
$new_price = str_replace(',','',$_REQUEST['new_price']);
$success="0";
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
if(isset($_REQUEST['ids'])){
      try{
         $query = "update orders set order_status_id=? where id=? and invoice_id=0 and driver_invoice_id=0 and storage_id=0";
         $query2 = "insert into tracking (order_id,order_status_id,date,staff_id,note) values(?,?,?,?,?)";
         $updateRecord = "update driver_records INNER join orders on orders.id = driver_records.order_id set driver_records.order_status_id = ? where driver_records.driver_id = orders.driver_id and driver_records.order_id = ?";
         $price = "update orders set new_price=? where id=?";
         $i = 0;
         foreach($ids as $v){
           if($statues[$i] >= 1){
             $data = setData($con,$query,[$statues[$i],$v]);
             if($data > 0){
               $note = "";
               if($statues[$i] == 9){
                 $note = $reason[$i];
               }
               setData($con,$query2,[$v,$statues[$i],date('Y-m-d H:i:s'),$_SESSION['userid'],$note]);
               //setData($con,$updateRecord,[$statues[$i],$v]);
               if($statues[$i] == 9){
                 setData($con,$price,[0,$v]);
               }
               ///---sync
               $sql = "select orders.note as note,isfrom ,clients.sync_token as token,clients.sync_dns as dns from orders
                       inner join clients on clients.id = orders.client_id
                       where orders.id=?";
               $order = getData($con,$sql,[$v]);
               if($order[0]['isfrom'] == 2){
                 $response = httpPost($order[0]['dns'].'/api/orderStatusSync.php',
                      [
                       'token'=>$order[0]['token'],
                       'status'=>$statues[$i],
                       'note'=>'',
                       'id'=>$v,
                      ]);
               }
             }
             $success="1";
           }
           if($new_price[$i] >= 0 && $statues[$i] !== 9){
             $new_pricequery = "update orders set new_price=? where id=? and invoice_id=0 and driver_invoice_id=0 and storage_id=0";
             setData($con,$new_pricequery,[$new_price[$i],$v]);
             $success="1";
           }
/*            $sql = "select order_status.status as status, staff.token as s_token, orders.id as id , clients.sync_dns as dns, clients.sync_token as token, orders.isfrom as isfrom, clients.token as c_token from orders inner join staff
            on
            staff.id = orders.manager_id
            or
            staff.id = orders.driver_id
            inner join clients on clients.id = orders.client_id
            inner join order_status on order_status.id = orders.order_status_id
            where orders.id =  ?";
            $res =getData($con,$sql,[$v]);
            sendNotification([$res[0]['s_token'],$res[0]['c_token']],[$order_id],'طلب رقم',$res[0]["status"],"../orderDetails.php?o=".$order_id);
*/
           $i++;
         }
      } catch(PDOException $ex) {
          $data=["error"=>$ex];
          $success="0";
      }
 }else{
  $success="2";
}

echo json_encode([$data,$_REQUEST,"success"=>$success,"data"=>$data,"response"=>json_decode(substr($response, 3)),$response]);
?>