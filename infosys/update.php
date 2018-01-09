<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=gb2312" />
	<title>资讯单申请系统</title>
	<link href="css/menu.css" rel="stylesheet" type="text/css" />
	<link href="css/calendar.css" rel="stylesheet" type="text/css" />
	<script src="js/jquery-1.11.1.min.js" type="text/javascript"></script>
	<script src="js/calendar.js" type="text/javascript"></script>
	<script src="js/calendar-setup.js" type="text/javascript"></script>
	<script src="js/calendar-zh.js" type="text/javascript"></script>
</head>
<body>
<?php 
require("./inc/common.inc");
$nextuser_sql = "select employee_no,name from employee where substr(depart_no,0,3) in ('103','109')
				and substr(employee_no,0,1)<>'9' and substr(lead_code,0,1)>=5  order by 1 desc ";
$nextuserArr = selectArray($nextuser_sql);

$sql_sys="select ap_key,ap_name from public.info_running_sys where stop_use!='Y'  order by ap_key";
$sys_array=selectMysql($sql_sys);
$proper_array=propertyNeed();
?>

<div>
<form action="update_write.php" method="post" enctype="multipart/form-data">
<table>
	<tr><td style="background-color:#A4D3EE;font-size:28px;text-align:center" colspan=3>资讯申请单资料变更作业</td></tr>
	<tr>
		<th style='text-align:left'><input type='radio' name='change' value='C1' />变更预定完成日</th>
		<td>申请单号<input type='text' name='sheet_no1' /></td>
		<td>新预定完成日期<input type='text' name='pre_finish_date' id='pre_finish_date'  onclick="return showCalendar('pre_finish_date', 'y/mm/dd');" readonly /></td>
		
	</tr>
	<tr>
		<th style='text-align:left'><input type='radio' name='change' value='C2' />重新指派</th>
		<td>申请单号<input type='text' name='sheet_no2' /></td>
		<td>新指派人员 <select name='next_user'>
		<?php
			foreach ($nextuserArr as $value){
				echo "<option value='$value[EMPLOYEE_NO]'>$value[EMPLOYEE_NO]-$value[NAME]</option>";
			}
			
		?></select></td>
		
	</tr>
	<tr>
		<th style='text-align:left'><input type='radio' name='change' value='C3' />退回资讯科长重新指派</th>
		<td>申请单号<input type='text' name='sheet_no3' /></td>
		<td></td>
	</tr>
	<tr>
		<th style='text-align:left'><input type='radio' name='change' value='C4' />变更指派日期</th>
		<td>申请单号<input type='text' name='sheet_no4' /></td>
		<td>新指派日期<input type='text' name='fh_date' id='fh_date'  onclick="return showCalendar('fh_date', 'y/mm/dd');" readonly /></td>
		
	</tr>
	<tr>
		<th style='text-align:left'><input type='radio' name='change' value='C5' />直接结案</th>
		<td>申请单号<input type='text' name='sheet_no5' /></td>
		<td></td>
	</tr>
	<tr>
		<th style='text-align:left'><input type='radio' name='change' value='C6' />变更系统选项</th>
		<td>申请单号<input type='text' name='sheet_no6' /></td>
		<td>系统名称 <select name='sys_code'><option value=""></option>
		<?php
			foreach ($sys_array as $value){
				echo "<option value='$value[0]'>$value[0]-$value[1]</option>";
			}
			
		?></select></td>
		
	</tr>
	<tr>
		<th style='text-align:left'><input type='radio' name='change' value='C7' />变更处理类别</th>
		<td>申请单号<input type='text' name='sheet_no7' /></td>
		<td>处理类别 <select name='need_kind'><option value=""></option>
		<?php
			foreach ($proper_array as $key => $value){
				echo "<option value='$key'>$value</option>";
			}
			
		?></select></td>
		
	</tr>
	<tr>
		<th style='text-align:left'><input type='radio' name='change' value='C8' />变更申请原因</th>
		<td>申请单号<input type='text' name='sheet_no8' /></td>
		<td>申请原因 <select name="apply_type">
			<option value=""></option>
			<option value="1">每月固定作业-后续拟系统化</option>
			<option value="2">配合委托部门-后续拟系统化</option>
			<option value="3">配合委托部门-无法系统化</option>
			<option value="4">资讯人员书写程式错误</option>
		</select>
		</td>
		
	</tr>
	
</table>
<br />
<p>
	<input type="submit" name="submit" value="确认修改"  />
	<input type="reset"  value="清除重写" />
</p>

</form>
</div>
<script>

function checkForm(){console.log($("textarea[name='qacontent']").val());
	if ($("textarea[name='qacontent']").val() == ''){
		alert("请填写问题内容");
		return false;
	}
}
</script>
</body>
</html>