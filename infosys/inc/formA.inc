<?php
$insys_plat = selectArray("select ch_platform,ch_account,ch_power,ch_rundate,ch_item,ch_reason,ch_mail
 from inf_need_plat where sheet_no='{$insys[0][SHEET_NO]}'");
 
 //申请人
 $apply_man_arr = explode(',',$insys[0][APPLY_PERSON]);
 $apply_sting = '';
 foreach($apply_man_arr as $apply_value){
	 $apply_string .= 'U'.$apply_value.'-'.name($apply_value).'<br>';
 }
 

	if($insys['0'][IS_PUBLIC] == 'N'){
		$public_n = 'selected';
		$public_name = "否";
	}else{
		$public_y = 'selected';
		$public_name = "是";
	}
	//账号增减
	if($insys_plat[0][CH_ACCOUNT] == 'A'){
		$account_a = 'selected';
		$account_name = '新增';
	}elseif($insys_plat[0][CH_ACCOUNT] == 'D'){
		$account_d = 'selected';
		$account_name = '取消';
	}
	//权限
	$ch_power = explode(',',$insys_plat[0][CH_POWER]);
	$power_name = '';
	$f_power = '';
	$c_power = '';
	$u_power = '';
	$d_power = '';
	for ($i = 0; $i < count($ch_power); $i++ ){
		
		
		if($ch_power[$i] == 'F'){
			$f_power = "checked";
			$power_name .= "  查询  ";
		}elseif($ch_power[$i] == 'C'){
			$c_power = "checked";
			$power_name .= "  建档  ";
		}elseif($ch_power[$i] == 'U'){
			$u_power = "checked";
			$power_name .= "  更新  ";
		}elseif($ch_power[$i] == 'D'){
			$d_power = "checked";
			$power_name .= "  删除  ";
		}
	}
	//
	$ch_platform = explode(',',$insys_plat[0][CH_PLATFORM]);
	$i_plat = '';
	$w_plat = '';
	$e_plat = '';
	$v_plat = '';
	$n_plat = '';
	$p_plat = '';
	$y_plat = '';
	$plat_name = '';
	for($i = 0; $i <count($ch_platform); $i++){
		
		if($ch_platform[$i] == 'I'){
			$i_plat = "checked";
			$plat_name .= "外部网站浏览 <br />";
		}elseif($ch_platform[$i] == 'W'){
			$w_plat = "checked";
			$plat_name .= "内部首页/oracle登录帐号 <br />";
		}elseif($ch_platform[$i] == 'E'){
			$e_plat = "checked";
			$plat_name .= "e-mail <br />";
		}elseif($ch_platform[$i] == 'V'){
			$v_plat = "checked";
			$plat_name .= "深信服VPN帐号 <br />";
		}elseif($ch_platform[$i] == 'N'){
			$n_plat = "checked";
			$plat_name .= "公司网络硬盘 <br />";
		}elseif($ch_platform[$i] == 'P'){
			$p_plat = "checked";
			$plat_name .= "PDA/EPC/平板电脑作业 <br />";
		}elseif($ch_platform[$i] == 'Y'){
			$y_plat = "checked";
			$plat_name .= "移动设备VPN账号 <br />";
		}
	}
				/*
				*申请人退回件维护画面
				*/	
if($insys[0][CREATOR] == $ID && $insys[0][FLOW_CODE] == 'A'){
?>
	<table>
	<tr><td style="background-color:#A4D3EE;font-size:28px;text-align:center" colspan=6>作业平台权限变更申请单</td></tr>
	
	<tr>
		<th>申请单位</th>
		<td><input type="hidden" name="depart_no" value="<?php echo $depart_no;?>"><?php echo $depart_name;?></td>
		<th>作成者</th>
		<td><input type="hidden" name="creator" value="<?php echo $ID?>"><?php echo $name?></td>
		<th>可否公开</th>
		<td>
			<select name="is_public[<?echo $num?>]">
				<option value="Y" <?php echo $public_y;?>>是</option>
				<option value="N" <?php echo $public_n;?>>否</option>
			</select>
		</td>
	</tr>
	<tr>
		<th>请选择需申请权限人员</th>
		<td>
			<select name="apply_man[<?php echo $num?>][]" multiple required>
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
		<th>生效日期</th>
		<td><input type="text" name="chrundate[<?php echo $num?>]" id="chrundate<?php echo $num?>" onclick="return showCalendar('chrundate<?php echo $num?>', 'y/mm/dd');"  value="<?php echo $insys_plat[0][CH_RUNDATE];?>" readonly ></td>
		<th>账号</th>
		<td>
			<select name="chaccount[<?php echo $num?>]">
				<option value="A" <?php echo $account_a;?>>新增</option>
				<option value="D" <?php echo $account_d;?>>取消</option>
			</select>
		</td>
	</tr>
	<tr>
		<th>权限</th>
		<td colspan=5>
		<input type="checkbox" name="chpower[<?php echo $num?>][]" value="F" <?php echo $f_power;?> />查询
		<input type="checkbox" name="chpower[<?php echo $num?>][]" value="C" <?php echo $c_power;?> />建档
		<input type="checkbox" name="chpower[<?php echo $num?>][]" value="U" <?php echo $u_power;?> />更新
		<input type="checkbox" name="chpower[<?php echo $num?>][]" value="D" <?php echo $d_power;?> />删除
		</td>
		
	</tr>
	<tr>
		<th rowspan=5>填写说明</th>
		<td>外部网站浏览</td>
		<td colspan=4>仅限九亭厂、天津厂、北京营业办公点、成都营业办公点、
	   浦东营业办公点办公人员申请,
	   经申请单位协理以上主管审核（资讯代为转会）后予以开通;</td>
	</tr>
	<tr>
		<td>E-mail</td>
		<td colspan=4>请于“E-mail帐号选项”栏中填写帐号名称，默认'U'+员工编号;</td>
	</tr>
	<tr>
		<td>深信服VPN帐号</td>
		<td colspan=4>限外勤及未经网络改造之外点办公人员进行申请,<br>
	    申请时须提供所使用电脑（固定资产编号、电脑型号、产品序列号等)信息;</td>
	</tr>
	<tr>
		<td>PDA/EPC/平板电脑作业</td>
		<td colspan=4>请提供如下信息:<br>
	1、“使用者/帐号”、“机身ID号”，该信息可在PDA/EPC申请管理系统中申请并获取;<br>
	2、服务单位请提供“职制编码”;<br>
	3、网点设备请提供设备编号/帐号，例如:P0541012 。</td>
	</tr>
	<tr>
		<td>移动设备VPN账号</td>
		<td colspan=4>1、适用于手机及平板电脑，系统为IOS/塞班/安卓(浏览器需为OPERA)等;<br>
	2、请提供移动设备的品牌,操作系统、序列号、IMEI、WLAN MAC及蓝牙地址等信息。</td>
	</tr>
	<tr>
		<th>作业平台</th>
		<td colspan=5 >
		  <input type="checkbox" name="chplatform[<?php echo $num?>][]" value="I" <?php echo $i_plat;?>>外部网站浏览
		  <input type="checkbox" name="chplatform[<?php echo $num?>][]" value="W" <?php echo $w_plat;?>>内部首页/oracle登录帐号
		  <input type="checkbox" name="chplatform[<?php echo $num?>][]" value="E" <?php echo $e_plat;?>>e-mail
		  <input type="checkbox" name="chplatform[<?php echo $num?>][]" value="V" <?php echo $v_plat;?>>深信服VPN帐号
		  <input type="checkbox" name="chplatform[<?php echo $num?>][]" value="N" <?php echo $n_plat;?>>公司网络硬盘
		  <input type="checkbox" name="chplatform[<?php echo $num?>][]" value="P" <?php echo $p_plat;?>>PDA/EPC/平板电脑作业
		  <input type="checkbox" name="chplatform[<?php echo $num?>][]" value="Y" <?php echo $y_plat;?>>移动设备VPN账号
		</td>
	</tr>
	<tr>
		<th>作业项目</th>
		<td colspan=5><textarea name="chitem[<?php echo $num?>]"  rows="5" cols="100" maxlength="2000" required>
		<?php echo $insys_plat[0][CH_ITEM]?></textarea><span style="color:red">* </span></td>
	</tr>
	<tr>
		<th>申请或变更原因</th>
		<td colspan=5><textarea name="chreason[<?php echo $num?>]" rows="5" cols="100" maxlength="2000"><?php echo $insys_plat[0][CH_REASON];?></textarea></td>
	</tr>
	<tr>
		<th>E-mail账号(选填项)</th>
		<td colspan=5><input type="text" name="chmail[<?php echo $num?>]" size="10" maxlength="8" value="<?php echo $insys_plat[0][CH_MAIL]?>"/>如：U12345</td>
	</tr>
	<tr><th>附  件</th>
				<td colspan=5><input type='file' name='file_upload1[<?php echo $num?>][]'></td>
	</tr>
	</table>
<?php 
include("inc/file_show.inc");
}

				/*
				*其他人签核画面
				*/ 
else{
list($dianHua) = fields("select case when tel_no is null then tel_phone else tel_no||'-'||tel_ext end as tel_pho 
		from tel_ext where employee_no='{$insys[0][CREATOR]}'");
echo "<table>
	<tr><td style='background-color:#A4D3EE;font-size:28px;text-align:center' colspan=6>作业平台权限变更申请单</td></tr>
	<tr>
		<th style='width:15%'>申请单号</th><td style='width:15%'>{$insys[0][SHEET_NO]}</td>
		<th style='width:15%'>申请单位</th><td style='width:15%'>{$insys[0][APPLY_DEPART]}-{$insys[0][APPLY_DEPART_NAME]}</td>
		<th style='width:15%'>申请日期</th><td style='width:15%'>{$insys[0][CREATE_DATE]}</td></tr>
	<tr>
		<th>是否公开</th><td>$public_name</td>
		<th>作成者</th><td>{$insys[0][CREATOR]}-{$insys[0][CREATE_NAME]} <br />电话-分机(手机): $dianHua</td>
		<th>申请人</th><td>$apply_string</td></tr>
	<tr>
		<th>账号</th><td>$account_name</td>
		<th>权限</th><td>$power_name</td>
		<th>生效日期</th><td>{$insys_plat[0][CH_RUNDATE]}</td></tr>
	<tr><th>作业平台</th><td colspan=5>$plat_name</td></tr>
	<tr><th>作业项目</th><td colspan=5>{$insys_plat[0][CH_ITEM]}</td></tr>
	<tr><th>申请或变更原因</th><td  colspan=5>{$insys_plat[0][CH_REASON]}</td></tr>
	<tr><th>E-mail账号</th><td colspan=5>{$insys_plat[0][CH_MAIL]}</td></tr>
	";
	
	echo "</table>";
	include("inc/file_show.inc");
	
	if($insys[0][FLOW_CODE] == 'D' || $insys[0][FLOW_CODE] == 'D0'){
		echo "<table style='margin-top:0px'><tr><th style='width:15%'>附件</th><td colspan='3' style='width:75%'><input type='file' name='file_upload_z[$num][]'></td></tr></table>";
	}
				/*
				*申请人满意度调查
				*/ 
	
}
?>