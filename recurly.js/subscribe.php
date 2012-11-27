<?php require_once("./lib/recurly_signature.php"); ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>RecurlyJS Subscribe Example</title>
<link rel='stylesheet' id='ss-theme-styles-css'  href='https://www.lovetea.co/wp-content/themes/smartstart/style.css?ver=3.4.2' type='text/css' media='all' />
<link rel="stylesheet" href="css/recurly-lovetea.css" type="text/css" />
<link rel="stylesheet" href="css/recurly.css" type="text/css" />
<script src="./lib/jquery-1.7.1.js"></script>
<script src="./build/recurly.js"></script>

<script>
  Recurly.config({
    subdomain: 'lovetea',
    currency: 'GBP'
  });

  Recurly.buildSubscriptionForm({
    target: '#recurly-subscribe',
    // Signature must be generated server-side with a utility method provided
    // in client libraries.
    signature: '<?php echo generateSignature($_GET['plan']) ?>',
    successURL: '/tea-delivered-monthly/thank-you/',
    planCode: 'classic',
    distinguishContactFromBillingInfo: true,
    termsOfServiceURL: '/terms-and-conditions/',
    acceptedCards: ['mastercard', 'visa']
  });
</script>
</head>
  <body>
    <section id="content">
    <div class="container">
 	<div class="two-third">
	    <div id="recurly-subscribe">
  	    </div>
	</div>
    </div>
	<div class="one-third last"><img src="/wp-content/uploads/2012/06/package-bag.jpg" alt="" title="LoveTea Bag" width="472" height="446" class="alignright size-full wp-image-712"><p></p>
	  <hr class="">
	  <div class="check dotted">
	    <ul style="margin-top: 20px"><strong class="highlight">Every package includes</strong>:<p></p>
		<li>One type of tea per month</li>
		<li>Include only the tea types you prefer</li>
		<li>Tea information included – telling you about the tea and tips for brewing</li>
		<li>Free UK delivery</li>
		<li>Cancel anytime</li>
	    </ul>
	  </div>
    </section>
  </body>
</html>
