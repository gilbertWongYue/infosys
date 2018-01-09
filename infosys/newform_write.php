<?php session_start();?>
<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=gb2312" />
	<title>资讯单申请系统</title>
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
		echo "<p style='color:red;font-size:28px'>你的申请单已提交成功，请不要重复提交<br />";
		echo "<h1><a href='newform$dockind.php'>再写一笔</a></h1>";
		echo "<h1><a href='http://w3.yungtay.com.cn/'>回永大首页</a></h1>";
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
		echo "<p style='color:red;font-size:28px'>请选择开通权限类别<br />
		<a href='javascript:history.go(-1)'>回上一页</a></p>";
		exit();
	}else{
		$chpower = implode(',',$chpower);
	}
	
	if(count($chplatform) == 0){
		echo "<p style='color:red;font-size:28px'>请选择需开通的作业平台<br />
		<a href='javascript:history.go(-1)'>回上一页</a></p>";
		exit();
	}else{
		$chplatform = implode(',',$chplatform);
	}
	if(empty($next_user)){
		echo "<p style='color:red;font-size:28px'>没有下一位签核人，请联系资讯处理。<br />
		<a href='javascript:history.go(-1)'>回上一页</a></p>";
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
			throw new PDOException($sql1."错误，请联系资讯处理");
		}
		$res=$dbh->exec($sql2);
		if(!$res){
			throw new PDOException($sql2."错误，请联系资讯处理");
		}
		$res=$dbh->exec($sql3);
		if(!$res){
			throw new PDOException($sql3."错误，请联系资讯处理");
		}
		$res=$dbh->exec($sql4);
		if(!$res){
			throw new PDOException($sql4."错误，请联系资讯处理");
		}
		include("inc/file_upload.inc");
		$dbh->commit();
	
	}catch(PDOException $e){
		$res=$dbh->rollback();
		echo $e->getTraceAsString();
		exit($e->getMessage());
	}
	echo "<h1>作业平台权限变更申请单提交成功</h1>";
	echo "<h1>此笔申请单之单号为".$sheet_no."</h1>";
	echo "<h1><a href='newformA.php'>再写一笔</a></h1>";
	echo "<h1><a href='http://w3.yungtay.com.cn/'>回永大首页</a></h1>";
		
	
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
		echo "<p style='color:red;font-size:28px'>没有下一位签核人，请联系资讯处理。<br />
		<a href='javascript:history.go(-1)'>回上一页</a></p>";
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
			throw new PDOException($sql1."错误，请联系资讯处理");
		}
		$res=$dbh->exec($sql2);
		if(!$res){
			throw new PDOException($sql2."错误，请联系资讯处理");
		}
		$res=$dbh->exec($sql3);
		if(!$res){
			throw new PDOException($sql3."错误，请联系资讯处理");
		}
		$res=$dbh->exec($sql4);
		if(!$res){
			throw new PDOException($sql4."错误，请联系资讯处理");
		}
	
		include("inc/file_upload.inc");
		$dbh->commit();
	}catch(PDOException $e){
		$res=$dbh->rollback();
		echo $e->getTraceAsString();
		exit($e->getMessage());
	}
	echo "<h1>系统检讨申请书提交成功</h1>";
	echo "<h1>此笔申请单之单号为".$sheet_no."</h1>";
	echo "<h1><a href='newformB.php'>再写一笔</a></h1>";
	echo "<h1><a href='http://w3.yungtay.com.cn/'>回永大首页</a></h1>";
	
}elseif($dockind == 'C'){
	$qadegree = $_POST['qadegree'];
	$apply_man = $_POST['apply_man'];
	$qacontent = $_POST['qacontent'];
	$qadescribe = $_POST['qadescribe'];
	
	if(empty($next_user)){
		echo "<p style='color:red;font-size:28px'>没有下一位签核人，请联系资讯处理。<br />
		<a href='javascript:history.go(-1)'>回上一页</a></p>";
		exit();
	}
	if(empty($qacontent)){
		echo "<p style='color:red;font-size:28px'>请填写问题说明<br />
		<a href='javascript:history.go(-1)'>回上一页</a></p>";
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
			throw new PDOException($sql1."错误，请联系资讯处理");
		}
		$res=$dbh->exec($sql2);
		if(!$res){
			throw new PDOException($sql2."错误，请联系资讯处理");
		}
		$res=$dbh->exec($sql3);
		if(!$res){
			throw new PDOException($sql3."错误，请联系资讯处理");
		}
		$res=$dbh->exec($sql4);
		if(!$res){
			throw new PDOException($sql4."错误，请联系资讯处理");
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
	echo "<h1>资讯作业问题与对策处理申请书提交成功</h1>";
	echo "<h1>此笔申请单之单号为".$sheet_no."</h1>";
	echo "<h1><a href='newformC.php'>再写一笔</a></h1>";
	echo "<h1><a href='http://w3.yungtay.com.cn/'>回永大首页</a></h1>";
}

?>
</div>
</body>
</html>