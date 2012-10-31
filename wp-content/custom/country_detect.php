<?php 

global $quick_flag;
if(isset($quick_flag) && is_object($quick_flag)){
    if(($info = $quick_flag->get_info()) != false){
        $code = $info->code;
		if($code == "AU") {
			header( 'Location: http://au.lovetea.co/' );
		}
    }
}

?>