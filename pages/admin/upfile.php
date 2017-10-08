<?php
	//先判断要保存的路劲存在不
    require '../function.php';
	if (!file_exists('../uploads')) {
		mkdir('../uploads');
	}


	//设置文件名	
	$nametime = time();
	//设置文件后缀
	//explode ,  用第一个字符串，将第二个参数的字符串分隔，返回数组
	$houzhui = explode('.', $_FILES['avatar']['name'])[1];
	//拼接文件保存路劲
	// var_dump($_FILES);
	$path = '/uploads/'.$nametime.'.'.$houzhui;
	// $path2 = 'pages'.$path;
	// echo $path2;exit;
	// var_dump($_FILES['avatar']);
	//上传文件
     move_uploaded_file($_FILES['avatar']['tmp_name'], '..'.$path);

	//读取用户id
	$user_id = $_SESSION['user_info']['id'];
	// echo $user_id;
	$tmp = array('avatar'=>'/pages'.$path);
	//将路劲保存到数据库中
	$res = update('users',$tmp,$user_id);
	//返回路劲
	echo '..'.$path;
	

?>