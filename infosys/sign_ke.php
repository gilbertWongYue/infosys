<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=gb2312" />
	<title>资讯单系统签核作业</title>
	<link href="css/menu.css" rel="stylesheet" type="text/css" />
	<link href="css/calendar.css" rel="stylesheet" type="text/css" />
	<script src="js/jquery-1.11.1.min.js" type="text/javascript"></script>
	<script src="js/calendar.js" type="text/javascript"></script>
	<script src="js/calendar-setup.js" type="text/javascript"></script>
	<script src="js/calendar-zh.js" type="text/javascript"></script>
</head>
<body>
<?php require("./inc/common.inc");?>

<div>
<form action="sign_ke_write.php" method="post" enctype="multipart/form_data">
<?php 
	$ziXunKeZhang = array('45224','22305','00279');
	if(!in_array($ID,$ziXunKeZhang)){
		echo "<p style='color:red;font-size:28px'>您非资讯人员，无权限查看此画面！<br /></p>";
			exit();
	}
			$insys = selectArray("select a.sheet_no,a.doc_kind,a.apply_person,a.apply_depart,get_depart_name(a.apply_depart) 
		as apply_depart_name,a.creator,name(a.creator) as create_name,a.create_date,a.is_public,a.vip_code,a.form_key,c.form_key as c_form_key,a.pre_finish_date,
			a.apply_type,finish_date,b.flow_code,c.current_user,name(c.current_user) as current_name,quality,timeliness,
			communication,attitude,nvl((quality+timeliness+communication+attitude),0) as sum_score from 
			inf_need_main a,flow b,subflow c where a.form_key=b.form_key and	a.form_key=c.form_key and
			a.sheet_no='$sheet_no'");
		
		// print_r($insys);
		if($insys[0][DOC_KIND] == 'A'){
			include("./inc/formA.inc");
		}elseif($insys[0][DOC_KIND] == 'B'){
			include("./inc/formB.inc");
			echo "<table><tr><th style='width:15%'>预判原因</th><td colspan='3' style='width:75%'>";
			foreach($proper_array as $key => $value){
				$pro_check = "";
				if($insys_check[0][SYS_NEEDKIND] == $key){
					$pro_check = "checked";
				}
				echo "<input type='radio' name='need_kind' value='$key' $pro_check>$value &nbsp;&nbsp;";
			}
			echo "</td></tr></table>";
		}elseif($insys[0][DOC_KIND] == 'C'){
			include("./inc/formC.inc");
		}
		include("./inc/assign.inc");
		// include("./inc/nextstatus.inc");
		$sql_status = "select code_name,content from code where field_name='assign_status' and code_name in ('FH','X3','F9','BC','B1') order by code_name desc";
		$status_result = selectArray($sql_status);
		echo "<table>
		
		<tr id='next_user'><th width='15%'>签核动作</th><td colspan='3' width='75%'>";
		foreach($status_result as $value){
			echo "<input type='radio' name='assign_status' value='$value[CODE_NAME]' onChange='showNextuser(this.value)'>$value[CONTENT]&nbsp;&nbsp;";
		}
		echo "</td></tr>
		
		<tr><th>签核意见</th><td colspan='3'>
	<textarea name='assign_opinion' cols='70' rows='5'></textarea></td></tr>
	</table>";
	
		
		echo "<input type='hidden' name='sheet_no' id='sheet_no' value='$sheet_no'>";
		echo "<input type='hidden' name='form_key' id='form_key' value='{$insys[0][FORM_KEY]}'>";
		echo "<input type='hidden' name='c_form_key' id='c_form_key' value='{$insys[0][C_FORM_KEY]}'>";
		echo "<input type='hidden' name='flow_code' id='flow_code' value='{$insys[0][FLOW_CODE]}'>";
		echo "<input type='hidden' name='creator'  value='{$insys[0][CREATOR]}'>";
		echo "<input type='hidden' name='doc_kind'  value='{$insys[0][DOC_KIND]}'>";
		echo "<input type='hidden' name='next_code' id='next_code' />";
		echo "<input type='hidden' name='current_user'  value='{$insys[0][CURRENT_USER]}'>";
		echo "<input type='hidden' name='aaa[]'  />";
	
	echo "<input type='hidden' name='lead_code' id='lead_code' value='$lead_code'>";
	echo "<p><input type='submit' name='submit' value='确认提交' onclick='return checkForm()'/></p>";
?>
</form>

</div>
<script>

function checkForm(){
	/* var sheetLen = $("input[name='aaa[]']").size();
	for (var i = 0; i < sheetLen; i++){
		if($("select[name='next_user["+i+"]']").val() == null){
			// var sheet_no_s = $("#sheet_no"+i).val();
			var j = i+1;
			alert("第"+ j +"笔申请单请选择签核动作，并选择下位签核人");
			$("select[name='assign_status["+i+"]']").focus();
			return false;
		}
	} */
	
}
function showNextuser(argv1){
	var sheet_no = $("#sheet_no").val();
	var form_key = $("#form_key").val();
	var c_form_key = $("#c_form_key").val();
	var lead_code = $("#lead_code").val();
	var flow_code = $("#flow_code").val();
	$('.trouble').empty();
	$.ajax({
			type:'post',
			url:'nextuser.php?timeStamp='+new Date().getTime(),
			data:{
				assign_status:argv1,
				flow_code:flow_code,
				form_key:form_key,
				c_form_key:c_form_key,
				lead_code:lead_code,
				sheet_no:sheet_no
				
			},
			dataType:'json',
			success:function(data){console.log(data);
				$('#next_user').after(data['content']);
				$('#next_code').val(data['next_code']);
			}
		});
}
</script>
</body>
</html>