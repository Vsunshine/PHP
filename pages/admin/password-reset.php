<?php

  require '../function.php';
  jiancha();
  session_start();
  $user_id = $_SESSION['user_id'];

  $res = query('SELECT * FROM users WHERE id='.$user_id);

  $flag = false;
  if (!empty($_POST)) {
    $error='';
    if($res[0]['password'] == $_POST['oldpwd']){
      $pwd = array();
      if ($_POST['newpwd'] == $_POST['new2pwd']) {
          $pwd['password'] = $_POST['new2pwd'];
          $genxin = update('users',$pwd,$user_id);
          if ($genxin) {
            header('Location: /pages/admin/password-reset.php'); 
          }
      }else{
        $flag = true;
        $error = '两次密码不一致';
      }
    }else{
      $flag = true;
      $error = '密码不正确';
    }
  }

?>

<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <title>Password reset &laquo; Admin</title>
  <?php include './inc/style.php'; ?>
  <link rel="stylesheet" href="../assets/css/admin.css">
  <script src="../assets/vendors/nprogress/nprogress.js"></script>
</head>
<body>
  <script>NProgress.start()</script>

  <div class="main">
  <?php include './inc/nav.php'; ?>
    <div class="container-fluid">
      <div class="page-title">
        <h1>修改密码</h1>
      </div>
      <!-- 有错误信息时展示 -->
      <?php if ($flag) {?>
        <div class="alert alert-danger">
          <strong>错误！</strong><?php echo $error;?>
        </div>
      <?php }?>
      <form class="form-horizontal" action="./password-reset.php" method="post">
        <div class="form-group">
          <label for="old" class="col-sm-3 control-label">旧密码</label>
          <div class="col-sm-7">
            <input id="old" class="form-control" type="password" placeholder="旧密码" name='oldpwd'>
          </div>
        </div>
        <div class="form-group">
          <label for="password" class="col-sm-3 control-label">新密码</label>
          <div class="col-sm-7">
            <input id="password" class="form-control" type="password" placeholder="新密码" name="newpwd">
          </div>
        </div>
        <div class="form-group">
          <label for="confirm" class="col-sm-3 control-label">确认新密码</label>
          <div class="col-sm-7">
            <input id="confirm" class="form-control" type="password" placeholder="确认新密码" name="new2pwd">
          </div>
        </div>
        <div class="form-group">
          <div class="col-sm-offset-3 col-sm-7">
            <button type="submit" class="btn btn-primary" id="xgmm">修改密码</button>
          </div>
        </div>
      </form>
    </div>
  </div>

  <?php include './inc/aside.php'; ?>
  <?php include './inc/script.php'; ?>
  <script>NProgress.done()</script>
</body>
</html>
