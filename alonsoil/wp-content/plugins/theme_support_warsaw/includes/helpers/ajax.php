<?php
class Bunch_Ajax
{
	
	function __construct()
	{
		add_action( 'wp_ajax__bunch_ajax_callback', array( $this, 'ajax_handler' ) );
		add_action( 'wp_ajax_nopriv__bunch_ajax_callback', array( $this, 'ajax_handler' ) );
	}
	
	function ajax_handler()
	{
		$method = bunch_set( $_REQUEST, 'subaction' );
		if( method_exists( $this, $method ) ) $this->$method();
		
		exit;
	}
	
	function tweets()
	{
		if( !class_exists( 'Codebird' ) ) include_once( 'codebird.php' );
		$cb = new Codebird;
		$method = bunch_set( $_POST, 'method' );
		
		$theme_options = _WSH()->option();
		//printr($theme_options);
		$api = bunch_set($theme_options, 'twitter_api');
		$api_secret = bunch_set($theme_options, 'twitter_api_secret');
		$token = bunch_set($theme_options, 'twitter_token');
		$token_secret = bunch_set($theme_options, 'twitter_token_Secret');
		if( !$api && $api_secret ) 
		{ 
			_e('Please provide tiwtter api information to fetch feed', BUNCH_NAME);exit;
		}
		$cb->setConsumerKey($api, $api_secret);
		$cb->setToken($token, $token_secret);
		
		$reply = (array) $cb->statuses_userTimeline(array('count'=>bunch_set( $_POST, 'number' ), 'screen_name'=>bunch_set($_POST, 'screen_name')));
		if( isset( $reply['httpstatus'] ) ) unset( $reply['httpstatus'] );
		foreach( $reply as $k => $v ){
			
			//if( $k == 'httpstatus' ) continue;
			$time = human_time_diff( strtotime( bunch_set( $v, 'created_at') ), current_time('timestamp') ) . __(' ago', BUNCH_NAME);
			$text = preg_replace('@(https?://([-\w\.]+[-\w])+(:\d+)?(/([\w/_\.#-]*(\?\S+)?[^\.\s])?)?)@', '<a href="$1" target="_blank">$1</a>', bunch_set( $v, 'text'));
			if($_POST['template'] === 'lead' )
			{
				echo '<i class="fa fa-twitter"></i>'.$text.' <a href="#"> '.$time.'</a>' ;
			}
			else {
				echo 
				'<li><span></span><p>'.$text.' <a href="#">'.__(' about ', BUNCH_NAME).$time.'</a></p></li>';
			}
		}
	}
	
	function contact_form_submit()
	{
		if( !count( $_POST ) ) return;
	
		_load_plugins_class( 'validation', 'helpers', true );
		$t = $GLOBALS['_bunch_base'];//printr($t);
		$settings = $t->option();
	
		/** set validation rules for contact form */
		$t->validation->set_rules('contact_subject','<strong>'.__('Subject', BUNCH_NAME).'</strong>', 'min_length[5]');
		
		$t->validation->set_rules('contact_name','<strong>'.__('Name', BUNCH_NAME).'</strong>', 'required|min_length[4]|max_lenth[30]');
		
		$t->validation->set_rules('contact_email','<strong>'.__('Email', BUNCH_NAME).'</strong>', 'required|valid_email');
		
		$t->validation->set_rules('contact_message','<strong>'.__('Message', BUNCH_NAME).'</strong>', 'required|min_length[5]');
		if( bunch_set($settings, 'contact_captcha_status'))
		{
			include_once( get_template_directory().'/includes/thirdparty/recaptchalib.php');
			$privatekey = bunch_set($settings, 'recaptcha_private');
			
			$resp = recaptcha_check_answer ($privatekey,
                                 $_SERVER["REMOTE_ADDR"],
                                 $_POST["recaptcha_challenge_field"],
                                 $_POST["recaptcha_response_field"]);
			
			if( !$resp->is_valid )
			{
					$t->validation->_error_array['captcha'] = __('Invalid captcha entered, please try again.', BUNCH_NAME);
			}
	
		}
		$messages = '';
		if($t->validation->run() !== FALSE && empty($t->validation->_error_array))
		{
			$subject = $t->validation->post('contact_subject');
			$name = $t->validation->post('contact_name');
			$email = $t->validation->post('contact_email');
			
			$message = __("Subject:\t", BUNCH_NAME).$subject."\r\n";
			$message .= __("Contact Name:\t", BUNCH_NAME).$name."\r\n";
			
			$message .= "\r\n".$t->validation->post('contact_message'); 
	
			$contact_to =  get_option('admin_email');
			//echo $contact_to; exit("sss");
	
			$headers = 'From: '.$name.' <'.$email.'>' . "\r\n";
			wp_mail($contact_to, sprintf(__('Contact Us Message from %s', BUNCH_NAME), get_bloginfo('name') ), $message, $headers);
	
			echo "<fieldset>";
			echo "<div id='success_page' class='alert alert-success'>";
			echo "<h1>".__('Email Sent Successfully.', BUNCH_NAME)."</h1>";
			echo "<p>".sprintf(__("Thank you <strong>%s</strong>, your message has been submitted to us.", BUNCH_NAME), $name)."</p>";
			echo "</div>";
			echo "</fieldset>";
			exit;
		
		}else
		{
	
			 if( is_array( $t->validation->_error_array ) )
			 {
				 foreach( $t->validation->_error_array as $msg )
				 {
					 $messages .= '<div class="alert alert-danger">'.__('Error! ', BUNCH_NAME).$msg.'</div>';
				 }
			}
	
		}
	
		echo $messages;exit;
	}
	
	//----widget contact form
	
	function footer_contact_form_submit()
	{
		if( !count( $_POST ) ) return;
	
		_load_plugins_class( 'validation', 'helpers', true );
		$t = $GLOBALS['_bunch_base'];//printr($t);
		$settings = $t->option();
	
		/** set validation rules for contact form */
		//$t->validation->set_rules('contact_subject','<strong>'.__('Subject', BUNCH_NAME).'</strong>', 'min_length[5]');
		
		$t->validation->set_rules('footer_contact_name','<strong>'.__('Name', BUNCH_NAME).'</strong>', 'required|min_length[4]|max_lenth[30]');
		
		$t->validation->set_rules('footer_contact_email','<strong>'.__('Email', BUNCH_NAME).'</strong>', 'required|valid_email');
		
		$t->validation->set_rules('footer_contact_message','<strong>'.__('Message', BUNCH_NAME).'</strong>', 'required|min_length[5]');
		if( bunch_set($settings, 'contact_captcha_status'))
		{
			include_once( get_template_directory().'/includes/thirdparty/recaptchalib.php');
			$privatekey = bunch_set($settings, 'recaptcha_private');
			
			$resp = recaptcha_check_answer ($privatekey,
                                 $_SERVER["REMOTE_ADDR"],
                                 $_POST["recaptcha_challenge_field"],
                                 $_POST["recaptcha_response_field"]);
			
			if( !$resp->is_valid )
			{
					$t->validation->_error_array['captcha'] = __('Invalid captcha entered, please try again.', BUNCH_NAME);
			}
	
		}
		$messages = '';
		if($t->validation->run() !== FALSE && empty($t->validation->_error_array))
		{
			//$subject = $t->validation->post('contact_subject');
			$name = $t->validation->post('footer_contact_name');
			$email = $t->validation->post('footer_contact_email');
			
			//$message = __("Subject:\t", BUNCH_NAME).$subject."\r\n";
			$message .= __("Contact Name:\t", BUNCH_NAME).$name."\r\n";
			
			$message .= "\r\n".$t->validation->post('contact_message'); 
	
			$contact_to =  get_option('admin_email');
			//echo $contact_to; exit("sss");
	
			$headers = 'From: '.$name.' <'.$email.'>' . "\r\n";
			wp_mail($contact_to, sprintf(__('Contact Us Message from %s', BUNCH_NAME), get_bloginfo('name') ), $message, $headers);
	
			echo "<fieldset>";
			echo "<div id='success_page' class='alert alert-success'>";
			echo "<h1>".__('Email Sent Successfully.', BUNCH_NAME)."</h1>";
			echo "<p>".sprintf(__("Thank you <strong>%s</strong>, your message has been submitted to us.", BUNCH_NAME), $name)."</p>";
			echo "</div>";
			echo "</fieldset>";
			exit;
		
		}else
		{
	
			 if( is_array( $t->validation->_error_array ) )
			 {
				 foreach( $t->validation->_error_array as $msg )
				 {
					 $messages .= '<div class="alert alert-danger">'.__('Error! ', BUNCH_NAME).$msg.'</div>';
				 }
			}
	
		}
	
		echo $messages;exit;
	}
	
	function wishlist()
	{
		global $current_user;
      	get_currentuserinfo();
			
		if( is_user_logged_in() ){
			
			$meta = (array)get_user_meta( $current_user->ID, '_ja_product_wishlist', true );
			$data_id = bunch_set( $_POST, 'data_id' );
			if( $meta && is_array( $meta ) ){
				if( in_array( $data_id, $meta ) ){
					exit(json_encode(array('code'=>'exists', 'msg'=>__('You have already added this product to wish list', BUNCH_NAME ) ) ) );
				}
				$newmeta = array_merge( array( bunch_set( $_POST, 'data_id' ) ), $meta );
				update_user_meta( $current_user->ID, '_ja_product_wishlist', array_unique($newmeta) );
				exit(json_encode(array('code'=>'success', 'msg'=>__('Product successfully added to wishlist', BUNCH_NAME ) ) ) );
			}else{
				exit(json_encode(array('code'=>'fail', 'msg'=>__('There is an error edding wishlist', BUNCH_NAME ) ) ) );
			}
		}
		else exit(json_encode(array('code'=>'fail', 'msg'=>__('Please login first to add the product to your wishlist', BUNCH_NAME ) ) ) );
	}
	
	function wishlist_del()
	{
		global $current_user;
      	get_currentuserinfo();
			
		if( is_user_logged_in() ){
			
			$meta = get_user_meta( $current_user->ID, '_ja_product_wishlist', true );
			$data_id = bunch_set( $_POST, 'data_id' );
			if( $meta && is_array( $meta ) ){
				$searched = array_search( $data_id, $meta );
				if( isset($meta[$searched]) ){
					unset( $meta[$searched] );
					update_user_meta( $current_user->ID, '_ja_product_wishlist', array_unique($meta) );
					exit(json_encode(array('code'=>'del', 'msg'=>__('Product is successfully deleted from wishlist', BUNCH_NAME ) ) ) );
				}else exit(json_encode(array('code'=>'fail', 'msg'=>__('Unable to find this product into wishlist', BUNCH_NAME ) ) ) );
			}else{
				update_user_meta( $current_user->ID, '_ja_product_wishlist', array( bunch_set( $_POST, 'data_id' ) ) );
				exit(json_encode(array('code'=>'fail', 'msg'=>__('Unable to retrieve your wishlist', BUNCH_NAME ) ) ) );
			}
		}
		else exit(json_encode(array('code'=>'fail', 'msg'=>__('Please login first to add/delete product in your wishlist', BUNCH_NAME ) ) ) );
	}
	
	function download_rating()
	{
		$ip = $_SERVER['REMOTE ADDR'];
		extract( $_POST );
		
		$meta = get_post_meta( $post_id, '_download_rating', true );
		
		if( !bunch_set( $meta, $ip ) )
		{
			$meta[$ip] = $value;
			
			update_post_meta( $post_id, '_download_rating', $meta );
			
			echo 'success';exit;
		}
		
		exit( 'failed' );
	}
	function likeit()
	{
		global $current_user;
      	get_currentuserinfo();
		
		$post_id = bunch_set( $_POST, 'data_id' );
			
		if( true ){
			
			$meta = (int)get_post_meta( $post_id, '_jolly_like_it', true );
			
			$new_meta = (int)$meta + 1;
			
			$res = update_post_meta( $post_id, '_jolly_like_it', $new_meta );
			
			if( $res ){
				exit(json_encode(array('code'=>'success', 'msg'=>__('Your vote is succefully added', BUNCH_NAME ), 'value' => $new_meta ) ) );
			}else{

				exit(json_encode(array('code'=>'fail', 'msg'=>__('There is an error adding vote', BUNCH_NAME ) ) ) );
			}
		}
		else exit(json_encode(array('code'=>'fail', 'msg'=>__('There is an error adding vote', BUNCH_NAME ) ) ) );
	}
	
	
}