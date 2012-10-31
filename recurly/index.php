<?php
require_once('../lib/recurly.php');

// Required for the API
Recurly_Client::$apiKey = '34fb62fe52974145b62f9d58c82dba34';

// Optional for Recurly.js:
//Recurly_js::$privateKey = 'bd2fa6382b1243ab99da52830f396416';

$subscription = new Recurly_Subscription();
$subscription->plan_code = 'classic';
$subscription->currency = 'GBP';
$subscription->starts_at = '2012-10-01';

$account = new Recurly_Account();
$account->account_code = 'sarahz@slight.me';
$account->email = 'sarahz@slight.me';
$account->first_name = 'Sarah';
$account->last_name = 'Slightz';

$billing_info = new Recurly_BillingInfo();
$billing_info->number = '4111-1111-1111-1111';
$billing_info->month = 1;
$billing_info->year = 2014;

$account->billing_info = $billing_info;
$subscription->account = $account;

$subscription->create();

?>