<?php
  require '../function.php';
  jiancha();

  $actives = array('a','b','c');
  $active = c;
  
  //查询数据库中的所有数据
  $res = query('SELECT * FROM categories');
  $tt = '添加新分类目录';
  $ss = '添加';
  $action = isset($_GET['action']) ? $_GET['action'] : 'add';
  //post提交方式
  if (!empty($_POST)) {
    //添加操作
    if ($action == 'add') {
      $res = insert('categories',$_POST);
      if ($res) {
        header('Location: ./categories.php');
      }else{
        echo "添加失败";
      }
    }

    //更新
    if ($action == 'update') {
      // print_r($_POST);exit();
      $id = $_POST['id'];
      unset($_POST['id']);
      $res1 = update('categories',$_POST,$id);
      if ($res1) {
        header('Location: ./categories.php');
      }else{
        echo "更新失败";
      }
    }

    //批量删除
    if ($action == 'deleteAll') {
      $sql = 'DELETE FROM categories WHERE id IN ('.implode(',', $_POST['ids']).')';
      $re5 = delete($sql);
      header('Content-Type: application/json');
      if ($re5) {
        $info = array('code'=>1000, 'message'=>'删除成功');
        echo json_encode($info);
      }else{
        $info = array('code'=>1001, 'message'=>'删除失败');
        echo json_encode($info);
      }
      exit;
    }

  }

  
  // if ($action == 'deleteAll') {
  //     // echo 'fdasdfasdasd';
  //     $sql = 'DELETE FROM categories WHERE id IN ('. implode(', ', $_POST['ids']). ')';
  //     $res3 = delete($sql);
  //     header('Content-Type: application/json');
  //     if($res3){
  //       $info = array('code'=>1000,'message'=>'删除成功');
  //       echo json_encode($info);
  //     }else {
  //       // 失败提示信息
  //       $info = array('code'=>10001, 'message'=>'删除失败!');

  //       echo json_encode($info);
  //     }

  //     exit;
  //   }
    
  // }

  
  //get  提交的两种方式
  //编辑操作

    if ($action == 'edit') {
      $action = 'update';
      $ress = query('SELECT * FROM categories WHERE id='.$_GET['user_id']);
      $tt = '修改目录';
      $ss = '确认修改';
    }else if($action == 'delete'){    //删除操作
      $res2 = delete('DELETE FROM categories WHERE id='.$_GET['user_id']);
      if ($res2) {
        header('Location: ./categories.php');
        exit;
      }
    }

?>


<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <title>Categories &laquo; Admin</title>
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
        <h1>分类目录</h1>
      </div>
      <!-- 有错误信息时展示 -->
      <!-- <div class="alert alert-danger">
        <strong>错误！</strong>发生XXX错误
      </div> -->
      <div class="row">
        <div class="col-md-4">
        <!-- 不管是get还是post请求，地址后面都可以带参数，但是这些参数只能用get方式获取 -->
          <form action="/pages/admin/categories.php?action=<?php echo $action;?>" method="post">
            <h2><?php echo $tt;?></h2>
            <div class="form-group">
              <label for="name">名称</label>
              <?php if ($action != 'add'){?>
                <input type="hidden" name='id' value="<?php echo $ress[0]['id'];?>">
              <?php }?>
              <input id="name" class="form-control" name="name" type="text" placeholder="分类名称"
              value="<?php echo $ress[0]['name'];?>">
            </div>
            <div class="form-group">
              <label for="slug">别名</label>
              <input id="slug" class="form-control" name="slug" type="text" placeholder="slug" value="<?php echo $ress[0]['slug'];?>">
              <p class="help-block">https://zce.me/category/<strong>slug</strong></p>
            </div>
            <div class="form-group">
              <button class="btn btn-primary" type="submit"><?php echo $ss;?></button>
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
                <th class="text-center" width="40"><input type="checkbox" id="first"></th>
                <th>名称</th>
                <th>Slug</th>
                <th class="text-center" width="100">操作</th>
              </tr>
            </thead>
            <tbody>
            <?php foreach ($res as $key => $val) {?>
              <tr>
                <td class="text-center"><input type="checkbox" class="child" value="<?php echo $val['id']?>"></td>
                <td><?php echo $val['name'];?></td>
                <td><?php echo $val['slug'];?></td>
                <td class="text-center">
                  <a href="/pages/admin/categories.php?action=edit&user_id=<?php echo $val['id'];?>" class="btn btn-info btn-xs">编辑</a>
                  <a href="/pages/admin/categories.php?action=delete&user_id=<?php echo $val['id'];?>" class="btn btn-danger btn-xs">删除</a>
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
  <script>NProgress.done()</script>
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
          url:'/pages/admin/categories.php?action=deleteAll',
          type:'post',
          data:{ids : ids},
          success:function(info){
            if (info.code == 1000) {
              location.reload();
            }
            alert(info.message);
          }
        })
    })
  </script>
</body>
</html>
