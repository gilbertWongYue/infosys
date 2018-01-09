<?php  
$insys_check = selectArray("select sys_code,sys_needkind,sys_sample,sys_cycle,sys_rundate,sys_summary,sys_discuss,
sys_wisheffect,zxd from inf_need_syscheck where sheet_no='{$insys[0][SHEET_NO]}'");

	//需求性质
	$proper_array=propertyNeed();
	//是否公开
	if($insys['0'][IS_PUBLIC] == 'N'){
		$public_n = 'selected';
		$public_name = "否";
	}else{
		$public_y = 'selected';
		$public_name = "是";
	}
	//系统概要式样书
	if($insys_check[0][SYS_SAMPLE] == 'Y'){
		$sample_y = "selected";
		$sample_name = "是";
	}else{
		$sample_n = "selected";
		$sample_name = "否";
	}
	//紧急度
	if($insys[0][VIP_CODE] == 'B'){
		$vcode_b = "selected";
	}else{
		$vcode_a = "selected";
	}
	//处理周期
	if($insys_check[0][SYS_CYCLE] == 'D'){
		$cycle_d = "selected";
		$cycle_name = "日";
	}elseif($insys_check[0][SYS_CYCLE] == 'W'){
		$cycle_w = "selected";
		$cycle_name = "周";
	}elseif($insys_check[0][SYS_CYCLE] == 'M'){
		$cycle_m = "selected";
		$cycle_name = "月";
	}elseif($insys_check[0][SYS_CYCLE] == 'S'){
		$cycle_s = "selected";
		$cycle_name = "季";
	}elseif($insys_check[0][SYS_CYCLE] == 'Y'){
		$cycle_y = "selected";
		$cycle_name = "年";
	}else{
		$cycle_o = "selected";
		$cycle_name = "其他";
	}
	//申请原因
	if($insys[0][APPLY_TYPE] == 1){
		$type1_check = "checked";
		$apply_reason = "每月固定作业-后续拟系统化";
	}elseif($insys[0][APPLY_TYPE] == 2){
		$type2_check = "checked";
		$apply_reason = "配合委托部门-后续拟系统化";
	}elseif($insys[0][APPLY_TYPE] == 3){
		$type3_check = "checked";
		$apply_reason = "配合委托部门-无法系统化";
	}elseif($insys[0][APPLY_TYPE] == 4){
		$type4_check = "checked";
		$apply_reason = "资讯人员书写程式错误|原资讯单号".$insys_check[0][ZXD];
	}
				/*
				*申请人退回件维护画面
				*/	
if($insys[0][CREATOR] == $ID && $insys[0][FLOW_CODE] == 'A'){
?>
<table>
	<tr><td style="background-color:#A4D3EE;font-size:28px;text-align:center" colspan=6>系统检讨申请书</td></tr>
	<tr><td style="background-color:#A4D3EE;font-size:16px;text-align:center" colspan=6>请申请人务必正确选择对应的系统名称，如无法选取或不确定亦请申请人描述清楚
	相关作业路径 <br />（如：首页——>办公室日常申请——>资讯需求申请——>新申请单——>系统检讨申请画面）</td></tr>
	<tr>
		<th>系统名称</th>
		<td colspan=5><select  id="sys_code<?php echo $num?>" name="sys_code[<?php echo $num?>]" required>
				<option value="">请选择系统名称</option>
		<?php 
			$sql_sys="select ap_key,ap_name from public.info_running_sys where stop_use!='Y'  order by kind";
			$sys_array=selectMysql($sql_sys);
			foreach($sys_array as $key => $value){
				$sys_select = "";
				if($insys_check[0][SYS_CODE] == $value[0]){
					$sys_select = "selected";
				}
				echo "<option value='$value[0]' $sys_select>$value[1]</option>";
			}
		?>
		</select>
		<!--<input type="text" id="txtSearch" maxlength=50 oninput="check()"  onpropertychange="check1()"
		onblur="this.style.backgroundColor='#ffffff'" /> <span style="color:red">您可在此输入框输入关键字查询系统名称</span>-->
		</td>
	</tr>
	<tr>
		<th>申请人</th>
		<td>
			<select name="apply_man[<?php echo $num?>]" required>
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
		<th>需求性质</th>
		<td>
			<select name="need_kind[<?php echo $num?>]" id="need_kind<?php echo $num?>" required>
			<option value="">请选择</option>
			<?php 
			
			foreach($proper_array as $key => $value){
				$pro_select = "";
				if($insys_check[0][SYS_NEEDKIND] == $key){
					$pro_select = "selected";
				}
				echo "<option value='$key' $pro_select>$value</option>";
			}
			?>
			</select>
		</td>
		<th>可否公开</th>
		<td>
			<select name="is_public[<?php echo $num?>]">
				<option value="Y" <?php echo $public_y;?>>是</option>
				<option value="N" <?php echo $public_n;?>>否</option>
			</select>
		</td>
	</tr>
	<tr>
		<th>系统概要式样书</th>
		<td>
			<select name="syssample[<?php echo $num?>]">
				<option value="Y" <?php echo $sample_y;?>>需要</option>
				<option value="N" <?php echo $sample_n;?>>不需要</option>
			</select>
		</td>
		<th>紧急度</th>
		<td>
			<select name="vcode[<?php echo $num?>]">
				<option value="A" <?php echo $vcode_a;?>>普通</option>
				<option value="B" <?php echo $vcode_b;?>>紧急</option>
			</select>
		</td>
		<th>作成者</th>
		<td><input type="hidden" name="creator" value="<?php echo $ID?>"><?php echo $name?></td>
	</tr>
	<tr>
		<th>希望处理周期</th>
		<td>
			<select name="syscycle[<?php echo $num?>]">
				<option value="D" <?php echo $cycle_d;?>>日</option>
				<option value="W" <?php echo $cycle_w;?>>周</option>
				<option value="M" <?php echo $cycle_m;?>>月</option>
				<option value="S" <?php echo $cycle_s;?>>季</option>
				<option value="Y" <?php echo $cycle_y;?>>年</option>
				<option value="O" <?php echo $cycle_o;?>>其他</option>
				
			</select>
		</td>
		<th>希望实施日期</th>
		<td><input type="text" name="sysrundate[<?php echo $num?>]"  id="sysrundate<?php echo $num?>" value="<?php echo $sysrundate?>" onclick="return showCalendar('sysrundate<?php echo $num?>', 'y/mm/dd');" readonly></td>
		<th></th>
		<td></td>
	</tr>
	<tr>
		<th>申请原因</th>
		<?php 
			if($insys[0][APPLY_TYPE] == 1){
				$type1_check = "checked";
			}elseif($insys[0][APPLY_TYPE] == 2){
				$type2_check = "checked";
			}elseif($insys[0][APPLY_TYPE] == 3){
				$type3_check = "checked";
			}elseif($insys[0][APPLY_TYPE] == 4){
				$type4_check = "checked";
			}
		?>
		<td colspan=5 >
		<input type="radio" name="apply_type[<?php echo $num?>]" class="mwdsb" id="apply_type1<?php echo $num?>" value="1" <?php echo $type1_check;?>>每月固定作业-后续拟系统化
		<br />
		<input type="radio" name="apply_type[<?php echo $num?>]" class="mwdsb" id="apply_type2<?php echo $num?>" value="2"  <?php echo $type2_check;?>>配合委托部门-后续拟系统化
		<br />
		<input type="radio" name="apply_type[<?php echo $num?>]" class="mwdsb" id="apply_type3<?php echo $num?>" value="3" <?php echo $type3_check;?>>配合委托部门-无法系统化
		<br />
		<input type="radio" id="sbsbsb<?php echo $num?>"  class="mwdsb" name="apply_type[<?php echo $num?>]" value="4" <?php echo $type4_check;?>>
		资讯人员书写程式错误|
  原资讯单号<input type="text" id="ssss<?php echo $num?>" class="mwdsb" name="zxd[<?php echo $num?>]" value='<?php echo $insys_check[0][ZXD];?>' onkeyup="this.value=this.value.replace(/[^\d]/g,'')" maxlength='9' />
		<br />
		</td>
	</tr>
	<tr>
		<th>系统概要</th>
		<td colspan=5><textarea name="syssummary[<?php echo $num?>]"  rows="5" cols="100" maxlength="2000"   required><?php echo $insys_check[0][SYS_SUMMARY]?></textarea><span style="color:red">* </span></td>
	</tr>
	<tr>
		<th>检讨经过</th>
		<td colspan=5><textarea name="sysdiscuss[<?php echo $num?>]" rows="5" cols="100" maxlength="2000" ><?php echo $insys_check[0][SYS_DISCUSS]?></textarea></td>
	</tr>
	<tr>
		<th>预想效果</th>
		<td colspan=5><textarea name="syswisheffect[<?php echo $num?>]" rows="5" cols="100" maxlength="2000" ><?php echo $insys_check[0][SYS_WISHEFFECT]?></textarea></td>
	</tr>
	<tr><th>附  件</th>
				<td colspan=5><input type='file' name='file_upload1[<?php echo $num?>][]'></td>
	</tr>
	</table>
<?php 
include("inc/file_show.inc");
}else{
	list($sys_name,$user_no)=mysql_fetch_row(mysql_query("select ap_name,user_no from public.info_running_sys where stop_use!='Y'  and ap_key='{$insys_check[0][SYS_CODE]}'"));
	$user_no_name = name($user_no);
	list($ext_extno)=mysql_fetch_row(mysql_query("select ext_extno from tel_ext where ext_empl='$user_no'"));
	$apply_person = name($insys[0][APPLY_PERSON]).'-'.$insys[0][APPLY_PERSON];
	list($dianHua) = fields("select case when tel_no is null then tel_phone else tel_no||'-'||tel_ext end as tel_pho 
		from tel_ext where employee_no='{$insys[0][CREATOR]}'");
	echo "<table>
	<tr><td style='background-color:#A4D3EE;font-size:28px;text-align:center' colspan=6>系统检讨申请书</td></tr>
	<tr><th style='width:15%'>系统名称</th><td colspan='5' style='width:75%'>$sys_name<br />系统负责人：U$user_no $user_no_name -- $ext_extno 分机</td></tr>
	<tr>
		<th style='width:15%'>申请单号</th><td style='width:15%'>{$insys[0][SHEET_NO]}</td>
		<th style='width:15%'>申请人</th><td style='width:15%'>$apply_person</td>
		<th style='width:15%'>申请单位</th><td style='width:15%'>{$insys[0][APPLY_DEPART]}-{$insys[0][APPLY_DEPART_NAME]}</td></tr>
	<tr>
		<th>需求性质</th><td>{$proper_array[$insys_check[0][SYS_NEEDKIND]]}</td>
		<th>系统概要式样书</th><td>$sample_name</td>
		<th>是否公开</th><td>$public_name</td></tr>
	<tr>
		<th>希望处理周期</th><td>$cycle_name</td>
		<th>希望实施日期</th><td>{$insys_check[0][SYS_RUNDATE]}</td>
		<th>作成者</th><td>{$insys[0][CREATOR]} - {$insys[0][CREATE_NAME]} <br />电话-分机(手机): $dianHua</td></tr>
	<tr><th>申请原因</th><td colspan=5>$apply_reason</td></tr>
	<tr><th>系统概要</th><td colspan=5>{$insys_check[0][SYS_SUMMARY]}</td></tr>
	<tr><th>检讨经过</th><td colspan=5>{$insys_check[0][SYS_DISCUSS]}</td></tr>
	<tr><th>预想效果</th><td colspan=5>{$insys[0][SYS_WISHEFFECT]}</td></tr>
	";
	
	echo "</table>";
	include("inc/file_show.inc");
	if($insys[0][FLOW_CODE] == 'D'){
		echo "<table><tr><th style='width:15%'>处理类别</th><td colspan='3' style='width:75%'>";
				foreach($proper_array as $key => $value){
					$pro_check = "";
					if($insys_check[0][SYS_NEEDKIND] == $key){
						$pro_check = "checked";
					}
					echo "<input type='radio' name='need_kind[$num]' value='$key' $pro_check>$value &nbsp;&nbsp;";
				}
		echo "</td></tr>
		<tr><th>附件</th><td colspan='3'><input type='file' name='file_upload_z[$num][]'></td></tr></table>";
	}
	if($insys[0][FLOW_CODE] == 'D0'){
		echo "<table>
		<tr><th style='width:15%'>附件</th><td colspan='3' style='width:75%'><input type='file' name='file_upload_z[$num][]'></td></tr></table>";
	}
				/*
				*申请人满意度调查
				*/ 
	if($insys[0][CREATOR] == $ID && ($insys[0][FLOW_CODE] == 'E' || $insys[0][FLOW_CODE] == 'E0')){
		echo "<table>
		<tr><th colspan=2>满意度调查</th></tr>
		<tr><th>达成品质</th><td>
		<input type='radio' name='quality[$num]' value='3' checked>非常满意&nbsp;&nbsp;
		<input type='radio' name='quality[$num]' value='2' >满意&nbsp;&nbsp;
		<input type='radio' name='quality[$num]' value='1' >不满意&nbsp;&nbsp;
		<input type='radio' name='quality[$num]' value='0' >非常不满意&nbsp;&nbsp;
		</td></tr>
		<tr><th>达成时效</th><td>
		<input type='radio' name='timeliness[$num]' value='3' checked>非常满意&nbsp;&nbsp;
		<input type='radio' name='timeliness[$num]' value='2' >满意&nbsp;&nbsp;
		<input type='radio' name='timeliness[$num]' value='1' >不满意&nbsp;&nbsp;
		<input type='radio' name='timeliness[$num]' value='0' >非常不满意&nbsp;&nbsp;
		</td></tr>
		<tr><th>沟通能力</th><td>
		<input type='radio' name='communication[$num]' value='3' checked>非常满意&nbsp;&nbsp;
		<input type='radio' name='communication[$num]' value='2' >满意&nbsp;&nbsp;
		<input type='radio' name='communication[$num]' value='1' >不满意&nbsp;&nbsp;
		<input type='radio' name='communication[$num]' value='0' >非常不满意&nbsp;&nbsp;
		</td></tr>
		<tr><th>服务态度</th><td>
		<input type='radio' name='attitude[$num]' value='3' checked>非常满意&nbsp;&nbsp;
		<input type='radio' name='attitude[$num]' value='2' >满意&nbsp;&nbsp;
		<input type='radio' name='attitude[$num]' value='1' >不满意&nbsp;&nbsp;
		<input type='radio' name='attitude[$num]' value='0' >非常不满意&nbsp;&nbsp;
		</td></tr>
		<tr><td colspan=2>注：如果选择不满意或者非常不满意请说明原因</td></tr>
		</table>";
	}
	if(($insys[0][FLOW_CODE] >= 'F' ) && (in_array(substr($depart_no,0,3),$ziXun) && substr($lead_code,0,1)<='5')){
		$satisfyResearch = satisfyResearch();
		if($insys[0][SUM_SCORE] >= 10){
			$score_name = "非常满意";
		}elseif($insys[0][SUM_SCORE] >= 7 && $insys[0][SUM_SCORE] < 10){
			$score_name = "满意";
		}elseif($insys[0][SUM_SCORE] >= 4 && $insys[0][SUM_SCORE] < 7){
			$score_name = "不满意";
		}elseif($insys[0][SUM_SCORE] < 4){
			$score_name = "非常不满意";
		}
		$quality_name = $satisfyResearch[$insys[0][QUALITY]];
		$timeliness_name = $satisfyResearch[$insys[0][TIMELINESS]];
		$communication_name = $satisfyResearch[$insys[0][COMMUNICATION]];
		$attitude_name = $satisfyResearch[$insys[0][ATTITUDE]];
		echo "<table style='margin-top:0px'>
		<tr><th style='width:15%'>满意度调查</th><td style='width:75%'>$score_name</td></tr>
		<tr><th style='width:15%'>达成品质</th><td style='width:75%'>$quality_name</td></tr>
		<tr><th style='width:15%'>达成时效</th><td style='width:75%'>$timeliness_name</td></tr>
		<tr><th style='width:15%'>沟通能力</th><td style='width:75%'>$communication_name</td></tr>
		<tr><th style='width:15%'>服务态度</th><td style='width:75%'>$attitude_name</td></tr>
		</table>";
		if($insys[0][FLOW_CODE] == 'F'){
			echo "<table><tr><th style='width:15%'>资讯判定</th><td style='width:75%'>";
			foreach($proper_array as $key => $value){
				$pro_check = "";
				if($insys_check[0][SYS_NEEDKIND] == $key){
					$pro_check = "checked";
				}
				echo "<input type='radio' name='need_kind[$num]' value='$key' $pro_check>$value &nbsp;&nbsp;";
			}
			echo "</td></tr></table>";
		}
	}
}
?>