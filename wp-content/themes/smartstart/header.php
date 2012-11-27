<?php //include_once(SS_BASE_DIR . '../../custom/country_detect.php'); ?>
<?php if ((strpos($_SERVER['REQUEST_URI'],'/deals') !== FALSE) && !( !empty( $_SERVER['HTTP_X_FORWARDED_PROTO'] ) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https' )) {
   header("HTTP/1.1 301 Moved Permanently");
   header("Location: https://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
   exit();
}
?>
<!DOCTYPE html>

<!--[if IE 7]>                  <html class="ie7 no-js"  <?php language_attributes(); ?>     <![endif]-->
<!--[if lte IE 8]>              <html class="ie8 no-js"  <?php language_attributes(); ?>     <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!--> <html class="not-ie no-js" <?php language_attributes(); ?>>  <!--<![endif]-->
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<meta name="title" content="Awesome. LoveTea - gourmet tea delivered monthly" />
	<meta name="description" content="We showcase world teas - sign up to get a loose leaf tea sent to you each month by free delivery . You get stylish tasting and brewing notes in a convienient package which fits through your letterbox. Get a free sample at www.lovetea.co" />
    <meta property="og:title" content="Awesome. LoveTea - gourmet tea delivered monthly" />
	<meta property="og:description" content="We showcase world teas - sign up to get a loose leaf tea sent to you each month by free delivery . You get stylish tasting and brewing notes in a convienient package which fits through your letterbox. Get a free sample at www.lovetea.co" />
    <meta property="og:image" content="http://www.lovetea.co/wp-content/uploads/2012/04/cup-of-tea.jpg" />


	
	<title><?php wp_title('|', true, 'right'); ?></title>

	<?php if( of_get_option('ss_favicon') ): ?>
		<link rel="icon" type="image/png" href="<?php echo of_get_option('ss_favicon'); ?>">
	<?php endif; ?>

	<link rel="profile" href="http://gmpg.org/xfn/11" />
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
	<link rel="stylesheet" href="//fonts.googleapis.com/css?family=Open+Sans:400,600,300,800,700,400italic|PT+Serif:400,400italic">
	<link rel='stylesheet' href='/wp-content/custom/css/lovetea.css' type='text/css' media='all' />

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

<header id="header" class="container clearfix">

	<a href="<?php echo home_url('/'); ?>" id="logo" title="<?php echo esc_attr( get_bloginfo('name', 'display') ); ?>">
		<?php if( of_get_option('ss_logo') ): ?>
			<img src="<?php echo of_get_option('ss_logo'); ?>" alt="<?php bloginfo('name'); ?>">
		<?php else: ?>
			<h1><?php bloginfo('name'); ?></h1>
		<?php endif; ?>
	</a>

	<nav id="main-nav">
		
		<?php echo ss_framework_main_navigation(); ?>

	</nav><!-- end #main-nav -->
	
</header><!-- end #header -->
