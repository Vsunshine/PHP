<?php
  require '../function.php';
  jiancha();

  $mess = '';
  $title = '添加用户';
  $btnText = '添加';
  $action = isset($_GET['action']) ? $_GET['action'] : 'add';
  
  if (!empty($_POST)) {
    //添加用户状态，将来用于填入数据库；
    if ($action == 'add') {
      $_POST['status'] = 'unactivated';
      $_POST['avatar'] = '/pages/uploads/avatar.jpg';
      //往数据库里添加数据
      $res = insert('users',$_POST);
  
      if ($res) {
        header('Location: ./users.php');
      }else{
        $mess = '添加失败';
      }
    }
    //更新操作
    if ($action == 'update') {
      //获取需要更新用户的id
      $id = $_POST['id'];
      //因为id不能复用，所以保存后需要删除；
      unset($_POST['id']);
      // echo $_POST['id'];
      //更新数据
      $gengxin = update('users',$_POST,$id);
      //更成功后重定向
      if ($gengxin) {
        header('Location: /pages/admin/users.php');
        exit;
      }
    }

    //批量删除
    if ($action == 'deleteAll') {
      // echo 'fdasdfasdasd';
      $sql = 'DELETE FROM users WHERE id IN ('. implode(', ', $_POST['ids']). ')';
      $res = delete($sql);
      header('Content-Type: application/json');
      if($res){
        $info = array('code'=>1000,'message'=>'删除成功');
        echo json_encode($info);
      }else {
        // 失败提示信息
        $info = array('code'=>10001, 'message'=>'删除失败!');

        echo json_encode($info);
      }

      exit;
    }
    
  }

  //获取所有用户信息，将来用于展示信息
  $rows1 = query('SELECT * FROM users'); 
  //获取用户的ID；
  $user_id = isset($_GET['user_id']) ? $_GET['user_id'] : '';
  //编辑操作
  if($action == 'edit'){
    //编辑之后下一步是将数据更新到数据库，所以改变$action的值；
     $action = 'update';
    //先将数据库中的所点的数据库用户信息，添加到表单中；
    $xiugai = query('SELECT * FROM users WHERE id=' .$user_id);
    $title = '修改用户信息';
    $btnText = '确认修改';
  } else if ($action == 'delete'){   //删除c操作
    $shanchu = delete('DELETE FROM users WHERE id=' .$user_id);
    //删除成功，刷新页面；
    if ($shanchu) {
      header('Location: /pages/admin/users.php');
      exit;
    }
  }

?>


<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <title>Users &laquo; Admin</title>
  <?php include './inc/style.php';?>
  <link rel="stylesheet" href="../assets/css/admin.css">
  <script src="../assets/vendors/nprogress/nprogress.js"></script>
</head>
<body>
  <!--<script>NProgress.start()</script>-->

  <div class="main">
    <?php include './inc/nav.php';?>
    <div class="container-fluid">
      <div class="page-title">
        <h1>用户</h1>
      </div>
      <!-- 有错误信息时展示 -->
      <?php if(!empty($mess)){?>
      <div class="alert alert-danger">
        <strong>错误！</strong><?php echo $mess;?>
      </div>
      <?php }?>
      <div class="row">
        <div class="col-md-4">
          <form action="./users.php?action=<?php echo $action;?>" method="post">
            <h2><?php echo $title;?></h2>
            <div class="form-group">
              <label for="email">邮箱</label>
              <!-- 添加隐藏的用户id -->
              <?php if ($action != 'add') {?>
                <input type="hidden" value="<?php echo $xiugai[0]['id'];?>" name="id">
              <?php }?>
              <input id="email" class="form-control" name="email" type="email" placeholder="邮箱" value="<?php echo $xiugai[0]['email']?>">
            </div>
            <div class="form-group">
              <label for="slug">别名</label>
              <input id="slug" class="form-control" name="slug" type="text" placeholder="slug" value="<?php echo $xiugai[0]['slug']?>">
              <p class="help-block">https://zce.me/author/<strong>slug</strong></p>
            </div>
            <div class="form-group">
              <label for="nickname">昵称</label>
              <input id="nickname" class="form-control" name="nickname" type="text" placeholder="昵称" value="<?php echo $xiugai[0]['nickname']?>">
            </div>
            <div class="form-group">
              <label for="password">密码</label>
              <input id="password" class="form-control" name="password" type="text" placeholder="密码" value="<?php echo $xiugai[0]['password']?>">
            </div>
            <div class="form-group">
              <button  type="submit" class="btn btn-primary" id="tianjia"><?php echo $btnText;?></button>
              <!-- <input type="submit" class="btn btn-primary" id="tianjia" value="添加"> -->
            </div>
          </form>
        </div>
        <div class="col-md-8">
          <div class="page-action">
            <!-- show when multiple checked -->
            <a class="btn btn-danger btn-sm delete" href="javascript:;" style="display: none">批量删除</a>
          </div>
          <table class="table table-striped table-bordered table-hover">
            <thead>
               <tr>
                <th class="text-center" width="40"><input type="checkbox"  id="first"></th>
                <th class="text-center" width="80">头像</th>
                <th>邮箱</th>
                <th>别名</th>
                <th>昵称</th>
                <th>状态</th>
                <th class="text-center" width="100">操作</th>
              </tr>
            </thead>
            <tbody id="tbod">
            <?php foreach ($rows1 as $key => $val) {?>
              <tr>
                <td class="text-center"><input type="checkbox" class="child" value="<?php echo $val['id'];?>"></td>
                <td class="text-center"><img class="avatar" src="<?php echo $val['avatar']?>"></td>
                <td><?php echo $val['email']?></td>
                <td><?php echo $val['slug']?></td>
                <td><?php echo $val['nickname']?></td>
                <?php if ($val['status'] == 'activated') {?>
                <td>已激活</td>
                <?php } else if($val['status'] == 'unactivated'){?>
                <td>未激活</td>
                <?php } else if($val['status'] == 'forbidden'){?>
                <td>已禁用</td>
                <?php } else {?>
                <td>已删除</td>
                <?php }?>
                <td class="text-center">
                  <a href="/pages/admin/users.php?action=edit&user_id=<?php echo $val['id'];?>" class="btn btn-default btn-xs">编辑</a>
                  <a href="/pages/admin/users.php?action=delete&user_id=<?php echo $val['id'];?>" class="btn btn-danger btn-xs">删除</a>
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
  <!--<script>NProgress.done()</script>-->
  <script type="text/javascript">
    $('#first').on({
      'change':function (){
        //全选，全不选；
          $('.child').prop('checked', $(this).prop('checked'));
          if ($(this).prop('checked')) {
            $('.delete').show();
          }else{
            $('.delete').hide();
          }
      }
    })
    $('.child').on({
      'change':function (){
          var size = $('.child:checked').size();
          var sizeChild = $('.child').size();
          if (size == sizeChild) {
            $('#first').prop('checked',true);
          }else{
            $('#first').prop('checked',false);
          }

          //批量删除显示
          if (size > 1) {
            $('.delete').show();
          }else{
            $('.delete').hide();
          }
      }
    })

    // //批量删除
    $('.delete').on('click',function (){
      var ids = [];
      $('.child:checked').each(function(){
        ids.push($(this).val());
      })
        $.ajax({
          url:'/pages/admin/users.php?action=deleteAll',
          type:'post',
          data:{ids : ids},
          success:function(info){
            alert(info.message);
            console.log(11);
            if (info.code == 1000) {
              location.reload();
            }
          }
        })
    })
  </script>
</body>
</html>