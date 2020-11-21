<?php
session_start();
error_reporting(0);
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
require_once("_apiAccess2.php");
access();
require_once("../script/dbconnection.php");
require_once("../config.php");

use Violin\Violin;
require_once('../validator/autoload.php');
$v = new Violin;

$error = [];
$success = 0;

$barcode = $_REQUEST['barcode'];
$id = $_REQUEST['id'];
$confirm = $_REQUEST['confirm'];

$v->validate([
    'barcode'   => [$barcode,  'required|int'],
    'id' => [$id,'required|int'],
    'confirm'   => [$confirm,  'required|int'],
]);

$response = [];
$sql ="select * from orders where id = ?";
$order = setData($con,$sql,[$id]);
if($v->passes()) {
  try{
    $sql   = 'update orders set remote_confirm='.$confirm.' where id ='.$id.' and bar_code='.$barcode;
    $result = setData($con,$sql);
  if($result > 0){
    $success = 1;
  }
  }catch(PDOException $ex) {
   $error=["error"=>$ex];
   $success="0";
}
}else{
$error = [
           'barcode'=>implode($v->errors()->get('barcode')),
           'id'=>implode($v->errors()->get('id')),
           'confirm'=>implode($v->errors()->get('confirm')),
           ];
}
echo json_encode([$_REQUEST,'success'=>$success, 'error'=>$error]);
?>