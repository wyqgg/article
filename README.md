###### 概述

​		因为需要对文章需要进行排序，所以我们需要用到redis的有序集合zset类型，而对象、数组都是可以使用redis的hash类型存储的，故可以设置将文章的id存在有序集合zset中，将文章的信息存在hash中，当文章新增时，需要先设置一个key来存文章自增的id，然后在每次新增时，先将自增的id存在zset中，来进行排序，然后再讲文章的信息存储在hash中，这样就能实现文章的新增。在删除文章时，我们需要先将zset中的值删除掉，然后将hash中的值删掉。

###### 注册前台代码:regist.html

```php
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>用户注册</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css" integrity="sha384-HSMxcRTRxnN+Bdg0JdbxYKrThecOKuH5zCYotlSAcp1+c8xmyTe9GYg1l9a69psu" crossorigin="anonymous">
</head>
<body>
<body>
<form class="form-horizontal" action="regist.php" method="POST">
    <div class="form-group">
        <label for="inputEmail3" class="col-sm-2 control-label">username</label>
        <div class="col-sm-10">
            <input type="username" name="username" class="form-control" id="inputEmail">
        </div>
    </div>

    <div class="form-group">
        <label for="inputEmail3" class="col-sm-2 control-label">Email</label>
        <div class="col-sm-10">
            <input type="Email" name="email" class="form-control" id="inputEmail3">
        </div>
    </div>

    <div class="form-group">
        <label for="inputPassword3" class="col-sm-2 control-label">Password</label>
        <div class="col-sm-10">
            <input type="password" name="password" class="form-control" id="inputPassword3" placeholder="Password">
        </div>
    </div>

    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
            <a type="submit" href="login.html" class="btn btn-default">Sign in</a>
            <button type="submit" class="btn btn-default">Register</button>
        </div>
    </div>
</form>
</body>
</body>
</html>
```

###### 注册逻辑代码:regist.php

```php
<?php
$redis = new Redis();
//链接redis
$redis->connect('127.0.0.1');
$post = $_POST;
//拼接key
$key = "user:".$_POST['username'];
$bool = $redis->exists($key);
if ($bool){
    var_dump("用户已存在");die;
}
//成功向hash中插入一行数据，完成注册
$res = $redis->hMSet($key,$post);
//跳转到登录页面
header("location:login.html");
```



###### 登录前台代码:login.html

```php
<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <meta charset="UTF-8">
    <title>用户登录</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css" integrity="sha384-HSMxcRTRxnN+Bdg0JdbxYKrThecOKuH5zCYotlSAcp1+c8xmyTe9GYg1l9a69psu" crossorigin="anonymous">
</head>
<body>
<form class="form-horizontal" action="login.php" method="POST">
    <div class="form-group">
        <label for="inputEmail3" class="col-sm-2 control-label">Email</label>
        <div class="col-sm-10">
            <input type="username" name="username" class="form-control" id="inputEmail3">
        </div>
    </div>
    <div class="form-group">
        <label for="inputPassword3" class="col-sm-2 control-label">Password</label>
        <div class="col-sm-10">
            <input type="password" name="password" class="form-control" id="inputPassword3" placeholder="Password">
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
            <button type="submit" class="btn btn-default">Sign in</button>
            <a type="button" href="regist.html" class="btn btn-default">Register</a>
        </div>
    </div>
</form>
</body>
</html>
```

###### 登录逻辑代码:login.php

```php
<?php
include "mail.php";
//实例化redis，这里必须开启php的redis扩展,不然会报错
$redis = new Redis();
$f = $redis->connect('127.0.0.1');
$data = $_POST;
//设置用户信息存储的key
$key = "user:".$data['username'];
$bool = $redis->exists($key);
if (!$bool){
    var_dump(false);die;
}
//从redis的Hash中获取用户信息
$user = $redis->hGetAll($key);
//对用户信息进行判断
if ($user['password'] != $data['password']){
    die;
}
session_start();
//将key存在session中
$_SESSION['user'] = $key;
//这里用list获取登录用户的邮箱
$listKey = "sendmaillist";
//当登录成功时发送邮箱
$mail = $redis->lPop($listKey);
//调用封装的mail方法发送邮箱
$res = new \mail\mail($mail);
//跳转到文章列表
header('location:article.php');
```

###### 文章列表页代码:article.php

```php
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
```

###### 文章新增前台代码：add_article.html

```php
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>添加文章</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css" integrity="sha384-HSMxcRTRxnN+Bdg0JdbxYKrThecOKuH5zCYotlSAcp1+c8xmyTe9GYg1l9a69psu" crossorigin="anonymous">
</head>
<body>
    <form class="form-horizontal" action="add_article.php" method="post">
    <div class="form-group">
        <label class="col-sm-2 control-label">文章标题</label>
        <div class="col-sm-10">
            <input class="form-control" name="title" id="title" placeholder="文章标题">
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label">文章内容</label>
        <div class="col-sm-10">
            <textarea class="form-control" name="desc" id="desc" rows="3"></textarea>
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
            <button type="submit" class="btn btn-default">新增</button>
        </div>
    </div>
</form>
</body>
</html>
```

###### 文章新增逻辑代码：add_article.php

```php
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
    //跳转到文章列表
    header('location:article.php');
}
```

文章删除逻辑代码:del_article.php

```php
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
//跳转到文章列表页
header("location:article.php");
```

