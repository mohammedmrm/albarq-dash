<?php
session_start();
error_reporting(0);
header('Content-Type: application/json');
require_once("_access.php");
access([1,2,5]);
require_once("dbconnection.php");
$store = $_REQUEST['store1'];
$driver = $_REQUEST['driver_id'];
$driver1 = $_REQUEST['driver1'];
$start = trim($_REQUEST['start']);
$ids = $_REQUEST['ids'];
$end = trim($_REQUEST['end']);
if(!empty($end)) {
   $end .=":59";
}else{
  $end =date('Y-m-d H:i:s');
}
if(!empty($start)) {
   $start .=":00";
}
if($driver1 > 1){
  $dri = ", driver_id=".$driver1;
}else{
  $dri = "";
}
function validateDate($date, $format = 'Y-m-d H:i:s')
{
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) == $date;
}

try{
  $query = "update orders set confirm = 1 ".$dri."
            where orders.driver_id=".$driver." and orders.date between '".$start."' and '".$end."' and confirm=7";
  $filter = "";
  if(count($ids) > 0){
      $a = 0;
      foreach($ids as $id){
        if($a==0){
          $f = " orders.id =".$id;
        }else{
          $f .= " or orders.id =".$id;
        }
        $a++;
     }
     $f = " and ( ".$f." )";
  }
  $filter .= $f;

  if($store >= 1){
    $filter .= " and store_id=".$store;
  }
  //$filter = preg_replace('/^ and/', '', $filter);
  $query .= " ".$filter;
  $data = setData($con,$query);
  if($data >= 1){
     $success="1";
  }else{
    $success="0";
  }

} catch(PDOException $ex) {
   $data=["error"=>$ex];
   $success="0";
}
echo (json_encode(array($_REQUEST,$query,"success"=>$success,"data"=>$data,'driver_id'=>$driver,"re_driver"=>$driver1)));

?>