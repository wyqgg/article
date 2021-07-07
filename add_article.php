<?php
//链接redis
$redis = new Redis();
$redis->connect('127.0.0.1');
//接受参数
$post = $_POST;
if ($_POST){
    //获取自增id
    $key = "article:id";
    $id = $redis->incr($key);
    //文章列表key
    $listKey = 'article:zset:id';
    //文章内容存储在hash类型中
    $hashKey = 'article:id:'.$id;
    $post['id'] = $id;
    $redis->hMSet($hashKey,$post);
    //zAdd($key,$rank,$value); $rank 就是排序位置
    $redis->zAdd($listKey,$id,$id);
    header('location:article.php');
}


