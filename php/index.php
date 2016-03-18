<?php
// require('User.php');
// $user = new User("huang","111");
session_start();
require_once 'SqlTool.class.php';

if($_REQUEST['action']=='register'){
	$array = array('result' => 1,'b'=>2 );

	$okay=TRUE;
	if(empty($_REQUEST['headpicture'])){
		$array['result']="请上传头像";
		$okay=FALSE;
	}
	if(empty($_REQUEST['username'])){
		$array['result']=" 请填写用户名！";
		$okay=FALSE;
	}
	if(empty($_REQUEST['password'])){
		$array['result']=" 请填写密码！";
		$okay=FALSE;
	}
	if(empty($_REQUEST['password_re'])){
		$array['result']=" 请填写密码！";
		$okay=FALSE;
	}
	if(empty($_REQUEST['email'])){
		$array['result']=" 请填写电子邮件！";
		$okay=FALSE;
	}
	if($_REQUEST['password'] != $_REQUEST['password_re']){
		$array['result']="两次输入的密码不匹配，请重新输入！";
		$okay=FALSE;
	}

	if($okay){
		// 插入数据库中
		$username=trim($_REQUEST['username']);
		$password=md5(trim($_REQUEST['password']));
		$headpicture=trim($_REQUEST['headpicture']);
		$email=trim($_REQUEST['email']);

		// 查询该用户名注册没有
		$sqlTool=new SqlTool();
		$sql="SELECT * FROM userinfo where username='$username'";
		$res=$sqlTool->execute_dql($sql);
		if($res=mysql_fetch_row($res)){
			$array['result']="该用户已经有人注册过了".$_REQUEST['username'];
			$array['res']="2";
		}else{
			$sql="INSERT INTO userinfo value('$username','$password','$email',NOW(),'$headpicture')";
			$res=$sqlTool->execute_dml($sql);
			if($res==1){
				$array['res']="1";
				$array['result']=$_REQUEST['username']."注册成功！点击登录页面，进行登录。";

			}else{
				$array['res']="0";
				$array['result']=$_REQUEST['username']."注册失败！";
			}
		}		
	}


	
	$json=json_encode($array);
	echo $_REQUEST['c'].'('.$json.')';
}
if($_REQUEST['action']=='login'){

	$array = array('result' => 1);

	$okay=TRUE;

	if(empty($_REQUEST['username'])){
		$array['result']=" 请填写用户名！";
		$okay=FALSE;
	}
	if(empty($_REQUEST['password'])){
		$array['result']=" 请填写密码！";
		$okay=FALSE;
	}

	$_SESSION['username']=$_REQUEST['username'];

	if($okay){
		// 插入数据库中
		$username=trim($_REQUEST['username']);
		$password=md5(trim($_REQUEST['password']));

		// 查询该用户名
		$sqlTool=new SqlTool();
		$sql="SELECT * FROM userinfo where username='$username'";
		$res=$sqlTool->execute_dql($sql);
		if($row=mysql_fetch_array($res)){
			if($row['password']==$password){
				$array['result']="登录成功";
				$_SESSION['headpicture']=$row['headpicture'];
			}
			else{
				$array['result']="密码不正确,请重新输入密码";
			}
			
		}else{
			$array['result']="没有该用户的注册信息，请先注册！";
		}		
	}

	update_info();

	$json=json_encode($array);
	echo $_REQUEST['l'].'('.$json.')';
}

if($_REQUEST['action']=='homepage'){
	$array = array('result' => 1);
	// 查询该用户名
	$sqlTool=new SqlTool();
	$username=$_SESSION['username'];

	if(!isset($_SESSION['username'])){
		$array['result']="failed";
	}
	$array['result']="ok";

	$array['html'] ="";
	$sql="SELECT * FROM microblog where owner='$username' order by publishtime desc";
	$res=$sqlTool->execute_dql($sql);
	while ($row=mysql_fetch_array($res)) {

		$sql1="SELECT * FROM userinfo WHERE username='".$row['owner']."'";
		$res1=$sqlTool->execute_dql($sql1);
		$row1=mysql_fetch_array($res1);

		if($row['trans_owner'] == ""){
			// 自己发的微博
			if($row['picture'] != ""){
				$array['html'] =$array['html'].'<div class="content">
				<div class="head_picture">
						<img src="'.$row1['headpicture'].'"/>
					</div>
					<div class="username">
						<a href=\"localhost\weibo\"><b>'.$username.'</b></a>
					</div>
					<div class="main_content">
						<div class="ccontent">'.$row['content'].'</div>
						<img src="'.$row['picture'].'"/><br/>
						<span class="publishtime">'.$row['publishtime'].'</span>
					</div>
					<div class="opt">
						<a href="#" onClick="face()">收藏</a>		
						<a href="#" class="transmit">转发</a>
						<a href="#" class="pub_comments">评论</a>
					</div>
					</div>';

			}else if($row['vedio'] != ""){
				$array['html'] =$array['html'].'<div class="content">
				<div class="head_picture">
						<img src="'.$row1['headpicture'].'"/>
					</div>
					<div class="username">
						<a href=\"localhost\weibo\"><b>'.$username.'</b></a>
					</div>
					<div class="main_content">
							'.$row['content'].'<br/>
							 <video controls="" autoplay="" name="media" width="320" height="240">
	  							<source src="'.$row['vedio'].'" type="video/mp4">
	  						</video><br/>
							<span class="publishtime">'.$row['publishtime'].'</span>
						</div>
					<div class="opt">
						<a href="#" onClick="face()">收藏</a>		
						<a href="#" class="transmit">转发</a>
						<a href="#" class="pub_comments">评论</a>
					</div>
					</div>';

			}else if($row['music'] != ""){
				$array['html'] =$array['html'].'<div class="content">
				<div class="head_picture">
						<img src="'.$row1['headpicture'].'"/>
					</div>
					<div class="username">
						<a href=\"localhost\weibo\"><b>'.$username.'</b></a>
					</div>
					<div class="main_content">
							'.$row['content'].'<br/>
							  <object data='.$row['music'].' /><br/>
							<span class="publishtime">'.$row['publishtime'].'</span>
						</div>
					<div class="opt">
						<a href="#" onClick="face()">收藏</a>		
						<a href="#" class="transmit">转发</a>
						<a href="#" class="pub_comments">评论</a>
					</div>
					</div>';
			}else{
				$array['html'] =$array['html'].'<div class="content">
				<div class="head_picture">
						<img src="'.$row1['headpicture'].'"/>
					</div>
					<div class="username">
						<a href=\"localhost\weibo\"><b>'.$username.'</b></a>
					</div>
					<div class="main_content">
						<div class="ccontent">'.$row['content'].'</div>
						<span class="publishtime">'.$row['publishtime'].'</span>
					</div>
					<div class="opt">
						<a href="#" onClick="face()">收藏</a>		
						<a href="#" class="transmit">转发</a>
						<a href="#" class="pub_comments">评论</a>
					</div>
					</div>';

			}
		}else{
			//转发的微博
			if($row['picture'] != ""){
				$array['html'] =$array['html'].'<div class="content">
				<div class="head_picture">
						<img src="'.$row1['headpicture'].'"/>
					</div>
					<div class="username">
						<a href=\"localhost\weibo\"><b>'.$username.'</b></a>
					</div>
					<div class="main_content">

						<div class="ccontent">@'.$row['content'].'</div>
						<img src="'.$row['picture'].'"/><br/>
						<span class="publishtime">'.$row['publishtime'].'</span>
					</div>
					<div class="opt">
						<a href="#" onClick="face()">收藏</a>		
						<a href="#" class="transmit">转发</a>
						<a href="#" class="pub_comments">评论</a>
					</div>
					</div>';
			}else if($row['vedio'] != ""){

			}else if($row['music'] != ""){

			}else{
				$array['html'] =$array['html'].'<div class="content">
				<div class="head_picture">
						<img src="'.$row1['headpicture'].'"/>
					</div>
					<div class="username">
						<a href=\"localhost\weibo\"><b>'.$username.'</b></a>
					</div>
					<div class="main_content">
						<div class="ccontent">@'.$row['content'].'</div>
					
						<span class="publishtime">'.$row['publishtime'].'</span>
					</div>
					<div class="opt">
						<a href="#" onClick="face()">收藏</a>		
						<a href="#" class="transmit">转发</a>
						<a href="#" class="pub_comments">评论</a>
					</div>
					</div>';

			}

		}
		
	}

	$sql="SELECT * FROM relationship where owner='$username'";
	$res=$sqlTool->execute_dql($sql);
	while($row=mysql_fetch_array($res)){
		$sql2="SELECT * FROM microblog where owner='".$row['attentions']."'";
		$res2=$sqlTool->execute_dql($sql2);
		//关注人的微博
		while ($row2=mysql_fetch_array($res2)) {

			$sql3="SELECT * FROM userinfo where username='".$row2['owner']."'";
			$res3=$sqlTool->execute_dql($sql3);
			$row3=mysql_fetch_array($res3);

			if($row2['picture'] != ""){
				$array['html'] =$array['html'].'<div class="content">
				<div class="head_picture">
						<img src="'.$row3['headpicture'].'"/>
					</div>
					<div class="username">
						<a href=\"localhost\weibo\"><b>'.$row2['owner'].'</b></a>
					</div>
					<div class="main_content">
						<div class="ccontent">'.$row2['content'].'</div>
						<img src="'.$row2['picture'].'"/><br/>
						<span class="publishtime">'.$row2['publishtime'].'</span>
					</div>
					<div class="opt">
						<a href="#" onClick="face()">收藏</a>		
						<a href="#" class="transmit">转发</a>
						<a href="#" class="pub_comments">评论</a>
					</div>
					</div>';
			}else{
				$array['html'] =$array['html'].'<div class="content">
				<div class="head_picture">
						<img src="'.$row3['headpicture'].'"/>
					</div>
					<div class="username">
						<a href=\"localhost\weibo\">'.$row2['owner'].'</a>
					</div>
					<div class="main_content">
						<div class="ccontent">'.$row2['content'].'</div>
						<span class="publishtime">'.$row2['publishtime'].'</span>
					</div>
					<div class="opt">
						<a href="#" onClick="face()">收藏</a>		
						<a href="#" class="transmit">转发</a>
						<a href="#" class="pub_comments">评论</a>
					</div>
					</div>';
			}
					
		}
	}

	$array['username']=$_SESSION['username'];
	$array['attentions']=$_SESSION['attentions'];
	$array['fans']=$_SESSION['fans'];
	$array['microblog']=$_SESSION['microblog'];
	$array['picture']=$_SESSION['headpicture'];


	$json=json_encode($array);
	echo $_REQUEST['h'].'('.$json.')';
}

if($_REQUEST['action']=='publish'){
	$array = array('result' => 1);
	// 查询该用户名
	$sqlTool=new SqlTool();
	$username=$_SESSION['username'];
	if(!isset($_SESSION['username'])){
		$array['result']="failed";
	}
	$array['result']="ok";
	
	$content=trim($_REQUEST['content']);
	$pic_path=$_SESSION['pic_path'];
	$vedio_path=$_SESSION['vedio_path'];
	$music_path=$_SESSION['music_path'];

	$sql="SELECT * FROM userinfo WHERE username='$username'";
	$res=$sqlTool->execute_dql($sql);
	$row=mysql_fetch_array($res);

	date_default_timezone_set("Asia/Shanghai");
	$pub_time=date('Y-m-d h:m:s');

	$biaoqing = array("拜拜","不能忍","吃饭","大哭","大笑","干杯","汗","好吃","开心","衰");
		$count=count($biaoqing);
		for($i=0;$i<$count;$i++){
			$content=str_replace('['.$biaoqing[$i].']', '<img src="php/imgs/'.$biaoqing[$i].'.gif" style="width:25px;height:25px;"/>', $content);
	}
	//插入微博
	if(isset($_SESSION['pic_path'])){
	
		//图片消息
		$sql="INSERT INTO microblog(owner,content,publishtime,picture) value('$username','$content',NOW(),'$pic_path')";
		// $sql="SELECT * FROM userinfo where username='$username'";
		$res=$sqlTool->execute_dml($sql);
		if($res==1){
			$array['result']="ok1";
			$array['html']='<div class="content">
					<div class="head_picture">
						<img src="'.$row['headpicture'].'"/>
					</div>
					<div class="username">
						<a href="localhost\weibo\"><b>'.$username.'</b></a>
					</div>
					<div class="main_content">
						'.$content.'<br/>
						<img src="'.$pic_path.'"/><br/>
						<span class="publishtime">'.$pub_time.'</span>
					</div>
					<div class="opt">
						<a href="#" onClick="face()">收藏</a>		
						<a href="#" class="transmit">转发</a>
						<a href="#" class="pub_comments">评论</a>
					</div>
				</div>';
			
		}else{
			$array['result']="failed";
		}	
		unset($_SESSION['pic_path']);
	}else if(isset($_SESSION['vedio_path'])){
		//视频消息
		$sql="INSERT INTO microblog(owner,content,publishtime,vedio) value('$username','$content',NOW(),'$vedio_path')";
		// $sql="SELECT * FROM userinfo where username='$username'";
		$res=$sqlTool->execute_dml($sql);
		if($res==1){
			$array['result']="ok1";
			$array['html']='<div class="content">
					<div class="head_picture">
						<img src="'.$row['headpicture'].'"/>
					</div>
					<div class="username">
						<a href="localhost\weibo\"><b>'.$username.'</b></a>
					</div>
					<div class="main_content">
						'.$content.'<br/>
						 <video controls="" autoplay="" name="media" width="320" height="240">
  							<source src="'.$vedio_path.'" type="video/mp4">
  						</video><br/>
						<span class="publishtime">'.$pub_time.'</span>
					</div>
					<div class="opt">
						<a href="#" onClick="face()">收藏</a>		
						<a href="#" class="transmit">转发</a>
						<a href="#" class="pub_comments">评论</a>
					</div>
				</div>';
			
		}else{
			$array['result']="failed";
		}	
		unset($_SESSION['vedio_path']);
	}else if(isset($_SESSION['music_path'])){
				//音乐消息
		$sql="INSERT INTO microblog(owner,content,publishtime,music) value('$username','$content',NOW(),'$music_path')";
		// $sql="SELECT * FROM userinfo where username='$username'";
		$res=$sqlTool->execute_dml($sql);
		if($res==1){
			$array['result']="ok1";
			$array['html']='<div class="content">
					<div class="head_picture">
						<img src="'.$row['headpicture'].'"/>
					</div>
					<div class="username">
						<a href="localhost\weibo\"><b>'.$username.'</b></a>
					</div>
					<div class="main_content">
						'.$content.'<br/>
						 <object data='.$music_path.' /><br/>
						<span class="publishtime">'.$pub_time.'</span>
					</div>
					<div class="opt">
						<a href="#" onClick="face()">收藏</a>		
						<a href="#" class="transmit">转发</a>
						<a href="#" class="pub_comments">评论</a>
					</div>
				</div>';
			
		}else{
			$array['result']="failed";
		}	
		unset($_SESSION['music_path']);
	}else{
		$sql="INSERT INTO microblog(owner,content,publishtime) value('$username','$content',NOW())";
		// $sql="SELECT * FROM userinfo where username='$username'";
		$res=$sqlTool->execute_dml($sql);
		if($res==1){
			$array['result']="ok4";
			$array['html']='<div class="content">
					<div class="head_picture">
						<img src="'.$_SESSION['headpicture'].'"/>
					</div>
					<div class="username">
						<a href="localhost\weibo\">'.$username.'</a>
					</div>
					<div class="main_content">
						'.$content.'<br/>
						<span class="publishtime">'.$pub_time.'</span>
					</div>
					<div class="opt">
						<a href="#" onClick="face()">收藏</a>		
						<a href="#" class="transmit">转发</a>
						<a href="#" class="pub_comments">评论</a>
					</div>
				</div>';
		}else{
			$array['result']="failed";
		}
		
	}
	//微博数量更新
	update_info();

	$array['microblog']=$_SESSION['microblog'];

	$json=json_encode($array);
	echo $_REQUEST['p'].'('.$json.')';
}

if($_REQUEST['action']=="comments"){
	$array = array('result' => 1);
	// 查询该用户名
	$sqlTool=new SqlTool();

	$com_owner=trim($_REQUEST['com_owner']);
	$content=trim($_REQUEST['com_content']);
	$com_person=$_SESSION['username'];
	$weibo_content=trim($_REQUEST['weibo_content']);

	// 找到该微博
	$sql="SELECT * FROM microblog WHERE content='$weibo_content';";
	$res=$sqlTool->execute_dql($sql);
	if($row=mysql_fetch_array($res)){
		$weibo_id=$row['id'];
		$array['result']="weibo".$weibo_id;
	}else{
		$array['result']="failed".$weibo_id.$weibo_content;
	}

	date_default_timezone_set("Asia/Shanghai");
	$com_time=date('Y-m-d H:i:s');

	// 插入微博
	$sql="INSERT INTO comments(weibo_id,com_owner,content,com_person,com_time) value('$weibo_id','$com_owner','$content','$com_person',NOW())";
	// $sql="SELECT * FROM userinfo where username='$username'";
	 // $sql="UPDATE comments SET com_owner='$com_owner',content='$content',com_person='$com_person',com_time=NOW() WHERE weibo_id='$weibo_id'";
	$res=$sqlTool->execute_dml($sql);
	if($res==1){
		$array['result']="ok";
		$array['html']='<div class="other">
                <div class="head_picture2">
                    <img src="'.$_SESSION['headpicture'].'"/>
                </div>
                <div class="username">
                    <a href="">'.$com_person.':</a>
                </div>
                <div class="com_com">
                    '.$content.'<br/>
                    <span class="com_time">'.$com_time.'</span>
                </div>
            </div>';

	}else{
		$array['result']="failedddd";
	}

	$json=json_encode($array);
	echo $_REQUEST['c'].'('.$json.')';
}

if($_REQUEST['action']=="show_comments"){
	$array = array('result' => 1);
	// 查询该用户名
	$sqlTool=new SqlTool();
	$weibo_content=trim($_REQUEST['weibo_content']);
	date_default_timezone_set("Asia/Shanghai");
	$com_time=date('Y-m-d H:i:s');
	// 找到该微博
	$sql="SELECT * FROM microblog WHERE content='$weibo_content';";
	$res=$sqlTool->execute_dql($sql);
	if($row=mysql_fetch_array($res)){
		$weibo_id=$row['id'];
		$array['result']="weibo".$weibo_id;
	}else{
		$array['result']="failed".$weibo_id.$weibo_content;
	}

	$sql="SELECT * FROM comments WHERE weibo_id='$weibo_id'";
	$res=$sqlTool->execute_dql($sql);

	while($row=mysql_fetch_array($res)){
		$sql2="SELECT * FROM userinfo WHERE username='".$row['com_person']."'";
		$res2=$sqlTool->execute_dql($sql2);
		$row2=mysql_fetch_array($res2);
		$array['result']="ok";
		$array['html'] .= '<div class="other">
                <div class="head_picture2">
                    <img src="'.$row2['headpicture'].'"/>
                </div>
                <div class="username">
                    <a href="">'.$row['com_person'].':</a>
                </div>
                <div class="com_com">
                    '.$row['content'].'<br/>
                    <span class="com_time">'.$com_time.'</span>
                </div>
            </div>';

	}

	$json=json_encode($array);
	echo $_REQUEST['sh'].'('.$json.')';
}


if($_REQUEST['action']=="discover"){
	$array = array('result' => 1);
	// 查询该用户名
	$sqlTool=new SqlTool();
	$username=$_REQUEST['username'];
	// $content=$_REQUEST['content'];
	//插入微博
	// $sql="INSERT INTO microblog(owner,content,publishtime,comments) value('$username','$content',NOW(),0)";
	$sql="SELECT * FROM userinfo ";
	$res=$sqlTool->execute_dql($sql);
	while($row=mysql_fetch_array($res)){
		// $array['result']="ok";
		if($row['username']==$username){
			$array['username']=$username;
			// $array['attentions']=$row['attentions'];
			// $array['fans']=$row['fans'];
			// $array['microblog']=$row['microblog'];

		}else{
			$array['html']=$array['html'].'<div class="discover">
					<div class="head_picture3">
						<img src="'.$row['headpicture'].'"/>
					</div>
					<div class="username1">
						<span class="username_att">'.$row['username'].'</span>
					</div>
					<button class="attention_button">关注</button>
					<button class="latter_button">私信</button>
				</div>';
			}
	}
	update_info();
	$array['username']=$_SESSION['username'];
	$array['attentions']=$_SESSION['attentions'];
	$array['fans']=$_SESSION['fans'];
	$array['microblog']=$_SESSION['microblog'];
	$array['picture']=$_SESSION['headpicture'];
	$json=json_encode($array);
	echo $_REQUEST['d'].'('.$json.')';
}


if($_REQUEST['action']=="attention"){
	$array = array('result' => 1);
	// 查询该用户名
	$sqlTool=new SqlTool();
	$username=$_SESSION['username'];
	$attentionname=trim($_REQUEST['attentionname']);
	
	$sql="SELECT * FROM relationship WHERE owner='$username' and attentions='$attentionname'";
	$res=$sqlTool->execute_dql($sql);
	$row=mysql_fetch_array($res);
	if($row>0){
		//取消关注
		$sql="DELETE FROM relationship WHERE owner='$username' and attentions='$attentionname'";
		// $sql="SELECT * FROM userinfo ";
		$res=$sqlTool->execute_dml($sql);
		if($res==1){ 
			$array['result']="ok";
		}else{
			$array['result']="failed1";
		}
	}else{
		//关注
		$sql="INSERT INTO relationship(owner,attentions) value('$username','$attentionname')";
		// $sql="SELECT * FROM userinfo ";
		$res=$sqlTool->execute_dml($sql);
		if($res==1){ 
			$array['result']="ok".$row;
		}else{
			$array['result']="failed";
		}
	}

	$sql1="SELECT count(*) FROM relationship WHERE owner='$username'";
	$res1=$sqlTool->execute_dql($sql1);
	$row1=mysql_fetch_array($res1);
	$_SESSION['attentions']=$row1[0];
	// update_info();
	$array['attentions']=$_SESSION['attentions'];
	
	$json=json_encode($array);
	echo $_REQUEST['att'].'('.$json.')';
}

//转发信息
if($_REQUEST['action']=="transmit"){
	$array = array('result' =>'1');
	$tran_content=trim($_REQUEST['tran_content']);
	$tran_username=trim($_REQUEST['tran_username']);
	$tran_content_pic=trim($_REQUEST['tran_content_pic']);
	$username=trim($_SESSION['username']);
	date_default_timezone_set('Asia/Shanghai'); 
	$tran_time=date('Y-m-d H:i:s');
	//将该微博插入到用户的微博下
	$sqlTool = new SqlTool();
	if($tran_content_pic == ""){
		$sql = "INSERT INTO microblog(owner,content,publishtime,trans_owner) value('$username','$tran_content',NOW(),'$tran_username')";
		$res=$sqlTool->execute_dml($sql);
		if($res==1){
			$array['result']="ok";
			$array['html']='<div class="content">
					<div class="head_picture">
						<img src="'.$_SESSION['headpicture'].'"/>
					</div>
					<div class="username">
						<a href="localhost\weibo\"><b>'.$username.'</b></a>
					</div>
					<div class="main_content">
						<div class="ccontent">@转发'.$tran_username.'<br/>'.$tran_content.'</div>
						<span class="publishtime">'.$tran_time.'</span>
					</div>
					<div class="opt">
						<a href="#" onClick="face()">收藏</a>		
						<a href="#" class="transmit">转发</a>
						<a href="#" class="pub_comments">评论</a>
					</div>
				</div>';
		}else{
			$array['result']="failed";
		}	
	}else{
		$sql = "INSERT INTO microblog(owner,content,publishtime,trans_owner,picture) value('$username','$tran_content',NOW(),'$tran_username','$tran_content_pic')";
		$res=$sqlTool->execute_dml($sql);
		if($res==1){
			$array['result']="ok";
			$array['html']='<div class="content">
					<div class="head_picture">
						<img src="'.$_SESSION['headpicture'].'"/>
					</div>
					<div class="username">
						<a href="localhost\weibo\"><b>'.$username.'</b></a>
					</div>
					<div class="main_content">
						<div class="ccontent">@转发'.$tran_username.'<br/>'.$tran_content.'</div>
						<img src="'.$tran_content_pic.'"/><br/>
						<span class="publishtime">'.$tran_time.'</span>
					</div>
					<div class="opt">
						<a href="#" onClick="face()">收藏</a>		
						<a href="#" class="transmit">转发</a>
						<a href="#" class="pub_comments">评论</a>
					</div>
				</div>';
		}else{
			$array['result']="failed";
		}	
	}
	update_info();
	$array['microblog']=$_SESSION['microblog'];

	$json=json_encode($array);
	echo $_REQUEST['tr'].'('.$json.')';
}

if($_REQUEST['action']=="private"){
	$array = array('result'=>'1');
	$sqlTool=new SqlTool();
	$username=$_SESSION['username'];
	$sql1="SELECT * FROM privatelatter WHERE toperson='$username'";
	$res1=$sqlTool->execute_dql($sql1);

	while ($row1=mysql_fetch_array($res1)) {
		$sql="SELECT * FROM userinfo WHERE username='".$row1['fromperson']."'";
		$res=$sqlTool->execute_dql($sql);
		$row=mysql_fetch_array($res);
		$array['html']=$array['html'].'<div class="private">
		<div class="latter">
			<div class="head_picture5">
				<img src="'.$row['headpicture'].'"/>
			</div>
				<button class="latter_button" >私信</button>
			<div class="username1">
				<span class="username_att">'.$row['username'].'</span>
			</div>
			<div class="latter_content">
				'.$row1['lattercontent'].'
			</div>		
		</div></div>';
	}


	$array['username']=$_SESSION['username'];
	$array['attentions']=$_SESSION['attentions'];
	$array['fans']=$_SESSION['fans'];
	$array['microblog']=$_SESSION['microblog'];
	$array['picture']=$_SESSION['headpicture'];
	$json=json_encode($array);
	echo $_REQUEST['p'].'('.$json.')';

}

if($_REQUEST['action']=="lattermeg"){
	$array = array('result'=>'1');
	$username=$_SESSION['username'];
    $latter_name=trim($_REQUEST['latter_name']);
    $latter_content=trim($_REQUEST['latter_content']);
	// $content=$_REQUEST['content'];
	//插入微博
	$sqlTool=new SqlTool();
	$sql="INSERT INTO privatelatter(fromperson,lattercontent,toperson,lattertime) value('$username','$latter_content','$latter_name',NOW())";
	// $sql="SELECT * FROM userinfo ";
	$res=$sqlTool->execute_dml($sql);
	if($res==1){
		$array['result']="ok";
	}else{
		$array['result']="failed";
	}
	$json=json_encode($array);
	echo $_REQUEST['la'].'('.$json.')';

}

if($_REQUEST['action']=="logout"){
	$array = array('result'=>'1');
	$array['result']="退出成功";
	$json=json_encode($array);
	echo $_REQUEST['la'].'('.$json.')';

	unset($_SESSION);
	session_destroy();	
}

function update_info(){
	$username=$_SESSION['username'];
	$sqlTool=new SqlTool();
	$sql1="SELECT count(*) FROM relationship WHERE owner='$username'";
	$res1=$sqlTool->execute_dql($sql1);
	$row1=mysql_fetch_array($res1);
	$_SESSION['attentions']=$row1[0];

	$sql2="SELECT count(*) FROM relationship WHERE attentions='$username'";
	$res2=$sqlTool->execute_dql($sql2);
	$row2=mysql_fetch_array($res2);
	$_SESSION['fans']=$row2[0];

	$sql3="SELECT count(*) FROM microblog WHERE owner='$username'";
	$res3=$sqlTool->execute_dql($sql3);
	$row3=mysql_fetch_array($res3);
	$_SESSION['microblog']=$row3[0];

}
?>