<?php
session_start();
error_reporting(0);
header('Content-Type: application/json');
require_once("_access.php");
access([1,2,3,4,5,6,7,8,9,10,11,12,13]);
require_once("dbconnection.php");
require_once("_crpt.php");

use Violin\Violin;
require_once('../validator/autoload.php');
$v = new Violin;


$success = 0;
$error = [];
$user_id = $_SESSION['userid'];


$sql = 'select count(*) as unseen from notification where for_client = 0 and staff_id = ? and admin_seen=0';
$res = getData($con,$sql,[$user_id]);
$unseen = $res[0]['unseen'];
$sql = 'select *,notification.id as n_id from notification
        left join orders on orders.id = notification.order_id
        where (for_client = 0 and staff_id = ?)
        group by notification.date ORDER BY `notification`.`date` DESC limit 200';
$result = getData($con,$sql,[$user_id]);
$success = 1;
echo json_encode(['notification','success'=>$success,"data"=>$result,'unseen'=>$unseen]);
?>