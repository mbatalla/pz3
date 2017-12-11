<?php

$bunch_sc = array();

$bunch_sc['bunch_call_out'] = array(

			"name" => __("Call Out", BUNCH_NAME),

			"base" => "bunch_call_out",

			"class" => "",

			"category" => __('Preshop', BUNCH_NAME),

			"icon" => 'fa-user' ,

			'description' => __('show the call out banner.', BUNCH_NAME),

			"params" => array(

				array(

				   "type" => "textfield",

				   "holder" => "div",

				   "class" => "",

				   "heading" => __("Image Link", BUNCH_NAME),

				   "param_name" => "link",

				   'value' => '',

				   "description" => __("Enter the Image Link", BUNCH_NAME)

				),

				array(

				   "type" => "attach_image",

				   "holder" => "div",

				   "class" => "",

				   "heading" => __("Image", BUNCH_NAME),

				   "param_name" => "img",

				   'value' => '',

				   "description" => __("Enter the Image url", BUNCH_NAME)

				),

				array(

				   "type" => "textfield",

				   "holder" => "div",

				   "class" => "",

				   "heading" => __("Image Title", BUNCH_NAME),

				   "param_name" => "title",

				   'value' => '',

				   "description" => __("Enter the Image Title", BUNCH_NAME)

				),

				array(

						   "type" => "dropdown",

						   "holder" => "div",

						   "class" => "",

						   "heading" => __(" Background Color Check", BUNCH_NAME),

						   "param_name" => "color_check",

						   'value' => array_flip(array('clearfix'=>__('None', BUNCH_NAME),'banner clearfix'=>__('Black', BUNCH_NAME),         'banner colorful clearfix'=>__('Orange', BUNCH_NAME) ) ),			

						   "description" => __("Select the background image color.", BUNCH_NAME)

						),

				

				

				

			)

	    );

		

		

		

		$bunch_sc['bunch_pricing-tables'] = array(

			"name" => __("Pricing tables", BUNCH_NAME),

			"base" => "bunch_pricing-tables",

			"class" => "",

			"category" => __('Preshop', BUNCH_NAME),

			"icon" => 'fa-user' ,

			'description' => __('show the pricing list.', BUNCH_NAME),

			"params" => array(

			

		

				array(

				   "type" => "textfield",

				   "holder" => "div",

				   "class" => "",

				   "heading" => __("Price Heading", BUNCH_NAME),

				   "param_name" => "price_heading",

				   'value' => '',

				   "description" => __("Enter the price heading", BUNCH_NAME)

				),

			    array(

				   "type" => "textfield",

				   "holder" => "div",

				   "class" => "",

				   "heading" => __("Price", BUNCH_NAME),

				   "param_name" => "price",

				   'value' => '',

				   "description" => __("Enter the price", BUNCH_NAME)

				),

			      	array(

						   "type" => "exploded_textarea",

						   "holder" => "div",

						   "class" => "",

						   "heading" => __("Features", BUNCH_NAME),

						   "param_name" => "features",

						   "description" => __("Enter One Feature per Line", BUNCH_NAME)

						),

				

				  array(

				   "type" => "textfield",

				   "holder" => "div",

				   "class" => "",

				   "heading" => __("Enter the button name", BUNCH_NAME),

				   "param_name" => "button_name",

				   'value' => '',

				   "description" => __("Enter the button name", BUNCH_NAME)

				),

					array(

				   "type" => "textfield",

				   "holder" => "div",

				   "class" => "",

				   "heading" => __("Button link", BUNCH_NAME),

				   "param_name" => "link",

				   'value' => '',

				   "description" => __("Enter the button link", BUNCH_NAME)

				),

				

				

			)

	    );

					

		$bunch_sc['bunch_parchase-lists'] = array(

			"name" => __("Parchase Lists", BUNCH_NAME),

			"base" => "bunch_parchase-lists",

			"class" => "",

			"category" => __('Preshop', BUNCH_NAME),

			"icon" => 'fa-user' ,

			'description' => __('show the parchase list.', BUNCH_NAME),

			"params" => array(

			

		

				array(

				   "type" => "textfield",

				   "holder" => "div",

				   "class" => "",

				   "heading" => __("Price Heading", BUNCH_NAME),

				   "param_name" => "price_heading",

				   'value' => '',

				   "description" => __("Enter the price heading", BUNCH_NAME)

				),

			    array(

				   "type" => "textfield",

				   "holder" => "div",

				   "class" => "",

				   "heading" => __("Price", BUNCH_NAME),

				   "param_name" => "price",

				   'value' => '',

				   "description" => __("Enter the price", BUNCH_NAME)

				),

			      	array(

						   "type" => "exploded_textarea",

						   "holder" => "div",

						   "class" => "",

						   "heading" => __("Features", BUNCH_NAME),

						   "param_name" => "features",

						   "description" => __("Enter One Feature per Line", BUNCH_NAME)

						),

				

				  array(

				   "type" => "textfield",

				   "holder" => "div",

				   "class" => "",

				   "heading" => __("Enter the button name", BUNCH_NAME),

				   "param_name" => "button_name",

				   'value' => '',

				   "description" => __("Enter the button name", BUNCH_NAME)

				),

				

					array(

				   "type" => "textfield",

				   "holder" => "div",

				   "class" => "",

				   "heading" => __("Button link", BUNCH_NAME),

				   "param_name" => "link",

				   'value' => '',

				   "description" => __("Enter the button link", BUNCH_NAME)

				),

				

				

			)

	    );

					

			$bunch_sc['bunch_parallex'] = array(

			"name" => __("parallex", BUNCH_NAME),

			"base" => "bunch_parallex",

			"class" => "",

			"category" => __('Preshop', BUNCH_NAME),

			'description' => __('show the Parallex banner.', BUNCH_NAME),

			"params" => array(

				array(

				   "type" => "textfield",

				   "holder" => "div",

				   "class" => "",

				   "heading" => __("Msg Title",BUNCH_NAME),

				   "param_name" => "msg_title",

				   'value' =>  '',

				   "description" => __("Enter the message title", BUNCH_NAME)

				),

				array(

				   "type" => "attach_image",

				   "holder" => "div",

				   "class" => "",

				   "heading" => __("background image", BUNCH_NAME),

				   "param_name" => "image",

				   "description" => __("Enter the  background image", BUNCH_NAME)

				),

							array(

				   "type" => "textarea",

				   "holder" => "div",

				   "class" => "",

				   "heading" => __("Short html Text", BUNCH_NAME),

				   "param_name" => "content",

						 'value' =>'',

				   "description" => __("Enter content, you can use html tags", BUNCH_NAME)

				),

				

				

				

			)

	    );

					

					

				$bunch_sc['bunch_parallex2'] = array(

			"name" => __("parallex2", BUNCH_NAME),

			"base" => "bunch_parallex2",

			"class" => "",

			"category" => __('Preshop', BUNCH_NAME),

			'description' => __('show the Parallex 2 banner.', BUNCH_NAME),

			"params" => array(

				array(

				   "type" => "textfield",

				   "holder" => "div",

				   "class" => "",

				   "heading" => __("Msg Title",BUNCH_NAME),

				   "param_name" => "msg_title",

				   'value' => '',

				   "description" => __("Enter the message title", BUNCH_NAME)

				),

						array(

				   "type" => "textfield",

				   "holder" => "div",

				   "class" => "",

				   "heading" => __("Button Link Title Attribute",BUNCH_NAME),

				   "param_name" => "link_title",

				   'value' => '',

				   "description" => __("Enter the message title", BUNCH_NAME)

				),

						array(

				   "type" => "textfield",

				   "holder" => "div",

				   "class" => "",

				   "heading" => __("Title",BUNCH_NAME),

				   "param_name" => "msg_title",

				   'value' => '',

				   "description" => __("Enter the message title", BUNCH_NAME)

				),

						array(

				   "type" => "textfield",

				   "holder" => "div",

				   "class" => "",

				   "heading" => __("Button Link URl",BUNCH_NAME),

				   "param_name" => "link",

				   'value' =>  '',

				   "description" => __("Enter the button link url", BUNCH_NAME)

				),

				array(

				   "type" => "attach_image",

				   "holder" => "div",

				   "class" => "",

				   "heading" => __("background image", BUNCH_NAME),

				   "param_name" => "image",

				   "description" => __("Enter the  background image", BUNCH_NAME)

				),

							array(

				   "type" => "textarea",

				   "holder" => "div",

				   "class" => "",

				   "heading" => __("Short html Text", BUNCH_NAME),

				   "param_name" => "content",

						 'value' =>  '',

				   "description" => __("Enter content, you can use html tags", BUNCH_NAME)

				),

				

				

				

			)

	    );

	$bunch_sc['bunch_right_small_banner'] = array(

			"name" => __("Right Small Banner", BUNCH_NAME),

			"base" => "bunch_right_small_banner",

			"class" => "",

			"category" => __('Preshop', BUNCH_NAME),

			"icon" => 'fa-user' ,

			'description' => __('show the mini banner.', BUNCH_NAME),

			"params" => array(

				array(

				   "type" => "textfield",

				   "holder" => "div",

				   "class" => "",

				   "heading" => __("Image Link", BUNCH_NAME),

				   "param_name" => "link",

				   'value' => '',

				   "description" => __("Enter the Image Link", BUNCH_NAME)

				),

				array(

				   "type" => "textfield",

				   "holder" => "div",

				   "class" => "",

				   "heading" => __("Image Title", BUNCH_NAME),

				   "param_name" => "title",

				   'value' => '',

				   "description" => __("Enter the Image Title", BUNCH_NAME)

				),

				array(

						   "type" => "dropdown",

						   "holder" => "div",

						   "class" => "",

						   "heading" => __("Banner Button 1 Status", BUNCH_NAME),

						   "param_name" => "btn_status",

						   'value' => array_flip(array('active'=>__('active', BUNCH_NAME),'none'=>__('none', BUNCH_NAME) ) ),			

						   "description" => __("show button status.", BUNCH_NAME)

						),

							array(

				   "type" => "attach_image",

				   "holder" => "div",

				   "class" => "",

				   "heading" => __("Image", BUNCH_NAME),

				   "param_name" => "img",

				   'value' => '',

				   "description" => __("Enter the Image url", BUNCH_NAME)

				),

						array(

				   "type" => "textfield",

				   "holder" => "div",

				   "class" => "",

				   "heading" => __("Banner Button Link", BUNCH_NAME),

				   "param_name" => "banner2_btn_link",

				   'value' => '',

				   "description" => __("Enter the banner2 button link", BUNCH_NAME)

				),

						array(

				   "type" => "textfield",

				   "holder" => "div",

				   "class" => "",

				   "heading" => __("Banner  Button Text", BUNCH_NAME),

				   "param_name" => "banner2_btn_text",

				   'value' => '',

				   "description" => __("Banner 2 Button Text", BUNCH_NAME)

				),

						array(

				   "type" => "textfield",

				   "holder" => "div",

				   "class" => "",

				   "heading" => __("Banner  title", BUNCH_NAME),

				   "param_name" => "banner2_title",

				   'value' => '',

				   "description" => __("Enter the banner2 title", BUNCH_NAME)

				),

						

			)

	    );

			

					

			$bunch_sc['bunch_mini_banner'] = array(

			"name" => __("Mini Banner", BUNCH_NAME),

			"base" => "bunch_mini_banner",

			"class" => "",

			"category" => __('Preshop', BUNCH_NAME),

			"icon" => 'fa-user' ,

			'description' => __('show the mini banner.', BUNCH_NAME),

			"params" => array(

				array(

				   "type" => "textfield",

				   "holder" => "div",

				   "class" => "",

				   "heading" => __("Image Link", BUNCH_NAME),

				   "param_name" => "link",

				   'value' => '',

				   "description" => __("Enter the Image Link", BUNCH_NAME)

				),

						

							array(

				   "type" => "textfield",

				   "holder" => "div",

				   "class" => "",

				   "heading" => __("Banner Button 1  Link", BUNCH_NAME),

				   "param_name" => "btn_link",

				   'value' => '',

				   "description" => __("Enter the button Link", BUNCH_NAME)

				),

							array(

				   "type" => "textfield",

				   "holder" => "div",

				   "class" => "",

				   "heading" => __("Banner Button 1 Text", BUNCH_NAME),

				   "param_name" => "btn_text",

				   'value' => '',

				   "description" => __("Enter the button title", BUNCH_NAME)

				),

				array(

				   "type" => "textfield",

				   "holder" => "div",

				   "class" => "",

				   "heading" => __("Image Title", BUNCH_NAME),

				   "param_name" => "title",

				   'value' => '',

				   "description" => __("Enter the Image Title", BUNCH_NAME)

				),

				array(

						   "type" => "dropdown",

						   "holder" => "div",

						   "class" => "",

						   "heading" => __("Banner Button 1 Status", BUNCH_NAME),

						   "param_name" => "btn_status",

						   'value' => array_flip(array('active'=>__('active', BUNCH_NAME),'none'=>__('none', BUNCH_NAME) ) ),			

						   "description" => __("show button status.", BUNCH_NAME)

						),

							array(

				   "type" => "attach_image",

				   "holder" => "div",

				   "class" => "",

				   "heading" => __("Image", BUNCH_NAME),

				   "param_name" => "img",

				   'value' => '',

				   "description" => __("Enter the Image url", BUNCH_NAME)

				),

					

			

			)

	    );

					

					

			$bunch_sc['bunch_parallex'] = array(

			"name" => __("parallex", BUNCH_NAME),

			"base" => "bunch_parallex",

			"class" => "",

			"category" => __('Preshop', BUNCH_NAME),

			'description' => __('show the Parallex banner.', BUNCH_NAME),

			"params" => array(

				array(

				   "type" => "textfield",

				   "holder" => "div",

				   "class" => "",

				   "heading" => __("Msg Title",BUNCH_NAME),

				   "param_name" => "msg_title",

				   'value' =>  __("Pretty & PreShop",BUNCH_NAME),

				   "description" => __("Enter the message title", BUNCH_NAME)

				),

				array(

				   "type" => "attach_image",

				   "holder" => "div",

				   "class" => "",

				   "heading" => __("background image", BUNCH_NAME),

				   "param_name" => "image",

				   "description" => __("Enter the  background image", BUNCH_NAME)

				),

							array(

				   "type" => "textarea",

				   "holder" => "div",

				   "class" => "",

				   "heading" => __("Short html Text", BUNCH_NAME),

				   "param_name" => "content",

						 'value' =>  __("Find your favorite design and put your personal touch on it before you buy..",BUNCH_NAME),

				   "description" => __("Enter content, you can use html tags", BUNCH_NAME)

				),

				

				

				

			)

	    );

$bunch_sc['bunch_services']	=	array(

					"name" => __("Services", BUNCH_NAME),

					"base" => "bunch_services",

					"class" => "",

					"category" => __('Preshop', BUNCH_NAME),

					"icon" => 'fa-briefcase' ,

					'description' => __('Show Service Style 1 in 3 Columns.', BUNCH_NAME),

					"params" => array(

					   	array(

						   "type" => "dropdown",

						   "holder" => "div",

						   "class" => "",

						   "heading" => __( 'Do you want to show title on top', BUNCH_NAME ),

						   "param_name" => "title_show",

						   'value' => array_flip(array('true'=>__('Show', BUNCH_NAME),'false'=>__('None', BUNCH_NAME)) ),			

						   "description" => __( 'select title if you want to show of top.', BUNCH_NAME )

						),

					      array(

						   "type" => "dropdown",

						   "holder" => "div",

						   "class" => "",

						   "heading" => __( 'Show read more button', BUNCH_NAME ),

						   "param_name" => "button_read_more",

						   'value' => array_flip(array('true'=>__('Show', BUNCH_NAME),'false'=>__('None', BUNCH_NAME)) ),			

						   "description" => __( 'choose if you want to show read more button.', BUNCH_NAME )

						),

						array(

						   "type" => "textfield",

						   "holder" => "div",

						   "class" => "",

						   "heading" => __('Title', BUNCH_NAME ),

						   "param_name" => "title",

						   "description" => __('Enter the title.', BUNCH_NAME )

						),

						  array(

						   "type" => "textfield",

						   "holder" => "div",

						   "class" => "",

						   "heading" => __('Inner Title', BUNCH_NAME ),

						   "param_name" => "title_inner",

						   "description" => __('Enter the Inner title.', BUNCH_NAME )

						),

							

					

					 

						array(

						   "type" => "textfield",

						   "holder" => "div",

						   "class" => "",

						   "heading" => __('Number', BUNCH_NAME ),

						   "param_name" => "num",

						   "description" => __('Enter Number of Services to Show.', BUNCH_NAME )

						),

		

						array(

						   "type" => "dropdown",

						   "holder" => "div",

						   "class" => "",

						   "heading" => __( 'Category', BUNCH_NAME ),

						   "param_name" => "cat",

						   "value" => array_flip( (array)bunch_get_categories( array( 'taxonomy' => 'services_category', 'hide_empty' => FALSE ), true ) ),

						   "description" => __( 'Choose Category.', BUNCH_NAME )

						),

						array(

						   "type" => "dropdown",

						   "holder" => "div",

						   "class" => "",

						   "heading" => __("Order By", BUNCH_NAME),

						   "param_name" => "sort",

						   'value' => array_flip( array('date'=>__('Date', BUNCH_NAME),'title'=>__('Title', BUNCH_NAME) ,'name'=>__('Name', BUNCH_NAME) ,'author'=>__('Author', BUNCH_NAME),'comment_count' =>__('Comment Count', BUNCH_NAME),'random' =>__('Random', BUNCH_NAME) ) ),			

						   "description" => __("Enter the sorting order.", BUNCH_NAME)

						),

						array(

						   "type" => "dropdown",

						   

						   "holder" => "div",

						   "class" => "",

						   "heading" => __("Order", BUNCH_NAME),

						   "param_name" => "order",

						   'value' => array_flip(array('ASC'=>__('Ascending', BUNCH_NAME),'DESC'=>__('Descending', BUNCH_NAME) ) ),			

						   "description" => __("Enter the sorting order.", BUNCH_NAME)

						),

			

			

		

					)

				);

	$bunch_sc['bunch_team']	=	array(

					"name" => __("Teams", BUNCH_NAME),

					"base" => "bunch_team",

					"class" => "",

					"category" => __('Preshop', BUNCH_NAME),

					"icon" => 'fa-briefcase' ,

					'description' => __('Show Team.', BUNCH_NAME),

					"params" => array(

						array(

						   "type" => "textfield",

						   "holder" => "div",

						   "class" => "",

						   "heading" => __('Title', BUNCH_NAME ),

						   "param_name" => "title",

						   "description" => __('Enter title.', BUNCH_NAME )

						),

		              	array(

						   "type" => "textfield",

						   "holder" => "div",

						   "class" => "",

						   "heading" => __('Title Inner', BUNCH_NAME ),

						   "param_name" => "title_inner",

						   "description" => __('Enter Inner Title .', BUNCH_NAME )

						),

		

						array(

						   "type" => "textfield",

						   "holder" => "div",

						   "class" => "",

						   "heading" => __('Number', BUNCH_NAME ),

						   "param_name" => "num",

						   "description" => __('Enter of Teams to show.', BUNCH_NAME )

						),

		

						array(

						   "type" => "dropdown",

						   "holder" => "div",

						   "class" => "",

						   "heading" => __( 'Category', BUNCH_NAME ),

						   "param_name" => "cat",

						   "value" => array_flip( (array)bunch_get_categories( array( 'taxonomy' => 'team_category', 'hide_empty' => FALSE ), true ) ),

						   "description" => __( 'Choose Category.', BUNCH_NAME )

						),

						array(

						   "type" => "dropdown",

						   "holder" => "div",

						   "class" => "",

						   "heading" => __("Order By", BUNCH_NAME),

						   "param_name" => "sort",

						   'value' => array_flip( array('date'=>__('Date', BUNCH_NAME),'title'=>__('Title', BUNCH_NAME) ,'name'=>__('Name', BUNCH_NAME) ,'author'=>__('Author', BUNCH_NAME),'comment_count' =>__('Comment Count', BUNCH_NAME),'random' =>__('Random', BUNCH_NAME) ) ),			

						   "description" => __("Enter the sorting order.", BUNCH_NAME)

						),

						array(

						   "type" => "dropdown",

						   "holder" => "div",

						   "class" => "",

						   "heading" => __("Order", BUNCH_NAME),

						   "param_name" => "order",

						   'value' => array_flip(array('ASC'=>__('Ascending', BUNCH_NAME),'DESC'=>__('Descending', BUNCH_NAME) ) ),			

						   "description" => __("Enter the sorting order.", BUNCH_NAME)

						),

			

			

		

					)

				);

				

				$bunch_sc['bunch_testimonials']	=	array(

					"name" => __("Testimonials ( 2 Columns)", BUNCH_NAME),

					"base" => "bunch_testimonials",

					"class" => "",

					"category" => __('Preshop', BUNCH_NAME),

					"icon" => 'fa-briefcase' ,

					'description' => __('Show testimonials Style 1 in 2 Columns.', BUNCH_NAME),

					"params" => array(

						array(

						   "type" => "textfield",

						   "holder" => "div",

						   "class" => "",

						   "heading" => __('Number', BUNCH_NAME ),

						   "param_name" => "num",

						   "description" => __('Enter Number of Services to Show.', BUNCH_NAME )

						),

		                 array(

						   "type" => "textfield",

						   "holder" => "div",

						   "class" => "",

						   "heading" => __('Title', BUNCH_NAME ),

						   "param_name" => "title",

						   "description" => __('Enter Title.', BUNCH_NAME )

						),

						array(

						   "type" => "textfield",

						   "holder" => "div",

						   "class" => "",

						   "heading" => __('Tagline', BUNCH_NAME ),

						   "param_name" => "tag",

						   "description" => __('Enter the tagline.', BUNCH_NAME )

						),

						array(

						   "type" => "dropdown",

						   "holder" => "div",

						   "class" => "",

						   "heading" => __( 'Category', BUNCH_NAME ),

						   "param_name" => "cat",

						   "value" => array_flip( (array)bunch_get_categories( array( 'taxonomy' => 'testimonial_category', 'hide_empty' => FALSE ), true ) ),

						   "description" => __( 'Choose Category.', BUNCH_NAME )

						),

						array(

						   "type" => "dropdown",

						   "holder" => "div",

						   "class" => "",

						   "heading" => __("Order By", BUNCH_NAME),

						   "param_name" => "sort",

						   'value' => array_flip( array('date'=>__('Date', BUNCH_NAME),'title'=>__('Title', BUNCH_NAME) ,'name'=>__('Name', BUNCH_NAME) ,'author'=>__('Author', BUNCH_NAME),'comment_count' =>__('Comment Count', BUNCH_NAME),'random' =>__('Random', BUNCH_NAME) ) ),			

						   "description" => __("Enter the sorting order.", BUNCH_NAME)

						),

						array(

						   "type" => "dropdown",

						   "holder" => "div",

						   "class" => "",

						   "heading" => __("Order", BUNCH_NAME),

						   "param_name" => "order",

						   'value' => array_flip(array('ASC'=>__('Ascending', BUNCH_NAME),'DESC'=>__('Descending', BUNCH_NAME) ) ),			

						   "description" => __("Enter the sorting order.", BUNCH_NAME)

						),

			

			

		

					)

				);

				

				

				

				

				$bunch_sc['bunch_products']	=	array(

					"name" => __("Products ( 2 Columns)", BUNCH_NAME),

					"base" => "bunch_products",

					"class" => "",

					"category" => __('Preshop', BUNCH_NAME),

					"icon" => 'fa-briefcase' ,

					'description' => __('Show Featured Products .', BUNCH_NAME),

					"params" => array(

						array(

						   "type" => "textfield",

						   "holder" => "div",

						   "class" => "",

						   "heading" => __('Featured Posts Title', BUNCH_NAME ),

						   "param_name" => "feature_title",

						   "description" => __('Enter Feature Post title.', BUNCH_NAME )

						),

						array(

						   "type" => "textfield",

						   "holder" => "div",

						   "class" => "",

						   "heading" => __('Number', BUNCH_NAME ),

						   "param_name" => "num",

						   "description" => __('Enter Number of Services to Show.', BUNCH_NAME )

						),

						array(

						   "type" => "dropdown",

						   "holder" => "div",

						   "class" => "",

						   "heading" => __( 'Category', BUNCH_NAME ),

						   "param_name" => "cat",

						   "value" => array_flip( (array)bunch_get_categories( array( 'taxonomy' => 'product_cat',                           'hide_empty' => FALSE ), true ) ),

						   "description" => __( 'Choose Category.', BUNCH_NAME )

						),

						array(

						   "type" => "dropdown",

						   "holder" => "div",

						   "class" => "",

						   "heading" => __("Order By", BUNCH_NAME),

						   "param_name" => "sort",

						   'value' => array_flip( array('date'=>__('Date', BUNCH_NAME),'title'=>__('Title', BUNCH_NAME) ,'name'=>__('Name', BUNCH_NAME) ,'author'=>__('Author', BUNCH_NAME),'comment_count' =>__('Comment Count', BUNCH_NAME),'random' =>__('Random', BUNCH_NAME) ) ),			

						   "description" => __("Enter the sorting order.", BUNCH_NAME)

						),

						array(

						   "type" => "dropdown",

						   "holder" => "div",

						   "class" => "",

						   "heading" => __("Order", BUNCH_NAME),

						   "param_name" => "order",

						   'value' => array_flip(array('ASC'=>__('Ascending', BUNCH_NAME),'DESC'=>__('Descending', BUNCH_NAME) ) ),			

						   "description" => __("Enter the sorting order.", BUNCH_NAME)

						),

			

			

		

					)

				);

				

				

				$bunch_sc['bunch_best-sellers']	=	array(

					"name" => __(" Best Sellers", BUNCH_NAME),

					"base" => "bunch_best-sellers",

					"class" => "",

					"category" => __('Preshop', BUNCH_NAME),

					"icon" => 'fa-briefcase' ,

					'description' => __('Show best selling products .', BUNCH_NAME),

					"params" => array(

						array(

						   "type" => "textfield",

						   "holder" => "div",

						   "class" => "",

						   "heading" => __('Title', BUNCH_NAME ),

						   "param_name" => "title",

						   "description" => __('Enter the title on the top', BUNCH_NAME )

						),

						array(

						   "type" => "textfield",

						   "holder" => "div",

						   "class" => "",

						   "heading" => __('Set Old Price of items', BUNCH_NAME ),

						   "param_name" => "old_price",

						   "description" => __('Enter the old price', BUNCH_NAME )

						),

							array(

						   "type" => "textfield",

						   "holder" => "div",

						   "class" => "",

						   "heading" => __('Set the new price of the items', BUNCH_NAME ),

						   "param_name" => "new_price",

						   "description" => __('Enter the new price', BUNCH_NAME )

						),

						array(

						   "type" => "textfield",

						   "holder" => "div",

						   "class" => "",

						   "heading" => __('Number', BUNCH_NAME ),

						   "param_name" => "num",

						   "description" => __('Enter Number of Services to Show.', BUNCH_NAME )

						),

						array(

						   "type" => "dropdown",

						   "holder" => "div",

						   "class" => "",

						   "heading" => __( 'Category', BUNCH_NAME ),

						   "param_name" => "cat",

						   "value" => array_flip( (array)bunch_get_categories( array( 'taxonomy' => 'product_cat',                           'hide_empty' => FALSE ), true ) ),

						   "description" => __( 'Choose Category.', BUNCH_NAME )

						),

						array(

						   "type" => "dropdown",

						   "holder" => "div",

						   "class" => "",

						   "heading" => __("Order By", BUNCH_NAME),

						   "param_name" => "sort",

						   'value' => array_flip( array('date'=>__('Date', BUNCH_NAME),'title'=>__('Title', BUNCH_NAME) ,'name'=>__('Name', BUNCH_NAME) ,'author'=>__('Author', BUNCH_NAME),'comment_count' =>__('Comment Count', BUNCH_NAME),'random' =>__('Random', BUNCH_NAME) ) ),			

						   "description" => __("Enter the sorting order.", BUNCH_NAME)

						),

						array(

						   "type" => "dropdown",

						   "holder" => "div",

						   "class" => "",

						   "heading" => __("Order", BUNCH_NAME),

						   "param_name" => "order",

						   'value' => array_flip(array('ASC'=>__('Ascending', BUNCH_NAME),'DESC'=>__('Descending', BUNCH_NAME) ) ),			

						   "description" => __("Enter the sorting order.", BUNCH_NAME)

						),

			

			

		

					)

				);

				$bunch_sc['bunch_middle-slider']	=	array(

					"name" => __("Middle slider  Products ", BUNCH_NAME),

					"base" => "bunch_middle-slider",

					"class" => "",

					"category" => __('Preshop', BUNCH_NAME),

					"icon" => 'fa-briefcase' ,

					'description' => __('Show Featured Products .', BUNCH_NAME),

					"params" => array(

						array(

						   "type" => "textfield",

						   "holder" => "div",

						   "class" => "",

						   "heading" => __('Title', BUNCH_NAME ),

						   "param_name" => "feature_title",

						   "description" => __('Enter Slider title.', BUNCH_NAME )

						),

						array(

						   "type" => "textfield",

						   "holder" => "div",

						   "class" => "",

						   "heading" => __('Number', BUNCH_NAME ),

						   "param_name" => "num",

						   "description" => __('Enter Number of Products to Show.', BUNCH_NAME )

						),

						array(

						   "type" => "dropdown",

						   "holder" => "div",

						   "class" => "",

						   "heading" => __( 'Category', BUNCH_NAME ),

						   "param_name" => "cat",

						   "value" => array_flip( (array)bunch_get_categories( array( 'taxonomy' => 'product_cat',                           'hide_empty' => FALSE ), true ) ),

						   "description" => __( 'Choose Category.', BUNCH_NAME )

						),

						array(

						   "type" => "dropdown",

						   "holder" => "div",

						   "class" => "",

						   "heading" => __("Order By", BUNCH_NAME),

						   "param_name" => "sort",

						   'value' => array_flip( array('date'=>__('Date', BUNCH_NAME),'title'=>__('Title', BUNCH_NAME) ,'name'=>__('Name', BUNCH_NAME) ,'author'=>__('Author', BUNCH_NAME),'comment_count' =>__('Comment Count', BUNCH_NAME),'random' =>__('Random', BUNCH_NAME) ) ),			

						   "description" => __("Enter the sorting order.", BUNCH_NAME)

						),

						array(

						   "type" => "dropdown",

						   "holder" => "div",

						   "class" => "",

						   "heading" => __("Order", BUNCH_NAME),

						   "param_name" => "order",

						   'value' => array_flip(array('ASC'=>__('Ascending', BUNCH_NAME),'DESC'=>__('Descending', BUNCH_NAME) ) ),			

						   "description" => __("Enter the sorting order.", BUNCH_NAME)

						),

			

			           	array(

						   "type" => "textfield",

						   "holder" => "div",

						   "class" => "",

						   "heading" => __("Product's Old Price", BUNCH_NAME ),

						   "param_name" => "old_price",

						   "description" => __("Enter Product's Old Price.", BUNCH_NAME )

						),

		                	array(

						   "type" => "textfield",

						   "holder" => "div",

						   "class" => "",

						   "heading" => __("Product's New Price", BUNCH_NAME ),

						   "param_name" => "new_price",

						   "description" => __("Enter Product's New Price.", BUNCH_NAME )

						),

					)

				);

								

				

					$bunch_sc['bunch_from-blog']	=	array(

					"name" => __("From Blog", BUNCH_NAME),

					"base" => "bunch_from-blog",

					"class" => "",

					"category" => __('Preshop', BUNCH_NAME),

					"icon" => 'fa-briefcase' ,

					'description' => __('Show Posts from the blog.', BUNCH_NAME),

					"params" => array(

						array(

						   "type" => "textfield",

						   "holder" => "div",

						   "class" => "",

						   "heading" => __('Enter title', BUNCH_NAME ),

						   "param_name" => "title",

						   "description" => __('Enter blog title.', BUNCH_NAME )

						),

						array(

						   "type" => "textfield",

						   "holder" => "div",

						   "class" => "",

						   "heading" => __('Number', BUNCH_NAME ),

						   "param_name" => "num",

						   "description" => __('Enter Number of Blog Posts to show.', BUNCH_NAME )

						),

						array(

						   "type" => "dropdown",

						   "holder" => "div",

						   "class" => "",

						   "heading" => __( 'Category', BUNCH_NAME ),

						   "param_name" => "cat",

						   "value" => array_flip( (array)bunch_get_categories( array( 'taxonomy' => 'category', 'hide_empty' =>          FALSE ), true ) ),

						   "description" => __( 'Choose Category.', BUNCH_NAME )

						),

						array(

						   "type" => "dropdown",

						   "holder" => "div",

						   "class" => "",

						   "heading" => __("Order By", BUNCH_NAME),

						   "param_name" => "sort",

						   'value' => array_flip( array('date'=>__('Date', BUNCH_NAME),'title'=>__('Title', BUNCH_NAME) ,'name'=>__('Name', BUNCH_NAME) ,'author'=>__('Author', BUNCH_NAME),'comment_count' =>__('Comment Count', BUNCH_NAME),'random' =>__('Random', BUNCH_NAME) ) ),			

						   "description" => __("Enter the sorting order.", BUNCH_NAME)

						),

						array(

						   "type" => "dropdown",

						   "holder" => "div",

						   "class" => "",

						   "heading" => __("Order", BUNCH_NAME),

						   "param_name" => "order",

						   'value' => array_flip(array('ASC'=>__('Ascending', BUNCH_NAME),'DESC'=>__('Descending', BUNCH_NAME) ) ),			

						   "description" => __("Enter the sorting order.", BUNCH_NAME)

						),

			

			

		

					)

				);



					$bunch_sc['bunch_top-blog-posts']	=	array(

					"name" => __("top-blog-posts", BUNCH_NAME),

					"base" => "bunch_top-blog-posts",

					"class" => "",

					"category" => __('Preshop', BUNCH_NAME),

					"icon" => 'fa-briefcase' ,

					'description' => __('Show Posts from the blog.', BUNCH_NAME),

					"params" => array(

						array(

						   "type" => "textfield",

						   "holder" => "div",

						   "class" => "",

						   "heading" => __('Number', BUNCH_NAME ),

						   "param_name" => "num",

						   "description" => __('Enter Number of Blog Posts to show.', BUNCH_NAME )

						),

						array(

						   "type" => "dropdown",

						   "holder" => "div",

						   "class" => "",

						   "heading" => __( 'Category', BUNCH_NAME ),

						   "param_name" => "cat",

						   "value" => array_flip( (array)bunch_get_categories( array( 'taxonomy' => 'category', 'hide_empty'                           => FALSE ), true ) ),

						   "description" => __( 'Choose Category.', BUNCH_NAME )

						),

						array(

						   "type" => "dropdown",

						   "holder" => "div",

						   "class" => "",

						   "heading" => __("Order By", BUNCH_NAME),

						   "param_name" => "sort",

						   'value' => array_flip( array('date'=>__('Date', BUNCH_NAME),'title'=>__('Title', BUNCH_NAME) ,'name'=>__('Name', BUNCH_NAME) ,'author'=>__('Author', BUNCH_NAME),'comment_count' =>__('Comment Count', BUNCH_NAME),'random' =>__('Random', BUNCH_NAME) ) ),			

						   "description" => __("Enter the sorting order.", BUNCH_NAME)

						),

						array(

						   "type" => "dropdown",

						   "holder" => "div",

						   "class" => "",

						   "heading" => __("Order", BUNCH_NAME),

						   "param_name" => "order",

						   'value' => array_flip(array('ASC'=>__('Ascending', BUNCH_NAME),'DESC'=>__('Descending', BUNCH_NAME) ) ),			

						   "description" => __("Enter the sorting order.", BUNCH_NAME)

						),

			

			

		

					)

				);

				

		$bunch_sc['bunch_fun_facts']	=	array(

					"name" => __("Fun Facts", BUNCH_NAME),

					"base" => "bunch_fun_facts",

					"class" => "",

					"category" => __('Preshop', BUNCH_NAME),

					"icon" => 'icon-wpb-layer-shape-text' ,

					"as_parent" => array('only' => 'bunch_fact'), // Use only|except attributes to limit child shortcodes (separate multiple values with comma)

					"content_element" => true,

					"show_settings_on_create" => false,

					'description' => __('Add Fun Facts to your theme.', BUNCH_NAME),

					"params" => array(

						array(

						   "type" => "attach_image",

						   "holder" => "div",

						   "class" => "",

						   "heading" => __("Background Image", BUNCH_NAME),

						   "param_name" => "bg",

						   "description" => __("Upload Background Image", BUNCH_NAME)

						),

					

					),

					"js_view" => 'VcColumnView'

				);

$bunch_sc['bunch_fact']	=	array(

					"name" => __("Fact", BUNCH_NAME),

					"base" => "bunch_fact",

					"class" => "",

					"category" => __('Preshop', BUNCH_NAME),

					"icon" => 'icon-wpb-layer-shape-text' ,

					"as_child" => array('only' => 'bunch_fun_facts'),// Use only|except attributes to limit child shortcodes (separate multiple values with comma)

					"content_element" => true,

					"show_settings_on_create" => true,

					'description' => __('Add Fact.', BUNCH_NAME),

					"params" => array(

						array(

						   "type" => "textfield",

						   "holder" => "div",

						   "class" => "",

						   "heading" => __("Title", BUNCH_NAME),

						   "param_name" => "title",

						   "description" => __("Enter Title for Skills Section", BUNCH_NAME)

						),

						array(

						   "type" => "textfield",

						   "holder" => "div",

						   "class" => "",

						   "heading" => __("Number", BUNCH_NAME),

						   "param_name" => "number",

						   "description" => __("Enter Number", BUNCH_NAME)

						),

					

					),

				);		

/*----------------------------------------------------------------------------*/

