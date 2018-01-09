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
	<tr><td style="background-color:#A4D3EE;font-size:28px;text-align:center" colspan=6>作业平台权限变更申请单</td></tr>
	
	<tr>
		<th style='width:10%'>申请单位</th>
		<td style='width:20%'><input type="hidden" name="depart_no" value="<?php echo $depart_no;?>"><?php echo $depart_name;?></td>
		<th style='width:10%'>作成者</th>
		<td style='width:20%'><input type="hidden" name="creator" value="<?php echo $ID?>"><?php echo $name?></td>
		<th style='width:10%'>可否公开</th>
		<td style='width:20%'>
			<select name="is_public">
				<option value="Y">是</option>
				<option value="N">否</option>
			</select>
		</td>
	</tr>
	<tr>
		<th style='width:10%'>请选择需申请权限人员</th>
		<td style='width:20%'>
			<select name="apply_man[]" multiple required>
			<?php 
			$sql="select employee_no,name,case when employee_no='$ID' then 0 else 1 end as flag 
					from it.employee where substr(depart_no,0,5)=substr('$depart_no',0,5) order by flag,employee_no";
			$man_array=selectArray($sql);
			foreach($man_array as $key => $value){
				if($ID == $value[EMPLOYEE_NO]){
					$man_select = 'selected';
				}else{
					$man_select = '';
				}
				echo "<option value='$value[EMPLOYEE_NO]' $man_select>$value[EMPLOYEE_NO]-$value[NAME]</option>";
			}
			?>
			</select><br />
			按Ctrl键可复选多名人员
		</td>
		<th style='width:10%'>生效日期</th>
		<td style='width:20%'><input type="text" name="chrundate" id="chrundate" onclick="return showCalendar('chrundate', 'y/mm/dd');"  value="<?php echo $chrundate;?>" size='12' readonly ></td>
		<th style='width:10%'>账号</th>
		<td style='width:20%'>
			<select name="chaccount">
				<option value="A">新增</option>
				<option value="D">取消</option>
			</select>
		</td>
	</tr>
	<tr>
		<th style='width:10%'>权限</th>
		<td colspan=5>
		<input type="checkbox" name="chpower[]" value="F" />查询
		<input type="checkbox" name="chpower[]" value="C" />建档
		<input type="checkbox" name="chpower[]" value="U" />更新
		<input type="checkbox" name="chpower[]" value="D" />删除
		</td>
		
	</tr>
	<tr>
		<th rowspan=5 style='width:10%'>填写说明</th>
		<td style='width:20%'>外部网站浏览</td>
		<td colspan=4 style='width:60%'>仅限九亭厂、天津厂、北京营业办公点、成都营业办公点、
	   浦东营业办公点办公人员申请,
	   经申请单位协理以上主管审核（资讯代为转会）后予以开通;</td>
	</tr>
	<tr>
		<td style='width:20%'>E-mail</td>
		<td colspan=4 style='width:60%'>请于“E-mail帐号选项”栏中填写帐号名称，默认'U'+员工编号;</td>
	</tr>
	<tr>
		<td style='width:20%'>深信服VPN帐号</td>
		<td colspan=4 style='width:60%'>限外勤及未经网络改造之外点办公人员进行申请,<br>
	    申请时须提供所使用电脑（固定资产编号、电脑型号、产品序列号等)信息;</td>
	</tr>
	<tr>
		<td style='width:20%'>PDA/EPC/平板电脑作业</td>
		<td colspan=4 style='width:60%'>请提供如下信息:<br>
	1、“使用者/帐号”、“机身ID号”，该信息可在PDA/EPC申请管理系统中申请并获取;<br>
	2、服务单位请提供“职制编码”;<br>
	3、网点设备请提供设备编号/帐号，例如:P0541012 。</td>
	</tr>
	<tr>
		<td style='width:20%'>移动设备VPN账号</td>
		<td colspan=4 style='width:60%'>1、适用于手机及平板电脑，系统为IOS/塞班/安卓(浏览器需为OPERA)等;<br>
	2、请提供移动设备的品牌,操作系统、序列号、IMEI、WLAN MAC及蓝牙地址等信息。</td>
	</tr>
	<tr>
		<th style='width:10%'>作业平台</th>
		<td colspan=5 style='width:80%'>
		  <input type="checkbox" name="chplatform[]" value="I">外部网站浏览
		  <input type="checkbox" name="chplatform[]" value="W">内部首页/oracle登录帐号
		  <input type="checkbox" name="chplatform[]" value="E">e-mail
		  <input type="checkbox" name="chplatform[]" value="V">深信服VPN帐号
		  <input type="checkbox" name="chplatform[]" value="N">公司网络硬盘
		  <input type="checkbox" name="chplatform[]" value="P">PDA/EPC/平板电脑作业
		  <input type="checkbox" name="chplatform[]" value="Y">移动设备VPN账号
		</td>
	</tr>
	<tr>
		<th style='width:10%'>作业项目</th>
		<td colspan=5 style='width:80%'><textarea name="chitem" id="chitem" rows="5" cols="100" maxlength="2000" required></textarea><span style="color:red">* </span></td>
	</tr>
	<tr>
		<th style='width:10%'>申请或变更原因</th>
		<td colspan=5 style='width:80%'><textarea name="chreason" rows="5" cols="100" maxlength="2000"></textarea></td>
	</tr>
	<tr>
		<th style='width:10%'>E-mail账号(选填项)</th>
		<td colspan=5 style='width:80%'><input type="text" name="chmail" size="10" maxlength="8" />如：U12345</td>
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
	<td style='width:20%'><select name="next_user" required>
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
<input type="hidden" name="dockind" value="A" />
<input type="hidden" name="next_code" value="<?php echo $next_code;?>" />
<input type="hidden" name="sess_code" value="<?php echo $sess_code;?>" />
</form>
</div>
<script>

function checkForm(){
	
	console.log($("input[name='chpower[]']:checked").val());
		if ($("input[name='chpower[]']:checked").length == 0){
			alert("请选择申请权限");
			return false;
		}
		if ($("input[name='chplatform[]']:checked").length == 0){
			alert("请选择权限平台");
			return false;
		}
		if($("#chitem").val() == ''){
			alert("请输入作业项目");
			return false;
		}
	
}
</script>
</body>
</html>