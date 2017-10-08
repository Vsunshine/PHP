<div class="aside">
    <div class="profile">
    <?php if(empty($_SESSION['user_info']['avatar'])){?>
      <img class="avatar" src="../uploads/avatar.jpg">
    <?php } else {?>
      <img class="avatar" src="<?php echo $_SESSION['user_info']['avatar'];?>">
    <?php }?>
      <h3 class="name"><?php echo $_SESSION['user_info']['nickname'];?></h3>
    </div>
    <ul class="nav">
    <li <?php if($active == 'shouye') {?> class="active" <?php }?>>
        <a href="/pages/admin"><i class="fa fa-dashboard"></i>仪表盘</a>
      </li>
      <li <?php if(in_array($active, $actives)) { ?> class="active" <?php } ?>>
        <a href="#menu-posts" class="collapsed" data-toggle="collapse">
          <i class="fa fa-thumb-tack"></i>文章<i class="fa fa-angle-right"></i>
        </a>
        <ul id="menu-posts" class="collapse <?php if(in_array($active, $actives)) { ?> in <?php } ?>">
          <li <?php if($active == 'a') {?> class="active" <?php }?>><a href="posts.php">所有文章</a></li>
          <li <?php if($active == 'b') {?> class="active" <?php }?>><a href="post.php">写文章</a></li>
          <li <?php if($active == 'c') {?> class="active" <?php }?>><a href="categories.php">分类目录</a></li>
        </ul>
      </li>
      <li>
        <a href="comments.html"><i class="fa fa-comments"></i>评论</a>
      </li>
      <li>
        <a href="users.php"><i class="fa fa-users"></i>用户</a>
      </li>
      <li>
        <a href="#menu-settings" class="collapsed" data-toggle="collapse">
          <i class="fa fa-cogs"></i>设置<i class="fa fa-angle-right"></i>
        </a>
        <ul id="menu-settings" class="collapse">
          <li><a href="nav-menus.php">导航菜单</a></li>
          <li><a href="slides.html">图片轮播</a></li>
          <li><a href="settings.html">网站设置</a></li>
        </ul>
      </li>
    </ul>
  </div>
