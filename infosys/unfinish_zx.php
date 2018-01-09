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
	<script type="text/javascript">
		$(function(){
			$("tr").hover(function(){
				$(this).css("background-color","#CAE8EA");
			},function(){
				$(this).css("background-color","#F0F8FF");
			});
		});
	</script>
	<style type="text/css">
	tr:nth-child(2n-1) {
		background-color: #CAE8EA;
		
	}
	</style>
</head>
<body>
<?php 
require("./inc/common.inc");
$sql = "select b.current_user,name(b.current_user) as current_name,count(b.current_user) as num from flow a,subflow b 
where a.form_key=b.form_key and a.system_id='inf' and a.flow_code in ('D','D0') group by b.current_user
order by 3 desc";
$sql_res = selectArray($sql);
?>

<div>

<table style="width:50%">
	<tr><td style="background-color:#A4D3EE;font-size:28px;text-align:center" colspan=2>资讯人员未完成笔数查询</td></tr>
	
		<?php
			foreach ($sql_res as $value){
				echo "<tr><td style='width:25%'><a href='un_finish_detail.php?current_user=$value[CURRENT_USER]'>$value[CURRENT_NAME]</a></td><td style='width:25%'>$value[NUM]</td></tr>";
			}
			
		?>
	
</table>

</div>

</body>
</html>