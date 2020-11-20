<?php
session_start();
error_reporting(0);
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
require_once("_apiAccess.php");
access();
require_once("../script/dbconnection.php");
require_once("../config.php");

use Violin\Violin;
require_once('../validator/autoload.php');
$v = new Violin;
function validateDate($date, $format = 'Y-m-d')
{
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) == $date;
}

$v->addRuleMessage('isPhoneNumber', 'رقم هاتف غير صحيح ');

$v->addRule('isPhoneNumber', function($value, $input, $args) {
  if(preg_match("/^[0-9]{10,15}$/",$value) || empty($value)){
    $x=(bool) 1;
  }
    return $x;
});

$v->addRuleMessage('isPrice', 'المبلغ غير صحيح');

$v->addRule('isPrice', function($value, $input, $args) {
  if(preg_match("/^(0|\-\d*|\d*)(\.\d{2})?$/",$value)){
    $x=(bool) 1;
  }
  return   $x;
});

$v->addRuleMessage('unique', 'القيمة المدخلة مستخدمة بالفعل ');

$v->addRule('unique', function($value, $input, $args) {
    $value  = trim($value);
    $exists = getData($GLOBALS['con'],"SELECT * FROM orders WHERE order_no ='".$value."' and id <> '".$GLOBALS['id']."'");
    return ! (bool) count($exists);
});
$v->addRuleMessages([
    'required' => 'الحقل مطلوب',
    'int'      => 'فقط الارقام مسموع بها',
    'regex'      => 'فقط الارقام مسموع بها',
    'min'      => 'قصير جداً',
    'max'      => 'تم ادخال بيانات اكثر من الحد المسموح',
    'email'      => 'البريد الالكتروني غيز صحيح',
]);
$error = [];
$success = 0;

$barcode = $_REQUEST['barcode'];
$id = $_REQUEST['remote_id'];
$confirm = $_REQUEST['confirm'];
if(!validateDate($date)){
  $date_err = "تاريخ غير صالح";
}else{
  $date_err = "";
}
if(empty($number)){
  $number = "1";
}
$v->validate([
    'barcode'   => [$barcode,  'required|int'],
    'remote_id' => [$id,'required|int'],
    'confirm'   => [$confirm,  'required|int'],
]);

$response = [];
$sql ="select * from orders where id = ?";
$order = setData($con,$sql,[$id]);
if($v->passes()) {
  try{
    $sql   = 'update orders set remote_confirm="'.$confirm.'"';
    $where = " where id =".$id." and bar_code=".$barcode;
    $sql  .= $where;
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
           'remote_id'=>implode($v->errors()->get('remote_id')),
           'confirm'=>implode($v->errors()->get('confirm')),
           ];
}
echo json_encode([$_REQUEST,'success'=>$success, 'error'=>$error]);
?>