<?php

    require '../function.php';
    // 账号密码错误信息提示
    $mess='';
    //先判断页面是不是刷新提交（这是get），还是点击按钮提交（post）
        //empty判断一个变量是否为空，不为空返回fasle；
    if (!empty($_POST)) {
      //获取账户邮箱
      $email = $_POST['email'];
      //获取密码
      $pwd = $_POST['password'];
      
      $rows = query('SELECT * FROM users WHERE email="' . $email . '"');      
      // print_r($rows);


      //验证邮箱与密码
      if ($rows[0]) {
        if ($rows[0]['password'] == $pwd) {
          //检测账户是否登录
          //用session和cookie

          //开启session，set-cookie给浏览器，浏览器再本地存一个cookie，这个cookie默认叫PHPSESSID；
          session_start();
          //将用户信息存入session，登录首页时用这个检测用户是否登录
          $_SESSION['user_info'] = $rows[0];
          // $_SESSION['user_id'] = $rows[0]['id'];
         header('Location: /pages/admin');
         exit;
        }else {
          // echo "密码错误";
          //设置错误提示
          $mess = '密码错误';
        }
      }else{
        // echo "用户不存再";
        $mess = '用户不存在';
      }
    }

?>


<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <title>Sign in &laquo; Admin</title>
  <link rel="stylesheet" href="../assets/vendors/bootstrap/css/bootstrap.css">
  <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
  <div class="login">
    <form action="./login.php" method="post" class="login-wrap">
      <img class="avatar" src="../assets/img/default.png">
      <!-- 有错误信息时展示 -->
      <?php if(!empty($mess)) { ?>
      <div class="alert alert-danger">
        <strong>错误！</strong> <?php echo $mess;?>
      </div>
      <?php }?>
      <div class="form-group">
        <label for="email"  class="sr-only">邮箱</label>
        <input id="email" name="email" type="email" class="form-control" placeholder="邮箱" autofocus>
      </div>
      <div class="form-group">
        <label for="password"   class="sr-only">密码</label>
        <input id="password" name="password" type="password" class="form-control" placeholder="密码">
      </div>
      <input class="btn btn-primary btn-block" type="submit" value="登 录">
    </form>
  </div>
</body>
</html>
