<?php require_once(ABSPATH . "recurly.js/lib/recurly_signature.php"); ?>
<?php
/**
* Template Name: Recurly Subscription
* Description: Loads the Recurly subscription area into the website
*/ 
get_header();
?>

<link rel="stylesheet" href="/recurly.js/css/recurly-lovetea.css" type="text/css" />
<link rel="stylesheet" href="/recurly.js/css/recurly.css" type="text/css" />
<script src="/recurly.js/build/recurly.js"></script>

<script>
  Recurly.config({
    subdomain: 'lovetea',
    currency: 'GBP'
  });

  Recurly.buildSubscriptionForm({
    target: '#recurly-subscribe',
    // Signature must be generated server-side with a utility method provided
    // in client libraries.
    signature: '<?php echo function_exists(generateSignature) ? generateSignature($_GET['plan']) : '' ?>',
    successURL: '/tea-delivered-monthly/thank-you/',
    planCode: '<?php echo $_GET['plan'] ? $_GET['plan'] : "classic" ?>',
    distinguishContactFromBillingInfo: true,
    termsOfServiceURL: '/terms-and-conditions/',
    acceptedCards: ['mastercard', 'visa']
  });
</script>

    <section id="content" style="min-height: 1133px">
    <div class="container">
        <div class="two-third">
            <div id="recurly-subscribe">
            </div>
        </div>
    </div>
	<div class="one-third last"><img src="/wp-content/uploads/2012/06/package-bag.jpg" alt="" title="LoveTea Bag" widt$
          <hr class="">
          <div class="check dotted">
            <ul style="margin-top: 20px"><strong class="highlight">Every package includes</strong>:<p></p>
                <li>One type of tea per month</li>
                <li>Include only the tea types you prefer</li>
                <li>Tea information included ?^?^? telling you about the tea and tips for brewing</li>
                <li>Free UK delivery</li>
                <li>Cancel anytime</li>
            </ul>
          </div>
    </section>
<div class="clearfix">&nbsp;</div>
<?php get_footer(); ?>
