<?php 
	header('content-type:application/json;charset=gb2312'); 
	require('/php/public/id.inc');
	require("/php/public/opendb.inc");
	require("/php/public/openmydb.inc");
	require("/php/public/power.inc");
	require("method/mFunction.php");
	
	list($pre_date)=fields("select calendar_date from (select a.*,rownum as rn from (select calendar_date 
	from calendar  where calendar_date>sysdate and is_work=0 order by calendar_date) a where 
	rownum<=7 ) b where b.rn=7");
	// $flow_code = $_POST['flow_code'];
	// $assign_status = $_POST['assign_status'];
	// $form_key = $_POST['form_key'];
	// $sheet_no = $_POST['sheet_no'];
	// $lead_code = $_POST['lead_code'];
	// $c_form_key = $_POST['c_form_key'];
	// $num = $_POST['argv2'];
	if($flow_code == 'A'){
		if($assign_status == 'F6'){
			$nextuser_sql = "select get_psm_leader('$ID') as employee_no,name(get_psm_leader('$ID')) name from dual";
			$next_code = 'A';
		}elseif($assign_status == 'F2'){
			$nextuser_sql = "select employee_no,name from it.employee where depart_no='103030' and lead_code like '5%'";
			$next_code = 'B';
		}elseif($assign_status == 'B1'){
			$nextuser_sql = "select creator as employee_no,name(creator) as name from inf_need_main where sheet_no='$sheet_no'";
			$next_code = 'A';
		}elseif($assign_status == 'D1'){
			$nextuser_sql = "select '00000' as employee_no,'结案' as name from dual";
			$next_code = 'Z';
		}
	}elseif($flow_code == 'B'){
		if($assign_status == 'FH'){
			$nextuser_sql = "select employee_no,name from employee where substr(depart_no,0,3) in ('103','109')
				and substr(employee_no,0,1)<>'9' and substr(lead_code,0,1)>=5  order by 1  ";
			$next_code = 'D';
		}elseif($assign_status == 'X3'){
			$nextuser_sql = "select get_depart_name(depart_no) as depart_name,depart_no,employee_no,name from it.employee
			 where lead_code>='12' and lead_code < '60' and SUBSTR(depart_no,1,3)!='103' and SUBSTR(depart_no,1,3)!='109'
			 order by depart_no DESC";
			 $next_code = 'C';
		}elseif($assign_status == 'B1'){
			$nextuser_sql = "select creator as employee_no,name(creator) as name from inf_need_main where sheet_no='$sheet_no'";
			$next_code = 'A';
		}elseif($assign_status == 'BC' || $assign_status == 'ZH'){
			$nextuser_sql = "select '00000' as employee_no,'结案' as name from dual";
			$next_code = 'Z';
		}elseif($assign_status == 'F9'){
			$nextuser_sql = "select creator as employee_no,name(creator) as name from inf_need_main where sheet_no='$sheet_no'";
			$next_code = 'E0';
		}
		
	}elseif($flow_code =='C'){
		if($assign_status == 'FH'){
			if(substr($lead_code,0,1) == '2'){
				$nextuser_sql = "select employee_no,name from employee where substr(depart_no,0,5) in (
				select substr(depart_no,0,5) from employee where employee_no='$ID') and employee_no<>'$ID'
				union 
				select employee_no,name from employee where substr(depart_no,0,3) in (select substr(depart_no,0,3)
				from employee where employee_no='$ID') and lead_code like '5%'
				and employee_no<>'$ID'";
				$next_code = 'C';
			}elseif(substr($lead_code,0,1) == '5'){
				$nextuser_sql = "select employee_no,name from employee where substr(depart_no,0,5) in (
				select substr(depart_no,0,5) from employee where employee_no='$ID') and employee_no<>'$ID'";
				$next_code = 'C';
			}
		}elseif($assign_status == 'F9'){
			$nextuser_sql = "select get_psm_leader('$ID') as employee_no,name(get_psm_leader('$ID')) as name from dual";
			$next_code = 'C';
		}elseif($assign_status == 'F1' || $assign_status == 'Y1'){
			$nextuser_sql = "select sender as employee_no,name(sender) as name from workflow_subflow where 
			form_key='$c_form_key'";
			$next_code = 'B';
		}
		
	}elseif($flow_code =='D'){
		
	}

	$nextuser_result = selectArray($nextuser_sql);
	$content = "";
	//资讯科长指派签核
	if($flow_code == 'B'){
		//指派
		if($assign_status == 'FH'){
			$content .= "<tr class='trouble'><th width='15%'>主要处理人员</th><td colspan='3' width='75%'><select name='next_user'>";
			foreach($nextuser_result as $value){
				$content .= "<option value='$value[EMPLOYEE_NO]'>$value[EMPLOYEE_NO]-$value[NAME]</option>";
			}
			$content .= "</select></td></tr><tr class='trouble'><th>配合处理人员</th><td colspan='3' >";
			$i = 1;
			$content .= "<div style='float:left;width:100%;'>";
			foreach($nextuser_result as $value){
				$content .= "<div style='float:left;width:25%'><input type='checkbox' name='next_user1[]' value='$value[EMPLOYEE_NO]'>$value[EMPLOYEE_NO] - $value[NAME]&nbsp;&nbsp;</div>";
				if($i%4 == 0){
					$content .= "</div><br><div style='float:left;width:100%'>";
				}
				$i ++;
			}
			$content .= "</div>";
			$content .= "</td></tr><tr class='trouble'><th width='15%'>预定完成时间</th><td colspan=3 width='75%'><input type='text' name='pre_finish_date' id='pre_finish_date' onclick=\\\"return showCalendar('pre_finish_date', 'y/mm/dd')\\\" value='$pre_date'></td></tr>";
		}elseif($assign_status == 'X3'){
			//会签各单位经理、科长
			$i = 1;
			$content .= "<tr class='trouble' ><th width='15%'>指定会签人员</th><td colspan='3' width='75%'>";
			$content .= "<div style='float:left;width:100%;'>";
			foreach($nextuser_result as $value){
				$content .= "<div style='float:left;width:25%'><input type='checkbox' name='next_user[]' value='$value[EMPLOYEE_NO]'>$value[DEPART_NAME] -$value[EMPLOYEE_NO] - $value[NAME]&nbsp;&nbsp;</div>";
				if($i%4 == 0){
					$content .= "</div><br><div style='float:left;width:100%'>";
				}
				$i ++;
			}
			$content .= "</div></tr>";
		}elseif($assign_status == 'B1'){
			//退回申请人修改
			$content .= "<tr class='trouble'><th width='15%'>下位签核人</th><td colspan='3' width='75%'><select name='next_user'>";
			$content .= "<option value='{$nextuser_result[0][EMPLOYEE_NO]}'>{$nextuser_result[0][EMPLOYEE_NO]} - {$nextuser_result[0][NAME]}</option>";
			$content .= "</select></td></tr>";
		}elseif($assign_status =='BC'){
			//退回结案
			$content .= "<tr class='trouble'><th width='15%'>下位签核人</th><td colspan='3' width='75%'><select name='next_user'>";
			$content .="<option value='{$nextuser_result[0][EMPLOYEE_NO]}'>{$nextuser_result[0][EMPLOYEE_NO]} - {$nextuser_result[0][NAME]}</option>";
			$content .= "</select></td></tr>";
		}elseif($assign_status == 'F9'){
			$content .= "<tr class='trouble'><th width='15%'>下位签核人</th><td colspan='3' width='75%'><select name='next_user'>";
			$content .="<option value='{$nextuser_result[0][EMPLOYEE_NO]}'>{$nextuser_result[0][EMPLOYEE_NO]} - {$nextuser_result[0][NAME]}</option>";
			$content .= "</select></td></tr>";
		}
		
		
	}else{
		foreach($nextuser_result as $value){
			$content.= "<option value='$value[EMPLOYEE_NO]'>$value[EMPLOYEE_NO]-$value[NAME]</option>";
		}
		
		// $content=urlencode($content);
		// $data['aaa'] = $nextuser_sql;
		
		// echo $content;
		// print_r($data);
	}
	$content=urlencode($content);
	$data['content'] = $content;
	$data['next_code'] = $next_code;
	echo urldecode(json_encode($data));
?>