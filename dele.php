<?php
//链接redis
$redis = new Redis();
$redis->connect('127.0.0.1');

$id = $_GET['id'];

//删除有序集合中的内容
$zKey = "article:zset:id";
$redis->zRem($zKey,$id);

//删除hash表中的文章内容
$key = "article:id:".$id;
$redis->hDel($key);
header("location:article.php");