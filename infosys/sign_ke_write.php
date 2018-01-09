<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=gb2312" />
	<title>资讯单系统签核作业</title>
	<link href="css/menu.css" rel="stylesheet" type="text/css" />
	<link href="css/calendar.css" rel="stylesheet" type="text/css" />
	<script src="js/jquery-1.11.1.min.js" type="text/javascript"></script>
	<script src="js/calendar.js" type="text/javascript"></script>
	<script src="js/calendar-setup.js" type="text/javascript"></script>
	<script src="js/calendar-zh.js" type="text/javascript"></script>
</head>
<body>
<?php require("./inc/common.inc");
	
	
	
	$sheet_no = $_POST['sheet_no'];
	$form_key = $_POST['form_key'];
	$c_form_key = $_POST['c_form_key'];
	$flow_code = $_POST['flow_code'];
	$creator = $_POST['creator'];
	$doc_kind = $_POST['doc_kind'];
	$next_code = $_POST['next_code'];
	$next_user = $_POST['next_user'];
	if(count($next_user) == 0){
		echo "<p style='color:red;font-size:28px'>请选择下一位签核人<br />
			<a href='javascript:history.go(-1)'>回上一页</a></p>";
			exit();
	}
	$ziXunKeZhang = array('45224','22305','00279');
	
	if(!in_array($current_user,$ziXunKeZhang)){
			echo "<p style='color:red;font-size:28px'>当前签核人为 $current_user ,当前账号为 $ID, 你无权限签核此申请单<br />
			<a href='javascript:history.go(-1)'>回上一页</a></p>";
			exit();
	}
	try{
		$dbh->setAttribute(PDO::ATTR_AUTOCOMMIT,false);
		$dbh->beginTransaction();
		
		if($doc_kind == 'B'){
			$need_kind = $_POST['need_kind'];
			$sql0 = "update inf_need_syscheck set sys_needkind='$need_kind' 
					where sheet_no='$sheet_no'";
			$res=$dbh->exec($sql0);
			if(!$res){
				throw new PDOException($sql0."错误，请联系资讯处理");
			}
		}
		/*
		*主要处理人签核走主流程，辅助处理人走子流程
		*辅助处理人code='D0'
		*/
		if($assign_status == 'FH'){
			
			$next_user1 = $_POST['next_user1'];
			$pre_finish_date = $_POST['pre_finish_date'];
			list($max_seq) = fields("select nvl(max(seq),0)+1 from assign where form_key='$form_key'");
			$sql1 = "update subflow set current_user='$next_user' where form_key='$form_key'";
			$sql2 = "update flow set flow_code='$next_code' where form_key='$form_key'";
			$sql3 = "insert into assign(form_key,seq,assigner,assign_status,assign_opinion,flow_code) values (
			'$form_key','$max_seq','$ID','$assign_status','$assign_opinion','$flow_code')";
			$sql4 = "update inf_need_main set pre_finish_date='$pre_finish_date' where form_key='$form_key'";
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
			if(count($next_user1) > 0){
				for($i = 0; $i < count($next_user1); $i++){
					if($next_user1[$i] == $next_user){
						
						echo "<p style='color:red;font-size:28px'>主处理人与配合处理人重复，请重新选择。<br />
						<a href='javascript:history.go(-1)'>回上一页</a></p>";
						exit();
						
					}
					list($no)=fields("select nvl(max(seq),0)+1 from workflow_subflow where parent_key='$form_key' and countersign_depart='zixun'");
					list($h_form_key) = fields("select flow_sequence12.nextval from dual");
					$sql_1="insert into workflow_subflow(form_key,seq,parent_key,leader_no,create_date,reply_flag,sender,key_no,countersign_depart) values('$h_form_key','$no','$form_key','$next_user1[$i]',sysdate,'N','$ID','$sheet_no','zixun')";
					$sql_2="insert into flow (system_id,form_id,form_key,flow_code,flow_code_seq) values('inf','inf','$h_form_key','D0','9')";
					$sql_3="insert into subflow(form_key,seq,current_user,receive_date) values('$h_form_key',0,'$next_user1[$i]',sysdate)";
					$res=$dbh->exec($sql_1);
					if(!$res){
						throw new PDOException($sql_1."错误，请联系资讯处理");
					}
					$res=$dbh->exec($sql_2);
					if(!$res){
						throw new PDOException($sql_2."错误，请联系资讯处理");
					}
					$res=$dbh->exec($sql_3);
					if(!$res){
						throw new PDOException($sql_3."错误，请联系资讯处理");
					}
				}
			}
		}elseif($assign_status == 'X3'){
			if(count($next_user) == 0){
				echo "<p style='color:red;font-size:28px'>请选择会签人员。<br />
						<a href='javascript:history.go(-1)'>回上一页</a></p>";
				exit();
			}
			for($i = 0; $i < count($next_user); $i++){
				list($no)=fields("select nvl(max(seq),0)+1 from workflow_subflow where parent_key='$form_key' and countersign_depart = 'hq'");
				list($h_form_key) = fields("select flow_sequence12.nextval from dual");
				$sql_1="insert into workflow_subflow(form_key,seq,parent_key,leader_no,create_date,reply_flag,sender,key_no,countersign_depart) values('$h_form_key','$no','$form_key','$next_user[$i]',sysdate,'N','$ID','$sheet_no','hq')";
				$sql_2="insert into flow (system_id,form_id,form_key,flow_code,flow_code_seq) values('inf','inf','$h_form_key','$next_code','9')";
				$sql_3="insert into subflow(form_key,seq,current_user,receive_date) values('$h_form_key',0,'$next_user[$i]',sysdate)";
				$res=$dbh->exec($sql_1);
				if(!$res){
					throw new PDOException($sql_1."错误，请联系资讯处理");
				}
				$res=$dbh->exec($sql_2);
				if(!$res){
					throw new PDOException($sql_2."错误，请联系资讯处理");
				}
				$res=$dbh->exec($sql_3);
				if(!$res){
					throw new PDOException($sql_3."错误，请联系资讯处理");
				}
			}
			list($max_seq) = fields("select nvl(max(seq),0)+1 from assign where form_key='$form_key'");
			$sql1 = "update subflow set current_user='00000' where form_key='$form_key'";
			$sql2 = "update flow set flow_code='Z0' where form_key='$form_key'";
			$sql3 = "insert into assign(form_key,seq,assigner,assign_status,assign_opinion,flow_code) values (
			'$form_key','$max_seq','$ID','$assign_status','$assign_opinion','$flow_code')";
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
			
			
		}else{
			list($max_seq) = fields("select nvl(max(seq),0)+1 from assign where form_key='$form_key'");
			$sql1 = "update subflow set current_user='$next_user' where form_key='$form_key'";
			$sql2 = "update flow set flow_code='$next_code' where form_key='$form_key'";
			$sql3 = "insert into assign(form_key,seq,assigner,assign_status,assign_opinion,flow_code) values (
			'$form_key','$max_seq','$ID','$assign_status','$assign_opinion','$flow_code')";
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
			//退回结案通知申请人
			if($assign_status == 'BC'){
				$sql4 = "update inf_need_main set finish_date=sysdate where sheet_no='$sheet_no' ";
				$res=$dbh->exec($sql4);
						if(!$res){
							throw new PDOException($sql4."错误，请联系资讯处理");
						}
				
				$sel_tell="insert into public.tell(doc_user,doc_mkdate,doc_title,doc_url,lev,sign) values('$creator',now(),'资讯单退回结案通知','http://oracle.yungtay.com.cn/inf/infosys/query_detail.php?doc_key[]=$sheet_no&total_num=1','#','N')";
				$res_tell=mysql_query($sel_tell) or die("个人通知失败");
				
			}
		}
		$dbh->commit();
	}catch(PDOException $e){
		$res=$dbh->rollback();
		echo $e->getTraceAsString();
		exit($e->getMessage());
	}
	echo "<h1>资讯申请单提交成功</h1>";
	echo "<h1><a href='sign_ke_list.php'>回资讯科长指派画面</a></h1>";
	echo "<h1><a href='http://w3.yungtay.com.cn/'>回永大首页</a></h1>";