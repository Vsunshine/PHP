<?php
//  进入这个页面，先检测用户是否已经登录
    require '../function.php';

    jiancha();

    $active = 'shouye';

    //文章数量统计
    $sql = 'SELECT count(*) AS tb FROM posts';
    $sql1 = 'SELECT count(*) AS tbs FROM posts WHERE status="drafted"';
    $rr = query($sql);
    $rrs = query($sql1);
    $tb = $rr[0]['tb'];
    $tbs = $rrs[0]['tbs'];
    $fenlei = query('SELECT count(*) AS tbr FROM categories'); 
    $tbr = $fenlei[0]['tbr'];
    
  
    $pl = query('SELECT count(*) AS tbp FROM comments');
    $tbp = $pl[0]['tbp'];

    $pp = query('SELECT count(*) AS tbn FROM comments WHERE status="held"');
    $tbn = $pp[0]['tbn'];


?>

<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <title>Dashboard &laquo; Admin</title>
  <!-- 样式 -->
  <?php include './inc/style.php'; ?>
  <link rel="stylesheet" href="../assets/css/admin.css">
  <script src="../assets/vendors/nprogress/nprogress.js"></script>
</head>
<body>
  <script>NProgress.start()</script>

  <div class="main">
    <!-- 顶部 -->
    <?php include './inc/nav.php'; ?>
    <div class="container-fluid">
      <div class="jumbotron text-center">
        <h1>One Belt, One Road</h1>
        <p>Thoughts, stories and ideas.</p>
        <p><a class="btn btn-primary btn-lg" href="post-add.html" role="button">写文章</a></p>
      </div>
      <div class="row">
        <div class="col-md-4">
          <div class="panel panel-default">
            <div class="panel-heading">
              <h3 class="panel-title">站点内容统计：</h3>
            </div>
            <ul class="list-group">
              <li class="list-group-item"><strong><?php echo $tb;?></strong>篇文章（<strong><?php echo $tbs;?></strong>篇草稿）</li>
              <li class="list-group-item"><strong><?php echo $tbr;?></strong>个分类</li>
              <li class="list-group-item"><strong><?php echo $tbp;?></strong>条评论（<strong><?php echo $tbn;?></strong>条待审核）</li>
            </ul>
          </div>
        </div>
        <div class="col-md-4"></div>
        <div class="col-md-4"></div>
      </div>
    </div>
  </div>
  <!-- 左部 -->
  <?php include './inc/aside.php'; ?>
  <?php include './inc/script.php'; ?>
 
  <script>
    NProgress.start();
     NProgress.done();
  </script>
</body>
</html>
