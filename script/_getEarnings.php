<?php
session_start();
error_reporting(0);
header('Content-Type: application/json');
require_once("../script/_access.php");
access([1,2,5,3]);
require_once("../script/dbconnection.php");
require_once("../config.php");
$start = trim($_REQUEST['start']);
$end = trim($_REQUEST['end']);
if(empty($end)) {
  $end = date('Y-m-d 23:59:59');
}else{
   $end .=" 23:59:59";
}
if(empty($start)) {
  $start = date('Y-m-d 00:00:00',strtotime($start. ' - 7 day'));
}else{
   $start .=" 00:00:00";
}
try{
if($_SESSION['user_details']['role_id'] == 1){
  $sql = 'select
            sum(
               if(order_status_id = 4 or order_status_id = 5 or order_status_id = 6,
                if(to_city = 1,
                 if(order_status_id=9,0,if(client_dev_price.price is null,('.$config['dev_b'].' - discount),(client_dev_price.price - discount))),
                 if(order_status_id=9,0,if(client_dev_price.price is null,('.$config['dev_o'].' - discount),(client_dev_price.price - discount)))
                ),0)
             ) as earnings,
             sum(
                 if(order_status_id = 4 or order_status_id = 5 or order_status_id = 6,
                   new_price -
                   (
                       if(to_city = 1,
                         if(order_status_id=9,0,if(client_dev_price.price is null,('.$config['dev_b'].' - discount),(client_dev_price.price - discount))),
                         if(order_status_id=9,0,if(client_dev_price.price is null,('.$config['dev_o'].' - discount),(client_dev_price.price - discount)))
                        )
                   ),0
                )
             ) as client_price,
             sum(
                 if((order_status_id = 4 or order_status_id = 5 or order_status_id = 6) and invoice_id=0,
                   new_price -
                   (
                       if(to_city = 1,
                         if(order_status_id=9,0,if(client_dev_price.price is null,('.$config['dev_b'].' - discount),(client_dev_price.price - discount))),
                         if(order_status_id=9,0,if(client_dev_price.price is null,('.$config['dev_o'].' - discount),(client_dev_price.price - discount)))
                        )
                   ),0
                )
             ) as with_company,min(a.balance) as balance,
             sum(if(order_status_id = 4 or order_status_id = 5 or order_status_id = 6,new_price,0)) as income,
             sum(if(order_status_id=9,0,discount)) as discount,
             count(orders.id) as orders,
            max(clients.name) as name,
            max(clients.phone) as phone,
            max(branches.name) as branch_name
            from orders
            left join clients on clients.id = orders.client_id
            left join branches on  branches.id = clients.branch_id
            left JOIN client_dev_price
            on client_dev_price.client_id = orders.client_id AND client_dev_price.city_id = orders.to_city
            left join (
                      SELECT sum(if(type = 1,(price),0)) as total,sum(if(type = 1,price,-price)) as balance, client_id
                      from loans GROUP by client_id
            ) a on a.client_id = orders.client_id
            where date between "'.$start.'" and "'.$end.'"
            and orders.confirm = 1 ';

}else{
  $sql = 'select
            sum(
                 if(order_status_id = 4 or order_status_id = 5 or order_status_id = 6,
                     if(to_city = 1,
                           if(order_status_id=9,0,if(client_dev_price.price is null,('.$config['dev_b'].' - discount),(client_dev_price.price - discount))),
                           if(order_status_id=9,0,if(client_dev_price.price is null,('.$config['dev_o'].' - discount),(client_dev_price.price - discount)))
                      ),0
                  )
             ) as earnings,
             sum(
                if(order_status_id = 4 or order_status_id = 5 or order_status_id = 6,
                 new_price -
                 (
                     if(to_city = 1,
                           if(order_status_id=9,0,if(client_dev_price.price is null,('.$config['dev_b'].' - discount),(client_dev_price.price - discount))),
                           if(order_status_id=9,0,if(client_dev_price.price is null,('.$config['dev_o'].' - discount),(client_dev_price.price - discount)))
                      )
                ),0)
             ) as client_price,
             sum(
                if((order_status_id = 4 or order_status_id = 5 or order_status_id = 6) and invoice_id=0,
                 new_price -
                 (
                     if(to_city = 1,
                           if(order_status_id=9,0,if(client_dev_price.price is null,('.$config['dev_b'].' - discount),(client_dev_price.price - discount))),
                           if(order_status_id=9,0,if(client_dev_price.price is null,('.$config['dev_o'].' - discount),(client_dev_price.price - discount)))
                      )
                ),0)
             ) as with_company,min(a.balance) as balance,
            sum(if(order_status_id = 4 or order_status_id = 5 or order_status_id = 6,new_price,0)) as income,
            sum(if(order_status_id=9,0,discount)) as discount,
            count(orders.id) as orders,
            max(clients.name) as name,
            max(clients.phone) as phone,
            max(branches.name) as branch_name
            from orders
            left join clients on clients.id = orders.client_id
            left join branches on  branches.id = clients.branch_id
            left JOIN client_dev_price
            on client_dev_price.client_id = orders.client_id AND client_dev_price.city_id = orders.to_city
            left join (
                      SELECT sum(if(type = 1,(price),0)) as total,sum(if(type = 1,price,-price)) as balance, client_id
                      from loans GROUP by client_id
            ) a on a.client_id = orders.client_id
            where branch_id ="'.$_SESSION['user_details']['branch_id'].'" and orders.confirm = 1 and date between "'.$start.'" and "'.$end.'"
            ';

}
$sqlw = $sql;
$sql1 = $sql."  GROUP by  orders.client_id";
$data =  getData($con,$sql1);
$total=  getData($con,$sql);
$sql = 'SELECT sum(if(type = 1,price,-price)) as balance, client_id
               from loans
               left join clients on clients.id = loans.client_id
               where clients.branch_id ="'.$_SESSION['user_details']['branch_id'].'" and date between "'.$start.'" and "'.$end.'"
               ';
$loans =  getData($con,$sql);
$total[0]['total_blance'] = $loans[0]['balance'];
$sql2 = 'SELECT sum(price) as pays FROM `pays` where date between "'.$start.'" and "'.$end.'"';
$pay=  getData($con,$sql2);
$total[0]['pays'] = $pay[0]['pays'];

$sql = 'select sum(new_price) as with_driver from orders
where date between "'.$start.'" and "'.$end.'" and driver_invoice_id=0
and (order_status_id = 4 or order_status_id=5 or order_status_id=6) and orders.confirm=1';

$withdriver = getData($con,$sql);
$total[0]['with_driver'] = $withdriver[0]['with_driver'];
$total[0]['start'] = date('Y-m-d', strtotime($start));
$total[0]['end'] = date('Y-m-d', strtotime($end));
}catch (PDOException $ex) {
 $data = [$ex,'error'];
}
echo json_encode([$sql,'data'=>$data,"total"=>$total]);
?>