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
	<tr><td style="background-color:#A4D3EE;font-size:28px;text-align:center" colspan=6>��ҵƽ̨Ȩ�ޱ�����뵥</td></tr>
	
	<tr>
		<th style='width:10%'>���뵥λ</th>
		<td style='width:20%'><input type="hidden" name="depart_no" value="<?php echo $depart_no;?>"><?php echo $depart_name;?></td>
		<th style='width:10%'>������</th>
		<td style='width:20%'><input type="hidden" name="creator" value="<?php echo $ID?>"><?php echo $name?></td>
		<th style='width:10%'>�ɷ񹫿�</th>
		<td style='width:20%'>
			<select name="is_public">
				<option value="Y">��</option>
				<option value="N">��</option>
			</select>
		</td>
	</tr>
	<tr>
		<th style='width:10%'>��ѡ��������Ȩ����Ա</th>
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
			��Ctrl���ɸ�ѡ������Ա
		</td>
		<th style='width:10%'>��Ч����</th>
		<td style='width:20%'><input type="text" name="chrundate" id="chrundate" onclick="return showCalendar('chrundate', 'y/mm/dd');"  value="<?php echo $chrundate;?>" size='12' readonly ></td>
		<th style='width:10%'>�˺�</th>
		<td style='width:20%'>
			<select name="chaccount">
				<option value="A">����</option>
				<option value="D">ȡ��</option>
			</select>
		</td>
	</tr>
	<tr>
		<th style='width:10%'>Ȩ��</th>
		<td colspan=5>
		<input type="checkbox" name="chpower[]" value="F" />��ѯ
		<input type="checkbox" name="chpower[]" value="C" />����
		<input type="checkbox" name="chpower[]" value="U" />����
		<input type="checkbox" name="chpower[]" value="D" />ɾ��
		</td>
		
	</tr>
	<tr>
		<th rowspan=5 style='width:10%'>��д˵��</th>
		<td style='width:20%'>�ⲿ��վ���</td>
		<td colspan=4 style='width:60%'>���޾�ͤ������򳧡�����Ӫҵ�칫�㡢�ɶ�Ӫҵ�칫�㡢
	   �ֶ�Ӫҵ�칫��칫��Ա����,
	   �����뵥λЭ������������ˣ���Ѷ��Ϊת�ᣩ�����Կ�ͨ;</td>
	</tr>
	<tr>
		<td style='width:20%'>E-mail</td>
		<td colspan=4 style='width:60%'>���ڡ�E-mail�ʺ�ѡ�������д�ʺ����ƣ�Ĭ��'U'+Ա�����;</td>
	</tr>
	<tr>
		<td style='width:20%'>���ŷ�VPN�ʺ�</td>
		<td colspan=4 style='width:60%'>�����ڼ�δ���������֮���칫��Ա��������,<br>
	    ����ʱ���ṩ��ʹ�õ��ԣ��̶��ʲ���š������ͺš���Ʒ���кŵ�)��Ϣ;</td>
	</tr>
	<tr>
		<td style='width:20%'>PDA/EPC/ƽ�������ҵ</td>
		<td colspan=4 style='width:60%'>���ṩ������Ϣ:<br>
	1����ʹ����/�ʺš���������ID�š�������Ϣ����PDA/EPC�������ϵͳ�����벢��ȡ;<br>
	2������λ���ṩ��ְ�Ʊ��롱;<br>
	3�������豸���ṩ�豸���/�ʺţ�����:P0541012 ��</td>
	</tr>
	<tr>
		<td style='width:20%'>�ƶ��豸VPN�˺�</td>
		<td colspan=4 style='width:60%'>1���������ֻ���ƽ����ԣ�ϵͳΪIOS/����/��׿(�������ΪOPERA)��;<br>
	2�����ṩ�ƶ��豸��Ʒ��,����ϵͳ�����кš�IMEI��WLAN MAC��������ַ����Ϣ��</td>
	</tr>
	<tr>
		<th style='width:10%'>��ҵƽ̨</th>
		<td colspan=5 style='width:80%'>
		  <input type="checkbox" name="chplatform[]" value="I">�ⲿ��վ���
		  <input type="checkbox" name="chplatform[]" value="W">�ڲ���ҳ/oracle��¼�ʺ�
		  <input type="checkbox" name="chplatform[]" value="E">e-mail
		  <input type="checkbox" name="chplatform[]" value="V">���ŷ�VPN�ʺ�
		  <input type="checkbox" name="chplatform[]" value="N">��˾����Ӳ��
		  <input type="checkbox" name="chplatform[]" value="P">PDA/EPC/ƽ�������ҵ
		  <input type="checkbox" name="chplatform[]" value="Y">�ƶ��豸VPN�˺�
		</td>
	</tr>
	<tr>
		<th style='width:10%'>��ҵ��Ŀ</th>
		<td colspan=5 style='width:80%'><textarea name="chitem" id="chitem" rows="5" cols="100" maxlength="2000" required></textarea><span style="color:red">* </span></td>
	</tr>
	<tr>
		<th style='width:10%'>�������ԭ��</th>
		<td colspan=5 style='width:80%'><textarea name="chreason" rows="5" cols="100" maxlength="2000"></textarea></td>
	</tr>
	<tr>
		<th style='width:10%'>E-mail�˺�(ѡ����)</th>
		<td colspan=5 style='width:80%'><input type="text" name="chmail" size="10" maxlength="8" />�磺U12345</td>
	</tr>
	<?php 
	$nn=3;
	for ($i = 0;$i < $nn;$i++){
		if($i%3 == 0){
			echo "<tr>";
		}
		echo "<th style='width:10%'>��  ��</th>
				<td style='width:20%'><input type='file' name='file_upload[]'></td>";
		
	}
	?>
	<tr><th style='width:10%'>��һλǩ����</th>
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
	<input type="submit" name="submit" value="�����ļ�" onclick="return checkForm()" />
	<input type="reset"  value="�����д" />
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
			alert("��ѡ������Ȩ��");
			return false;
		}
		if ($("input[name='chplatform[]']:checked").length == 0){
			alert("��ѡ��Ȩ��ƽ̨");
			return false;
		}
		if($("#chitem").val() == ''){
			alert("��������ҵ��Ŀ");
			return false;
		}
	
}
</script>
</body>
</html>