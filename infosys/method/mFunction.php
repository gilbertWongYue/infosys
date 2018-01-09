<?php  
	
	/*
	*��ѯ����
	*���ض�ά����$_array[$i][$j]
	*$iΪ������$jΪ�ֶ���ϸ
	*/
	function selectArray($sql){
		global $dbh;
		try{
			$i=0;
			$res=$dbh->prepare($sql);
			$res->execute()||die("$sql");
			while($result=$res->fetch(PDO::FETCH_ASSOC)){
				/* for($j=0;$j<(count($result));$j++){
					//��ȡ��ֵ($result[$j])��Ӧ�ļ���($keys)
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
	*mysql ��ѯ����
	*����ͬ����selectArray()һ��
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
	*�޸����ݿⷽ��
	*$sql_arrayΪ���飬ÿ��Ԫ��Ϊsql ���
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
					throw new PDOException($sql_array[$i]."��������ϵ��Ѷ����");
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
	* ��������
	*/
	function propertyNeed(){
		$pro_arr = array(
			
			'1' => '��ϵͳ����',
			'2' => '���̱��',
			'3' => '���ܱ��',
			'4' => '�߼����',
			'5' => '��ѯ���漰������',
			'6' => 'Ȩ�ޱ��',
			'7' => '���ϴ���',
			'8' => '����',
			'9' => '���ϼ���',
			'A' => '�������Ŵ������'
		);
		return $pro_arr;
	
	}
	
	function queLevel(){
		$que_arr = array(
			'B' => 'Ӧ��ϵͳ�޷�ʹ��',
			'A' => 'ϵͳ�޷�ʹ��',
			'C' => '���˻� PC �޷�ʹ��',
			'D' => 'ѯ����Ŀ',
			'E' => '��·�޷�ʹ��'
		);
		return $que_arr;
	}
	
	function applyReason(){
		$rea_arr = array(
			'1' => 'ÿ�¹̶���ҵ-������ϵͳ��',
			'2' => '���ί�в���-������ϵͳ��',
			'3' => '���ί�в���-�޷�ϵͳ��',
			'4' => '��Ѷ��Ա��д��ʽ����'
		);
		return $rea_arr;
	}
	function satisfyResearch(){
		$sat_arr = array(
			'0' => '�ǳ�������',
			'1' => '������',
			'2' => '����',
			'3' => '�ǳ�����'
		);
		return $sat_arr;
	}
?>