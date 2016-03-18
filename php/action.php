<?php
session_start();
$action = $_GET['act'];
if($action=='delimg'){
	$filename = $_POST['imagename'];
	if(!empty($filename)){
		unlink('files/images/'.$filename);
		echo '1';
	}else{
		echo '删除失败!';
	}
}else if($action=='delimg_register'){
	$filename = $_POST['imagename'];
	if(!empty($filename)){
		unlink('files/headpictures/'.$filename);
		echo '1';
	}else{
		echo '删除失败!';
	}
}else if($action=='picture'){
	$picname = $_FILES['mypic']['name'];
	$picsize = $_FILES['mypic']['size'];
	if ($picname != "") {
		if ($picsize > 1024000) {
			echo '图片大小不能超过1M';
			exit;
		}
		$type = strstr($picname, '.');
		if ($type != ".gif" && $type != ".jpg") {
			echo '图片格式不对！,请重新选择图片！';
			exit;
		}
		$rand = rand(100, 999);
		$pics = time() . $rand . $type;
		//上传路径
		$pic_path = "files/images/". $pics;

		move_uploaded_file($_FILES['mypic']['tmp_name'], $pic_path);
		// move_uploaded_file($picname, $pic_path);
		
		// $_SESSION['path']=;
	}
	$size = round($picsize/1024,2);
	$arr = array(
		'name'=>$picname,
		'pic'=>$pics,
		'size'=>$size
	);

	$_SESSION['pic_path']="php/".$pic_path;
	echo json_encode($arr);

}else if($action=='vedios'){
	$picname = $_FILES['mypic']['name'];
	$picsize = $_FILES['mypic']['size'];
	if ($picname != "") {
		if ($picsize > 1024000*50) {
			echo '视频大小不能超过50M';
			exit;
		}
		$type = strstr($picname, '.');
		if ($type != ".flv" && $type != ".avi" && $type != ".mp4" && $type != ".rmvb" && $type != ".mov") {
			echo '视频格式不对！,请重新选择视频！';
			exit;
		}
		$rand = rand(100, 999);

		$pics = time() . $rand . $type;
		
		//上传路径
		$pic_path = "files/vedios/". $pics;
		move_uploaded_file($_FILES['mypic']['tmp_name'], $pic_path);
	}
	$size = round($picsize/1024,2);
	$arr = array(
		'name'=>$picname,
		'pic'=>$pics,
		'size'=>$size
	);
	$_SESSION['vedio_path']="php/".$pic_path;
	echo json_encode($arr);

}else if($action=='musics'){
	$picname = $_FILES['mypic']['name'];
	$picsize = $_FILES['mypic']['size'];
	if ($picname != "") {
		if ($picsize > 1024000*4) {
			echo '音乐大小不能超过4M';
			exit;
		}
		$type = strstr($picname, '.');
		if ($type != ".mp3" && $type != ".wma") {
			echo '音乐格式不对！,请重新选择视频！';
			exit;
		}
		$rand = rand(100, 999);

		$pics = time() . $rand . $type;
		
		//上传路径
		$pic_path = "files/musics/". $pics;
		move_uploaded_file($_FILES['mypic']['tmp_name'], $pic_path);
	}
	$size = round($picsize/1024,2);
	$arr = array(
		'name'=>$picname,
		'pic'=>$pics,
		'size'=>$size
	);
	$_SESSION['music_path']="php/".$pic_path;
	echo json_encode($arr);
}else if($action=="register"){
	//上传注册头像
	$picname = $_FILES['mypic']['name'];
	$picsize = $_FILES['mypic']['size'];
	if ($picname != "") {
		if ($picsize > 1024000) {
			echo '图片大小不能超过1M';
			exit;
		}
		$type = strstr($picname, '.');
		if ($type != ".gif" && $type != ".jpg") {
			echo '图片格式不对！,请重新选择图片！';
			exit;
		}
		$rand = rand(100, 999);
		$pics = time() . $rand . $type;
		//上传路径
		$pic_path = "files/headpictures/". $pics;

		move_uploaded_file($_FILES['mypic']['tmp_name'], $pic_path);
		// move_uploaded_file($picname, $pic_path);
		
		// $_SESSION['path']=;
	}
	$size = round($picsize/1024,2);
	$arr = array(
		'name'=>$picname,
		'pic'=>$pics,
		'size'=>$size
	);

	// $_SESSION['pic_path']="php/".$pic_path;
	echo json_encode($arr);
}
?>