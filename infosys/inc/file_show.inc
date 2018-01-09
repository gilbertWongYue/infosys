<?
//========================附件显示
 $filename = date('YmdHis');
 @set_time_limit(600);
 $err='';
 $today=date('y/m/d H:i:s');
 $html=" ";
 $table_name='it.upload';
 $dbname='ibmora';
 $sys_id='inf';
 $form_id='inf';
 $form_id1='inf1';
 $keyword=$insys[0][SHEET_NO];
 
 // $file_show_names='附　　件'; 
 // $file_show_names1='资讯回复附件';
 


 //====show出附件
 $app_sql="select seq,upload_file_name,save_dir,save_file_name,creator,form_id
             from it.upload
            where sys_id='$sys_id' and form_id in ('$form_id','$form_id1') and keyword='$keyword' order by create_date";
 $file_res1 = selectArray($app_sql); 
 if(count($file_res1) > 0){
	 echo "<table style='margin-top:0px'>";
	 foreach($file_res1 as $key => $value){
		 $dir = $value[SAVE_DIR];
		 $save_file_name = $value[SAVE_FILE_NAME];
		 $file_name1=$ID.$save_file_name;
		 $upload_file_name = $value[UPLOAD_FILE_NAME];
		 system("ln -sf /upload/$dbname/$sys_id/$dir/$save_file_name /php/upload/file_link/$file_name1" );
		 if($value[FORM_ID] == 'inf1'){
			 $file_show_names = "资讯回复附件";
		 }else{
			 $file_show_names = "附　　件";
		 }
		 echo "<tr><th style='width:15%'>$file_show_names</th><td style='width:75%'><a href='http://oracle.yungtay.com.cn/upload/file_link/$file_name1' target='_new'>$upload_file_name</a></td></tr>";
	 }
	 echo "</table>";
 }
 
 /* $app_sql="select seq,upload_file_name,save_dir,save_file_name,creator
             from it.upload
            where sys_id='$sys_id' and form_id='$form_id1' and keyword='$keyword' order by seq";
 $file_res2 = selectArray($app_sql); 
 if(count($file_res2) > 0){
	 echo "<table>";
	 foreach($file_res2 as $key => $value){
		 $dir = $value[SAVE_DIR];
		 $save_file_name = $value[SAVE_FILE_NAME];
		 $file_name1=$ID.$save_file_name;
		 $upload_file_name = $value[UPLOAD_FILE_NAME];
		 system("ln -sf /upload/$dbname/$sys_id/$dir/$save_file_name /php/upload/file_link/$file_name1" );
		 echo "<tr><th style='width:20%'>$file_show_names1</th><td ><a href='http://oracle.yungtay.com.cn/upload/file_link/$file_name1' target='_new'>$upload_file_name</a></td></tr>";
	 }
	 echo "</table>";
 } */

 
?>
