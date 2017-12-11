<?php



class Bunch_Functions {

	

	function __construct()

	{

		add_action( '_bunch_logo', array( $this, 'logo' ), 10 );

		

		$options = get_option(BUNCH_NAME.'_theme_options');

		

		if( bunch_set( $options, '_enable_wp_login' ) ) {

			

			$this->wp_login_logo = bunch_set( $options, '_wp_login_logo', get_stylesheet_directory_uri().'/images/mlogo.png' );

			$this->wp_login_bg = bunch_set( $options, '_wp_login_bg', get_stylesheet_directory_uri().'/images/mbg_01.png' );

			$this->wp_login_name = bunch_set( $options, '_wp_login_page_title', get_bloginfo( 'name' ) );

			

			add_action( 'login_enqueue_scripts', array( $this, 'bunch_login_logo' ) );

			add_filter( 'login_headerurl', array( $this,  'bunch_login_logo_url' ) );

			add_filter( 'login_headertitle', array( $this, 'bunch_login_logo_url_title' ) );

			add_action( 'login_enqueue_scripts', array( $this, 'bunch_login_stylesheet' ) );

		}

	}

	

	

	/**

	 * Update the post views on visiting the post detail page.

	*/

	function post_views( $update = false )

	{

		global $post;

		

		if( !isset( $post->ID ) ) return;

		

		$key = '_bunch_'.$post->post_type.'_views';

		

		$views = get_post_meta( $post->ID, $key, true );

		

		if( $update ) {

			$new_views = ( $views ) ? ((int)$views + 1) : 1;

			

			update_post_meta( $post->ID, $key, $new_views );

		} 

		else $new_views = (int)$views;

		

		return $this->format_num( $new_views );

	}

	

	function format_num( $number )

	{

		return number_format( (int) $number, 0 );

	}

	

	function maintainance()

	{

		$options = _WSH()->option();

		

		if( bunch_set( $options, 'maintainance_status' ) ) {

			$m_page = bunch_set( $options, 'maintainance_page' );

			

			if( $m_page && !is_user_logged_in() && !is_page( $m_page ) ) {

				wp_redirect( get_permalink( $m_page ) );

			}

		}

	}

	

	function logo()

	{

		include( _WSH()->includes('includes/modules/_bunch_logo.php') );

	}

	

	

	function bunch_login_logo() { ?>

		<style type="text/css">

			body.login div#login h1 a {

				background-image: url(<?php echo $this->wp_login_logo; ?>);

				padding-bottom: 30px;

			}

			body.login { background-image: url(<?php echo $this->wp_login_bg; ?>); }

		</style>

	<?php }

	

	function bunch_login_stylesheet() {

		wp_enqueue_style( 'custom-login', get_template_directory_uri() . '/css/style-login.css' );

		//wp_enqueue_script( 'custom-login', get_template_directory_uri() . '/style-login.js' );

	}

	

	function bunch_login_logo_url() {

    	return home_url();

	}

	

	function bunch_login_logo_url_title() {

		return $this->wp_login_name;

	}

}

