<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=gb2312" />
	<title>资讯单系统查询作业</title>
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
<form action="sign_write.php" method="post" enctype="multipart/form-data">
<?php 
	$doc_key = $_REQUEST['doc_key'];
	if(count($doc_key) == 0){
		echo "<p style='color:red;font-size:28px'>您没有选则文件，请选择后重新查询<br />
		<a href='javascript:history.go(-1)'>回上一页</a></p>";
	}
	//foreach($doc_key as $num => $value){
	for ($num = 0; $num < $total_num; $num++){
		if(empty($doc_key[$num])){
			continue;
		}
		
			$insys = selectArray("select a.sheet_no,a.doc_kind,a.apply_person,a.apply_depart,get_depart_name(a.apply_depart) 
		as apply_depart_name,a.creator,name(a.creator) as create_name,a.create_date,a.is_public,a.vip_code,a.form_key,c.form_key as c_form_key,a.pre_finish_date,
			a.apply_type,finish_date,b.flow_code,c.current_user,name(c.current_user) as current_name,quality,timeliness,
			communication,attitude,nvl((quality+timeliness+communication+attitude),0) as sum_score from 
			inf_need_main a,flow b,subflow c where a.form_key=b.form_key and	a.form_key=c.form_key and
			a.sheet_no='$doc_key[$num]'");
		
		// print_r($insys);
		if($insys[0][DOC_KIND] == 'A'){
			include("./inc/formA.inc");
		}elseif($insys[0][DOC_KIND] == 'B'){
			include("./inc/formB.inc");
		}elseif($insys[0][DOC_KIND] == 'C'){
			include("./inc/formC.inc");
		}
		include("./inc/assign.inc");
		
	}
	
?>
</form>

</div>
<script>

function checkForm(){
	var sheetLen = $("input[name='aaa[]']").size();
	/* for (var i = 0; i < sheetLen; i++){
		if($("select[name='next_user["+i+"]']").val() == null){
			// var sheet_no_s = $("#sheet_no"+i).val();
			var j = i+1;
			alert("第"+ j +"笔申请单请选择签核动作，并选择下位签核人");
			$("select[name='assign_status["+i+"]']").focus();
			return false;
		}
	} */
	
}
function showNextuser(argv1,argv2){
	var sheet_no = $("#sheet_no"+argv2).val();
	var form_key = $("#form_key"+argv2).val();
	var c_form_key = $("#c_form_key"+argv2).val();
	var lead_code = $("#lead_code").val();
	var flow_code = $("#flow_code"+argv2).val();
	$('#next_user'+argv2).empty();
	$.ajax({
			type:'post',
			url:'nextuser.php?timeStamp='+new Date().getTime(),
			data:{
				assign_status:argv1,
				flow_code:flow_code,
				form_key:form_key,
				c_form_key:c_form_key,
				lead_code:lead_code,
				sheet_no:sheet_no,
				argv2:argv2
			},
			dataType:'json',
			success:function(data){
				$('#next_user'+argv2).append(data['content']);
				$('#next_code'+argv2).val(data['next_code']);
			}
		});
}
</script>
</body>
</html>