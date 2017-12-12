<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<?php wp_site_icon(); ?>
<?php wp_head(); ?>
</head>

<body <?php body_class(); ?> itemscope itemtype="http://schema.org/WebPage">
	<div class="ct-site">
		<?php get_template_part( 'template-parts/header/header' ); ?>
		<?php get_template_part( 'template-parts/header/hero' );?>
	  <?php get_template_part( 'template-parts/global/wrapper-before' ); ?>
