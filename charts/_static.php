<?php
session_start();
error_reporting(0);
header('Content-Type: application/json');
require("../script/_access.php");
access([1,2,5,3]);
require("../script/dbconnection.php");
$start = trim($_REQUEST['start']);
$end = trim($_REQUEST['end']);
$response = [];

$end =date('Y-m-d');
$end  .=" 00:00:00";
$start = date('Y-m-d');
$start  .= " 23:59:59";

if($_SESSION['user_details']['role_id'] == 1){
$sql = "SELECT
          count(*) as  total,
          SUM(IF (order_status_id = '1',1,0)) as  regiserd,
          SUM(IF (order_status_id = '2',1,0)) as  redy,
          SUM(IF (order_status_id = '3',1,0)) as  ontheway,
          SUM(IF (order_status_id = '4',1,0)) as  recieved,
          SUM(IF (order_status_id = '5',1,0)) as  chan,
          SUM(IF (order_status_id = '9',1,0)) as  returnd,
          SUM(IF (order_status_id = '7',1,0)) as  posponded
          FROM orders inner join branches on branches.id = orders.from_branch
          where date between '".$start."' and '".$end."'";
}else{
$sql = "SELECT
          count(*) as  total,
          sum(IF (order_status = '1',1,0)) as  regiserd,
          SUM(IF (order_status = '2',1,0)) as  redy,
          SUM(IF (order_status = '3',1,0)) as  ontheway,
          SUM(IF (order_status = '4',1,0)) as  recieved,
          SUM(IF (order_status = '5',1,0)) as  chan,
          SUM(IF (order_status = '9',1,0)) as  returnd,
          SUM(IF (order_status = '7',1,0)) as  posponded
          FROM orders inner join branches on branches.id = orders.from_branch
          where (date between '".$start."' and '".$end."') and from_branch = '".$_SESSION['user_details']['branch_id']."'
          ";
}
$total = getData($con,$sql);



echo json_encode(['orders'=>$total]);
?>