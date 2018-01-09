<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=gb2312" />
	<title>资讯单系统查询系统</title>
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
	<form action="query.php" method="get">
		<h1>资讯申请单查询画面</h1>
		<table>
			<tr>
				<td>申请单号</td><td><input type='text' name='sheet_no' value='<?php echo $sheet_no?>'></td>
				<td>是否结案</td>
				<td>
				<select name='if_finish'>
				<option value=''></option>
				<option value='Y'>已结案</option>
				<option value='N'>未结案</option>
				</select>
				</td>
			</tr>
			<tr>
				<td>申请人</td><td><input type='text' name='creator' value='<?php echo $creator?>'><span style='font-size:10px;color:red'>请输入申请人工号</span></td>
				<td>申请单位</td><td>
				<select name='create_dep'><option value=''></option>
				<?php 
				$sql = "select distinct segment_no,segment_name from gl_segment a,employee b where a.segment_no=b.depart_no and a.if_stop is null";
				$segmentArr = selectArray($sql);
				foreach($segmentArr as  $value){
					$dep_select = '';
					if($create_dep == $value[SEGMENT_NO]){
						$dep_select = "selected";
					}
					echo "<option value='$value[SEGMENT_NO]' $dep_select>$value[SEGMENT_NO] - $value[SEGMENT_NAME]</option>";
				}
				?>
				</td></tr>
			<tr><td>申请日期</td><td colspan='3'>
			<input type='text' name='bdate' id='bdate' onclick="return showCalendar('bdate', 'y/mm/dd');" value='<?php echo $bdate?>'  />- <input type='text' name='edate' id='edate' onclick="return showCalendar('edate', 'y/mm/dd');" value='<?php echo $edate?>'  /> </td></tr>
		</table>
		<p><input type='submit' name='submit' value='查询' /></p>
	</form>
</div>

<div>
<form action="query_detail.php" method="post" enctype="multipart/form-data">

<?php 
	
	if(isset($_GET['submit'])){
		$content .= "";
		if(!empty($sheet_no)){
			$content .= "  and a.sheet_no like '$sheet_no%' ";
		}
		if(!empty($if_finish)){
			if($if_finish == 'Y'){
				$content .= " and a.finish_date is not null ";
			}elseif($if_finish == 'N'){
				$content .= " and a.finish_date is null ";
			}
		}
		if(!empty($creator)){
			$content .= " and a.creator like '$creator%' ";
		}
		if(!empty($create_dep)){
			$content .= " and a.apply_depart like '$create_dep%' ";
		}
		if(!empty($bdate) && !empty($edate)){
			$content .= " and to_char(a.create_date,'yyyy/mm/dd') between '$bdate' and '$edate' ";
		}
		$sql0 = "select count(*) from (select a.sheet_no from inf_need_main a,	flow b,subflow c  where a.form_key=b.form_key and	a.form_key=c.form_key  and b.system_id='inf' and b.form_id='inf' 
		$content 	)";
		list($total_num) = fields($sql0);
		
		if ($total_num == 0){
			echo "<p style='color:red;font-size:28px'>没有符合条件的资料<br /></p>";
			exit();
		}else{
			$page_size = 30;
			$url=$_SERVER['PHP_SELF'];
			if($total_num < $page_size){
				$total_page = 1;
			}else{
				$total_page = ceil($total_num/$page_size);
			}
			$page = $_GET["page"];//当前页数
			
			if($page == '' || $page <= 0){
				$page = 1;
			}elseif($page > $total_page){
				$page = $total_page;
			}
			$last_page = $page - 1;
			$pre_page = $page + 1;
			$first_page = 1;
			$end_page = $total_page;
			if($total_page > 1){
				if($page == 1){
					$navigation_list .= "<div><p>&nbsp;&nbsp;第一页&nbsp;&nbsp;&nbsp;&nbsp;上一页&nbsp;&nbsp;&nbsp;&nbsp;共 $total_num 笔
					资料，$page / $total_page 页 &nbsp;&nbsp;&nbsp;&nbsp; <a href='$url?page=$pre_page&submit=1'>下一页</a>
					&nbsp;&nbsp;&nbsp;&nbsp;<a href='$url?page=$end_page&submit=1'>末页</a></p></div>";
				}elseif($page == $total_page){
					$navigation_list .= "<div><p>&nbsp;&nbsp;<a href='$url?page=$first_page&submit=1'>第一页</a>
					&nbsp;&nbsp;&nbsp;&nbsp;<a href='$url?page=$last_page&submit=1'>上一页</a>&nbsp;&nbsp;&nbsp;&nbsp;
					共 $total_num 笔资料，$page / $total_page 页&nbsp;&nbsp;&nbsp;&nbsp;
					下一页&nbsp;&nbsp;&nbsp;&nbsp; 末页</p></div>";
				}else{
					$navigation_list .= "<div><p>&nbsp;&nbsp;<a href='$url?page=$first_page&submit=1'>第一页</a>
					&nbsp;&nbsp;&nbsp;&nbsp;<a href='$url?page=$last_page&submit=1'>上一页</a>&nbsp;&nbsp;&nbsp;&nbsp;
					共 $total_num 笔资料，$page / $total_page 页&nbsp;&nbsp;&nbsp;&nbsp; 
					<a href='$url?page=$pre_page&submit=1'>下一页</a>
					&nbsp;&nbsp;&nbsp;&nbsp;<a href='$url?page=$end_page&submit=1'>末页</a></p></div>";
				}
				
			}else{
				$navigation_list .= "<div><p>&nbsp;&nbsp;第一页&nbsp;&nbsp;&nbsp;&nbsp;上一页&nbsp;&nbsp;&nbsp;&nbsp;
				共 $total_num 笔资料，$page / $total_page 页 &nbsp;&nbsp;&nbsp;&nbsp; 下一页
					&nbsp;&nbsp;&nbsp;&nbsp;末页</p></div>";
			}
		
		
			$sql = "select * from (select r.*,rownum as rn from (
			select a.sheet_no,a.doc_kind,a.apply_depart,get_depart_name(a.apply_depart) as apply_depart_name
			,a.creator,name(a.creator) as create_name,a.create_date,a.is_public,a.vip_code,a.form_key,a.pre_finish_date,
			a.apply_type,finish_date,b.flow_code,c.current_user,name(c.current_user) as current_name from 
			inf_need_main a, flow b, subflow c where a.form_key=b.form_key and	a.form_key=c.form_key and b.system_id='inf'	and b.form_id='inf' $content
			
			order by sheet_no desc
			) r where rownum<=($page*$page_size)) 
			where rn>(($page-1)*$page_size) ";
			// echo $sql;
			$main_array = selectArray($sql);
			//需求性质array
			$propertyArr = propertyNeed();
			//申请原因array
			$applyArr = applyReason();
			//系统名称
			// $sys_array=selectMysql("select ap_key,ap_name from public.info_running_sys where stop_use!='Y'  order by kind)";
			
			echo "<br />".$navigation_list;
			echo "<table><tr>
			<th><input type='checkbox' name='allcheckbox' id='allcheckbox' onclick='selectAll()' />全选/取消</th>
			<th>申请单号</th><th>申请单位</th><th>申请人</th><th>申请种类</th>
			<th>系统名称</th><th>申请原因</th><th>送出日期</th><th>签核者</th><th>紧急度</th></tr>";
			$i = 0;
			foreach ($main_array as $key => $value){
				$apply_reason = '';
				$need_kind_name = '';
				$sys_name = '';
				if($value[DOC_KIND] == 'A'){
					$doc_kind = "作业平台使用权限变更申请单";
				}elseif($value[DOC_KIND] == 'B'){
					$doc_kind = "系统检讨申请书";
					list($ap_key,$need_kind) = fields("select sys_code,sys_needkind from inf_need_syscheck where sheet_no='$value[SHEET_NO]'");
					list($sys_name)=mysql_fetch_row(mysql_query("select ap_name from public.info_running_sys where stop_use!='Y'  and ap_key='$ap_key'"));
					$need_kind_name = $propertyArr[$need_kind];
					$apply_reason = $applyArr[$value[APPLY_TYPE]];
				}elseif($value[DOC_KIND] == 'C'){
					$doc_kind = "问题与对策处理单";
				}
				
				if($value[VIP_CODE] == 'A'){
					$vip_code_name = "普通";
				}elseif($value[VIP_CODE] == 'B'){
					$vip_code_name = "紧急";
				}else{
					$vip_code_name = "";
				}
				$current_content = '';
				if($value[FLOW_CODE] == 'Z0'){
					$current_content = "会签中".'<br>';
					$hqArr = selectArray("select current_user,name(current_user) as current_name from subflow where form_key in (select form_key from workflow_subflow where parent_key='$value[FORM_KEY]' and countersign_depart='hq')");
					foreach($hqArr as $value1){
						$current_content .= $value1[CURRENT_USER] .'-'.$value1[CURRENT_NAME].'<br>';
					}
					// $current_content = "会签中";
				}elseif($value[FLOW_CODE] == 'Z'){
					$current_content = "已结案";
				}else{
					$current_content = $value[CURRENT_USER] .'-'.$value[CURRENT_NAME];
				}
				
				$sql_sys="select ap_name from public.info_running_sys where stop_use!='Y'  and ap_key='$ap_key'";
				echo "<tr><td><input type='checkbox' name='doc_key[$i]' class='aaa' value='$value[SHEET_NO]'></td>
				<td>$value[SHEET_NO]</td>
				<td>$value[APPLY_DEPART_NAME]</td>
				<td>$value[CREATOR]-$value[CREATE_NAME]</td>
				<td>$doc_kind</td>
				<td>$sys_name</td>
				<td>$apply_reason</td>
				<td>$value[CREATE_DATE]</td>
				<td>$current_content</td>
				<td>$vip_code_name</td></tr>";
				echo "<input type='hidden' name='flow_code[$i]' value='$value[FLOW_CODE]'>";
				echo "<input type='hidden' name='doc_kind[$i]' value='$value[DOC_KIND]'>";
				$i ++;
			}
			echo "</table>";
			echo "<br />".$navigation_list."<br />";
			echo "<input type='hidden' name='total_num' value='$total_num'>";
			echo "<p><input type='submit' name='submit' value='开始查询' /></p>";
		}
	}

?>
</form>
</div>
<script>

function selectAll(){
	
	var obj = $("input[class='aaa']");console.log(obj.length);
	if ($("#allcheckbox").prop('checked') == true){
		for (var i = 0; i < obj.length; i++){
			obj[i].checked = true;
		}
	}else{
		for (var i = 0; i < obj.length; i++){
			obj[i].checked = false;
		}
	}
		
}
</script>
</body>
</html>