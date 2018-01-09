<?php  
	
	/*
	*查询方法
	*返回二维数组$_array[$i][$j]
	*$i为行数，$j为字段明细
	*/
	function selectArray($sql){
		global $dbh;
		try{
			$i=0;
			$res=$dbh->prepare($sql);
			$res->execute()||die("$sql");
			while($result=$res->fetch(PDO::FETCH_ASSOC)){
				/* for($j=0;$j<(count($result));$j++){
					//获取健值($result[$j])对应的键名($keys)
					// $keys=array_search($result[$j],$result);
					$keys = array_keys($result[$j]);
					$_array[$i][$keys]=$result[$j];
				} */
				foreach ($result as $key => $value){
					$_array[$i][$key] =$value;
				}
				$i++;
			}
			return $_array;
		}catch(PDOException $e){
			echo $e->getTraceAsString();
			exit($e->getMessage());
		}
	}
	/*
	*mysql 查询方法
	*返回同方法selectArray()一致
	*/
	function selectMysql($sql1){
	
		try{
			$i=0;
			$_stmt=mysql_query($sql1);
			
			while($result=mysql_fetch_array($_stmt,MYSQL_NUM)){
				for($j=0;$j<(count($result));$j++){
					$_array[$i][$j]=$result[$j];
				}
				$i++;
			}
			return $_array;
			
		}catch(PDOException $e){
			exit($e->getMessage());
		}
	}
	/*
	*修改数据库方法
	*$sql_array为数组，每个元素为sql 语句
	*include  insert ,update ,delete 
	*/
	function insertDb($sql_array){
		global $dbh;
		try{
			$dbh->setAttribute(PDO::ATTR_AUTOCOMMIT,false);
			$dbh->beginTransaction();
			for($i=0;$i<count($sql_array);$i++){
				$res=$dbh->exec($sql_array[$i]);
				if(!$res){
					throw new PDOException($sql_array[$i]."错误，请联系资讯处理");
				}
			}
			$res=$dbh->commit();
			$dbh->setAttribute(PDO::ATTR_AUTOCOMMIT,true);
		}catch(PDOException $e){
			$res=$dbh->rollback();
			exit($e->getMessage());
		}
		
	}
	/*
	* 需求性质
	*/
	function propertyNeed(){
		$pro_arr = array(
			
			'1' => '新系统开发',
			'2' => '流程变更',
			'3' => '功能变更',
			'4' => '逻辑变更',
			'5' => '查询画面及报表变更',
			'6' => '权限变更',
			'7' => '资料处理',
			'8' => '其它',
			'9' => '资料检索',
			'A' => '其他部门处理完成'
		);
		return $pro_arr;
	
	}
	
	function queLevel(){
		$que_arr = array(
			'B' => '应用系统无法使用',
			'A' => '系统无法使用',
			'C' => '个人或 PC 无法使用',
			'D' => '询问项目',
			'E' => '网路无法使用'
		);
		return $que_arr;
	}
	
	function applyReason(){
		$rea_arr = array(
			'1' => '每月固定作业-后续拟系统化',
			'2' => '配合委托部门-后续拟系统化',
			'3' => '配合委托部门-无法系统化',
			'4' => '资讯人员书写程式错误'
		);
		return $rea_arr;
	}
	function satisfyResearch(){
		$sat_arr = array(
			'0' => '非常不满意',
			'1' => '不满意',
			'2' => '满意',
			'3' => '非常满意'
		);
		return $sat_arr;
	}
?>