<?php
session_start();
header('Content-Type: application/json');
require_once("_access.php");
access([1,2,3,4,5,6,7,8,9,10,11,12]);
error_reporting(0); 
require_once("dbconnection.php");
$city = $_REQUEST['city'];
if(empty($city)){
  $city =1;
}
if( is_array($city)){
///-----------------city
  $s = "";
  if(count($city) > 0){
    foreach($city as $cit){
      if($cit > 0){
        $s .= " or city_id=".$cit;
      }
    }
  }
  $s = preg_replace('/^ or/', '', $s);
   if($s != ""){
    $s = "  (".$s." )";
    $filter .= $s;
  }
//---------------------end of city
}else{
  if($city >= 1){
    $filter .= " city_id=".$city;
  }
}
try{
  $query = "select * from towns where ".$filter." order by main DESC";
  $data = getData($con,$query);
  $success="1";
} catch(PDOException $ex) {
   $data=["error"=>$ex,'q'=>$query];
   $success="0";
}
print_r(json_encode(array("success"=>$success,"data"=>$data,'q'=>$query,'P'=>$city)));
?>