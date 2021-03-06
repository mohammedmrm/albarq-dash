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
$id    = $_REQUEST['e_storage_id'];
$name    = $_REQUEST['e_storage_name'];
$branch   = $_REQUEST['e_storage_branch'];



$v->addRuleMessage('isPhoneNumber', ' رقم هاتف غير صحيح  ');

$v->addRule('isPhoneNumber', function($value, $input, $args) {
    return   (bool) preg_match("/^[0-9]{10,15}$/",$value);
});
$v->addRuleMessage('unique', 'القيمة المدخلة مستخدمة بالفعل ');


$v->addRuleMessages([
    'required' => 'الحقل مطلوب',
    'int'      => 'فقط الارقام مسموع بها',
    'regex'      => 'فقط الارقام مسموع بها',
    'min'      => 'قصير جداً',
    'max'      => 'مسموح ب {value} رمز كحد اعلى ',
    'email'      => 'البريد الالكتروني غيز صحيح',
]);

$v->validate([
    'branch_id'    => [$id,    'required|int'],
    'branch_name'    => [$name,    'required|min(2)|max(20)'],
    'branch_email'   => [$email,   'email'],
    'branch_phone'   => [$phone,   "required|isPhoneNumber"],
    'branch_manager'   => [$manager,   "required|int"],
    'branch_city'  => [$city,  'required|int']
]);

if($v->passes()) {
  $sql = 'update branches set name = ?, branch_id=? where id=?';
  $result = setData($con,$sql,[$name,$branch]);
  if($result > 0){
    $success = 1;
  }
}else{
  $error = [
           'branch_id_err'=> implode($v->errors()->get('branch_id')),
           'branch_name_err'=> implode($v->errors()->get('branch_name')),
           'branch_email_err'=>implode($v->errors()->get('branch_email')),
           'branch_phone_err'=>implode($v->errors()->get('branch_phone')),
           'branch_manager_err'=>implode($v->errors()->get('branch_manager')),
           'branch_city_err'=>implode($v->errors()->get('branch_city'))
           ];
}
echo json_encode(['success'=>$success, 'error'=>$error]);
