<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=gb2312" />
	<title>��Ѷ��ǩ��ϵͳ</title>
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
<form action="sign.php" method="post" enctype="multipart/form-data">
<?php 
	
	
	
		// $sql0 = "select count(a.sheet_no) from inf_need_main a,	flow b,subflow c  where a.form_key=b.form_key and	
		// a.form_key=c.form_key and  c.current_user='$ID' ";
		$sql0 = "select count(*) from (select a.sheet_no from inf_need_main a,	flow b,subflow c  where a.form_key=b.form_key and	a.form_key=c.form_key and  c.current_user='$ID' and b.system_id='inf'
		and b.form_id='inf' and b.flow_code<>'B'
		union select a.sheet_no from inf_need_main a, flow b, 
		subflow c, workflow_subflow w where a.form_key=w.parent_key and b.form_key=w.form_key and c.form_key=w.form_key and c.current_user='$ID' and b.system_id='inf'	and b.form_id='inf' and b.flow_code<>'B')";
		list($total_num) = fields($sql0);
		
		if ($total_num == 0){
			echo "<p style='color:red;font-size:28px'>Ŀǰû�����Ĵ�ǩ�ļ�<br /></p>";
			exit();
		}else{
			$page_size = 30;
			$url=$_SERVER['PHP_SELF'];
			if($total_num < $page_size){
				$total_page = 1;
			}else{
				$total_page = ceil($total_num/$page_size);
			}
			$page = $_GET["page"];//��ǰҳ��
			
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
					$navigation_list .= "<div><p>&nbsp;&nbsp;��һҳ&nbsp;&nbsp;&nbsp;&nbsp;��һҳ&nbsp;&nbsp;&nbsp;&nbsp;�� $total_num ��
					���ϣ�$page / $total_page ҳ &nbsp;&nbsp;&nbsp;&nbsp; <a href='$url?page=$pre_page'>��һҳ</a>
					&nbsp;&nbsp;&nbsp;&nbsp;<a href='$url?page=$end_page'>ĩҳ</a></p></div>";
				}elseif($page == $total_page){
					$navigation_list .= "<div><p>&nbsp;&nbsp;<a href='$url?page=$first_page'>��һҳ</a>
					&nbsp;&nbsp;&nbsp;&nbsp;<a href='$url?page=$last_page'>��һҳ</a>&nbsp;&nbsp;&nbsp;&nbsp;
					�� $total_num �����ϣ�$page / $total_page ҳ&nbsp;&nbsp;&nbsp;&nbsp;
					��һҳ&nbsp;&nbsp;&nbsp;&nbsp; ĩҳ</p></div>";
				}else{
					$navigation_list .= "<div><p>&nbsp;&nbsp;<a href='$url?page=$first_page'>��һҳ</a>
					&nbsp;&nbsp;&nbsp;&nbsp;<a href='$url?page=$last_page'>��һҳ</a>&nbsp;&nbsp;&nbsp;&nbsp;
					�� $total_num �����ϣ�$page / $total_page ҳ&nbsp;&nbsp;&nbsp;&nbsp; 
					<a href='$url?page=$pre_page'>��һҳ</a>
					&nbsp;&nbsp;&nbsp;&nbsp;<a href='$url?page=$end_page'>ĩҳ</a></p></div>";
				}
				
			}else{
				$navigation_list .= "<div><p>&nbsp;&nbsp;��һҳ&nbsp;&nbsp;&nbsp;&nbsp;��һҳ&nbsp;&nbsp;&nbsp;&nbsp;
				�� $total_num �����ϣ�$page / $total_page ҳ &nbsp;&nbsp;&nbsp;&nbsp; ��һҳ
					&nbsp;&nbsp;&nbsp;&nbsp;ĩҳ</p></div>";
			}
		
		
			$sql = "select * from (select r.*,rownum as rn from (
			select a.sheet_no,a.doc_kind,a.apply_depart,get_depart_name(a.apply_depart) as apply_depart_name
			,a.creator,name(a.creator) as create_name,a.create_date,a.is_public,a.vip_code,a.form_key,a.pre_finish_date,
			a.apply_type,finish_date,b.flow_code,c.current_user,name(c.current_user) as current_name from 
			inf_need_main a, flow b, subflow c where a.form_key=b.form_key and	a.form_key=c.form_key and 
			c.current_user='$ID'	and b.system_id='inf'	and b.form_id='inf' and b.flow_code<>'B'
			union 
			select a.sheet_no,a.doc_kind,a.apply_depart,get_depart_name(a.apply_depart) as apply_depart_name
			,a.creator,name(a.creator) as create_name,a.create_date,a.is_public,a.vip_code,a.form_key,a.pre_finish_date,
			a.apply_type,finish_date,b.flow_code,c.current_user,name(c.current_user) as current_name from 
			inf_need_main a,flow b,subflow c, workflow_subflow w where a.form_key=w.parent_key and b.form_key=w.form_key and c.form_key=w.form_key and c.current_user='$ID' and b.system_id='inf'	and b.form_id='inf' and b.flow_code<>'B'
			order by sheet_no 
			) r where rownum<=($page*$page_size)) 
			where rn>(($page-1)*$page_size) ";
			// echo $sql;
			$main_array = selectArray($sql);
			//��������array
			$propertyArr = propertyNeed();
			//����ԭ��array
			$applyArr = applyReason();
			//ϵͳ����
			// $sys_array=selectMysql("select ap_key,ap_name from public.info_running_sys where stop_use!='Y'  order by kind)";
			
			echo "<br />".$navigation_list;
			echo "<table><tr>
			<th><input type='checkbox' name='allcheckbox' id='allcheckbox' onclick='selectAll()' />ȫѡ/ȡ��</th>
			<th>���뵥��</th><th>���뵥λ</th><th>������</th><th>��������</th>
			<th>ϵͳ����</th><th>����ԭ��</th><th>�ͳ�����</th><th>Ԥ�����</th><th>ǩ����</th><th>������</th></tr>";
			$i = 0;
			
			foreach ($main_array as $key => $value){
				$apply_reason = '';
				$need_kind_name = '';
				$sys_name = '';
				if($value[DOC_KIND] == 'A'){
					$doc_kind = "��ҵƽ̨ʹ��Ȩ�ޱ�����뵥";
				}elseif($value[DOC_KIND] == 'B'){
					$doc_kind = "ϵͳ����������";
					list($ap_key,$need_kind) = fields("select sys_code,sys_needkind from inf_need_syscheck where sheet_no='$value[SHEET_NO]'");
					list($sys_name)=mysql_fetch_row(mysql_query("select ap_name from public.info_running_sys where stop_use!='Y'  and ap_key='$ap_key'"));
					$need_kind_name = $propertyArr[$need_kind];
					$apply_reason = $applyArr[$value[APPLY_TYPE]];
				}elseif($value[DOC_KIND] == 'C'){
					$doc_kind = "������Բߴ���";
				} 
				
				if($value[VIP_CODE] == 'A'){
					$vip_code_name = "��ͨ";
				}elseif($value[VIP_CODE] == 'B'){
					$vip_code_name = "����";
				}else{
					$vip_code_name = "";
				}
				
				$sql_sys="select ap_name from public.info_running_sys where stop_use!='Y'  and ap_key='$ap_key'";
				$news_tag = '';
				if(substr($depart_no,0,3) == '103' || substr($depart_no,0,3) == '109'){
					$diffArr = selectArray("select * from (select trunc(sysdate)-trunc(assign_date) as diff_day from assign where form_key='$value[FORM_KEY]' and flow_code='B' and assign_status='FH' order by assign_date desc) where rownum=1");
					if(count($diffArr) >0 && $diffArr[0][DIFF_DAY] <= 3){
						$news_tag = "<span style='color:red;font-weight:bold;font-style:italic;'>news</span>";
					}
				}
				
				echo "<tr><td><input type='checkbox' name='doc_key[$i]' class='aaa' value='$value[SHEET_NO]'></td>
				<td>$value[SHEET_NO] $news_tag</td>
				<td>$value[APPLY_DEPART_NAME]</td>
				<td>$value[CREATOR]-$value[CREATE_NAME]</td>
				<td>$doc_kind</td>
				<td>$sys_name</td>
				<td>$apply_reason</td>
				<td>$value[CREATE_DATE]</td>
				<td>$value[PRE_FINISH_DATE]</td>
				<td>$value[CURRENT_USER]-$value[CURRENT_NAME]</td>
				<td>$vip_code_name</td></tr>";
				echo "<input type='hidden' name='flow_code[$i]' value='$value[FLOW_CODE]'>";
				echo "<input type='hidden' name='doc_kind[$i]' value='$value[DOC_KIND]'>";
				$i ++;
			}
			echo "</table>";
			echo "<input type='hidden' name='total_num' value='$total_num'>";
			echo "<p><input type='submit' name='submit' value='��ʼ��ѯ' /></p>";
		}
	

?>
</form>
</div>
<div>
<?php 
	if(substr($depart_no,0,3) == '103' || substr($depart_no,0,3) == '109'){
		echo "<p style='color:red;font-size:16px;text-align:left;width:90%;margin:0px auto;'>ע��<br />
				1��ȷ�ϸ���Ѷ�������Ƿ�����Ƿ���Ը��ƣ��粻�ܸ��ƣ��뼰ʱ�ʼ�֪ͨ��Ƴ���ע��ԭ������Ƴ���ȷ�Ͻ����˻ػ��߼��֡�<br />
				2�����ܸ��ƣ�����ȷ�ϡ�Ԥ��������ڡ��Ƿ�������޷��ڡ�Ԥ��������ڡ�ǰ��ɣ����ṩ�Լ���Ԥ���ո���Ƴ�������Ƴ�Э�������<br />
				3��������Ҫ��Ƴ��޸ĵģ�����ڡ�new��������ʾ�ڼ���ɡ�<br />
				4����Ѷ��ʱЧ�Էǳ���Ҫ����������ӶԴ���
			</p>";
	}
?>
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