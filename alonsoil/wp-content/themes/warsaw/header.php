<?php $options = _WSH()->option();
	warsaw_bunch_global_variable();
	$icon_href = (warsaw_set( $options, 'site_favicon' )) ? warsaw_set( $options, 'site_favicon' ) : get_template_directory_uri().'/images/favicon.png';
 ?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
	<head>
		 <!-- Basic -->
	    <meta charset="<?php bloginfo( 'charset' ); ?>">
	    <meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
		<!-- Favcon -->
		<?php if ( ! function_exists( 'has_site_icon' ) || ! has_site_icon() ):?>
			<link rel="shortcut icon" type="image/png" href="<?php echo esc_url($icon_href);?>">
		<?php endif;?>
		<?php wp_head(); ?>
	</head>
	<body <?php body_class(); ?>>
	
    <div class="page-wrapper<?php if(warsaw_set($options, 'boxed'))echo ' boxed';?>">
 	
    <!-- Preloader -->
    <div class="preloader"></div>
    
    <?php $header = warsaw_set($options, 'header_style');
	  $header = (warsaw_set($_GET, 'header_style')) ? warsaw_set($_GET, 'header_style') : $header;
	  switch($header){
	  	case "header_v2":
			get_template_part('includes/modules/header_v2');
			break;
		case "header_v3":
			get_template_part('includes/modules/header_v3');
			break;
		default:
			get_template_part('includes/modules/header_v1');
		} 	
	?>
    