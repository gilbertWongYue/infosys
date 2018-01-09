<?php 
	require('/php/public/id.inc');
	require("/php/public/opendb.inc");
	require("/php/public/openmydb.inc");
	require("/php/public/power.inc");
	
	require("./method/mFunction.php");
	
	$user_sql = "select name,depart_no,get_depart_name(depart_no) as depart_name,lead_code from employee where employee_no='$ID'";
	$user_info = selectArray($user_sql);
	$name = $user_info[0][NAME];
	$depart_no = $user_info[0][DEPART_NO];
	$depart_name = $user_info[0][DEPART_NAME];
	$lead_code = $user_info[0][LEAD_CODE];
	$ziXun = array('103','109');
	//默认希望实施日期为7个工作日
	list($sysrundate)=fields("select calendar_date from (select a.*,rownum as rn from (select calendar_date 
	from calendar  where calendar_date>sysdate and is_work=0 order by calendar_date) a where 
	rownum<=7 ) b where b.rn=7");
	$chrundate = date("Y/m/d");
	echo "<div style='text-align:center'>部门： $depart_name  员工号码：$ID   员工姓名：$name</div>";
?>