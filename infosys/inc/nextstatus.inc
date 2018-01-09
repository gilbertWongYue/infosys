<?php 
	if($insys[0][FLOW_CODE] == 'A'){
		if($ID == $insys[0][CREATOR]){
			if(substr($lead_code,0,1) >= '5'){
				$sql_status = "select code_name,content from code where field_name='assign_status' and code_name in ('F6','D1') order by code_name desc";
			}elseif(substr($lead_code,0,1) <= '2'){
				$sql_status = "select code_name,content from code where field_name='assign_status' and code_name in ('F2','F6','D1') order by code_name desc";
			}
		}else{
			if(substr($lead_code,0,1) >= '5'){
				$sql_status = "select code_name,content from code where field_name='assign_status' and code_name in ('F6','B1') order by code_name desc";
			}elseif(substr($lead_code,0,1) <= '2' && $lead_code >= '12'){
				$sql_status = "select code_name,content from code where field_name='assign_status' and code_name in ('F2','F6','B1') order by code_name desc";
			}else{
				$sql_status = "select code_name,content from code where field_name='assign_status' and code_name in ('F2','B1') order by code_name desc";
			}
		}
	}elseif($insys[0][FLOW_CODE] == 'B'){
		$sql_status = "select code_name,content from code where field_name='assign_status' and code_name in ('FH','X3','F9','BC','B4','X6','ZA','ZH') order by code_name desc";
	}
	//会签阶段
	elseif($insys[0][FLOW_CODE] == 'C'){
		//判断当前签核人是否符合workflow_subflow.leader_no,$leader_no:会签部门领导
		list($leader_no) = fields("select leader_no from workflow_subflow where form_key='{$insys[0][C_FORM_KEY]}'");
		if($ID == $leader_no){
			$sql_status = "select code_name,content from code where field_name='assign_status' and code_name in ('FH','F1','Y1') order by code_name";
		}else{
			$sql_status = "select code_name,content from code where field_name='assign_status' and code_name in ('F9') order by code_name desc";
		}
		
	}
	//资讯部专人完成后下位签核人是固定的，无需选择签核动作
	elseif($insys[0][FLOW_CODE] == 'D' || $insys[0][FLOW_CODE] == 'D0'){
		$sql_status = "select code_name,content from code where field_name='assign_status' and code_name in ('FM') order by code_name desc";
		$sql_nextuser = "select employee_no,name from employee where employee_no='{$insys[0][CREATOR]}'";
		// $sql_nextuser = "select employee_no,name,case when employee_no='45224' then 0 else 1 end as flag
		// from employee where substr(depart_no,0,3) in ('103','109') and lead_code like '5%' order by flag";
		$next_code = 'E';
	}elseif($insys[0][FLOW_CODE] == 'D1'){
		$sql_status = "select code_name,content from code where field_name='assign_status' and code_name in ('FM') order by code_name desc";
		$sql_nextuser = "select assigner as employee_no,name(assigner) as name from assign where form_key='{$insys[0][FORM_KEY]}' and flow_code='B'";
		$next_code = 'F';
	}elseif($insys[0][FLOW_CODE] == 'E' || $insys[0][FLOW_CODE] == 'E0'){
		$sql_status = "select code_name,content from code where field_name='assign_status' and code_name in ('P2') order by code_name desc";
		// $sql_nextuser = "select assigner as employee_no,name(assigner) as name from assign where form_key='{$insys[0][FORM_KEY]}' and flow_code='D' and rownum=1";
		$sql_nextuser = "select * from (select assigner as employee_no,name(assigner) as name from assign where form_key='{$insys[0][FORM_KEY]}' order by assign_date desc) where rownum=1";
		if($insys[0][FLOW_CODE] == 'E0'){
			$next_code = 'F';
		}else{
			$next_code = 'D1';
		}
		
	}elseif($insys[0][FLOW_CODE] == 'F'){
		$sql_status = "select code_name,content from code where field_name='assign_status' and code_name in ('F2') order by code_name desc";
		$sql_nextuser = "select employee_no,name from employee where substr(depart_no,0,3) in ('103','109') and lead_code like '2%' order by depart_no";
		$next_code = 'H';
	}elseif($insys[0][FLOW_CODE] == 'H'){
		$sql_status = "select code_name,content from code where field_name='assign_status' and code_name in ('F4') order by code_name desc";
		$sql_nextuser = "select '00000' as employee_no,'结案' as name from dual";
		$next_code = 'Z';
	}
	//A,B,C
	$code_array=array('A','B','C');
	$status_result = selectArray($sql_status);
	echo "<table style='margin-top:0px'>";
	if(in_array($insys[0][FLOW_CODE],$code_array)){
		echo "<tr><th style='width:15%'>签核动作</th><td style='width:30%'><select name='assign_status[$num]' onChange='showNextuser(this.value,$num)'>
		<option value=''>请选择签核批示</option>";
		foreach ($status_result as $value){
			echo "<option value='$value[CODE_NAME]'>$value[CONTENT]</option>";
		}
		echo "</select></td><th style='width:15%'>下一位签核人</th><td style='width:30%'><select name='next_user[$num]' id='next_user$num' ></select></td></tr>";
		
		echo "<input type='hidden' name='next_code[$num]' id='next_code$num' />";
	}else{
		$nextuser_result = selectArray($sql_nextuser);
		echo "<tr><th style='width:15%'>签核动作</th><td style='width:30%'><select name='assign_status[$num]'>";
		foreach ($status_result as $value){
			echo "<option value='$value[CODE_NAME]'>$value[CONTENT]</option>";
		}
		echo "</select></td>";
		echo "<th style='width:15%'>下一位签核人</th><td style='width:30%'>
					<select name='next_user[$num]' id='next_user$num'>";
		foreach($nextuser_result as $value){
			echo "<option value='$value[EMPLOYEE_NO]'>$value[EMPLOYEE_NO] - $value[NAME]</option>";
		}
					
		echo "</select></td></tr>";
		echo "<input type='hidden' name='next_code[$num]' value='$next_code' />";
	}
	if($insys[0][FLOW_CODE] == 'D' || $insys[0][FLOW_CODE] == 'D0'){
		echo "<tr><th>签核意见</th><td >
	<textarea name='assign_opinion[$num]' cols='70' rows='5'></textarea></td>
	<th>工时</th><td><input type='text' name='work_hour[$num]' value='1.0' onkeyup='value=value.replace(/[^\d\.]/g,\"\")' ></td></tr>
	</table>";
	}else{
		echo "<tr><th>签核意见</th><td colspan='3'>
	<textarea name='assign_opinion[$num]' cols='70' rows='5'></textarea></td></tr>
	</table>";
	}
	
?>