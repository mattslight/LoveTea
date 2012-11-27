<?php if ((strpos($_SERVER['REQUEST_URI'],'/test') !== FALSE) && !( !empty( $_SERVER['HTTP_X_FORWARDED_PROTO'] ) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https' )) {
   header("HTTP/1.1 301 Moved Permanently");
   header("Location: https://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
   exit();
}
echo "here";
echo (strpos($_SERVER['REQUEST_URI'],'/test') !== FALSE);
?>
