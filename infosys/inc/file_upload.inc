<?
//========================附件上传
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
 $keyword=$sheet_no;
 
  
 

 for($i=0;$i<count($file_upload);$i++)
    {
      IF($file_upload[$i] != '')
      {	
        $m_file_name1=str_replace(' ','',str_replace(':','',str_replace('/','',$today)));
        $m_file_name=substr($m_file_name1,0,12).substr($m_file_name1,11,1);
        $save_dir=substr($m_file_name1,11,1);
        $s_file_name=strrchr($file_upload_name[$i],'.');
        
		list($seq) = fields("select nvl(max(seq),0)+1 from it.upload where sys_id='$sys_id' and form_id='$form_id' and keyword = '$keyword'");
       

        $sql="insert into ".$table_name." (sys_id,form_id,keyword,seq,upload_file_name,save_file_name,save_dir,creator,create_date) values ";
        $sql=$sql."('$sys_id','$form_id','$keyword',$seq,'$file_upload_name[$i]','$m_file_name$seq$s_file_name','$save_dir','$ID',to_date('".date('Y/m/d H:i:s')."','YYYY/MM/DD HH24:MI:SS'))";
        $stmt=$dbh->prepare("$sql");
        $stmt->execute()||die("INSERT $table_name 错误1");
        $system="cp $file_upload[$i] /upload/$dbname/$sys_id/$save_dir/$m_file_name$seq$s_file_name";
        @system("$system;");
		echo "<h1>上传附件成功</h1>";
      }
    }
	//申请人上传附件
	for($i=0;$i<count($file_upload1[$num]);$i++){
		$keyword1 = $sheet_no[$num];
    
      IF($file_upload1[$num][$i] != '')
      {	
        $m_file_name1=str_replace(' ','',str_replace(':','',str_replace('/','',$today)));
        $m_file_name=substr($m_file_name1,0,12).substr($m_file_name1,11,1);
        $save_dir=substr($m_file_name1,11,1);
        $s_file_name=strrchr($file_upload1_name[$num][$i],'.');
        
		list($seq) = fields("select nvl(max(seq),0)+1 from it.upload where sys_id='$sys_id' and form_id='$form_id' and keyword = '$keyword1'");
       

        $sql="insert into ".$table_name." (sys_id,form_id,keyword,seq,upload_file_name,save_file_name,save_dir,creator,create_date) values ";
        $sql=$sql."('$sys_id','$form_id','$keyword1',$seq,'{$file_upload1_name[$num][$i]}','$m_file_name$seq$s_file_name','$save_dir','$ID',to_date('".date('Y/m/d H:i:s')."','YYYY/MM/DD HH24:MI:SS'))";
        $stmt=$dbh->prepare("$sql");
        $stmt->execute()||die("INSERT $table_name 错误1");
        $system="cp {$file_upload1[$num][$i]} /upload/$dbname/$sys_id/$save_dir/$m_file_name$seq$s_file_name";
        @system("$system;");
		echo "<h1>上传附件成功</h1>";
      }
    }
	// print_r($_FILES['file_upload_z']['name']);exit;
	//资讯回复附件
	for($j = 0; $j <= count($file_upload_z[$num]); $j++){ 
		$keyword1 = $sheet_no[$num];
		IF($file_upload_z[$num][$j] != '')
      {	
        $m_file_name1=str_replace(' ','',str_replace(':','',str_replace('/','',$today)));
        $m_file_name=substr($m_file_name1,0,12).substr($m_file_name1,11,1);
        $save_dir=substr($m_file_name1,11,1);
        $s_file_name=strrchr($file_upload_z_name[$num][$j],'.');
        
		list($seq) = fields("select nvl(max(seq),0)+1 from it.upload where sys_id='$sys_id' and form_id='$form_id1' and keyword = '$keyword1'");
       

        $sql="insert into ".$table_name." (sys_id,form_id,keyword,seq,upload_file_name,save_file_name,save_dir,creator,create_date) values ";
        $sql=$sql."('$sys_id','$form_id1','$keyword1',$seq,'{$file_upload_z_name[$num][$j]}','$m_file_name$seq$s_file_name','$save_dir','$ID',sysdate)";
        $stmt=$dbh->prepare("$sql");
        $stmt->execute()||die("INSERT $table_name 错误1");
        $system="cp {$file_upload_z[$num][$j]} /upload/$dbname/$sys_id/$save_dir/$m_file_name$seq$s_file_name";
        @system("$system;");
		echo "<h1>上传附件成功</h1>";
      }
	}
?>
