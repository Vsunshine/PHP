<?php
    require '../function.php';
    jiancha();

    //查询所有列表的数据
    $res = query('SELECT `value` FROM options WHERE `key`="nav_menus"');
    $json = $res[0]['value'];

    //第二参数为true时，强转为数组
    $data = json_decode($json,true);

    //操作方式
    $action = isset($_GET['action']) ? $_GET['action'] : 'add';


    if (!empty($_POST)) {
      //将数据添加到数组
      $date[] = $_POST;
      //将数组装换成json；
      $json = json_encode($data,JSON_UNESCAPED_UNICODE);
      //更新数据库
      $upd = update('options',array('value'=>$json),9);
      if($result) {
        header('Location: /admin/menus.php');
        exit;
      }
    }





?>


<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <title>Navigation menus &laquo; Admin</title>
  <?php include './inc/style.php';?>
  <link rel="stylesheet" href="../assets/css/admin.css">
  <script src="../assets/vendors/nprogress/nprogress.js"></script>
</head>
<body>
  <script>NProgress.start()</script>

  <div class="main">
  <?php include './inc/nav.php';?>
    <div class="container-fluid">
      <div class="page-title">
        <h1>导航菜单</h1>
      </div>
      <!-- 有错误信息时展示 -->
      <!-- <div class="alert alert-danger">
        <strong>错误！</strong>发生XXX错误
      </div> -->
      <div class="row">
        <div class="col-md-4">
          <form action='./nav-menus.php' method='post'>
            <h2>添加新导航链接</h2>
            <div class="form-group">
              <label for="text">文本</label>
              <input id="text" class="form-control" name="text" type="text" placeholder="文本">
            </div>
            <div class="form-group">
              <label for="title">标题</label>
              <input id="title" class="form-control" name="title" type="text" placeholder="标题">
            </div>
            <div class="form-group">
              <label for="href">链接</label>
              <input id="href" class="form-control" name="href" type="text" placeholder="链接">
            </div>
            <div class="form-group">
              <button class="btn btn-primary" type="submit">添加</button>
            </div>
          </form>
        </div>
        <div class="col-md-8">
          <div class="page-action">
            <!-- show when multiple checked -->
            <a class="btn btn-danger btn-sm" href="javascript:;" style="display: none">批量删除</a>
          </div>
          <table class="table table-striped table-bordered table-hover">
            <thead>
              <tr>
                <th class="text-center" width="40"><input type="checkbox"></th>
                <th>文本</th>
                <th>标题</th>
                <th>链接</th>
                <th class="text-center" width="100">操作</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach($data as $key=>$val){?>
              <tr>
              <td class="text-center">
              <input type="checkbox">
              </td>
              <td>
                <i class="<?php echo $val['icon']; ?>"></i>
                <?php echo $val['text']; ?>
              </td>
               <td><?php echo $val['title']; ?></td>
              <td><?php echo $val['link']; ?></td>
              <td class="text-center">
              <a href="/admin/menus.php?action=delete&index=<?php echo $key; ?>" class="btn btn-danger btn-xs">删除</a>
              </td>
              </tr>
              <?php }?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <?php include './inc/aside.php';?>

  <?php include './inc/script.php';?>
  <script>
    NProgress.start();
    NProgress.done();
  </script>
</body>
</html>
