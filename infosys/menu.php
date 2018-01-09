<?php  
require_once('/php/public/id.inc');
require_once('/php/public/opendb.inc');
require("/php/public/power.inc");
	
	$ziXunKeZhang = array('45224','22305','00279');
	list($depart_no) = fields("select depart_no from employee where employee_no='$ID'");
	// $ziXunKeZhang = array('72548');
		if(in_array($ID,$ziXunKeZhang)){
			 header('Location: //w3.yungtay.com.cn/inforequire/menu17.html');
		}elseif(substr($depart_no,0,3) == '103' || substr($depart_no,0,3) == '109'){
			header('Location: //w3.yungtay.com.cn/inforequire/menu19.html');
		}else{
			 header('Location: //w3.yungtay.com.cn/inforequire/menu18.html');
		}
	
?>
