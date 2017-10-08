<?php
  require  '../function.php';
  jiancha();

  $actives = array('a','b','c');
  $active = b;

  $res = query('SELECT * FROM categories');
  // print_r($res);

  $action = isset($_GET['action']) ? $_GET['action']:'add';

  //上传文件时$_post为空，使用||让upfile可以执行；
  if (!empty($_POST) || $action == 'upfile') {
    //添加操作
      if ($action == 'add') {
        //将数据添加到数据库
        // print_r($_POST);
        $ress = insert('posts',$_POST);
        // var_dump($_POST);exit;
        if ($ress) {
          header('Location: ./post.php');
        }else{
          $mess = '添加文章失败';
          echo $mess;
          exit;
        }
      } else if ($action == 'upfile') {
        //设置长传目录；
        $path = '../uploads/thumbs';
        if (!file_exists($path)) {
          mkdir($path);
        }
        //获取文件后缀；
        $houzhui = explode('.', $_FILES['feature']['name'])[1];
        //设置文件名；
        $filename = time();
        //拼接路径；
        $des = $path .'/'.$filename.'.'.$houzhui;
        //上传文件
        move_uploaded_file($_FILES['feature']['tmp_name'], $des);
        //处理成网路路劲；
        echo substr($des, 2);
        exit;
      }else if ($action == 'update') {  //更新
        // 获取文章id
        $id = $_POST['id'];
        //id不更新
        unset($_POST['id']);

        //更新文章
        // 此处有未解决的bug，$resuu什么都输出不了
        $resuu = update('posts',$_POST,$id);
        if($resu){
          header('Loaction: /pages/admin/post.php');
          exit;
        }

      }
  }

  // 修改文章
  if($action == 'edit'){
    $action = 'update';
    $idd = $_GET['pid'];
    $sqll = 'SELECT * FROM posts WHERE id='.$idd; 
    $roo = query($sqll);
  }


?>




<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <title>Add new post &laquo; Admin</title>
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
        <h1>写文章</h1>
      </div>
      <!-- 有错误信息时展示 -->
      <!-- <div class="alert alert-danger">
        <strong>错误！</strong>发生XXX错误
      </div> -->
      <form class="row" action="/pages/admin/post.php?action=<?php echo $action;?>" method="post">
        <input type="hidden" name="user_id" value="<?php echo $_SESSION['user_info']['id']; ?>">
        <!-- <input type="hidden" name='id' value="<?php echo $pid;?>"> -->
        <div class="col-md-9">
          <div class="form-group">
            <label for="title">标题</label>
            <input value="<?php echo isset($roo[0]['title'])?$roo[0]['title']:'' ;?>" id="title" class="form-control input-lg" name="title" type="text" placeholder="文章标题">
          </div>
          <div class="form-group">
            <label for="content">内容</label>
            <textarea style='height:300px' id="content" name="content" cols="30" rows="10" placeholder="内容">
              <?php echo isset($roo[0]['content'])?$roo[0]['content']:'' ;?>"
            </textarea>
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <label for="slug">别名</label>
            <input id="slug" class="form-control" name="slug" type="text" placeholder="slug" value="<?php echo isset($roo[0]['slug'])?$roo[0]['slug']:'' ;?>">
            <p class="help-block">https://zce.me/post/<strong>slug</strong></p>
          </div>
          <div class="form-group">
            <label for="feature">特色图像</label>
            <!-- show when image chose -->
            <?php if(empty($roo[0]['feature'])){?>
              <img class="help-block thumbnail" style="display: none">
            <?php } else {?>
              <img class="help-block thumbnail" src='<?php echo $roo[0]['feature'] ;?>'>
            <?php }?>
            <input id="feature" class="form-control" name="feature" type="file">
            <input type="hidden" name="feature" id="thumb" value='<?php echo isset($roo[0]['feature'])?$roo[0]['feature']:'' ?>'>
          </div>
          <div class="form-group">
            <label for="category">所属分类</label>
            <select id="category" class="form-control" name="category_id">
            <?php foreach ($res as $key => $val) {?>
            <option value="<?php echo $val['id']; ?>" <?php if(isset($roo) && $roo[0]['category_id'] == $val['id']) {?> selected <?php }?>>
                <?php echo $val['name'];?>
              </option>
            <?php }?>
            </select>
          </div>
          <div class="form-group">
            <label for="created">发布时间</label>
            <?php if($_GET['action'] == 'edit'){?>
              <input id="created" class="form-control" name="created" value="<?php echo isset($roo[0]['created']) ? $roo[0]['created'] : ''; ?>" type="text">
            <?php } else{?>
              <input id="created" class="form-control" name="created" type="datetime-local">
            <?php }?>
          </div>
          <div class="form-group">
            <label for="status">状态</label>
            <select id="status" class="form-control" name="status">
            <option value="drafted" <?php if(isset($roo) && $roo[0]['status'] == 'drafted' ){ ?> selected <?php }?>>草稿</option>
              <option value="published" <?php if(isset($roo) && $roo[0]['status'] == 'published' ){ ?> selected <?php }?>>已发布</option>
            </select>
          </div>
          <div class="form-group">
            <button class="btn btn-primary" type="submit">保存</button>
          </div>
        </div>
      </form>
    </div>
  </div>

  <?php include './inc/aside.php';?>

  <?php include './inc/script.php';?>
  <script src='/pages/assets/vendors/ueditor/ueditor.config.js'></script>
  <script src='/pages/assets/vendors/ueditor/ueditor.all.min.js'></script>
  <script type="text/javascript">
      //富文本编辑器
      UE.getEditor('content')

      $('#feature').on('change',function(){
        // 通过原生 DOM 可以获得文件信息
      // this.files[0];
      // 通过H5内置的对象 FormData 可以实现文件数据的
      // 管理
        var data = new FormData();
        data.append('feature',this.files[0]);

        var xhr = new XMLHttpRequest();
        xhr.open('post','/pages/admin/post.php?action=upfile');
        xhr.send(data);
        xhr.onreadystatechange = function(){
          if (xhr.readyState == 4 && xhr.status == 200) {
            $('.thumbnail').attr('src','/pages'+xhr.responseText).show();
            //将图片的路劲给选框，然后将这个传入数据库；
            $('#thumb').val('/pages'+xhr.responseText);
          }
        }
      })
  </script>
</body>
</html>
