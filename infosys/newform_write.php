<?php session_start();?>
<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=gb2312" />
	<title>��Ѷ������ϵͳ</title>
	<link href="css/menu.css" rel="stylesheet" type="text/css" />
	<link href="css/calendar.css" rel="stylesheet" type="text/css" />
	
</head>
<body>

<?php require("./inc/common.inc");?>

<div>

<?php 
$dockind = $_POST['dockind'];


if(isset($_POST['sess_code'])){
	if($_POST['sess_code'] == $_SESSION['sess_code']){
		echo "<p style='color:red;font-size:28px'>������뵥���ύ�ɹ����벻Ҫ�ظ��ύ<br />";
		echo "<h1><a href='newform$dockind.php'>��дһ��</a></h1>";
		echo "<h1><a href='http://w3.yungtay.com.cn/'>��������ҳ</a></h1>";
		exit();
	}
}


if($dockind == 'A'){
	$is_public = $_POST['is_public'];
	$apply_man = implode(',',$_POST['apply_man']);
	$chrundate = $_POST['chrundate'];
	$chaccount = $_POST['chaccount'];
	$chpower = $_POST['chpower'];
	$chplatform = $_POST['chplatform'];
	$chitem = $_POST['chitem'];
	$chreason = $_POST['chreason'];
	$chmail = $_POST['chmail'];
	$next_user = $_POST['next_user'];
	
	if(count($chpower) == 0){
		echo "<p style='color:red;font-size:28px'>��ѡ��ͨȨ�����<br />
		<a href='javascript:history.go(-1)'>����һҳ</a></p>";
		exit();
	}else{
		$chpower = implode(',',$chpower);
	}
	
	if(count($chplatform) == 0){
		echo "<p style='color:red;font-size:28px'>��ѡ���迪ͨ����ҵƽ̨<br />
		<a href='javascript:history.go(-1)'>����һҳ</a></p>";
		exit();
	}else{
		$chplatform = implode(',',$chplatform);
	}
	if(empty($next_user)){
		echo "<p style='color:red;font-size:28px'>û����һλǩ���ˣ�����ϵ��Ѷ����<br />
		<a href='javascript:history.go(-1)'>����һҳ</a></p>";
		exit();
	}
	
	$yymm = date('Ym');
	$ini_sheet_no = $yymm.'000';
	list($sheet_no) = fields("select nvl(max(sheet_no),$ini_sheet_no)+1 from inf_need_main where sheet_no like '$yymm%'");
	list($form_key) = fields("select flow_sequence12.nextval from dual");
	try{
		$dbh->setAttribute(PDO::ATTR_AUTOCOMMIT,false);
		$dbh->beginTransaction();
		$sql1 = "insert into inf_need_main
		(sheet_no,doc_kind,form_key,creator,create_date,apply_person,apply_depart,is_public,apply_type,vip_code) 
		values('$sheet_no','$dockind','$form_key','$ID',sysdate,'$apply_man','$depart_no','$is_public','','')";
		
		$sql2 = "insert into inf_need_plat (sheet_no,ch_platform,ch_account,ch_power,ch_rundate,ch_item,ch_reason,
		ch_mail) values ('$sheet_no','$chplatform','$chaccount','$chpower','$chrundate','$chitem','$chreason','$chmail')";
		
		$sql3 = "insert into flow (system_id,form_id,form_key,flow_code) values('inf','inf','$form_key','$next_code')";
		$sql4 = "insert into subflow (form_key,seq,current_user) values ('$form_key','0','$next_user')";
		
		$res=$dbh->exec($sql1);
		if(!$res){
			throw new PDOException($sql1."��������ϵ��Ѷ����");
		}
		$res=$dbh->exec($sql2);
		if(!$res){
			throw new PDOException($sql2."��������ϵ��Ѷ����");
		}
		$res=$dbh->exec($sql3);
		if(!$res){
			throw new PDOException($sql3."��������ϵ��Ѷ����");
		}
		$res=$dbh->exec($sql4);
		if(!$res){
			throw new PDOException($sql4."��������ϵ��Ѷ����");
		}
		include("inc/file_upload.inc");
		$dbh->commit();
	
	}catch(PDOException $e){
		$res=$dbh->rollback();
		echo $e->getTraceAsString();
		exit($e->getMessage());
	}
	echo "<h1>��ҵƽ̨Ȩ�ޱ�����뵥�ύ�ɹ�</h1>";
	echo "<h1>�˱����뵥֮����Ϊ".$sheet_no."</h1>";
	echo "<h1><a href='newformA.php'>��дһ��</a></h1>";
	echo "<h1><a href='http://w3.yungtay.com.cn/'>��������ҳ</a></h1>";
		
	
}elseif($dockind == 'B'){
	$apply_man = $_POST['apply_man'];
	$sys_code = $_POST['sys_code'];
	$need_kind = $_POST['need_kind'];
	$is_public = $_POST['is_public'];
	$syssample = $_POST['syssample'];
	$vcode = $_POST['vcode'];
	$syscycle = $_POST['syscycle'];
	$sysrundate = $_POST['sysrundate'];
	$apply_type = $_POST['apply_type'];
	$zxd = $_POST['zxd'];
	$syssummary = $_POST['syssummary'];
	$sysdiscuss = $_POST['sysdiscuss'];
	$syswisheffect = $_POST['syswisheffect'];
	$next_user = $_POST['next_user'];
	
	if(empty($next_user)){
		echo "<p style='color:red;font-size:28px'>û����һλǩ���ˣ�����ϵ��Ѷ����<br />
		<a href='javascript:history.go(-1)'>����һҳ</a></p>";
		exit();
	}
	
	$yymm = date('Ym');
	$ini_sheet_no = $yymm.'000';
	list($sheet_no) = fields("select nvl(max(sheet_no),$ini_sheet_no)+1 from inf_need_main where sheet_no like '$yymm%'");
	list($form_key) = fields("select flow_sequence12.nextval from dual");
	try{
		$dbh->setAttribute(PDO::ATTR_AUTOCOMMIT,false);
		$dbh->beginTransaction();
		$sql1 = "insert into inf_need_main
		(sheet_no,doc_kind,form_key,creator,create_date,apply_person,apply_depart,is_public,apply_type,vip_code) 
		values('$sheet_no','$dockind','$form_key','$ID',sysdate,'$apply_man','$depart_no','$is_public','$apply_type','$vcode')";
		
		$sql2 = "insert into inf_need_syscheck
		(sheet_no,sys_code,sys_cycle,sys_discuss,sys_rundate,sys_sample,sys_summary,sys_wisheffect,zxd,sys_needkind)
		values('$sheet_no','$sys_code','$syscycle','$sysdiscuss','$sysrundate','$syssample','$syssummary','$syswisheffect','$zxd','$need_kind')";
		
		$sql3 = "insert into flow (system_id,form_id,form_key,flow_code) values('inf','inf','$form_key','$next_code')";
		$sql4 = "insert into subflow (form_key,seq,current_user) values ('$form_key','0','$next_user')";
		
		$res=$dbh->exec($sql1);
		if(!$res){
			throw new PDOException($sql1."��������ϵ��Ѷ����");
		}
		$res=$dbh->exec($sql2);
		if(!$res){
			throw new PDOException($sql2."��������ϵ��Ѷ����");
		}
		$res=$dbh->exec($sql3);
		if(!$res){
			throw new PDOException($sql3."��������ϵ��Ѷ����");
		}
		$res=$dbh->exec($sql4);
		if(!$res){
			throw new PDOException($sql4."��������ϵ��Ѷ����");
		}
	
		include("inc/file_upload.inc");
		$dbh->commit();
	}catch(PDOException $e){
		$res=$dbh->rollback();
		echo $e->getTraceAsString();
		exit($e->getMessage());
	}
	echo "<h1>ϵͳ�����������ύ�ɹ�</h1>";
	echo "<h1>�˱����뵥֮����Ϊ".$sheet_no."</h1>";
	echo "<h1><a href='newformB.php'>��дһ��</a></h1>";
	echo "<h1><a href='http://w3.yungtay.com.cn/'>��������ҳ</a></h1>";
	
}elseif($dockind == 'C'){
	$qadegree = $_POST['qadegree'];
	$apply_man = $_POST['apply_man'];
	$qacontent = $_POST['qacontent'];
	$qadescribe = $_POST['qadescribe'];
	
	if(empty($next_user)){
		echo "<p style='color:red;font-size:28px'>û����һλǩ���ˣ�����ϵ��Ѷ����<br />
		<a href='javascript:history.go(-1)'>����һҳ</a></p>";
		exit();
	}
	if(empty($qacontent)){
		echo "<p style='color:red;font-size:28px'>����д����˵��<br />
		<a href='javascript:history.go(-1)'>����һҳ</a></p>";
		exit();
	}
	
	$yymm = date('Ym');
	$ini_sheet_no = $yymm.'000';
	list($sheet_no) = fields("select nvl(max(sheet_no),$ini_sheet_no)+1 from inf_need_main where sheet_no like '$yymm%'");
	list($form_key) = fields("select flow_sequence12.nextval from dual");
	try{
		$dbh->setAttribute(PDO::ATTR_AUTOCOMMIT,false);
		$dbh->beginTransaction();
		$sql1 = "insert into inf_need_main
		(sheet_no,doc_kind,form_key,creator,create_date,apply_person,apply_depart,is_public,apply_type,vip_code) 
		values('$sheet_no','$dockind','$form_key','$ID',sysdate,'$apply_man','$depart_no','','','')";
		
		$sql2 = "insert into inf_need_qa (sheet_no,qa_degree,qa_content,qa_describe) values ('$sheet_no','$qadegree','$qacontent','$qadescribe')";
		
		$sql3 = "insert into flow (system_id,form_id,form_key,flow_code) values('inf','inf','$form_key','$next_code')";
		$sql4 = "insert into subflow (form_key,seq,current_user) values ('$form_key','0','$next_user')";
	
		$res=$dbh->exec($sql1);
		if(!$res){
			throw new PDOException($sql1."��������ϵ��Ѷ����");
		}
		$res=$dbh->exec($sql2);
		if(!$res){
			throw new PDOException($sql2."��������ϵ��Ѷ����");
		}
		$res=$dbh->exec($sql3);
		if(!$res){
			throw new PDOException($sql3."��������ϵ��Ѷ����");
		}
		$res=$dbh->exec($sql4);
		if(!$res){
			throw new PDOException($sql4."��������ϵ��Ѷ����");
		}
		include("inc/file_upload.inc");
	$dbh->commit();
	// $res=$dbh->rollback();
	}catch(PDOException $e){
		$res=$dbh->rollback();
		echo $e->getTraceAsString();
		exit($e->getMessage());
	}
	$_SESSION['sess_code'] = $_POST['sess_code'];
	echo "<h1>��Ѷ��ҵ������Բߴ����������ύ�ɹ�</h1>";
	echo "<h1>�˱����뵥֮����Ϊ".$sheet_no."</h1>";
	echo "<h1><a href='newformC.php'>��дһ��</a></h1>";
	echo "<h1><a href='http://w3.yungtay.com.cn/'>��������ҳ</a></h1>";
}

?>
</div>
</body>
</html>