<?php

    require '../function.php';
    jiancha();
  //获取当前登录用户的信息；
    //获取当前登录用户的ID；
    session_start();
    $user_id = $_SESSION['user_info']['id'];
    //获取当前用户信息；
    $res = query('SELECT * FROM users WHERE id='.$user_id);
  //填充到表单中；

  //点击更新执行更新操作，讲数据库信息更新；
    if (!empty($_POST)) {
      //email不允许更改
      unset($_POST['email']);
      $gengxin = update('users',$_POST,$user_id);
      if ($gengxin) {
        header('Location: /pages/admin/profile.php');
        exit();
      }
    }
?>




<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <title>Dashboard &laquo; Admin</title>
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
        <h1>我的个人资料</h1>
      </div>
      <!-- 有错误信息时展示 -->
      <!-- <div class="alert alert-danger">
        <strong>错误！</strong>发生XXX错误
      </div> -->
      <form class="form-horizontal" action="./profile.php" method="post" enctype="multipart/form-data">
        <div class="form-group">
          <label class="col-sm-3 control-label">头像</label>
          <div class="col-sm-6">
            <label class="form-image">
              <input id="avatar" type="file">
              <?php if($res[0]['avatar']){?>
                <img src="<?php echo $res[0]['avatar'];?>" class='preview'>
              <?php } else {?>
                <img src="/pages/assets/img/default.png" class='preview'>
              <?php }?>
              <i class="mask fa fa-upload"></i>
            </label>
          </div>
        </div>
        <div class="form-group">
          <label for="email" class="col-sm-3 control-label">邮箱</label>
          <div class="col-sm-6">
            <input id="email" class="form-control" name="email" type="type" value="<?php echo $res[0]['email']?>" placeholder="邮箱" readonly disabled>
            <p class="help-block">登录邮箱不允许修改</p>
          </div>
        </div>
        <div class="form-group">
          <label for="slug" class="col-sm-3 control-label">别名</label>
          <div class="col-sm-6">
            <input id="slug" class="form-control" name="slug" type="type" value="<?php echo $res[0]['slug']?>" placeholder="slug">
            <p class="help-block">https://zce.me/author/<strong>zce</strong></p>
          </div>
        </div>
        <div class="form-group">
          <label for="nickname" class="col-sm-3 control-label">昵称</label>
          <div class="col-sm-6">
            <input id="nickname" class="form-control" name="nickname" type="type" value="<?php echo $res[0]['nickname']?>" placeholder="昵称">
            <p class="help-block">限制在 2-16 个字符</p>
          </div>
        </div>
        <div class="form-group">
          <label for="bio" class="col-sm-3 control-label">简介</label>
          <div class="col-sm-6">
            <textarea id="bio" class="form-control" placeholder="Bio" cols="30" rows="6"><?php echo $res[0]['bio']?></textarea>
          </div>
        </div>
        <div class="form-group">
          <div class="col-sm-offset-3 col-sm-6">
            <button type="submit" class="btn btn-primary">更新</button>
            <a class="btn btn-link" href="password-reset.php">修改密码</a>
          </div>
        </div>
      </form>
    </div>
  </div>

  <?php include './inc/aside.php'; ?>
  <?php include './inc/script.php'; ?>
  <!-- <script>NProgress.done()</script> -->
  <script>
    $('#avatar').on('change',function(){
      //将图片转换成二进制形式进行上传；
      var data = new FormData();
      //添加图片路径
      data.append('avatar',this.files[0]);
      var xhr = new XMLHttpRequest;
      xhr.open('post','/pages/admin/upfile.php');
      xhr.send(data);
      NProgress.start();
      xhr.onreadystatechange = function(){
        if (xhr.readyState == 4 && xhr.status == 200) {
            $('.preview').attr('src',xhr.responseText);
            NProgress.done();
        }
      }


    })
  </script>
</body>
</html>
