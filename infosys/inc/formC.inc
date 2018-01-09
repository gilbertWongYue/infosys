<?php  
$insys_plat = selectArray("select qa_degree,qa_content,qa_describe from inf_need_qa where sheet_no='{$insys[0][SHEET_NO]}'");

$ques_arr = queLevel();
if($insys[0][CREATOR] == $ID && $insys[0][FLOW_CODE] == 'A'){
?>

<table>
	<tr><td style="background-color:#A4D3EE;font-size:28px;text-align:center" colspan=4>资讯作业问题与对策处理申请书</td></tr>
	<tr>
		<th>严重程度</th>
		<td><select name="qadegree[<?php echo $num?>]">
		<?php 
			
			foreach($ques_arr as $key => $value){
				if($insys_plat[0][QA_DEGREE] == $key){
					$degree_select = "selected";
				}else{
					$degree_select = "";
				}
				echo "<option value='$key' $degree_select>$value</option>";
			}
		?>
		</td>
		<th>作成者</th>
		<td><input type="hidden" name="creator" value="<?php echo $ID?>"><?php echo $name?></td>
	</tr>
	<tr>
		<th>提案单位</th>
		<td><input type="hidden" name="depart_no" value="<?php echo $depart_no;?>"><?php echo $depart_name;?></td>
		<th>提案人</th>
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
	</tr>
	<tr>
		<th>问题内容</th>
		<td colspan=3>
			<textarea name="qacontent[<?php echo $num?>]"  required rows=5 cols=70><?php echo $insys_plat[0][QA_CONTENT]?></textarea>
		</td>
	</tr>
	<tr>
		<th>说明</th>
		<td colspan=3>
			<textarea name="qadescribe[<?php echo $num?>]" rows=5 cols=70><?php echo $insys_plat[0][QA_DESCRIBE]?></textarea>
		</td>
		
	</tr>
	<tr><th>附  件</th>
				<td colspan=5><input type='file' name='file_upload1[<?php echo $num?>][]'></td>
	</tr>
	</table>
<?php 
include("inc/file_show.inc");
}else{
	$apply_name = name($insys[0][APPLY_PERSON]).'-'.$insys[0][APPLY_PERSON];
	list($dianHua) = fields("select case when tel_no is null then tel_phone else tel_no||'-'||tel_ext end as tel_pho 
		from tel_ext where employee_no='{$insys[0][CREATOR]}'");
	echo "<table>
	<tr><td style='background-color:#A4D3EE;font-size:28px;text-align:center' colspan=4>资讯作业问题与对策处理申请书</td></tr>
	<tr>
		<th style='width:15%'>申请单号</th><td style='width:30%'>{$insys[0][SHEET_NO]}</td>
	</tr>
	<tr>
		<th style='width:15%'>严重程度</th><td style='width:30%'>{$ques_arr[$insys_plat[0][QA_DEGREE]]}</td>
		<th style='width:15%'>作成者</th><td style='width:30%'>{$insys[0][CREATOR]}-{$insys[0][CREATE_NAME]} <br />电话-分机(手机): $dianHua</td>
	<tr>
		<th style='width:15%'>提案单位</th><td>{$insys[0][APPLY_DEPART]}-{$insys[0][APPLY_DEPART_NAME]}</td>
		<th style='width:15%'>提案人</th><td>$apply_name</td>
	</tr>
	<tr>
		<th style='width:15%'>问题内容</th><td colspan=3>{$insys_plat[0][QA_CONTENT]}</td>
	</tr>
	<tr>
		<th style='width:15%'>说明</th><td colspan=3>{$insys_plat[0][QA_DESCRIBE]}</td>
	</tr>";
	echo "</table>";
	include("inc/file_show.inc");
	
	if($insys[0][FLOW_CODE] == 'D' || $insys[0][FLOW_CODE] == 'D0'){
		echo "<table style='margin-top:0px'><tr><th style='width:15%'>附件</th><td colspan='3' style='width:75%'><input type='file' name='file_upload_z[$num][]'></td></tr></table>";
	}
	if($insys[0][CREATOR] == $ID && ($insys[0][FLOW_CODE] == 'E' || $insys[0][FLOW_CODE] == 'E0')){
		echo "<table style='margin-top:0px'>
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
	}
}
?>