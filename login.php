<?php
include "mail.php";

$redis = new Redis();
$f = $redis->connect('127.0.0.1');
$data = $_POST;
$key = "user:".$data['username'];
$bool = $redis->exists($key);
if (!$bool){
    var_dump(false);die;
}
//获取用户信息
$user = $redis->hGetAll($key);
if ($user['password'] != $data['password']){
    die;
}
session_start();
$_SESSION['user'] = $key;
$listKey = "sendmaillist";
$redis->lPush($listKey,$user['email']);
//header('location:list.php');
//当登录成功时发送邮箱

$mail = $redis->lPop($listKey);
//var_dump($mail);
$res = new \mail\mail($mail);