<?php require("./inc/common.inc");?>
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


<div>
<form action="newform_write.php" method="post" enctype="multipart/form-data">
<table>
	<tr><td style="background-color:#A4D3EE;font-size:28px;text-align:center" colspan=6>系统检讨申请书</td></tr>
	<tr><td style="background-color:#A4D3EE;font-size:16px;text-align:center" colspan=6>请申请人务必正确选择对应的系统名称，如无法选取或不确定亦请申请人描述清楚
	相关作业路径 <br />（如：首页——>办公室日常申请——>资讯需求申请——>新申请单——>系统检讨申请画面）</td></tr>
	<tr>
		<th style='width:10%'>系统名称</th>
		<td colspan=5 style='width:80%'><select type="text" id="sys_code" name="sys_code" required>
				<option value="">请选择系统名称</option>
		<?php 
			$sql_sys="select ap_key,ap_name from public.info_running_sys where stop_use!='Y'  order by kind";
			$sys_array=selectMysql($sql_sys);
			foreach($sys_array as $key => $value){
				echo "<option value='$value[0]'>$value[0] - $value[1]</option>";
			}
		?>
		</select>
		<input type="text" id="txtSearch" maxlength=50 
		onblur="this.style.backgroundColor='#ffffff'" /> <span style="color:red">您可在此输入框输入关键字查询系统名称</span>
		</td>
	</tr>
	<tr>
		<th style='width:10%'>申请人</th>
		<td style='width:20%'>
			<select name="apply_man" required>
			<?php 
			$sql="select employee_no,name,case when employee_no='$ID' then 0 else 1 end as flag 
					from it.employee where substr(depart_no,0,5)=substr('$depart_no',0,5) order by flag,employee_no";
			$man_array=selectArray($sql);
			foreach($man_array as $key => $value){
				echo "<option value='$value[EMPLOYEE_NO]'>$value[EMPLOYEE_NO]-$value[NAME]</option>";
			}
			?>
			</select>
		</td>
		<th style='width:10%'>需求性质</th>
		<td style='width:20%'>
			<select name="need_kind" id="need_kind" required>
			<option value="">请选择</option>
			<?php 
			$proper_array=propertyNeed();
			foreach($proper_array as $key => $value){
				echo "<option value='$key'>$value</option>";
			}
			?>
			</select>
		</td>
		<th style='width:10%'>可否公开</th>
		<td style='width:20%'>
			<select name="is_public">
				<option value="Y">是</option>
				<option value="N">否</option>
			</select>
		</td>
	</tr>
	<tr>
		<th style='width:10%'>系统概要式样书</th>
		<td style='width:20%'>
			<select name="syssample">
				<option value="Y">需要</option>
				<option value="N" selected>不需要</option>
			</select>
		</td>
		<th style='width:10%'>紧急度</th>
		<td style='width:20%'>
			<select name="vcode">
				<option value="A" selected>普通</option>
				<option value="B" >紧急</option>
			</select>
		</td>
		<th style='width:10%'>作成者</th>
		<td style='width:20%'><input type="hidden" name="creator" value="<?php echo $ID?>"><?php echo $name?></td>
	</tr>
	<tr>
		<th style='width:10%'>希望处理周期</th>
		<td style='width:20%'>
			<select name="syscycle">
				<option value="D" >日</option>
				<option value="W" selected>周</option>
				<option value="M" >月</option>
				<option value="S" >季</option>
				<option value="Y" >年</option>
				<option value="O" >其他</option>
				
			</select>
		</td>
		<th style='width:10%'>希望实施日期</th>
		<td style='width:20%'><input type="text" name="sysrundate"  id="sysrundate" value="<?php echo $sysrundate?>" onclick="return showCalendar('sysrundate', 'y/mm/dd');" readonly></td>
		<th></th>
		<td></td>
	</tr>
	<tr>
		<th style='width:10%'>申请原因</th>
		<td colspan=5  style='width:80%'>
		<input type="radio" name="apply_type" class="mwdsb" id="apply_type1" value="1">每月固定作业-后续拟系统化
		<br />
		<input type="radio" name="apply_type" class="mwdsb" id="apply_type2" value="2" >配合委托部门-后续拟系统化
		<br />
		<input type="radio" name="apply_type" class="mwdsb" id="apply_type3" value="3">配合委托部门-无法系统化
		<br />
		<input type="radio" id="sbsbsb"  class="mwdsb" name="apply_type" value="4">
		资讯人员书写程式错误|
  原资讯单号<input type="text" id="ssss" class="mwdsb" name="zxd" onkeyup="this.value=this.value.replace(/[^\d]/g,'')"  maxlength='9' />
		<br />
		</td>
	</tr>
	<tr>
		<th style='width:10%'>系统概要</th>
		<td colspan=5 style='width:80%'><textarea name="syssummary" id="syssummary" rows="5" cols="100" maxlength="2000" required></textarea><span style="color:red">* </span></td>
	</tr>
	<tr>
		<th style='width:10%'>检讨经过</th>
		<td colspan=5 style='width:80%'><textarea name="sysdiscuss" rows="5" cols="100" maxlength="2000"></textarea></td>
	</tr>
	<tr>
		<th style='width:10%'>预想效果</th>
		<td colspan=5 style='width:80%'><textarea name="syswisheffect" rows="5" cols="100" maxlength="2000"></textarea></td>
	</tr>
	<?php 
	$nn=3;
	for ($i = 0;$i < $nn;$i++){
		if($i%3 == 0){
			echo "<tr>";
		}
		echo "<th style='width:10%'>附  件</th>
				<td style='width:20%'><input type='file' name='file_upload[]'></td>";
		
	}
	?>
	<tr><th style='width:10%'>下一位签核人</th>
	<td style='width:20%'><select name="next_user" >
		<?php 
		include("inc/nextuser.inc");
		foreach($next_arr as $key => $value){
			echo "<option value='$value[EMPLOYEE_NO]'>$value[EMPLOYEE_NO]-$value[NAME]</option>";
		}
		?>
		</select>
	</td>
	</tr>
</table>
<br />
<p>
	<input type="submit" name="submit" value="传送文件" onclick="return checkForm()" />
	<input type="reset"  value="清除重写" />
</p>
<?php 
$sess_code = mt_rand(1,1000000);
?>
<input type="hidden" name="dockind" value="B" />
<input type="hidden" name="next_code" value="<?php echo $next_code;?>" />
<input type="hidden" name="sess_code" value="<?php echo $sess_code;?>" />
</form>
</div>
<script>
$(function(){
		$(".mwdsb").prop('disabled',true);
		// var inputList = $("input[name='apply_type']");console.log(inputList);
		// for (var x = 0; x < inputList.length; x++){
			// inputList[x].checked = false;
		// }
		
		var txtSearch = document.getElementById("txtSearch");
		var  sysName = document.getElementById("sys_code");
		//List 存放所有option
		var List = [];
		
		for  (var  i = 0; i < sysName.length; i++) {
			List[i] = sysName[i].value + "|" + sysName[i].text;
		}
		txtSearch.oninput = function() { 
			if (!(txtSearch.value.length < 1)){
				//查找输入的字符串是否在List中
				for (var i = 0; i < List.length; i++){
					//indexOf 返回值在字符串中首次出现的位置
					if (List[i].indexOf(txtSearch.value) > -1){
						//如果存在，将当前option 选中
						sysName[i].selected = true;
						
					}
				}
				
				
			}
		}
		txtSearch.onpropertychange = function() {
			if (!(txtSearch.value.length < 1)){
				//查找输入的字符串是否在List中
				for (var i = 0; i < List.length; i++){
					//indexOf 返回值在字符串中首次出现的位置
					if (List[i].indexOf(txtSearch.value) > -1){
						//如果存在，将当前option 选中
						sysName[i].selected = true;
						
					}
				}
				
				
			}
		}
	
});
$("#need_kind").change(function(){
	//需求性质为资料处理时-7，需选择申请原因
	if ($("#need_kind").val() != '7'){
		$(".mwdsb").prop('disabled',true);
	}else{
		$(".mwdsb").prop('disabled',false);
		//选择资讯人员书写程式错误，可输入原资讯单号
		if ($("#sbsbsb").prop('checked')==true){
			$("#ssss").prop('disabled',false);
		}else{
			$("#ssss").prop('disabled',true);
		}
	}
});
$(".mwdsb").change(function(){
		if($("#sbsbsb").prop('checked')==true){
				$("#ssss").prop('disabled',false);
			}
			else{
				$("#ssss").prop('disabled',true);
			}
});
/* function check(){
	var txtSearch = document.getElementById("txtSearch");
	var  sysName = document.getElementById("sys_code");
	//List 存放所有option
	var List = [];
	
	for  (var  i = 0; i < sysName.length; i++) {
		List[i] = sysName[i].value + "|" + sysName[i].text;
	}
	txtSearch.oninput = function() { 
		if (!(txtSearch.value.length < 1)){
			//查找输入的字符串是否在List中
			for (var i = 0; i < List.length; i++){
				//indexOf 返回值在字符串中首次出现的位置
				if (List[i].indexOf(txtSearch.value) > -1){
					//如果存在，将当前option 选中
					sysName[i].selected = true;
					
				}
			}
			
			
		}
	}
}
function check1(){
	var txtSearch = document.getElementById("txtSearch");
	var  sysName = document.getElementById("sys_code");
	//List 存放所有option
	var List = [];
	for  (var  i = 0; i < sysName.length; i++) {
		List[i] = sysName[i].value + "|" + sysName[i].text;
	}
	if (!(txtSearch.value.length < 1)){
			//查找输入的字符串是否在List中
			for (var i = 0; i < List.length; i++){
				//indexOf 返回值在字符串中首次出现的位置
				if (List[i].indexOf(txtSearch.value) > -1){
					//如果存在，将当前option 选中
					sysName[i].selected = true;
				}
			}
	}
} */
function checkForm(){
	if ($("#sys_code").val() == ''){
		alert("请选择系统名称");
		return false;
	}
	if ($("#need_kind").val() == ''){
		alert("请选择需求性质");
		return false;
	}
	if ($("#need_kind").val() == '7'){
		if ($("input[name='apply_type']:checked").length == 0){
			alert("需求性质选择资料处理时，请选择申请原因");
			return false;
		}
		if ($("input[name='apply_type']:checked").val() == '4' && $("input[name='zxd']").val() == ''){
			alert("请填写原资讯单号");
			return false;
		}
	}
	if ($("#syssummary").val() == ''){
		alert("请输入系统概要");
		return false;
	}
}
</script>
</body>
</html>