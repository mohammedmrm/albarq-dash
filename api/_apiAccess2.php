<?php
if(!isset($_SESSION)){
 session_start();
}
header('Content-Type: application/json');
$access = (bool) 0;
require_once("../script/dbconnection.php");
if(!(empty($_REQUEST['token']))){
  $token = $_REQUEST['token'];
}else{
  $token = "000";
}
$sql = 'select * from companies where sync_token=?';
$res  = getData($con,$sql,[$token]);
$clinetdata = $res[0];
if(count($res) == 1){
  $access = (bool) 1;
}
function access(){
  if(!$GLOBALS['access']){
     die(json_encode(['message'=>'refused']));
  }
}
?>