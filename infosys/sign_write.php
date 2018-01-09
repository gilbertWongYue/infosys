<?php session_start();?>
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
	$assign_status = $_POST['assign_status'];
	
	if(isset($_POST['sess_code'])){
		if($_POST['sess_code'] == $_SESSION['sess_code']){
			echo "<p style='color:red;font-size:28px'>你的申请单已提交成功，请不要重复提交<br />";
			echo "<h1><a href='sign_list.php'>回待我签核画面</a></h1>";
			echo "<h1><a href='http://w3.yungtay.com.cn/'>回永大首页</a></h1>";
			exit();
		}
	}
	
	foreach ($sheet_no as $key => $value){
		if(empty($next_user[$key])){
			echo "<p style='color:red;font-size:28px'>申请单 $sheet_no[$key] 没有选择下一位签核人<br />
			<a href='javascript:history.go(-1)'>回上一页</a></p>";
			exit();
		}
		if($current_user[$key] != $ID){
			echo "<p style='color:red;font-size:28px'>当前签核人为 $current_user[$key] , 你无权限签核此申请单<br />
			<a href='javascript:history.go(-1)'>回上一页</a></p>";
			exit();
		}
		// echo $key.'-'.$sheet_no[$key].'-'.$next_user[$key].'-'.$next_code[$key].'<br>';
	}
	
	try{
		$dbh->setAttribute(PDO::ATTR_AUTOCOMMIT,false);
		$dbh->beginTransaction();
		
		foreach ($sheet_no as $num => $value){
			/*
			*C:会签单位签核；D0：资讯辅助处理人签核
			*会签单位维护签核意见，下位签核人
			*资讯负责人维护签核意见，工时，下位签核人，上传附件
			*/
			if($flow_code[$num] == 'C' || $flow_code[$num] == 'D0'){
				
				if($flow_code[$num] == 'C'){
					if($assign_status[$num] == 'F1' || $assign_status[$num] == 'Y1'){
						list($max_seq) = fields("select nvl(max(seq),0)+1 from assign where form_key='$c_form_key[$num]'");
						$sql1 = "update subflow set current_user='00000' where form_key='$c_form_key[$num]'";
						$sql2 = "update flow set flow_code='Z' where form_key='$c_form_key[$num]'";
						$sql3 = "insert into assign(form_key,seq,assigner,assign_status,assign_opinion,flow_code) values (
						'$c_form_key[$num]','$max_seq','$ID','$assign_status[$num]','$assign_opinion[$num]','$flow_code[$num]')";
						$sql4 =  "update workflow_subflow set reply_flag='Y' where form_key='$c_form_key[$num]'";
						// $sql_array = array($sql1,$sql2,$sql3,$sql4);
						// insertDB($sql_array);
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
						list($flag_num) = fields("select count(*) from workflow_subflow where 
						parent_key='$form_key[$num]' and reply_flag='N' and countersign_depart != 'zixun'");
						if($flag_num == 0){
							$sql1 = "update subflow set current_user='$next_user[$num]' where form_key='$form_key[$num]'";
							$sql2 = "update flow set flow_code='$next_code[$num]' where form_key='$form_key[$num]'";
							$res=$dbh->exec($sql1);
							if(!$res){
								throw new PDOException($sql1."错误，请联系资讯处理");
							}
							$res=$dbh->exec($sql2);
							if(!$res){
								throw new PDOException($sql2."错误，请联系资讯处理");
							}
						}
					}else{
						list($max_seq) = fields("select nvl(max(seq),0)+1 from assign where form_key='$c_form_key[$num]'");
						$sql1 = "update subflow set current_user='$next_user[$num]' where form_key='$c_form_key[$num]'";
						$sql2 = "update flow set flow_code='$next_code[$num]' where form_key='$c_form_key[$num]'";
						$sql3 = "insert into assign(form_key,seq,assigner,assign_status,assign_opinion,flow_code) values (
						'$c_form_key[$num]','$max_seq','$ID','$assign_status[$num]','$assign_opinion[$num]','$flow_code[$num]')";
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
					}
				}elseif($flow_code[$num] == 'D0'){
					list($max_seq) = fields("select nvl(max(seq),0)+1 from assign where form_key='$c_form_key[$num]'");
					$sql1 = "update subflow set current_user='00000' where form_key='$c_form_key[$num]'";
					$sql2 = "update flow set flow_code='Z' where form_key='$c_form_key[$num]'";
					$sql3 = "insert into assign(form_key,seq,assigner,assign_status,assign_opinion,flow_code,over_level_opinion) values (
					'$c_form_key[$num]','$max_seq','$ID','$assign_status[$num]','$assign_opinion[$num]','$flow_code[$num]','$work_hour[$num]')";
					$sql4 =  "update workflow_subflow set reply_flag='Y' where form_key='$c_form_key[$num]'";
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
					/* list($flag_num) = fields("select count(*) from workflow_subflow where 
						parent_key='$form_key[$num]' and reply_flag='N' and countersign_depart = 'zixun'");
					if($flag_num == 0){
						$sql1 = "update subflow set current_user='$next_user[$num]' where form_key='$form_key[$num]'";
						$sql2 = "update flow set flow_code='$next_code[$num]' where form_key='$form_key[$num]'";
						$res=$dbh->exec($sql1);
						if(!$res){
							throw new PDOException($sql1."错误，请联系资讯处理");
						}
						$res=$dbh->exec($sql2);
						if(!$res){
							throw new PDOException($sql2."错误，请联系资讯处理");
						}
					} */
					include("inc/file_upload.inc");
				}
			}
			/*
			*系统负责人签核流程，检查辅助处理人有无完成
			*/
			elseif($flow_code[$num] == 'D'){
				list($flag_num) = fields("select count(*) from workflow_subflow where 
						parent_key='$form_key[$num]' and reply_flag='N' and countersign_depart = 'zixun'");
				if($flag_num > 0){
					echo "<p style='color:red;font-size:28px'>资讯单号 $sheet_no[$num] 还有辅助处理人员未完成。<br />
					<a href='javascript:history.go(-1)'>回上一页</a></p>";
					continue;
				}else{
					if($doc_kind[$num] == 'B'){
						$need_kind = $_POST['need_kind'];
						$sql0 = "update inf_need_syscheck set sys_needkind='$need_kind[$num]' 
								where sheet_no='$sheet_no[$num]'";
						$res=$dbh->exec($sql0);
						if(!$res){
							throw new PDOException($sql0."错误，请联系资讯处理");
						}
					}
					list($max_seq) = fields("select nvl(max(seq),0)+1 from assign where form_key='$form_key[$num]'");
					$sql1 = "update subflow set current_user='$next_user[$num]' where form_key='$form_key[$num]'";
					$sql2 = "update flow set flow_code='$next_code[$num]' where form_key='$form_key[$num]'";
					$sql3 = "insert into assign(form_key,seq,assigner,assign_status,assign_opinion,flow_code,over_level_opinion) values (
					'$form_key[$num]','$max_seq','$ID','$assign_status[$num]','$assign_opinion[$num]','$flow_code[$num]','$work_hour[$num]')";
					
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
					
					include("inc/file_upload.inc");
				}
			}
				/*
				*申请人可修改所有资料
				*/
			elseif($flow_code[$num] == 'A' && $ID == $creator[$num]){
				if($doc_kind[$num] == 'A'){
					if($assign_status[$num] == 'D1'){
						$sql0 = "delete inf_need_main where form_key='$form_key[$num]'";
						$sql00 = "delete inf_need_plat where sheet_no='$sheet_no[$num]'";
						$sql000 = "delete upload where sys_id='inf' and form_id='inf' and keyword='$sheet_no[$num]'";
						$res=$dbh->exec($sql0);
						if(!$res){
							throw new PDOException($sql0."错误，请联系资讯处理");
						}
						$res=$dbh->exec($sql00);
						if(!$res){
							throw new PDOException($sql00."错误，请联系资讯处理");
						}
						if(count(selectArray("select keyword from upload where sys_id='inf' and form_id='inf' and keyword='$sheet_no[$num]'"))>0){
							$res=$dbh->exec($sql000);
							if(!$res){
								throw new PDOException($sql000."错误，请联系资讯处理");
							}
						}
						
					}else{
						$is_public = $_POST['is_public'];
						$apply_man = $_POST['apply_man'];
						$chrundate = $_POST['chrundate'];
						$chaccount = $_POST['chaccount'];
						$chpower = $_POST['chpower'];
						$chplatform = $_POST['chplatform'];
						$chitem = $_POST['chitem'];
						$chreason = $_POST['chreason'];
						$chmail = $_POST['chmail'];
						if(count($chpower[$num]) == 0){
							echo "<p style='color:red;font-size:28px'>资讯单号 $sheet_no[$num] 请选择开通权限类别<br />
							<a href='javascript:history.go(-1)'>回上一页</a></p>";
							exit();
						}else{
							$chpower[$num] = implode(',',$chpower[$num]);
						}
						
						if(count($chplatform[$num]) == 0){
							echo "<p style='color:red;font-size:28px'>资讯单号 $sheet_no[$num] 请选择需开通的作业平台<br />
							<a href='javascript:history.go(-1)'>回上一页</a></p>";
							exit();
						}else{
							$chplatform[$num] = implode(',',$chplatform[$num]);
						}
						$apply_man[$num] = implode(',',$apply_man[$num]);
						$sql0 = "update inf_need_main set apply_person='$apply_man[$num]',is_public='$is_public[$num]' where form_key='$form_key[$num]'";
						$sql00 = "update inf_need_plat set ch_platform='$chplatform[$num]',ch_account='$chaccount[$num]',
						ch_power='$chpower[$num]',ch_rundate='$chrundate[$num]',ch_item='$chitem[$num]',
						ch_reason='$chreason[$num]',ch_mail='$chmail[$num]' where sheet_no='$sheet_no[$num]'";
						$res=$dbh->exec($sql0);
						if(!$res){
							throw new PDOException($sql0."错误，请联系资讯处理");
						}
						$res=$dbh->exec($sql00);
						if(!$res){
							throw new PDOException($sql00."错误，请联系资讯处理");
						}
					}
					
				}elseif($doc_kind[$num] == 'B'){
					if($assign_status[$num] == 'D1'){
						$sql0 = "delete inf_need_main where form_key='$form_key[$num]'";
						$sql00 = "delete inf_need_syscheck where sheet_no='$sheet_no[$num]'";
						$sql000 = "delete upload where sys_id='inf' and form_id='inf' and keyword='$sheet_no[$num]'";
						$res=$dbh->exec($sql0);
						if(!$res){
							throw new PDOException($sql0."错误，请联系资讯处理");
						}
						$res=$dbh->exec($sql00);
						if(!$res){
							throw new PDOException($sql00."错误，请联系资讯处理");
						}
						if(count(selectArray("select keyword from upload where sys_id='inf' and form_id='inf' and keyword='$sheet_no[$num]'"))>0){
							$res=$dbh->exec($sql000);
							if(!$res){
								throw new PDOException($sql000."错误，请联系资讯处理");
							}
						}
					}else{
						$sys_code = $_POST['sys_code'];
						$apply_man = $_POST['apply_man'];
						$need_kind = $_POST['need_kind'];
						$is_public = $_POST['is_public'];
						$syssample = $_POST['syssample'];
						$vcode = $_POST['vcode'];
						$syscycle = $_POST['syscycle'];
						$sysrundate = $_POST['sysrundate'];
						$zxd = $_POST['zxd'];
						$syssummary = $_POST['syssummary'];
						$sysdiscuss = $_POST['sysdiscuss'];
						$syswisheffect = $_POST['syswisheffect'];
						$apply_type = $_POST['apply_type'];
						
						$sql0 = "update inf_need_main set apply_person='$apply_man[$num]',is_public='$is_public[$num]',apply_type='$apply_type[$num]' where form_key='$form_key[$num]'";
						$sql00 = "update inf_need_syscheck set sys_code='$sys_code[$num]',sys_cycle='$syscycle[$num]'
						,sys_discuss='$sysdiscuss[$num]',sys_rundate='$sysrundate[$num]',
						sys_sample='$syssample[$num]',sys_summary='$syssummary[$num]',
						sys_wisheffect='$syswisheffect[$num]',zxd='$zxd[$num]',sys_needkind='$need_kind[$num]' 
						where sheet_no='$sheet_no[$num]'";
						$res=$dbh->exec($sql0);
						if(!$res){
							throw new PDOException($sql0."错误，请联系资讯处理");
						}
						$res=$dbh->exec($sql00);
						if(!$res){
							throw new PDOException($sql00."错误，请联系资讯处理");
						}
					}
					
				}elseif($doc_kind[$num] == 'C'){
					if($assign_status[$num] == 'D1'){
						$sql0 = "delete inf_need_main where form_key='$form_key[$num]'";
						$sql00 = "delete inf_need_qa where sheet_no='$sheet_no[$num]'";
						$sql000 = "delete upload where sys_id='inf' and form_id='inf' and keyword='$sheet_no[$num]'";
						$res=$dbh->exec($sql0);
						if(!$res){
							throw new PDOException($sql0."错误，请联系资讯处理");
						}
						$res=$dbh->exec($sql00);
						if(!$res){
							throw new PDOException($sql00."错误，请联系资讯处理");
						}
						if(count(selectArray("select keyword from upload where sys_id='inf' and form_id='inf' and keyword='$sheet_no[$num]'"))>0){
							$res=$dbh->exec($sql000);
							if(!$res){
								throw new PDOException($sql000."错误，请联系资讯处理");
							}
						}
					}else{
						$qadegree = $_POST['qadegree'];
						$apply_man = $_POST['apply_man'];
						$qacontent = $_POST['qacontent'];
						$qadescribe = $_POST['qadescribe'];
						
						$sql0 = "update inf_need_main set apply_person='$apply_man[$num]' where form_key='$form_key[$num]'";
						$sql00 = "update inf_need_qa set qa_degree='$qadegree[$num]',qa_content='$qacontent[$num]',qa_describe='$qadescribe[$num]' where sheet_no='$sheet_no[$num]'";
						
						$res=$dbh->exec($sql0);
						if(!$res){
							throw new PDOException($sql0."错误，请联系资讯处理");
						}
						$res=$dbh->exec($sql00);
						if(!$res){
							throw new PDOException($sql00."错误，请联系资讯处理");
						}
					}
				}
					list($max_seq) = fields("select nvl(max(seq),0)+1 from assign where form_key='$form_key[$num]'");
					$sql1 = "update subflow set current_user='$next_user[$num]' where form_key='$form_key[$num]'";
					$sql2 = "update flow set flow_code='$next_code[$num]' where form_key='$form_key[$num]'";
					$sql3 = "insert into assign(form_key,seq,assigner,assign_status,assign_opinion,flow_code) values (
					'$form_key[$num]','$max_seq','$ID','$assign_status[$num]','$assign_opinion[$num]','$flow_code[$num]')";
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
				include("inc/file_upload.inc");
			}
				/*
				*申请人对资讯负责人满意度调查
				*/
			elseif($flow_code[$num] == 'E' && $ID == $creator[$num]){
				$quality = $_POST['quality'];
				$timeliness = $_POST['timeliness'];
				$communication = $_POST['communication'];
				$attitude = $_POST['attitude'];
				$sql0 = "update inf_need_main set quality='$quality[$num]',timeliness='$timeliness[$num]',communication='$communication[$num]',attitude='$attitude[$num]' where form_key='$form_key[$num]'";
				$res=$dbh->exec($sql0);
				if(!$res){
					throw new PDOException($sql0."错误，请联系资讯处理");
				}
				list($max_seq) = fields("select nvl(max(seq),0)+1 from assign where form_key='$form_key[$num]'");
				$sql1 = "update subflow set current_user='$next_user[$num]' where form_key='$form_key[$num]'";
				$sql2 = "update flow set flow_code='$next_code[$num]' where form_key='$form_key[$num]'";
				$sql3 = "insert into assign(form_key,seq,assigner,assign_status,assign_opinion,flow_code) values (
				'$form_key[$num]','$max_seq','$ID','$assign_status[$num]','$assign_opinion[$num]','$flow_code[$num]')";
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
				if($flow_code[$num] == 'F' && $doc_kind[$num] == 'B'){
					$sql0 = "update inf_need_syscheck set sys_needkind='$need_kind[$num]' 
								where sheet_no='$sheet_no[$num]'";
						$res=$dbh->exec($sql0);
						if(!$res){
							throw new PDOException($sql0."错误，请联系资讯处理");
						}
				}
				//资讯经理结案
				if($flow_code[$num] == 'H' && $assign_status[$num] == 'F4'){
					$sql0 = "update inf_need_main set finish_date=sysdate where sheet_no='$sheet_no[$num]'";
						$res=$dbh->exec($sql0);
						if(!$res){
							throw new PDOException($sql0."错误，请联系资讯处理");
						}
				}
				list($max_seq) = fields("select nvl(max(seq),0)+1 from assign where form_key='$form_key[$num]'");
				$sql1 = "update subflow set current_user='$next_user[$num]' where form_key='$form_key[$num]'";
				$sql2 = "update flow set flow_code='$next_code[$num]' where form_key='$form_key[$num]'";
				$sql3 = "insert into assign(form_key,seq,assigner,assign_status,assign_opinion,flow_code) values (
				'$form_key[$num]','$max_seq','$ID','$assign_status[$num]','$assign_opinion[$num]','$flow_code[$num]')";
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
			}
		}
	$dbh->commit();
	// $dbh->rollback();
	}catch(PDOException $e){
		$res=$dbh->rollback();
		echo $e->getTraceAsString();
		exit($e->getMessage());
	}
	$_SESSION['sess_code'] = $_POST['sess_code'];
	echo "<h1>资讯申请单提交成功</h1>";
	echo "<h1><a href='sign_list.php'>回待我签核画面</a></h1>";
	echo "<h1><a href='http://w3.yungtay.com.cn/'>回永大首页</a></h1>";
?>


</body>