<?php
require_once('/var/sites/l/lovetea.co/public_html/recurly.js/lib/recurly.php');

function generateSignature($code_plan = "classic") {

	// Required for the API
	$apiKey = '34fb62fe52974145b62f9d58c82dba34';

	// Optional for Recurly.js:
	Recurly_js::$privateKey = 'bd2fa6382b1243ab99da52830f396416';

	$signature = Recurly_js::sign(
  	array('subscription' => array('plan_code' => $code_plan))
	);

  return $signature;
}

?>
