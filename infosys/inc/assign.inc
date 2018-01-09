<?php 
	//申请单位签核意见
	$sql_app = "select seq,assigner,name(assigner) as assign_name,get_depart_name(get_depart(assigner)) as assign_depart_name,assign_status,code_name('assign_status',assign_status) as assign_status_name,assign_opinion,
	to_char(assign_date,'yyyy/mm/dd hh24:mi') as assign_date,flow_code from assign where form_key='{$insys[0][FORM_KEY]}' and flow_code in ('A','E','E0') order by seq";
	$sql_app_arr = selectArray($sql_app);
	echo "<table style='margin-top:0px'>
	<tr><th colspan='6' >申请单位签核意见</th></tr>
	<tr><th style='width:15%'>部门</th><th style='width:15%'>签核人</th><th style='width:15%'>签核状态</th><th style='width:15%'>签核日期</th><th colspan=2 style='width:30%'>签核意见</th></tr>";
	if(count($sql_app_arr) > 0){
		foreach($sql_app_arr as $key1 => $value1){
			echo "<tr>
				
				<td>{$value1[ASSIGN_DEPART_NAME]}</td>
				<td>{$value1[ASSIGNER]} - {$value1[ASSIGN_NAME]}</td>
				
				<td>{$value1[ASSIGN_STATUS_NAME]}</td>
				<td>{$value1[ASSIGN_DATE]}</td>
				<td colspan=2>{$value1[ASSIGN_OPINION]}</td>
				</tr>";
		}
	}
	// echo "</table>";
	
	/*
	*会签单位签核意见
	*/
	$sql_hq = "select seq,assigner,name(assigner) as assign_name,get_depart_name(get_depart(assigner)) 
	as assign_depart_name,assign_status,code_name('assign_status',assign_status) as assign_status_name,
	assign_opinion,	to_char(assign_date,'yyyy/mm/dd hh24:mi') as assign_date,flow_code from assign 
	where form_key='{$insys[0][FORM_KEY]}' and flow_code='B' and  assign_status='X3'
	union
	select seq,assigner,name(assigner) as assign_name,get_depart_name(get_depart(assigner)) as assign_depart_name,assign_status,code_name('assign_status',assign_status) as assign_status_name,assign_opinion,
	to_char(assign_date,'yyyy/mm/dd hh24:mi') as assign_date,flow_code from assign where form_key in (select form_key from workflow_subflow where parent_key='{$insys[0][FORM_KEY]}' and countersign_depart = 'hq') and flow_code in('C') order by
    assign_date ";
	$sql_hq_arr = selectArray($sql_hq);
		if(count($sql_hq_arr) > 0){
			echo "
			<tr><th colspan='6'>会签单位签核意见</th></tr>
			<tr><th>部门</th><th>签核人</th><th>签核状态</th><th>签核日期</th>
			<th>签核意见</th></tr>";
			foreach($sql_hq_arr as $key1 => $value1){
				echo "<tr>
				
				<td>{$value1[ASSIGN_DEPART_NAME]}</td>
				<td>{$value1[ASSIGNER]} - {$value1[ASSIGN_NAME]}</td>
				<td>{$value1[ASSIGN_STATUS_NAME]}</td>
				<td>{$value1[ASSIGN_DATE]}</td>
				<td>{$value1[ASSIGN_OPINION]}</td>
				</tr>";
			}
			
		}
	
	/*
	*资讯部签核处理意见
	*资讯科长，经理签核意见
	*/
	$sql_zixun = "select seq,assigner,name(assigner) as assign_name,get_depart_name(get_depart(assigner)) as assign_depart_name,assign_status,code_name('assign_status',assign_status) as assign_status_name,assign_opinion,
	to_char(assign_date,'yyyy/mm/dd hh24:mi') as assign_date,flow_code from assign where form_key='{$insys[0][FORM_KEY]}' and flow_code in('B') and assign_status <> 'X3' order by seq";
	$sql_zixun_arr = selectArray($sql_zixun);
		if(count($sql_zixun_arr) > 0){
			echo "
			<tr><th colspan='6'>资讯部签核意见</th></tr>
			<tr><th>签核人</th><th>签核状态</th><th>签核日期</th>
			<th>预定完成日期</th><th colspan=2>签核意见</th></tr>";
			foreach($sql_zixun_arr as $key1 => $value1){
			
				echo "<tr>
				
				
				<td>{$value1[ASSIGNER]} - {$value1[ASSIGN_NAME]}</td>
				
				<td>{$value1[ASSIGN_STATUS_NAME]}</td>
				<td>{$value1[ASSIGN_DATE]}</td>
				<td>{$insys[0][PRE_FINISH_DATE]}</td>
				<td colspan=2>{$value1[ASSIGN_OPINION]}</td>
				</tr>";
			}
		/*
		*资讯部系统负责人签核意见
		*/
			$sql_zixun1 = "select seq,assigner,name(assigner) as assign_name,get_depart_name(get_depart(assigner)) as 
			assign_depart_name,assign_status,code_name('assign_status',assign_status) as assign_status_name,
			assign_opinion,	to_char(assign_date,'yyyy/mm/dd hh24:mi') as assign_date,flow_code,over_level_opinion from assign where form_key='{$insys[0][FORM_KEY]}' and flow_code='D'
			union
			select seq,assigner,name(assigner) as assign_name,get_depart_name(get_depart(assigner)) as assign_depart_name,assign_status,code_name('assign_status',assign_status) as assign_status_name,assign_opinion,
			to_char(assign_date,'yyyy/mm/dd hh24:mi') as assign_date,flow_code,over_level_opinion from assign where form_key in (select form_key from workflow_subflow where parent_key='{$insys[0][FORM_KEY]}' and countersign_depart='zixun') and flow_code in('D0') order by seq";
			$sql_zixun1_arr = selectArray($sql_zixun1);
				if(count($sql_zixun1_arr) > 0){
					echo "<tr><th>签核人</th><th>签核状态</th><th>签核日期</th>
			<th>工时</th><th colspan=2>签核意见</th></tr>";
					foreach($sql_zixun1_arr as $key1 => $value1){
						echo "<tr>
						
						
						<td>{$value1[ASSIGNER]} - {$value1[ASSIGN_NAME]}</td>
						
						<td>{$value1[ASSIGN_STATUS_NAME]}</td>
						<td>{$value1[ASSIGN_DATE]}</td>
						<td>".number_format($value1[OVER_LEVEL_OPINION],1)."</td>
						<td colspan=2>{$value1[ASSIGN_OPINION]}</td>
						</tr>";
					}
				}
			/*
			*资讯主管签核意见
			*/
			$sql_zixun2 = "select seq,assigner,name(assigner) as assign_name,get_depart_name(get_depart(assigner)) as assign_depart_name,assign_status,code_name('assign_status',assign_status) as assign_status_name,assign_opinion,
			to_char(assign_date,'yyyy/mm/dd hh24:mi') as assign_date,flow_code from assign where form_key='{$insys[0][FORM_KEY]}' and flow_code in('F','H') order by seq";
			$sql_zixun2_arr = selectArray($sql_zixun2);
				if(count($sql_zixun2_arr) > 0){
					echo "<tr><th>资讯主管</th><th>签核状态</th><th>签核日期</th>
			<th colspan=3>签核意见</th></tr>";
					foreach($sql_zixun2_arr as $key2 => $value2){
						echo "<tr>
						
						<td>{$value2[ASSIGNER]} - {$value2[ASSIGN_NAME]}</td>
						
						<td>{$value2[ASSIGN_STATUS_NAME]}</td>
						<td>{$value2[ASSIGN_DATE]}</td>
						<td colspan=3>{$value2[ASSIGN_OPINION]}</td>
						</tr>";
					}
				}
		// echo "</table>";
		}
	
		echo "</table>";
?>