<?php
session_start();
error_reporting(0);
header('Content-Type: application/json');
require_once("_access.php");
access([1,2]);
require_once("dbconnection.php");
require_once("_crpt.php");

use Violin\Violin;
require_once('../validator/autoload.php');
$v = new Violin;


$success = 0;
$error = [];
$id    = $_REQUEST['e_town_id'];
$city  = $_REQUEST['e_town_city'];
$center  = $_REQUEST['e_center'];
$name  = $_REQUEST['e_town_name'];
$v->addRuleMessages([
    'required' => 'الحقل مطلوب',
    'int'      => 'فقط الارقام مسموع بها',
    'regex'      => 'فقط الارقام مسموع بها',
    'min'      => 'قصير جداً',
    'max'      => 'مسموح ب {value} رمز كحد اعلى ',
    'email'      => 'البريد الالكتروني غيز صحيح',
]);

$v->validate([
    'town_name' => [$name,'required|min(2)|max(100)'],
    'town_city' => [$city,'required|int'],
    'town_center' => [$center,'int'],
    'town_id'   => [$id,  'required|int'],
]);
if($center == 1){
    $center = 1;
}else{
   $center = 0;
}
if($v->passes()) {
  $sql = 'update towns set name = ?, city_id=?, center=? where id=?';
  $result = setData($con,$sql,[$name,$city,$center,$id]);
  if($result > 0){
    $success = 1;
  }
}else{
  $error = [
           'town_id_err'    => implode($v->errors()->get('town_id')),
           'town_name_err'  => implode($v->errors()->get('town_name')),
           'town_city_err'  => implode($v->errors()->get('town_city')),
           'town_center_err'=> implode($v->errors()->get('town_center'))
           ];
}
echo json_encode(['success'=>$success, 'error'=>$error,[$name,$city,$id]]);
?>