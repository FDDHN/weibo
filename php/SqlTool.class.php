<?php
	
	class SqlTool{
		private $conn;
		private $host="localhost";
		private $user="root";
		private $password="123";
		private $db="weibo";
		
		function SqlTool()
		{
			$this->conn=mysql_connect($this->host,$this->user,$this->password);
			
			if(!$this->conn)
			{
				die("连接数据库失败".mysql_error);
			}
			mysql_select_db($this->db,$this->conn);
			mysql_query("set names utf8");
		}
		
		//完成select
		public function execute_dql($sql){
			$res = mysql_query($sql,$this->conn) or die(mysql_error());
		//	echo "添加的id=".mysql_insert_id($this->conn);
			return $res;
		}
		 
		//完成upadate delete insert
		function execute_dml($sql)
		{
			$b=mysql_query($sql,$this->conn);
			if(!$b)
			{
				return 0;
			}else{
				if(mysql_affected_rows($this->conn)>0)
				{
					return 1;//表示成功
				}else{
					return 2;//没有影响行数
				}
			}
		}
	}

?>