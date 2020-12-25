<?php
session_start();
error_reporting(0);
header('Content-Type: application/json');
require_once("_access.php");
access([1,2,3,5,6,7,8,9]);
require_once("dbconnection.php");
require_once("_sendNoti.php");
require_once("../config.php");

use Violin\Violin;
require_once('../validator/autoload.php');
$v = new Violin;


$v->addRuleMessage('isPhoneNumber', 'رقم هاتف غير صحيح ');

$v->addRule('isPhoneNumber', function($value, $input, $args) {
  if(preg_match("/^[0-9]{10,15}$/",$value) || empty($value)){
    $x=(bool) 1;
  }
    return $x;
});

$v->addRuleMessage('isPrice', 'المبلغ غير صحيح');

$v->addRule('isPrice', function($value, $input, $args) {
  if(preg_match("/^(0|\d*)(\.\d{2})?$/",$value) && $value > 0){
    $x=(bool) 1;
  }
  return   $x;
});

$v->addRuleMessage('unique', 'رقم الوصل مكرر');

$v->addRule('unique', function($value, $input, $args) {
    $value  = trim($value);
    if($args['0'] == 1){
        $exists = getData($GLOBALS['con'],"SELECT * FROM orders WHERE order_no='".$value."' and orders.confirm <> 99 and date >= DATE_SUB(CURDATE(), INTERVAL 1 DAY)");
      return ! (bool) count($exists);
    }else{
      return (bool) 1;
    }
});
$v->addRuleMessages([
    'required' => 'الحقل مطلوب',
    'int'      => 'فقط الارقام مسموع بها',
    'regex'      => 'فقط الارقام مسموع بها',
    'min'      => 'قصير جداً',
    'max'      => 'تم ادخال بيانات اكثر من الحد المسموح',
    'email'      => 'البريد الالكتروني غيز صحيح',
]);
$tokens = [];

$error = [];
$success = 0;
$manger = $_SESSION['userid'];
$orders = [];
$onumber = $_REQUEST['order_no'];
$order_id = $_REQUEST['order_id'];
$prefix = $_REQUEST['prefix'];
$order_type = "multi";//$_REQUEST['order_type'];
$weight = 1;//$_REQUEST['weight'];
$qty = 1;//$_REQUEST['qty'];
$order_price = str_replace(',','',$_REQUEST['order_price']);
$order_price = str_replace('.','',$order_price);
$store = $_REQUEST['store'];
$client_phone = $_REQUEST['client_phone'];
$company = $_REQUEST['company'];
$apistore = $_REQUEST['apistore'];
$customer_name = "";//$_REQUEST['customer_name'];
$customer_phone =  str_replace('-','',$_REQUEST['customer_phone']);
$customer_phone =  str_replace('_','',$customer_phone);
$city_to = $_REQUEST['city'];
$town_to = $_REQUEST['town'];
$branch_to = 1;//$_REQUEST['branch_to'];
$with_dev = 1;
$order_note= $_REQUEST['order_note'];
$order_address= $_REQUEST['order_address'];
$mainstore= $_REQUEST['mainstore'];
$maincity= $_REQUEST['maincity'];
$mainbranch= 1;//$_REQUEST['mainbranch'];
$by= $_REQUEST['by'];
if(empty($number)){
  $number = "1";
}
$confirm = 4;
if($_SESSION['user_details']['branch_id'] == 1){
  $confirm = 1;
}
$no = 0;
if($_REQUEST['check'] == 1){
  $check = 0;
}else{
  $check = 1;
}

$v->validate([
    'manger'        => [$manger,    'required|int'],
    'order_no'      => [$prefix.$onumber,  'required|int|min(1)|max(100)|unique('.$check.'")'],
    'prefix'        => [$prefix,  'min(1)|max(10)'],
    'order_type'    => [$order_type/*$order_type[$k]*/,    'required|min(3)|max(10)'],
    'weight'        => [$weight,   'required|int'],
    'qty'           => [$qty,'int'],
    'order_price'   => [$order_price,   "isPrice"],
    'store'         => [$store,  'required|int'],
    'customer_phone'=> [$customer_phone,  'required|isPhoneNumber'],
    'city'          => [$city_to,  'required|int'],
    'town'          => [$town_to,  'required|int'],
    'branch_to'     => [$branch_to,  'required|int'],
    'with_dev'      => [$with_dev,  'required'],
    'order_note'    => [$order_note,  'max(250)'],
    'order_address' => [$order_address,'max(250)'],
    'company' => [$company,  'int'],
]);
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
if($v->passes()) {
            $sql = 'select *,clients.id as c_id from stores inner join clients on clients.id = stores.client_id where stores.id=?';
            $res = getData($con,$sql,[$store]);
            $client = $res[0]['c_id'];
            if(count($getbranch) > 0){
              $branch = $res[0]['branch_id'];
            }else{
              $branch = 1;
            }
            $c =0;
           if($money == 1){
              $order_price = '-'.$order_price[$k];
              $order_note = $order_note[$k]. " (تسليم مبلغ)";
            }
            if($company > 0){
              $sql = "select * from driver_towns where town_id = ?";
              $getdriver = getData($con,$sql,[$town_to]);
              if(count($getdriver) > 0){
                  $driver = $getdriver[0]['driver_id'];
              }else{
                $sql = "select * from driver_cities where city_id = ?";
                $getbranch = getData($con,$sql,[$city_to[$k]]);
                if(count($getbranch) > 0){
                 $driver = $getbranch[0]['driver_id'];
                }else{
                 $driver = 0;
                }
              }
            }else{
              $driver = 0;
            }

            $sql = "select * from stores inner join clients on clients.id = stores.client_id where stores.id = ?";
            $getbranch = getData($con,$sql,[$store]);

            //-- get possible to_branch  of the order
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
            $confirm = 1;
            if($_SESSION['user_details']['branch_id'] == $to_branch){
              $confirm = 1;
            }
            if($city_to == 1){
               $dev_price = $config['dev_b'];
            }else{
               $dev_price = $config['dev_o'];
            }
            if(!empty($with_dev)){
               $with_dev = 1;
               $dev_price = 0;
            }
            if(empty($order_address)){
              $order_address = "";
            }
            $new_price = $order_price;

            ///---- add order to db
            $sql = 'insert into orders (driver_id,manager_id,order_no,order_type,weight,qty,
                                          price,dev_price,from_branch,
                                          client_id,client_phone,store_id,customer_name,
                                          customer_phone,to_city,to_town,to_branch,with_dev,note,new_price,address,company_id,confirm)
                                          VALUES
                                          (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)';
              $result = setDataWithLastID($con,$sql,
                               [$driver,$manger,$prefix.$onumber,$order_type,$weight,$qty,
                                $order_price,$dev_price,$branch,
                                $client,$client_phone,$store,$customer_name,
                                $customer_phone,$city_to,$town_to,$to_branch,$with_dev,$order_note,$new_price,$order_address,$company,$confirm]);
                $sqlNote = 'select token from clients where id ='.$client;
                $res = getData($con,$sqlNote,[$client]);
                foreach($res as $k => $val){
                $tokens[] = $val['token'];
                }
                $a =  "insert ";
      //---Start-- this for add order tracking record
      $c++;
      if($result>=1){
        $success =1;
        if($driver == 0){
          $sql = "insert into tracking (order_id,order_status_id,note,staff_id) values(?,?,?,?)";
          $result3 = setData($con,$sql,[$result,1,$order_note,$_SESSION['userid']]);

        }else{
          $sql = "insert into tracking (order_id,order_status_id,note,staff_id) values(?,?,?,?)";
          $result3 = setData($con,$sql,[$result,3,$order_note,$_SESSION['userid']]);
        }
        $orders[] = $result2[0]["id"];
        $response = ["N/A"];
        if($company > 0){
         $sql ="select * from companies where id=?";
         $res= getData($con,$sql,[$company]);
         $sql = "select orders.*,clients.phone as c_phone,to_city as city_id, to_town as town_id, date_format(orders.date,'%Y-%m-%d') as dat,
                  orders.price as price,
                  cites.name as city,
                  towns.name as town
                  from orders
                  left join cites on  cites.id = orders.to_city
                  left join towns on  towns.id = orders.to_town
                  left join clients on  clients.id = orders.client_id
                  where orders.id='".$result."'  group by orders.id";
            $order =getData($con,$sql);
            $response = httpPost($res[0]['dns'].'/api/addOrdersByClient.php',['token'=>$res[0]['token'],'store'=>$apistore,'orders'=>$order]);
            $response1 = $response;
            $response = json_decode($response, true);
            foreach($response['data'] as $k=>$val){
                if(isset($val['barcode'])){
                  $sql = "update orders set bar_code = ?,delivery_company_id=?,remote_driver_phone=?, remote_confirm=0 where id=? ";
                  $update = setData($con,$sql,[$val['barcode'],$company,$val['driver_phone'],$val['id']]);
                }
            }
        }
      }else{
        $success =3;
      }
      //---END-- this for add order tracking record
}else{
$error = [
           'no'=>$no,
           'manger'=> implode($v->errors()->get('manger')),
           'order_no'=>implode($v->errors()->get('order_no')),
           'prefix'=>implode($v->errors()->get('prefix')),
           'order_type'=>implode($v->errors()->get('order_type')),
           'weight'=>implode($v->errors()->get('weight')),
           'qty'=>implode($v->errors()->get('qty')),
           'order_price'=>implode($v->errors()->get('order_price')),
           'branch_from'=>implode($v->errors()->get('mainbranch')),
           'store'=>implode($v->errors()->get('store')),
           'client_phone'=>implode($v->errors()->get('client_phone')),
           'customer_name'=>implode($v->errors()->get('customer_name')),
           'customer_phone'=>implode($v->errors()->get('customer_phone')),
           'city'=>implode($v->errors()->get('city')),
           'town'=>implode($v->errors()->get('town')),
           'branch_to'=>implode($v->errors()->get('branch_to')),
           'with_dev'=>implode($v->errors()->get('with_dev')),
           'order_note'=>implode($v->errors()->get('order_note')),
           'order_address'=>implode($v->errors()->get('order_address'))
           ];
}
echo json_encode([$apistore,'success'=>$success, 'error'=>$error,'response'=>$response]);
?>