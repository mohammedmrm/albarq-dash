<?php
ob_start();
session_start();
error_reporting(0);
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, X-Requested-With");

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
$id = $_REQUEST['bar_code'];
$number = $_REQUEST['order_no'];
$order_price = $_REQUEST['price'];
$customer_name = $_REQUEST['customer_name'];
$customer_phone = $_REQUEST['customer_phone'];
$city_to = $_REQUEST['city'];
$address = $_REQUEST['address'];
$order_note= $_REQUEST['note'];
$price = $_REQUEST['price'];


$v->validate([
    'id'            => [$id,    'required|int'],
    'order_no'      => [$number,'required|min(1)|max(100)'],
    'order_price'   => [$order_price,"isPrice"],
    'store'         => [$store,  'int'],
    'customer_name' => [$customer_name, 'min(2)|max(100)'],
    'customer_phone'=> [$customer_phone,'isPhoneNumber'],
    'city'          => [$city_to,  'int'],
    'order_note'    => [$order_note,'max(250)'],
    'address'    => [$address,'max(250)'],
]);

$response = [];
$sql ="select * from orders where id = ? and client_id=?";
$order = getData($con,$sql,[$id,$clinetdata['id']]);
if(count($order) == 1){
if($order[0]['confirm'] == 5){
if($v->passes()) {
try{
  if(!empty($city_to)&& $city_to > 0){
    $sql = "select * from towns where city_id=? and main=1 limit 1";
    $town = getData($con,$sql,[$city_to]);
    $town_to = $town[0]['id'];
    $sql = "select * from branch_towns where town_id = ?";
    $getbranch = getData($con,$sql,[$town_to]);
    if(count($getbranch) > 0){
     $to_branch = $getbranch[0]['branch_id'];
    }else{
        $sql = "select * from branch_cities where city_id = ?";
        $getbranch = getData($con,$sql,[$city_to]);
        if(count($getbranch) > 0){
         $to_branch = $getbranch[0]['branch_id'];
        }else{
         $to_branch = 1;
        }
    }
    $sql = "select * from driver_towns left join staff on driver_towns.driver_id = staff.id where town_id = ?";
    $getdriver = getData($con,$sql,[$town_to]);
    if(count($getdriver) > 0){
     $driver = $getdriver[0]['driver_id'];
     $driver_phone = $getdriver[0]['phone'];
    }else{
     $driver_phone = '';
     $driver = 0;
    }
  }


  $sql = 'update orders set order_no="'.$number.'"';
  $up = "";
  if(!empty($city_to) && $city_to > 0){
    $up .= ' , to_city='.$city_to;
  }
  if(!empty($address)){
    $up .= ' , address="'.$address.'"';
  }
  if(!empty($driver) && $driver > 0){
    $up .= ' , driver_id='.$driver;
  }
  if(!empty($town_to) && $town_to > 0){
    $up .= ' , to_town="'.$town_to.'"';
  }
  if(!empty($to_branch) && $to_branch > 0){
    $up .= ' , to_branch="'.$to_branch.'"';
  }
  if(!empty($order_price)){
    $up .= ' , price="'.$price.'"';
  }
  if(!empty($order_price)){
    $up .= ' , new_price="'.$price.'"';
  }
  if(!empty($customer_phone)){
    $up .= ' , customer_phone="'.$customer_phone.'"';
  }
  if(!empty($customer_name)){
    $up .= ' , customer_name="'.$customer_name.'"';
  }
  if(!empty($order_note)){
    $up .= ' , note="'.$order_note.'"';
  }
  $where = " where id =".$id."  and invoice_id=0 and driver_invoice_id=0 and confirm = 5 and client_id=?";
  $sql .= $up.$where;
  $result = setData($con,$sql,[$clinetdata['id']]);
  if($result > 0){
    $success = 1;
  }else{
   $error="Nothing changed";
   $success="0";
  }
  }catch(PDOException $ex) {
   $error="Query Error";
   $success="0";
}
}else{
$error = [
           'bar_code'=> implode($v->errors()->get('id')),
           'order_no'=>implode($v->errors()->get('order_no')),
           'order_price'=>implode($v->errors()->get('order_price')),
           'customer_name'=>implode($v->errors()->get('customer_name')),
           'customer_phone'=>implode($v->errors()->get('customer_phone')),
           'city'=>implode($v->errors()->get('city')),
           'address'=>implode($v->errors()->get('address')),
           'order_note'=>implode($v->errors()->get('order_note')),
           ];
}
}else{
   $error='Cannot be edited It is already confirmed';
   $success="0";
}
}else{
   $error='No premission';
   $success="0";
}
ob_end_clean();
echo json_encode(['success'=>$success, 'error'=>$error]);
?>