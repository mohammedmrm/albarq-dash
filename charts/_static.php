<?php
session_start();
//error_reporting(0);
header('Content-Type: application/json');
require_once("../script/_access.php");
require_once("../config.php");
access([1,2,5,3]);
require_once("../script/dbconnection.php");
$start = trim($_REQUEST['start']);
$end = trim($_REQUEST['end']);
$response = [];

$end =date('Y-m-d');
$end  .=" 23:59:59";
$start = date('Y-m-d');
$start2 = date('Y-m-d',strtotime("-7 day"));
$start  .= " 00:00:00";
$start2  .= " 00:00:00";

if($_SESSION['user_details']['role_id'] == 1){
$sql = "SELECT
          count(*) as  total
          FROM orders
          where orders.confirm = 1 and date between '".$start."' and '".$end."'";

$prices= "SELECT
          sum( if((order_status_id=4 or order_status_id=6 or order_status_id=5) and (driver_invoice_id = 0 or driver_invoice_id is null),
                   orders.new_price,0)
             ) as withdriver,
          sum(
              if((order_status_id = 4 or order_status_id = 5 or order_status_id = 6) and invoice_id = 0,
               new_price -
               (
                   if(to_city = 1,
                         if(client_dev_price.price is null,(".$config['dev_b']." - discount),(client_dev_price.price - discount)),
                         if(client_dev_price.price is null,(".$config['dev_o']." - discount),(client_dev_price.price - discount))
                    )
                 + if(new_price > 500000 ,( (ceil(new_price/500000)-1) * ".$config['addOnOver500']." ),0)
                 + if(weight > 1 ,( (weight-1) * ".$config['weightPrice']." ),0)
              ),0)
           ) as withcompany
          FROM orders
          left JOIN client_dev_price on client_dev_price.client_id = orders.client_id AND client_dev_price.city_id = orders.to_city
          left join towns on  towns.id = orders.to_town
          inner join branches on branches.id = orders.from_branch
          where orders.confirm = 1";
$dev= "SELECT
           sum(
            if(to_city = 1,
                 if(orders.order_status_id=9,0,if(client_dev_price.price is null,(".$config['dev_b']." - discount),(client_dev_price.price - discount))),
                 if(orders.order_status_id=9,0,if(client_dev_price.price is null,(".$config['dev_o']." - discount),(client_dev_price.price - discount)))
              )
            + if(new_price > 500000 ,( (ceil(new_price/500000)-1) * ".$config['addOnOver500']." ),0)
            + if(weight > 1 ,( (weight-1) * ".$config['weightPrice']." ),0)
            + if(towns.center = 0 ,".$config['countrysidePrice'].",0)
            )
           as dev_price
          FROM orders
          left JOIN client_dev_price on client_dev_price.client_id = orders.client_id AND client_dev_price.city_id = orders.to_city
          inner join branches on branches.id = orders.from_branch
          left join towns on  towns.id = orders.to_town
          where orders.confirm = 1 and date between '".$start2."' and '".$end."'";
}else{
$sql = "SELECT
          count(*) as  total
          FROM orders
          where orders.confirm = 1 and (date between '".$start."' and '".$end."') and from_branch = '".$_SESSION['user_details']['branch_id']."'
          ";
$prices =  "SELECT
          sum( if((order_status_id=4 or order_status_id=6 or order_status_id=5) and (driver_invoice_id = 0 or driver_invoice_id is null),
                   orders.new_price,0)
             ) as withdriver,
          sum(
              if((order_status_id = 4 or order_status_id = 5 or order_status_id = 6) and invoice_id = 0,
               new_price -
               (
                   if(to_city = 1,
                         if(client_dev_price.price is null,(".$config['dev_b']." - discount),(client_dev_price.price - discount)),
                         if(client_dev_price.price is null,(".$config['dev_o']." - discount),(client_dev_price.price - discount))
                    )
                 + if(new_price > 500000 ,( (ceil(new_price/500000)-1) * ".$config['addOnOver500']." ),0)
                 + if(weight > 1 ,( (weight-1) * ".$config['weightPrice']." ),0)
              ),0)
           ) as withcompany
          FROM orders
          left JOIN client_dev_price on client_dev_price.client_id = orders.client_id AND client_dev_price.city_id = orders.to_city
          inner join branches on branches.id = orders.from_branch
          left join towns on  towns.id = orders.to_town

          where orders.confirm = 1 and from_branch = '".$_SESSION['user_details']['branch_id']."'
          ";
$dev= "SELECT
          sum(if(to_city = 1,
                 if(orders.order_status_id=9,0,if(client_dev_price.price is null,(".$config['dev_b']." - discount),(client_dev_price.price - discount))),
                 if(orders.order_status_id=9,0,if(client_dev_price.price is null,(".$config['dev_o']." - discount),(client_dev_price.price - discount)))
              )
            + if(new_price > 500000 ,( (ceil(new_price/500000)-1) * ".$config['addOnOver500']." ),0)
            + if(weight > 1 ,( (weight-1) * ".$config['weightPrice']." ),0)
            + if(towns.center = 0 ,".$config['countrysidePrice'].",0)
            )as dev_price
          FROM orders
          left JOIN client_dev_price on client_dev_price.client_id = orders.client_id AND client_dev_price.city_id = orders.to_city
          left join towns on  towns.id = orders.to_town
          inner join branches on branches.id = orders.from_branch
          where orders.confirm = 1 and date between '".$start2."' and '".$end."'  and from_branch = '".$_SESSION['user_details']['branch_id']."'";
}
$total = getData($con,$sql);
$price = getData($con,$prices);
$devprice = getData($con,$dev);
$total[0]['withdriver'] = $price[0]['withdriver'];
$total[0]['withcompany']= $price[0]['withcompany'];
$total[0]['dev_price']= $devprice[0]['dev_price'];


echo json_encode(['orders'=>$total,$prices]);
?>