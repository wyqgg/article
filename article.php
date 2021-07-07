<?php
$redis = new Redis();
$redis->connect('127.0.0.1');

//获取有序集合的内容
$key = 'article:zset:id';
$arr = $redis->zRange($key,0,-1);
//根据有序集合的值来获取hash中的文章信息
foreach ($arr as $k=>$v){
    $hkey = 'article:id:'.$v;
    $data[$k]= $redis->hGetAll($hkey);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>文章列表</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css" integrity="sha384-HSMxcRTRxnN+Bdg0JdbxYKrThecOKuH5zCYotlSAcp1+c8xmyTe9GYg1l9a69psu" crossorigin="anonymous">
</head>
<body>
<a href="add_article.html" class="btn btn-success">新增文章</a>
    <?php foreach ($data as $k=>$v): ?>
        <div class="list-group">
                <a href="#" class="list-group-item list-group-item-info">文章id：<?php echo $v['id']?></a>
                <a href="#" class="list-group-item list-group-item-success">文章标题：<?php echo $v['title']?></a>
                <a href="#" class="list-group-item list-group-item-info">文章详情：<?php echo $v['desc']?></a>
                <a href="dele.php?id=<?php echo $v['id']?>" class="btn btn-danger">删除文章</a>
        </div>
    <?php endforeach;?>
</body>
</html>