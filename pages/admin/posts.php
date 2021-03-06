<?php
    require '../function.php';
    jiancha();

    $actives = array('a','b','c');
    $active = a;

    //分页显示设置

     //获取总共有多少条数据
    $sql = 'SELECT count(*) AS total FROM posts';
    $total = query($sql)[0]['total'];

    //每页显示的条数；
    $pageSize = 3;

    //总共多少页
    $pageCount = ceil($total/$pageSize);


    //设置当前页
    $currentPage = isset($_GET['page']) ? $_GET['page'] : 1;


    //设置上一页
    $prevPage = $currentPage - 1;

    //下一页
    $nextPage = $currentPage + 1;

    //固定显示的页码数
    $pageLimt = 3;

    //页码数的起点
    if ($currentPage === 1) {
      $start = 1;
    }else{
      $start = abs($pageLimt  -  $currentPage
      -1);
      $start = $start < 1 ? 1 : $start;
    }


    //页码数的终点
    $end = $start + $pageLimt - 1;
    // echo $end.'--'.$start;exit;
    $end = $end > $pageCount ? $pageCount : $end;


    //页码在后面的时候，为拉保证长度固定，再根据重点的页码来确定起点的页码；
    $start = $end - $pageLimt + 1;
    $start = $start < 1 ? 1 : $start;

    //获取所有可能出现的页码
    // range从参数一到参数二所有的数字的数组；
    $pages = range($start, $end);

    $offset = ($currentPage - 1) * $pageSize;

    //连表查询 ，
    //页面需要的数据来自多个表；
    //从第几个开始获取数据（不包括$offset），获取多少个（$pageSize）；
    $sql = 'SELECT posts.id, posts.title, posts.created, posts.status, users.nickname, categories.name FROM posts LEFT JOIN users ON posts.user_id=users.id LEFT JOIN categories ON posts.category_id = categories.id LIMIT ' . $offset . ', ' . $pageSize;
    $lists = query($sql);

    //删除操作
    if ($_GET['action'] == 'delete') {
      $sql = 'DELETE FROM posts WHERE id='.$_GET['pid'];
      $result = delete($sql);
      if ($result) {
        header('Location: /pages/admin/posts.php?page='.$currentPage);
        exit;
      }
    }


?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <title>Posts &laquo; Admin</title>
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
        <h1>所有文章</h1>
        <a href="post-add.html" class="btn btn-primary btn-xs">写文章</a>
      </div>
      <!-- 有错误信息时展示 -->
      <!-- <div class="alert alert-danger">
        <strong>错误！</strong>发生XXX错误
      </div> -->
      <div class="page-action">
        <!-- show when multiple checked -->
        <a class="btn btn-danger btn-sm" href="javascript:;" style="display: none">批量删除</a>
        <form class="form-inline">
          <select name="" class="form-control input-sm">
            <option value="">所有分类</option>
            <option value="">未分类</option>
          </select>
          <select name="" class="form-control input-sm">
            <option value="">所有状态</option>
            <option value="">草稿</option>
            <option value="">已发布</option>
          </select>
          <button class="btn btn-default btn-sm">筛选</button>
        </form>
        <ul class="pagination pagination-sm pull-right">
        <?php if($currentPage > 1){?>
          <li><a href="/pages/admin/posts.php?page=<?php echo $prevPage; ?>">上一页</a></li>
        <?php }?>

        <?php foreach($pages as $key=>$val) { ?>
            <?php if($currentPage == $val) { ?>
            <li class="active" >
                <a href="/pages/admin/posts.php?page=<?php echo $val; ?>">
                  <?php echo $val; ?>
                </a>
            </li>
            <?php } else { ?>
            <li>
                <a href="/pages/admin/posts.php?page=<?php echo $val; ?>">
                  <?php echo $val; ?>
                </a>
            </li>
            <?php } ?>
          <?php } ?>

          <?php if($currentPage < $pageCount) { ?>
            <li>
              <a href="/pages/admin/posts.php?page=<?php echo $nextPage; ?>">
                下一页
              </a>
            </li>
          <?php } ?>
        </ul>
      </div>
      <table class="table table-striped table-bordered table-hover">
        <thead>
          <tr>
            <th class="text-center" width="40"><input type="checkbox"></th>
            <th>标题</th>
            <th>作者</th>
            <th>分类</th>
            <th class="text-center">发表时间</th>
            <th class="text-center">状态</th>
            <th class="text-center" width="100">操作</th>
          </tr>
        </thead>
        <tbody>
        <?php foreach ($lists as $key => $val) {;?>
          <tr>
            <td class="text-center"><input type="checkbox"></td>
            <td><?php echo $val['title'];?></td>
            <td><?php echo $val['slug'];?></td>
            <?php if(empty($val['name'])){?>
            <td>未定义</td>
            <?php } else {?>
            <td><?php echo $val['name'];?></td>
            <?php }?>
            <td class="text-center"><?php echo $val['created'];?></td>
            <?php if($val['status'] == 'published'){?>
            <td class="text-center">已发布</td>
            <?php } else {?>
            <td class="text-center">草稿</td>
            <?php }?>
            <td class="text-center">
              <a href="/pages/admin/post.php?action=edit&pid=<?php echo $val['id'];?>" class="btn btn-default btn-xs">编辑</a>
              <a href="/pages/admin/posts.php?action=delete&pid=<?php echo $val['id'];?>&page=<?php echo $currentPage;?>" class="btn btn-danger btn-xs">删除</a>
            </td>
          </tr>
        <?php }?>
        </tbody>
      </table>
    </div>
  </div>

  <?php include './inc/aside.php';?>

  <?php include './inc/script.php';?>
  <!-- <script>NProgress.done()</script> -->
</body>
</html>
