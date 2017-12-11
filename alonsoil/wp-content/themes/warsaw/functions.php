<?php
add_action('after_setup_theme', 'warsaw_bunch_theme_setup');
function warsaw_bunch_theme_setup()
{
	global $wp_version;
	if(!defined('WARSAW_VERSION')) define('WARSAW_VERSION', '1.0');
	if( !defined( 'WARSAW_ROOT' ) ) define('WARSAW_ROOT', get_template_directory().'/');
	if( !defined( 'WARSAW_URL' ) ) define('WARSAW_URL', get_template_directory_uri().'/');	
	include_once get_template_directory() . '/includes/loader.php';
	
	
	load_theme_textdomain('warsaw', get_template_directory() . '/languages');
	
	//ADD THUMBNAIL SUPPORT
	add_theme_support('post-thumbnails');
	add_theme_support('woocommerce');
	add_theme_support('menus'); //Add menu support
	add_theme_support('automatic-feed-links'); //Enables post and comment RSS feed links to head.
	add_theme_support('widgets'); //Add widgets and sidebar support
	add_theme_support( "title-tag" );
	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form', 'comment-form', 'comment-list', 'gallery', 'caption'
	) );
	/** Register wp_nav_menus */
	if(function_exists('register_nav_menu'))
	{
		register_nav_menus(
			array(
				/** Register Main Menu location header */
				'main_menu' => esc_html__('Main Menu', 'warsaw'),
				'main_menu_left' => esc_html__('Main Menu Left', 'warsaw'),
				'main_menu_right' => esc_html__('Main Menu Right', 'warsaw'),
			)
		);
	}
	if ( ! isset( $content_width ) ) $content_width = 960;
	add_image_size( 'warsaw_110x110', 110, 110, true ); // '110x110 Our Testimonials'
	add_image_size( 'warsaw_116x116', 116, 116, true ); // '116x116 Our Testimonials 2'
	add_image_size( 'warsaw_480x480', 480, 480, true ); // '480x480 Our Gallery'
	add_image_size( 'warsaw_370x230', 370, 230, true ); // '370x230 Our Gallery'
	add_image_size( 'warsaw_150x150', 150, 150, true ); // '150x150 Our Services'
	add_image_size( 'warsaw_370x340', 370, 340, true ); // '370x340 Gallery 3 Column '
	add_image_size( 'warsaw_575x301', 575, 301, true ); // '575x301 Gallery Masonry '
	add_image_size( 'warsaw_575x620', 575, 620, true ); // '575x620 Gallery Masonry '
	add_image_size( 'warsaw_278x300', 278, 300, true ); // '278x300 Gallery Masonry '
	add_image_size( 'warsaw_376x270', 376, 270, true ); // '376x270 Gallery Full Width '
	add_image_size( 'warsaw_1170x450', 1170, 450, true ); // '1170x450 Our Blog '
	add_image_size( 'warsaw_90x90', 90, 90, true ); // '90x90 Gallery Widget'
	add_image_size( 'warsaw_100x100', 100, 100, true ); // '100x100 Latest News'
	add_image_size( 'warsaw_370x310', 370, 310, true ); // '370x310 Our Shop'
	add_image_size( 'warsaw_266x280', 266, 280, true ); // '266x280 Our Shop 2'
	add_image_size( 'warsaw_600x450', 600, 450, true ); // '600x450 Our Shop Carousel'
}

function warsaw_bunch_widget_init()
{
	global $wp_registered_sidebars;
	$theme_options = _WSH()->option();
	if( class_exists( 'Bunch_About_us' ) )register_widget( 'Bunch_About_us' );
	if( class_exists( 'Bunch_Keep_in_Touch' ) )register_widget( 'Bunch_Keep_in_Touch' );
	if( class_exists( 'Bunch_gallery' ) )register_widget( 'Bunch_gallery' );
	if( class_exists( 'Bunch_Recent_Post' ) )register_widget( 'Bunch_Recent_Post' );
	
	
	register_sidebar(array(
	  'name' => esc_html__( 'Default Sidebar', 'warsaw' ),
	  'id' => 'default-sidebar',
	  'description' => esc_html__( 'Widgets in this area will be shown on the right-hand side.', 'warsaw' ),
	  'before_widget'=>'<div id="%1$s" class="widget sidebar-widget %2$s">',
	  'after_widget'=>'</div>',
	  'before_title' => '<div class="sidebar-title"><h3>',
	  'after_title' => '</h3></div>'
	));
	register_sidebar(array(
	  'name' => esc_html__( 'Footer Sidebar', 'warsaw' ),
	  'id' => 'footer-sidebar',
	  'description' => esc_html__( 'Widgets in this area will be shown in Footer Area.', 'warsaw' ),
	  'before_widget'=>'<div id="%1$s" class="col-lg-3 col-md-3 col-sm-6 col-xs-12 column footer-widget %2$s">',
	  'after_widget'=>'</div>',
	  'before_title' => '<h2>',
	  'after_title' => '</h2>'
	));
	
	register_sidebar(array(
	  'name' => esc_html__( 'Blog Listing', 'warsaw' ),
	  'id' => 'blog-sidebar',
	  'description' => esc_html__( 'Widgets in this area will be shown on the right-hand side.', 'warsaw' ),
	  'before_widget'=>'<div id="%1$s" class="widget sidebar-widget %2$s">',
	  'after_widget'=>'</div>',
	  'before_title' => '<div class="sidebar-title"><h3>',
	  'after_title' => '</h3></div>'
	));
	if( !is_object( _WSH() )  )  return;
	$sidebars = warsaw_set(warsaw_set( $theme_options, 'dynamic_sidebar' ) , 'dynamic_sidebar' ); 
	foreach( array_filter((array)$sidebars) as $sidebar)
	{
		if(warsaw_set($sidebar , 'topcopy')) continue ;
		
		$name = warsaw_set( $sidebar, 'sidebar_name' );
		
		if( ! $name ) continue;
		$slug = warsaw_bunch_slug( $name ) ;
		
		register_sidebar( array(
			'name' => $name,
			'id' =>  sanitize_title( $slug ) ,
			'before_widget' => '<div id="%1$s" class="side-bar widget sidebar_widget %2$s">',
			'after_widget' => "</div>",
			'before_title' => '<div class="sec-title"><h3 class="skew-lines">',
			'after_title' => '</h3></div>',
		) );		
	}
	
	update_option('wp_registered_sidebars' , $wp_registered_sidebars) ;
}
add_action( 'widgets_init', 'warsaw_bunch_widget_init' );
// Update items in cart via AJAX
function warsaw_load_head_scripts() {
	$options = _WSH()->option();
    if ( !is_admin() ) {
	$protocol = is_ssl() ? 'https://' : 'http://';
	$map_path = '?key='.warsaw_set($options, 'map_api_key');	
	wp_enqueue_script( 'map_api', ''.$protocol.'maps.google.com/maps/api/js'.$map_path, array(), false, false );
	wp_enqueue_script( 'html5shiv', get_template_directory_uri().'/js/html5shiv.js', array(), false, false );
	wp_script_add_data( 'html5shiv', 'conditional', 'lt IE 9' );
	wp_enqueue_script( 'respond-min', get_template_directory_uri().'/js/respond.min.js', array(), false, false );
	wp_script_add_data( 'respond-min', 'conditional', 'lt IE 9' );
	}
    }
    add_action( 'wp_enqueue_scripts', 'warsaw_load_head_scripts' );
//global variables
function warsaw_bunch_global_variable() {
    global $wp_query;
}

function warsaw_enqueue_scripts() {
	$theme_options = _WSH()->option();
	$maincolor = str_replace( '#', '' , warsaw_set( $theme_options, 'main_color_scheme', '#5ec79c' ) );
	$secondcolor = str_replace( '#', '' , warsaw_set( $theme_options, 'second_color_scheme', '#f5b062' ) );
    //styles
	wp_enqueue_style( 'bootstrap', get_template_directory_uri() . '/css/bootstrap.css' );
	wp_enqueue_style( 'gui', get_template_directory_uri() . '/css/gui.css' );
	wp_enqueue_style( 'fontawesom', get_template_directory_uri() . '/css/font-awesome.css' );
	wp_enqueue_style( 'flaticon', get_template_directory_uri() . '/css/flaticon.css' );
	wp_enqueue_style( 'animate', get_template_directory_uri() . '/css/animate.css' );
	wp_enqueue_style( 'owl-theme', get_template_directory_uri() . '/css/owl.css' );
	wp_enqueue_style( 'fancybox', get_template_directory_uri() . '/css/jquery.fancybox.css' );
	wp_enqueue_style( 'mCustomScrollbar', get_template_directory_uri() . '/css/jquery.mCustomScrollbar.min.css' );
	wp_enqueue_style( 'bootstrap-touchspin', get_template_directory_uri() . '/css/jquery.bootstrap-touchspin.css' );
	wp_enqueue_style( 'warsaw_main-style', get_stylesheet_uri() );
	wp_enqueue_style( 'warsaw_custom-style', get_template_directory_uri() . '/css/custom.css' );
	wp_enqueue_style( 'warsaw_responsive', get_template_directory_uri() . '/css/responsive.css' );
	if(class_exists('woocommerce')) wp_enqueue_style( 'warsaw_woocommerce', get_template_directory_uri() . '/css/woocommerce.css' );
	wp_enqueue_style( 'warsaw-main-color', get_template_directory_uri() . '/css/color.php?main_color='.$maincolor.
	'&second_color='.$secondcolor );
	wp_enqueue_style( 'warsaw-color-panel', get_template_directory_uri() . '/css/color-panel.css' );
	
	
    //scripts
	wp_enqueue_script( 'jquery-ui-core' );
	wp_enqueue_script( 'bootstrap', get_template_directory_uri().'/js/bootstrap.min.js', array(), false, true );
	wp_enqueue_script( 'mCustomScrollbar', get_template_directory_uri().'/js/jquery.mCustomScrollbar.concat.min.js', array(), false, true );
	wp_enqueue_script( 'fancybox', get_template_directory_uri().'/js/jquery.fancybox.pack.js', array(), false, true );
	wp_enqueue_script( 'fancybox-media', get_template_directory_uri().'/js/jquery.fancybox-media.js', array(), false, true );
	wp_enqueue_script( 'isotope', get_template_directory_uri().'/js/isotope.js', array(), false, true );
	wp_enqueue_script( 'countdown', get_template_directory_uri().'/js/jquery.countdown.js', array(), false, true );
	wp_enqueue_script( 'owl', get_template_directory_uri().'/js/owl.js', array(), false, true );
	wp_enqueue_script( 'mixitup', get_template_directory_uri().'/js/mixitup.js', array(), false, true );
	wp_enqueue_script( 'owl', get_template_directory_uri().'/js/owl.js', array(), false, true );
	wp_enqueue_script( 'wow', get_template_directory_uri().'/js/wow.js', array(), false, true );
	wp_enqueue_script( 'warsaw_main_script', get_template_directory_uri().'/js/script.js', array(), false, true );
	wp_enqueue_script( 'gmap', get_template_directory_uri().'/js/map-script.js', array(), false, true );
	if( is_singular() ) wp_enqueue_script('comment-reply');
	
}
add_action( 'wp_enqueue_scripts', 'warsaw_enqueue_scripts' );

/*-------------------------------------------------------------*/
function warsaw_theme_slug_fonts_url() {
    $fonts_url = '';
 
    /* Translators: If there are characters in your language that are not
    * supported by Lora, translate this to 'off'. Do not translate
    * into your own language.
    */
    $grand_hotel = _x( 'on', 'Grand+Hotel font: on or off', 'warsaw' );
	$roboto_slab = _x( 'on', 'Roboto+Slab font: on or off', 'warsaw' );
	$roboto_cond = _x( 'on', 'Roboto+Condensed font: on or off', 'warsaw' );
	
    /* Translators: If there are characters in your language that are not
    * supported by Open Sans, translate this to 'off'. Do not translate
    * into your own language.
    */
    $oswald = _x( 'on', 'Oswald font: on or off', 'warsaw' );
 
    if ( 'off' !== $grand_hotel || 'off' !== $roboto_slab || 'off' !== $roboto_cond || 'off' !== $oswald ) {
        $font_families = array();
 
        if ( 'off' !== $grand_hotel ) {
            $font_families[] = 'Grand Hotel';
        }
		
		if ( 'off' !== $roboto_slab ) {
            $font_families[] = 'Roboto Slab:100,300,400,700';
        }
		
		if ( 'off' !== $roboto_cond ) {
            $font_families[] = 'Roboto Condensed:300,400,700';
        }
		
		if ( 'off' !== $oswald ) {
            $font_families[] = 'Oswald:300,400,700';
        }
 
        $query_args = array(
            'family' => urlencode( implode( '|', $font_families ) ),
            'subset' => urlencode( 'latin,latin-ext' ),
        );
 
        $fonts_url = add_query_arg( $query_args, 'https://fonts.googleapis.com/css' );
    }
 
    return esc_url_raw( $fonts_url );
}
function warsaw_theme_slug_scripts_styles() {
    wp_enqueue_style( 'warsaw-theme-slug-fonts', warsaw_theme_slug_fonts_url(), array(), null );
}
add_action( 'wp_enqueue_scripts', 'warsaw_theme_slug_scripts_styles' );
/*---------------------------------------------------------------------*/
function warsaw_add_editor_styles() {
    add_editor_style( 'custom-editor-style.css' );
}
add_action( 'admin_init', 'warsaw_add_editor_styles' );
/**
 * WooCommerce Extra Feature
 * --------------------------
 *
 * Change number of related products on product page
 * Set your own value for 'posts_per_page'
 *
 */ 
function warsaw_woo_related_products_limit() {
  global $product;
	
	$args['posts_per_page'] = 6;
	return $args;
}
add_filter( 'woocommerce_output_related_products_args', 'warsaw_jk_related_products_args' );
  function warsaw_jk_related_products_args( $args ) {
	$args['posts_per_page'] = 4; // 4 related products
	$args['columns'] = 4; // arranged in 2 columns
	return $args;
}