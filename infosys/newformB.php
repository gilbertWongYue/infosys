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
	<tr><td style="background-color:#A4D3EE;font-size:28px;text-align:center" colspan=6>ϵͳ����������</td></tr>
	<tr><td style="background-color:#A4D3EE;font-size:16px;text-align:center" colspan=6>�������������ȷѡ���Ӧ��ϵͳ���ƣ����޷�ѡȡ��ȷ�������������������
	�����ҵ·�� <br />���磺��ҳ����>�칫���ճ����롪��>��Ѷ�������롪��>�����뵥����>ϵͳ�������뻭�棩</td></tr>
	<tr>
		<th style='width:10%'>ϵͳ����</th>
		<td colspan=5 style='width:80%'><select type="text" id="sys_code" name="sys_code" required>
				<option value="">��ѡ��ϵͳ����</option>
		<?php 
			$sql_sys="select ap_key,ap_name from public.info_running_sys where stop_use!='Y'  order by kind";
			$sys_array=selectMysql($sql_sys);
			foreach($sys_array as $key => $value){
				echo "<option value='$value[0]'>$value[0] - $value[1]</option>";
			}
		?>
		</select>
		<input type="text" id="txtSearch" maxlength=50 
		onblur="this.style.backgroundColor='#ffffff'" /> <span style="color:red">�����ڴ����������ؼ��ֲ�ѯϵͳ����</span>
		</td>
	</tr>
	<tr>
		<th style='width:10%'>������</th>
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
		<th style='width:10%'>��������</th>
		<td style='width:20%'>
			<select name="need_kind" id="need_kind" required>
			<option value="">��ѡ��</option>
			<?php 
			$proper_array=propertyNeed();
			foreach($proper_array as $key => $value){
				echo "<option value='$key'>$value</option>";
			}
			?>
			</select>
		</td>
		<th style='width:10%'>�ɷ񹫿�</th>
		<td style='width:20%'>
			<select name="is_public">
				<option value="Y">��</option>
				<option value="N">��</option>
			</select>
		</td>
	</tr>
	<tr>
		<th style='width:10%'>ϵͳ��Ҫʽ����</th>
		<td style='width:20%'>
			<select name="syssample">
				<option value="Y">��Ҫ</option>
				<option value="N" selected>����Ҫ</option>
			</select>
		</td>
		<th style='width:10%'>������</th>
		<td style='width:20%'>
			<select name="vcode">
				<option value="A" selected>��ͨ</option>
				<option value="B" >����</option>
			</select>
		</td>
		<th style='width:10%'>������</th>
		<td style='width:20%'><input type="hidden" name="creator" value="<?php echo $ID?>"><?php echo $name?></td>
	</tr>
	<tr>
		<th style='width:10%'>ϣ����������</th>
		<td style='width:20%'>
			<select name="syscycle">
				<option value="D" >��</option>
				<option value="W" selected>��</option>
				<option value="M" >��</option>
				<option value="S" >��</option>
				<option value="Y" >��</option>
				<option value="O" >����</option>
				
			</select>
		</td>
		<th style='width:10%'>ϣ��ʵʩ����</th>
		<td style='width:20%'><input type="text" name="sysrundate"  id="sysrundate" value="<?php echo $sysrundate?>" onclick="return showCalendar('sysrundate', 'y/mm/dd');" readonly></td>
		<th></th>
		<td></td>
	</tr>
	<tr>
		<th style='width:10%'>����ԭ��</th>
		<td colspan=5  style='width:80%'>
		<input type="radio" name="apply_type" class="mwdsb" id="apply_type1" value="1">ÿ�¹̶���ҵ-������ϵͳ��
		<br />
		<input type="radio" name="apply_type" class="mwdsb" id="apply_type2" value="2" >���ί�в���-������ϵͳ��
		<br />
		<input type="radio" name="apply_type" class="mwdsb" id="apply_type3" value="3">���ί�в���-�޷�ϵͳ��
		<br />
		<input type="radio" id="sbsbsb"  class="mwdsb" name="apply_type" value="4">
		��Ѷ��Ա��д��ʽ����|
  ԭ��Ѷ����<input type="text" id="ssss" class="mwdsb" name="zxd" onkeyup="this.value=this.value.replace(/[^\d]/g,'')"  maxlength='9' />
		<br />
		</td>
	</tr>
	<tr>
		<th style='width:10%'>ϵͳ��Ҫ</th>
		<td colspan=5 style='width:80%'><textarea name="syssummary" id="syssummary" rows="5" cols="100" maxlength="2000" required></textarea><span style="color:red">* </span></td>
	</tr>
	<tr>
		<th style='width:10%'>���־���</th>
		<td colspan=5 style='width:80%'><textarea name="sysdiscuss" rows="5" cols="100" maxlength="2000"></textarea></td>
	</tr>
	<tr>
		<th style='width:10%'>Ԥ��Ч��</th>
		<td colspan=5 style='width:80%'><textarea name="syswisheffect" rows="5" cols="100" maxlength="2000"></textarea></td>
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
	<input type="submit" name="submit" value="�����ļ�" onclick="return checkForm()" />
	<input type="reset"  value="�����д" />
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
		//List �������option
		var List = [];
		
		for  (var  i = 0; i < sysName.length; i++) {
			List[i] = sysName[i].value + "|" + sysName[i].text;
		}
		txtSearch.oninput = function() { 
			if (!(txtSearch.value.length < 1)){
				//����������ַ����Ƿ���List��
				for (var i = 0; i < List.length; i++){
					//indexOf ����ֵ���ַ������״γ��ֵ�λ��
					if (List[i].indexOf(txtSearch.value) > -1){
						//������ڣ�����ǰoption ѡ��
						sysName[i].selected = true;
						
					}
				}
				
				
			}
		}
		txtSearch.onpropertychange = function() {
			if (!(txtSearch.value.length < 1)){
				//����������ַ����Ƿ���List��
				for (var i = 0; i < List.length; i++){
					//indexOf ����ֵ���ַ������״γ��ֵ�λ��
					if (List[i].indexOf(txtSearch.value) > -1){
						//������ڣ�����ǰoption ѡ��
						sysName[i].selected = true;
						
					}
				}
				
				
			}
		}
	
});
$("#need_kind").change(function(){
	//��������Ϊ���ϴ���ʱ-7����ѡ������ԭ��
	if ($("#need_kind").val() != '7'){
		$(".mwdsb").prop('disabled',true);
	}else{
		$(".mwdsb").prop('disabled',false);
		//ѡ����Ѷ��Ա��д��ʽ���󣬿�����ԭ��Ѷ����
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
	//List �������option
	var List = [];
	
	for  (var  i = 0; i < sysName.length; i++) {
		List[i] = sysName[i].value + "|" + sysName[i].text;
	}
	txtSearch.oninput = function() { 
		if (!(txtSearch.value.length < 1)){
			//����������ַ����Ƿ���List��
			for (var i = 0; i < List.length; i++){
				//indexOf ����ֵ���ַ������״γ��ֵ�λ��
				if (List[i].indexOf(txtSearch.value) > -1){
					//������ڣ�����ǰoption ѡ��
					sysName[i].selected = true;
					
				}
			}
			
			
		}
	}
}
function check1(){
	var txtSearch = document.getElementById("txtSearch");
	var  sysName = document.getElementById("sys_code");
	//List �������option
	var List = [];
	for  (var  i = 0; i < sysName.length; i++) {
		List[i] = sysName[i].value + "|" + sysName[i].text;
	}
	if (!(txtSearch.value.length < 1)){
			//����������ַ����Ƿ���List��
			for (var i = 0; i < List.length; i++){
				//indexOf ����ֵ���ַ������״γ��ֵ�λ��
				if (List[i].indexOf(txtSearch.value) > -1){
					//������ڣ�����ǰoption ѡ��
					sysName[i].selected = true;
				}
			}
	}
} */
function checkForm(){
	if ($("#sys_code").val() == ''){
		alert("��ѡ��ϵͳ����");
		return false;
	}
	if ($("#need_kind").val() == ''){
		alert("��ѡ����������");
		return false;
	}
	if ($("#need_kind").val() == '7'){
		if ($("input[name='apply_type']:checked").length == 0){
			alert("��������ѡ�����ϴ���ʱ����ѡ������ԭ��");
			return false;
		}
		if ($("input[name='apply_type']:checked").val() == '4' && $("input[name='zxd']").val() == ''){
			alert("����дԭ��Ѷ����");
			return false;
		}
	}
	if ($("#syssummary").val() == ''){
		alert("������ϵͳ��Ҫ");
		return false;
	}
}
</script>
</body>
</html>