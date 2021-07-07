<?php
$redis = new Redis();
$redis->connect('127.0.0.1');
$post = $_POST;
$key = "user:".$_POST['username'];
$bool = $redis->exists($key);
$id = $redis->incr($key.":id");
$post['id'] = $id;
if ($bool){
    var_dump("用户已存在");
}else{
    $res = $redis->hMSet($key,$post);
    header("location:login.html");
}
