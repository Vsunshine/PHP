<?php
	require '../config.php';
	session_start();
	function jiancha(){
		//开启session；
		
		//判断是否有用户信息；
		if (!isset($_SESSION['user_info'])) {
		  header('Location: /pages/admin/login.php');
		  exit;
		}
	}

	//封装连接数据库
	function connect(){
		//连接数据库
		$connection = mysqli_connect(DB_HOST,DB_USER,DB_PASSWORD);
		//判断是否成功；
		if (!isset($connection)) {
			die("数据库链接失败") ;
		}
		//选择数据库
		mysqli_select_db($connection,DB_NAME);
		//设置编码
		mysqli_set_charset($connection,DB_CHARSET);
		return $connection;
	}

	/**
	*	功能：查询操作
	*/
	function query($sql){
		$connection = connect();
		$res = mysqli_query($connection,$sql);
		// 将结果转换成数组
		$rows = featch($res);
		return $rows;
	}

	//数据提取：
	function featch($res){
		$rows = array();
		while ($row = mysqli_fetch_assoc($res)) {
			$rows[] = $row;
		}

		return $rows;
	}

	//添加数据
	function insert($table, $arr){
		$connection = connect();
		// 获取数组中所有的值
		$key = array_keys($arr);
		// 获取数组中所有的value；
		$val = array_values($arr);
		//  implode 一维数组转换成字符串；
		// $sql = 'INSERT INTO ' . $table . ' (' . implode(', ', $key) . ') VALUES(`' . implode('`, `', $val) . '`)';
		$sql = "INSERT INTO " . $table . " (" . implode(", ", $key) . ") VALUES('" . implode("', '", $val) . "')";

		// echo $sql;exit;
		$res = mysqli_query($connection,$sql);
		return $res;
	}

	//删除数据
	function delete($sql){
		$connection = connect();
		$res = mysqli_query($connection,$sql);
		return res;

	}

	//修改数据
	function update($table, $arr, $id){
		$connection = connect();
		$str = '';
		// 将关联数组处理成 字段名=值, 字段名=值... 格式
		foreach ($arr as $key => $val) {
			$str .= $key . "=" . "'" . $val . "', ";
			// $str .= $key .'=' . '"'.$val.'", ';
		}
		//截掉多余的逗号和空格
		$str = substr($str, 0, -2);	

		// $sql = 'UPDATE '.$table.' SET '.$str.' WHERE id='.$id;
		$sql = "UPDATE " . $table . " SET " . $str . " WHERE id=" . $id;
		// echo $sql;exit;
		$res = mysqli_query($connection, $sql);
		// var_dump($res); exit;
		return $res;
	}




?>