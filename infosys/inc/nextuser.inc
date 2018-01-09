<?php 
	/*
	*经理级以上，资讯部申请直接签到资讯科长
	*其他情况由本部门领导审核
	*/
	if(substr($lead_code,0,1) <= 2 || in_array(substr($depart_no,0,3),array('103','109'))){
		$sql = "select employee_no,name from it.employee where depart_no='103030' and lead_code like '5%'";
		$next_code = 'B';
	}else{
		$sql = "select get_psm_leader('$ID') as employee_no,name(get_psm_leader('$ID')) as name from dual";
		$next_code = 'A';
	}
	$next_arr = selectArray($sql);
?>