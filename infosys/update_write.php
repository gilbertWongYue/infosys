<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=gb2312" />
	<title>��Ѷ��ϵͳǩ����ҵ</title>
	<link href="css/menu.css" rel="stylesheet" type="text/css" />
	<link href="css/calendar.css" rel="stylesheet" type="text/css" />
	<script src="js/jquery-1.11.1.min.js" type="text/javascript"></script>
	<script src="js/calendar.js" type="text/javascript"></script>
	<script src="js/calendar-setup.js" type="text/javascript"></script>
	<script src="js/calendar-zh.js" type="text/javascript"></script>
</head>
<body>
<?php require("./inc/common.inc");
	
	
	
	
	$change = $_POST['change'];
	if(empty($change)){
		echo "<p style='color:red;font-size:28px'>��ѡ��������<br />
			<a href='javascript:history.go(-1)'>����һҳ</a></p>";
			exit();
	}
	
	/* if(count($next_user) == 0){
		echo "<p style='color:red;font-size:28px'>��ѡ����һλǩ����<br />
			<a href='javascript:history.go(-1)'>����һҳ</a></p>";
			exit();
	}
	if($current_user != $ID){
			echo "<p style='color:red;font-size:28px'>��ǰǩ����Ϊ $current_user , ����Ȩ��ǩ�˴����뵥<br />
			<a href='javascript:history.go(-1)'>����һҳ</a></p>";
			exit();
	} */
	try{
		$dbh->setAttribute(PDO::ATTR_AUTOCOMMIT,false);
		$dbh->beginTransaction();
		
		if($change == 'C1'){
			$sheet_no1 = trim($_POST['sheet_no1']);
			list($sh_num) = fields("select count(*) from inf_need_main where sheet_no='$sheet_no1'");
			if($sh_num == 0){
				echo "<p style='color:red;font-size:28px'>��Ѷ���뵥��Ϊ�ջ��߲����ڣ�<br />
			<a href='javascript:history.go(-1)'>����һҳ</a></p>";
			exit();
			}
			$sql1 = "update inf_need_main set pre_finish_date='$pre_finish_date' where sheet_no='$sheet_no1'";
		}
		if($change == 'C2'){
			$sheet_no2 = trim($_POST['sheet_no2']);
			list($sh_num) = fields("select count(*) from inf_need_main where sheet_no='$sheet_no2'");
			if($sh_num == 0){
				echo "<p style='color:red;font-size:28px'>��Ѷ���뵥��Ϊ�ջ��߲����ڣ�<br />
			<a href='javascript:history.go(-1)'>����һҳ</a></p>";
			exit();
			}
			$sql1 = "update subflow set current_user='$next_user' where form_key in (select form_key from inf_need_main where sheet_no='$sheet_no2')";
		}
		if($change == 'C3'){
			$sheet_no3 = trim($_POST['sheet_no3']);
			list($sh_num) = fields("select count(*) from inf_need_main where sheet_no='$sheet_no3'");
			if($sh_num == 0){
				echo "<p style='color:red;font-size:28px'>��Ѷ���뵥��Ϊ�ջ��߲����ڣ�<br />
			<a href='javascript:history.go(-1)'>����һҳ</a></p>";
			exit();
			}
			list($assigner,$form_key) = fields("select assigner,form_key from assign where form_key in (select form_key from inf_need_main where sheet_no='$sheet_no3') and flow_code='B' order by assign_date desc") ;
			$sql1 = "update subflow set current_user='$assigner' where form_key='$form_key'";
			$sql2 = "update flow set flow_code='B' where form_key='$form_key'";
			$res=$dbh->exec($sql2);
			if(!$res){
				throw new PDOException($sql2."��������ϵ��Ѷ����");
			}
			list($ws_num) = fields("select count(*) from workflow_subflow where parent_key='$form_key'");
			if($ws_num > 0 ){
				$sql3 = "update workflow_subflow set reply_flag='Y' where parent_key='$form_key'";
				$res=$dbh->exec($sql3);
				if(!$res){
					throw new PDOException($sql3."��������ϵ��Ѷ����");
				}
			}
			
			
		}
		if($change == 'C4'){
			$sheet_no4 = trim($_POST['sheet_no4']);
			list($sh_num) = fields("select count(*) from inf_need_main where sheet_no='$sheet_no4'");
			if($sh_num == 0){
				echo "<p style='color:red;font-size:28px'>��Ѷ���뵥��Ϊ�ջ��߲����ڣ�<br />
			<a href='javascript:history.go(-1)'>����һҳ</a></p>";
			exit();
			}
			$sql1 = "update assign set assign_date='$fh_date' where form_key in (select form_key from inf_need_main where sheet_no='$sheet_no4') and flow_code='B' and assign_status='FH'";
		}
		if($change == 'C5'){
			$sheet_no5 = trim($_POST['sheet_no5']);
			list($sh_num) = fields("select count(*) from inf_need_main where sheet_no='$sheet_no5'");
			if($sh_num == 0){
				echo "<p style='color:red;font-size:28px'>��Ѷ���뵥��Ϊ�ջ��߲����ڣ�<br />
			<a href='javascript:history.go(-1)'>����һҳ</a></p>";
			exit();
			}
			list($form_key) = fields("select form_key from inf_need_main where sheet_no='$sheet_no5'");
			$sql1 = "update subflow set current_user='00000' where form_key='$form_key'";
			$sql2 = "update flow set flow_code='Z' where form_key='$form_key'";
			$res=$dbh->exec($sql2);
			if(!$res){
				throw new PDOException($sql2."��������ϵ��Ѷ����");
			}
			list($ws_num) = fields("select count(*) from workflow_subflow where parent_key='$form_key'");
			if($ws_num > 0){
				$sql3 = "update subflow set current_user='00000' where form_key in (select form_key from workflow_subflow where parent_key='$form_key')";
				$sql4 = "update flow set flow_code='Z' where form_key in (select form_key from workflow_subflow where parent_key='$form_key')";
				
				$res=$dbh->exec($sql3);
				if(!$res){
					throw new PDOException($sql3."��������ϵ��Ѷ����");
				}
				$res=$dbh->exec($sql4);
				if(!$res){
					throw new PDOException($sql4."��������ϵ��Ѷ����");
				}
			}
			$sql5 = "update inf_need_main set finish_date=sysdate where sheet_no='$sheet_no5' ";
			$res=$dbh->exec($sql5);
			if(!$res){
				throw new PDOException($sql5."��������ϵ��Ѷ����");
			}
		}
		if($change == 'C6'){
			$sheet_no6 = trim($_POST['sheet_no6']);
			list($sh_num) = fields("select count(*) from inf_need_main where sheet_no='$sheet_no6'");
			if($sh_num == 0){
				echo "<p style='color:red;font-size:28px'>��Ѷ���뵥��Ϊ�ջ��߲����ڣ�<br />
			<a href='javascript:history.go(-1)'>����һҳ</a></p>";
			exit();
			}
			if(empty($sys_code)){
				echo "<p style='color:red;font-size:28px'>��ѡ��ϵͳ����<br />
			<a href='javascript:history.go(-1)'>����һҳ</a></p>";
			exit();
			}
			$sql1 = "update inf_need_syscheck set sys_code='$sys_code' where sheet_no='$sheet_no6'";
		}
		if($change == 'C7'){
			$sheet_no7 = trim($_POST['sheet_no7']);
			list($sh_num) = fields("select count(*) from inf_need_main where sheet_no='$sheet_no7'");
			if($sh_num == 0){
				echo "<p style='color:red;font-size:28px'>��Ѷ���뵥��Ϊ�ջ��߲����ڣ�<br />
			<a href='javascript:history.go(-1)'>����һҳ</a></p>";
			exit();
			}
			if(empty($need_kind)){
				echo "<p style='color:red;font-size:28px'>��ѡ�������<br />
			<a href='javascript:history.go(-1)'>����һҳ</a></p>";
			exit();
			}
			$sql1 = "update inf_need_syscheck set sys_needkind='$need_kind'  where sheet_no='$sheet_no7'";
		}
		if($change == 'C8'){
			$sheet_no8 = trim($_POST['sheet_no8']);
			list($sh_num) = fields("select count(*) from inf_need_main where sheet_no='$sheet_no8'");
			if($sh_num == 0){
				echo "<p style='color:red;font-size:28px'>��Ѷ���뵥��Ϊ�ջ��߲����ڣ�<br />
			<a href='javascript:history.go(-1)'>����һҳ</a></p>";
			exit();
			}
			if(empty($apply_type)){
				echo "<p style='color:red;font-size:28px'>��ѡ������ԭ��<br />
			<a href='javascript:history.go(-1)'>����һҳ</a></p>";
			exit();
			}
			$sql1 = "update inf_need_main set apply_type='$apply_type'  where sheet_no='$sheet_no8'";
		}
		$res=$dbh->exec($sql1);
		if(!$res){
			throw new PDOException($sql1."��������ϵ��Ѷ����");
		}
		// echo $sql1.'<br>'.$sql2.'<br>'.$sql3.'<br>'.$sql4.'<br>'.$sql5;
		$dbh->commit();
		// $res=$dbh->rollback();
	}catch(PDOException $e){
		$res=$dbh->rollback();
		echo $e->getTraceAsString();
		exit($e->getMessage());
	}
	echo "<h1>��Ѷ���뵥�޸ĳɹ�</h1>";
	echo "<h1><a href='sign_ke_list.php'>����Ѷ�Ƴ�ָ�ɻ���</a></h1>";
	echo "<h1><a href='http://w3.yungtay.com.cn/'>��������ҳ</a></h1>";