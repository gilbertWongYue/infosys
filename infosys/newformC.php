<?php require("./inc/common.inc");?>
<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=gb2312" />
	<title>��Ѷ������ϵͳ</title>
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
	<tr><td style="background-color:#A4D3EE;font-size:28px;text-align:center" colspan=4>��Ѷ��ҵ������Բߴ���������</td></tr>
	<tr>
		<th style='width:15%'>���س̶�</th>
		<td style='width:30%'><select name="qadegree">
		<?php 
			$ques_arr = queLevel();
			foreach($ques_arr as $key => $value){
				echo "<option value='$key'>$value</option>";
			}
		?>
		</td>
		<th style='width:15%'>������</th>
		<td style='width:30%'><input type="hidden" name="creator" value="<?php echo $ID?>"><?php echo $name?></td>
	</tr>
	<tr>
		<th style='width:15%'>�᰸��λ</th>
		<td style='width:30%'><input type="hidden" name="depart_no" value="<?php echo $depart_no;?>"><?php echo $depart_name;?></td>
		<th style='width:15%'>�᰸��</th>
		<td style='width:30%'>
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
	</tr>
	<tr>
		<th style='width:15%'>��������</th>
		<td colspan=3 style='width:75%'>
			<textarea name="qacontent"  required rows=5 cols=70></textarea>
		</td>
	</tr>
	<tr>
		<th style='width:15%'>˵��</th>
		<td colspan=3 style='width:75%'>
			<textarea name="qadescribe" rows=5 cols=70></textarea>
		</td>
		
	</tr>
	
	
	<?php 
	$nn=2;
	for ($i = 0;$i < $nn;$i++){
		if($i%2 == 0){
			echo "<tr>";
		}
		echo "<th>��  ��</th>
				<td><input type='file' name='file_upload[]'></td>";
		
	}
	?>
	<tr><th style='width:15%'>��һλǩ����</th>
	<td style='width:30%'><select name="next_user" >
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
	<input type="submit" name="submit" value="�����ļ�" onclick="return checkForm()" />
	<input type="reset"  value="�����д" />
</p>
<?php 
$sess_code = mt_rand(1,1000000);
?>
<input type="hidden" name="dockind" value="C" />
<input type="hidden" name="next_code" value="<?php echo $next_code;?>" />
<input type="hidden" name="sess_code" value="<?php echo $sess_code;?>" />
</form>
</div>
<script>

function checkForm(){console.log($("textarea[name='qacontent']").val());
	if ($("textarea[name='qacontent']").val() == ''){
		alert("����д��������");
		return false;
	}
}
</script>
</body>
</html>