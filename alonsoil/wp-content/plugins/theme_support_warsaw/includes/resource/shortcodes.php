<?php
$bunch_sc = array();
$bunch_sc['bunch_healthy_form']	=	array(
					"name" => __("Healthy Form", BUNCH_NAME),
					"base" => "bunch_healthy_form",
					"class" => "",
					"category" => __('Warsaw', BUNCH_NAME),
					"icon" => 'fa-briefcase' ,
					'description' => __('Show Healthy Form.', BUNCH_NAME),
					"params" => array(
								array(
								   "type" => "attach_image",
								   "holder" => "div",
								   "class" => "",
								   "heading" => __("Background Image", BUNCH_NAME),
								   "param_name" => "bg_img",
								   "description" => __("Enter the Section Image to show.", BUNCH_NAME)
								),
								array(
								   "type" => "textfield",
								   "holder" => "div",
								   "class" => "",
								   "heading" => __('Title', BUNCH_NAME ),
								   "param_name" => "title",
								   "description" => __('Enter The Title to Show.', BUNCH_NAME )
								),
								array(
								   "type" => "textfield",
								   "holder" => "div",
								   "class" => "",
								   "heading" => __('Title', BUNCH_NAME ),
								   "param_name" => "title1",
								   "description" => __('Enter The Title to Show.', BUNCH_NAME ),
								   'group' => esc_html__('Organic', BUNCH_NAME)
								),
								array(
								   "type" => "textarea",
								   "holder" => "div",
								   "class" => "",
								   "heading" => __('Text', BUNCH_NAME ),
								   "param_name" => "text1",
								   "description" => __('Enter The Section Text to Show.', BUNCH_NAME ),
								   'group' => esc_html__('Organic', BUNCH_NAME)
								),
								array(
								   "type" => "textfield",
								   "holder" => "div",
								   "class" => "",
								   "heading" => __('Button Link', BUNCH_NAME ),
								   "param_name" => "btn_link1",
								   "description" => __('Enter The Button Link to Show.', BUNCH_NAME ),
								   'group' => esc_html__('Organic', BUNCH_NAME)
								),
								array(
								   "type" => "textfield",
								   "holder" => "div",
								   "class" => "",
								   "heading" => __('Title', BUNCH_NAME ),
								   "param_name" => "title2",
								   "description" => __('Enter The Title to Show.', BUNCH_NAME ),
								   'group' => esc_html__('Best Quality Products', BUNCH_NAME)
								),
								array(
								   "type" => "textarea",
								   "holder" => "div",
								   "class" => "",
								   "heading" => __('Text', BUNCH_NAME ),
								   "param_name" => "text2",
								   "description" => __('Enter The Section Text to Show.', BUNCH_NAME ),
								   'group' => esc_html__('Best Quality Products', BUNCH_NAME)
								),
								/*array(
								   "type" => "textfield",
								   "holder" => "div",
								   "class" => "",
								   "heading" => __('Button Link', BUNCH_NAME ),
								   "param_name" => "btn_link2",
								   "description" => __('Enter The Button Link to Show.', BUNCH_NAME ),
								   'group' => esc_html__('Best Quality Products', BUNCH_NAME)
								),*/
							)
						);
$bunch_sc['bunch_about_form'] = array(
			"name" => __("About Form", BUNCH_NAME),
			"base" => "bunch_about_form",
			"class" => "",
			"category" => __('Warsaw', BUNCH_NAME),
			"icon" => 'icon-wpb-layer-shape-text' ,
			'description' => __('Show About Form', BUNCH_NAME),
			"params" => array(
						array(
						   "type" => "textfield",
						   "holder" => "div",
						   "class" => "",
						   "heading" => __('Title', BUNCH_NAME ),
						   "param_name" => "title",
						   "description" => __('Enter The Title to Show.', BUNCH_NAME ),
						),
						array(
						   "type" => "attach_image",
						   "holder" => "div",
						   "class" => "",
						   "heading" => __("Image", BUNCH_NAME),
						   "param_name" => "image",
						   "description" => __("Enter the Section Image to show.", BUNCH_NAME)
						),
						// params group
			            array(
			                'type' => 'param_group',
			                'value' => '',
			                'param_name' => 'about_form',
							'group' => esc_html__('About Our Form', BUNCH_NAME),
			                'params' => array(
										array(
											'type' => 'textfield',
											'value' => '',
											'heading' => esc_html__('Year', BUNCH_NAME ),
											'param_name' => 'year',
										),
										array(
											'type' => 'textfield',
											'value' => '',
											'heading' => esc_html__('Title', BUNCH_NAME ),
											'param_name' => 'title1',
										),
										array(
											'type' => 'textarea',
											'value' => '',
											'heading' => esc_html__('Text', BUNCH_NAME ),
											'param_name' => 'text',
										),
										array(
											'type' => 'textfield',
											'value' => '',
											'heading' => esc_html__('Author Name', BUNCH_NAME ),
											'param_name' => 'author_name',
										),
									)
								),
							)
						);
$bunch_sc['bunch_free_shipping']	=	array(
					"name" => __("Free Shipping", BUNCH_NAME),
					"base" => "bunch_free_shipping",
					"class" => "",
					"category" => __('Warsaw', BUNCH_NAME),
					"icon" => 'fa-briefcase' ,
					'description' => __('Show Healthy Form.', BUNCH_NAME),
					"params" => array(
								array(
								   "type" => "attach_image",
								   "holder" => "div",
								   "class" => "",
								   "heading" => __("Background Image", BUNCH_NAME),
								   "param_name" => "bg_img",
								   "description" => __("Enter the Section Image to show.", BUNCH_NAME),
								),
								array(
								   "type" => "attach_image",
								   "holder" => "div",
								   "class" => "",
								   "heading" => __("Image", BUNCH_NAME),
								   "param_name" => "image1",
								   "description" => __("Enter the Section Image to show.", BUNCH_NAME),
									'group' => esc_html__('left Side', BUNCH_NAME)
								),
								array(
								   "type" => "textfield",
								   "holder" => "div",
								   "class" => "",
								   "heading" => __('Title', BUNCH_NAME ),
								   "param_name" => "title1",
								   "description" => __('Enter The Title to Show.', BUNCH_NAME ),
								   'group' => esc_html__('left Side', BUNCH_NAME)
								),
								array(
								   "type" => "textfield",
								   "holder" => "div",
								   "class" => "",
								   "heading" => __('Sub Title', BUNCH_NAME ),
								   "param_name" => "sub_title1",
								   "description" => __('Enter The Sub Title to Show.', BUNCH_NAME ),
								   'group' => esc_html__('left Side', BUNCH_NAME)
								),
								array(
								   "type" => "textfield",
								   "holder" => "div",
								   "class" => "",
								   "heading" => __('Button Link', BUNCH_NAME ),
								   "param_name" => "btn_link1",
								   "description" => __('Enter The Button Link to Show.', BUNCH_NAME ),
								   'group' => esc_html__('left Side', BUNCH_NAME)
								),
								array(
								   "type" => "attach_image",
								   "holder" => "div",
								   "class" => "",
								   "heading" => __("Image", BUNCH_NAME),
								   "param_name" => "image2",
								   "description" => __("Enter the Section Image to show.", BUNCH_NAME),
								   'group' => esc_html__('Right Side', BUNCH_NAME)
								),
								array(
								   "type" => "textfield",
								   "holder" => "div",
								   "class" => "",
								   "heading" => __('Title', BUNCH_NAME ),
								   "param_name" => "title2",
								   "description" => __('Enter The Title to Show.', BUNCH_NAME ),
								   'group' => esc_html__('Right Side', BUNCH_NAME)
								),
								array(
								   "type" => "textfield",
								   "holder" => "div",
								   "class" => "",
								   "heading" => __('Sub title', BUNCH_NAME ),
								   "param_name" => "sub_title2",
								   "description" => __('Enter The Sub Title to Show.', BUNCH_NAME ),
								   'group' => esc_html__('Right Side', BUNCH_NAME)
								),
								array(
								   "type" => "textfield",
								   "holder" => "div",
								   "class" => "",
								   "heading" => __('Button Link', BUNCH_NAME ),
								   "param_name" => "btn_link2",
								   "description" => __('Enter The Button Link to Show.', BUNCH_NAME ),
								   'group' => esc_html__('Right Side', BUNCH_NAME)
								),
							)
						);
$bunch_sc['bunch_our_team']	=	array(
					"name" => __("Our Team", BUNCH_NAME),
					"base" => "bunch_our_team",
					"class" => "",
					"category" => __('Warsaw', BUNCH_NAME),
					"icon" => 'fa-briefcase' ,
					'description' => __('Show Our Team.', BUNCH_NAME),
					"params" => array(
								array(
								   "type" => "textfield",
								   "holder" => "div",
								   "class" => "",
								   "heading" => __('Title', BUNCH_NAME ),
								   "param_name" => "title",
								   "description" => __('Enter The Title to Show.', BUNCH_NAME ),
								),
								array(
								   "type" => "attach_image",
								   "holder" => "div",
								   "class" => "",
								   "heading" => __("Image", BUNCH_NAME),
								   "param_name" => "image1",
								   "description" => __("Enter the Section Image to show.", BUNCH_NAME),
								   'group' => esc_html__('Team Member One', BUNCH_NAME)
								),
								array(
								   "type" => "textfield",
								   "holder" => "div",
								   "class" => "",
								   "heading" => __('External Link', BUNCH_NAME ),
								   "param_name" => "btn_link1",
								   "description" => __('Enter The External link to Show.', BUNCH_NAME ),
								   'group' => esc_html__('Team Member One', BUNCH_NAME)
								),
								array(
								   "type" => "textfield",
								   "holder" => "div",
								   "class" => "",
								   "heading" => __('Title', BUNCH_NAME ),
								   "param_name" => "title1",
								   "description" => __('Enter The Title to Show.', BUNCH_NAME ),
								   'group' => esc_html__('Team Member One', BUNCH_NAME)
								),
								array(
								   "type" => "textarea",
								   "holder" => "div",
								   "class" => "",
								   "heading" => __('Text', BUNCH_NAME ),
								   "param_name" => "text1",
								   "description" => __('Enter The Text to Show.', BUNCH_NAME ),
								   'group' => esc_html__('Team Member One', BUNCH_NAME)
								),
								array(
								   "type" => "textfield",
								   "holder" => "div",
								   "class" => "",
								   "heading" => __('Facebook', BUNCH_NAME ),
								   "param_name" => "facebook",
								   "description" => __('Enter The Facebook to Show.', BUNCH_NAME ),
								   'group' => esc_html__('Team Member One', BUNCH_NAME)
								),
								array(
								   "type" => "textfield",
								   "holder" => "div",
								   "class" => "",
								   "heading" => __('Twitter', BUNCH_NAME ),
								   "param_name" => "twitter",
								   "description" => __('Enter The Twitter to Show.', BUNCH_NAME ),
								   'group' => esc_html__('Team Member One', BUNCH_NAME)
								),
								array(
								   "type" => "textfield",
								   "holder" => "div",
								   "class" => "",
								   "heading" => __('Instagram', BUNCH_NAME ),
								   "param_name" => "instagram",
								   "description" => __('Enter The Instagram to Show.', BUNCH_NAME ),
								   'group' => esc_html__('Team Member One', BUNCH_NAME)
								),
								array(
								   "type" => "textfield",
								   "holder" => "div",
								   "class" => "",
								   "heading" => __('Skype', BUNCH_NAME ),
								   "param_name" => "skype",
								   "description" => __('Enter The Skype to Show.', BUNCH_NAME ),
								   'group' => esc_html__('Team Member One', BUNCH_NAME)
								),
								array(
								   "type" => "textfield",
								   "holder" => "div",
								   "class" => "",
								   "heading" => __('Vimeo Square', BUNCH_NAME ),
								   "param_name" => "vimeo",
								   "description" => __('Enter The Vimeo Square to Show.', BUNCH_NAME ),
								   'group' => esc_html__('Team Member One', BUNCH_NAME)
								),
								array(
								   "type" => "attach_image",
								   "holder" => "div",
								   "class" => "",
								   "heading" => __("Image", BUNCH_NAME),
								   "param_name" => "image2",
								   "description" => __("Enter the Section Image to show.", BUNCH_NAME),
								   'group' => esc_html__('Team Member Two', BUNCH_NAME)
								),
								array(
								   "type" => "textfield",
								   "holder" => "div",
								   "class" => "",
								   "heading" => __('External Link', BUNCH_NAME ),
								   "param_name" => "btn_link2",
								   "description" => __('Enter The External Link to Show.', BUNCH_NAME ),
								   'group' => esc_html__('Team Member Two', BUNCH_NAME)
								),
								array(
								   "type" => "textfield",
								   "holder" => "div",
								   "class" => "",
								   "heading" => __('Title', BUNCH_NAME ),
								   "param_name" => "title2",
								   "description" => __('Enter The Title to Show.', BUNCH_NAME ),
								   'group' => esc_html__('Team Member Two', BUNCH_NAME)
								),
								array(
								   "type" => "textarea",
								   "holder" => "div",
								   "class" => "",
								   "heading" => __('Text', BUNCH_NAME ),
								   "param_name" => "text2",
								   "description" => __('Enter The Text to Show.', BUNCH_NAME ),
								   'group' => esc_html__('Team Member Two', BUNCH_NAME)
								),
								array(
								   "type" => "textfield",
								   "holder" => "div",
								   "class" => "",
								   "heading" => __('Facebook', BUNCH_NAME ),
								   "param_name" => "facebook1",
								   "description" => __('Enter The Facebook to Show.', BUNCH_NAME ),
								   'group' => esc_html__('Team Member Two', BUNCH_NAME)
								),
								array(
								   "type" => "textfield",
								   "holder" => "div",
								   "class" => "",
								   "heading" => __('Twitter', BUNCH_NAME ),
								   "param_name" => "twitter1",
								   "description" => __('Enter The Twitter to Show.', BUNCH_NAME ),
								   'group' => esc_html__('Team Member Two', BUNCH_NAME)
								),
								array(
								   "type" => "textfield",
								   "holder" => "div",
								   "class" => "",
								   "heading" => __('Instagram', BUNCH_NAME ),
								   "param_name" => "instagram1",
								   "description" => __('Enter The Instagram to Show.', BUNCH_NAME ),
								   'group' => esc_html__('Team Member Two', BUNCH_NAME)
								),
								array(
								   "type" => "textfield",
								   "holder" => "div",
								   "class" => "",
								   "heading" => __('Skype', BUNCH_NAME ),
								   "param_name" => "skype1",
								   "description" => __('Enter The Skype to Show.', BUNCH_NAME ),
								   'group' => esc_html__('Team Member Two', BUNCH_NAME)
								),
								array(
								   "type" => "textfield",
								   "holder" => "div",
								   "class" => "",
								   "heading" => __('Vimeo Square', BUNCH_NAME ),
								   "param_name" => "vimeo1",
								   "description" => __('Enter The Vimeo Square to Show.', BUNCH_NAME ),
								   'group' => esc_html__('Team Member Two', BUNCH_NAME)
								),
								array(
								   "type" => "attach_image",
								   "holder" => "div",
								   "class" => "",
								   "heading" => __("Image", BUNCH_NAME),
								   "param_name" => "image3",
								   "description" => __("Enter the Section Image to show.", BUNCH_NAME),
								   'group' => esc_html__('Team Member Three', BUNCH_NAME)
								),
								array(
								   "type" => "textfield",
								   "holder" => "div",
								   "class" => "",
								   "heading" => __('External Link', BUNCH_NAME ),
								   "param_name" => "btn_link3",
								   "description" => __('Enter The External Link to Show.', BUNCH_NAME ),
								   'group' => esc_html__('Team Member Three', BUNCH_NAME)
								),
								array(
								   "type" => "textfield",
								   "holder" => "div",
								   "class" => "",
								   "heading" => __('Title', BUNCH_NAME ),
								   "param_name" => "title3",
								   "description" => __('Enter The Title to Show.', BUNCH_NAME ),
								   'group' => esc_html__('Team Member Three', BUNCH_NAME)
								),
								array(
								   "type" => "textarea",
								   "holder" => "div",
								   "class" => "",
								   "heading" => __('Text', BUNCH_NAME ),
								   "param_name" => "text3",
								   "description" => __('Enter The Text to Show.', BUNCH_NAME ),
								   'group' => esc_html__('Team Member Three', BUNCH_NAME)
								),
								array(
								   "type" => "textfield",
								   "holder" => "div",
								   "class" => "",
								   "heading" => __('Facebook', BUNCH_NAME ),
								   "param_name" => "facebook2",
								   "description" => __('Enter The Facebook to Show.', BUNCH_NAME ),
								   'group' => esc_html__('Team Member Three', BUNCH_NAME)
								),
								array(
								   "type" => "textfield",
								   "holder" => "div",
								   "class" => "",
								   "heading" => __('Twitter', BUNCH_NAME ),
								   "param_name" => "twitter2",
								   "description" => __('Enter The Twitter to Show.', BUNCH_NAME ),
								   'group' => esc_html__('Team Member Three', BUNCH_NAME)
								),
								array(
								   "type" => "textfield",
								   "holder" => "div",
								   "class" => "",
								   "heading" => __('Instagram', BUNCH_NAME ),
								   "param_name" => "instagram2",
								   "description" => __('Enter The Instagram to Show.', BUNCH_NAME ),
								   'group' => esc_html__('Team Member Three', BUNCH_NAME)
								),
								array(
								   "type" => "textfield",
								   "holder" => "div",
								   "class" => "",
								   "heading" => __('Skype', BUNCH_NAME ),
								   "param_name" => "skype2",
								   "description" => __('Enter The Skype to Show.', BUNCH_NAME ),
								   'group' => esc_html__('Team Member Three', BUNCH_NAME)
								),
								array(
								   "type" => "textfield",
								   "holder" => "div",
								   "class" => "",
								   "heading" => __('Vimeo Square', BUNCH_NAME ),
								   "param_name" => "vimeo2",
								   "description" => __('Enter The Vimeo Square to Show.', BUNCH_NAME ),
								   'group' => esc_html__('Team Member Three', BUNCH_NAME)
								),
								array(
									"type" => "checkbox",
									"holder" => "div",
									"class" => "",
									"heading" => __('Style Two', BUNCH_NAME ),
									"param_name" => "style_two",
									'value' => array(__('Style Two for change the Padding Top', BUNCH_NAME )=>true),
									"description" => __('Choose whether you want to show The Padding Top', BUNCH_NAME )
								),
							)
						);
$bunch_sc['bunch_our_testimonials']	=	array(
					"name" => __("Our Testimonials", BUNCH_NAME),
					"base" => "bunch_our_testimonials",
					"class" => "",
					"category" => __('Warsaw', BUNCH_NAME),
					"icon" => 'fa-briefcase' ,
					'description' => __('Show Our Testimonials.', BUNCH_NAME),
					"params" => array(
								array(
								   "type" => "attach_image",
								   "holder" => "div",
								   "class" => "",
								   "heading" => __("Background Image", BUNCH_NAME),
								   "param_name" => "bg_img",
								   "description" => __("Enter the Section Image to show.", BUNCH_NAME),
								),
								array(
								   "type" => "textfield",
								   "holder" => "div",
								   "class" => "",
								   "heading" => __('Text Limit', BUNCH_NAME ),
								   "param_name" => "text_limit",
								   "description" => __('Enter The Limit Of Text to Show.', BUNCH_NAME )
								),
								array(
								   "type" => "textfield",
								   "holder" => "div",
								   "class" => "",
								   "heading" => __('Number', BUNCH_NAME ),
								   "param_name" => "num",
								   "description" => __('Enter Number of Items to Show.', BUNCH_NAME )
								),
								array(
								   "type" => "dropdown",
								   "holder" => "div",
								   "class" => "",
								   "heading" => __( 'Category', BUNCH_NAME ),
								   "param_name" => "cat",
								   "value" => array_flip( (array)bunch_get_categories( array( 'taxonomy' => 'testimonials_category', 'hide_empty' => FALSE ), true ) ),
								   "description" => __( 'Choose Category.', BUNCH_NAME )
								),
								array(
								   "type" => "dropdown",
								   "holder" => "div",
								   "class" => "",
								   "heading" => __("Order By", BUNCH_NAME),
								   "param_name" => "sort",
								   'value' => array_flip( array('select'=>__('Select Options', BUNCH_NAME),'date'=>__('Date', BUNCH_NAME),'title'=>__('Title', BUNCH_NAME) ,'name'=>__('Name', BUNCH_NAME) ,'author'=>__('Author', BUNCH_NAME),'comment_count' =>__('Comment Count', BUNCH_NAME),'random' =>__('Random', BUNCH_NAME) ) ),			
								   "description" => __("Enter the sorting order.", BUNCH_NAME)
								),
								array(
								   "type" => "dropdown",
								   "holder" => "div",
								   "class" => "",
								   "heading" => __("Order", BUNCH_NAME),
								   "param_name" => "order",
								   'value' => array_flip(array('select'=>__('Select Options', BUNCH_NAME),'ASC'=>__('Ascending', BUNCH_NAME),'DESC'=>__('Descending', BUNCH_NAME) ) ),			
								   "description" => __("Enter the sorting order.", BUNCH_NAME)
								),
							)
						);								
$bunch_sc['bunch_our_partners'] = array(
			"name" => __("Our Partners", BUNCH_NAME),
			"base" => "bunch_our_partners",
			"class" => "",
			"category" => __('Warsaw', BUNCH_NAME),
			"icon" => 'icon-wpb-layer-shape-text' ,
			'description' => __('Show Our Partners', BUNCH_NAME),
			"params" => array(
						array(
						   "type" => "textfield",
						   "holder" => "div",
						   "class" => "",
						   "heading" => __('Title', BUNCH_NAME ),
						   "param_name" => "title",
						   "description" => __('Enter The Title to Show.', BUNCH_NAME ),
						),
						array(
							"type" => "checkbox",
							"holder" => "div",
							"class" => "",
							"heading" => __('Style Two', BUNCH_NAME ),
							"param_name" => "style_two",
							'value' => array(__('Style Two for change the Title', BUNCH_NAME )=>true),
							"description" => __('Choose whether you want to show The Title', BUNCH_NAME )
						),
						// params group
			            array(
			                'type' => 'param_group',
			                'value' => '',
			                'param_name' => 'partners',
							'group' => esc_html__('Our Partners', BUNCH_NAME),
			                'params' => array(
										array(
										   "type" => "attach_image",
										   "holder" => "div",
										   "heading" => __("Image", BUNCH_NAME),
										   "param_name" => "image",
										),
										array(
											'type' => 'textfield',
											'value' => '',
											'heading' => esc_html__('External Link', BUNCH_NAME ),
											'param_name' => 'ext_url',
										),
									)
								),
							)
						);
$bunch_sc['bunch_google_map']	=	array(
					"name" => __("Google map", BUNCH_NAME),
					"base" => "bunch_google_map",
					"class" => "",
					"category" => __('Warsaw', BUNCH_NAME),
					"icon" => 'fa-briefcase' ,
					'description' => __('Show Google map.', BUNCH_NAME),
					"params" => array(
								array(
								   "type" => "attach_image",
								   "holder" => "div",
								   "class" => "",
								   "heading" => __("Background Image", BUNCH_NAME),
								   "param_name" => "bg_img",
								   "description" => __("Enter the Section Image to show.", BUNCH_NAME),
									'group' => esc_html__('Newsletter', BUNCH_NAME),
								),
								array(
								   "type" => "textfield",
								   "holder" => "div",
								   "class" => "",
								   "heading" => __('Title', BUNCH_NAME ),
								   "param_name" => "title",
								   "description" => __('Enter The Title to Show.', BUNCH_NAME ),
								   'group' => esc_html__('Newsletter', BUNCH_NAME),
								),
								array(
								   "type" => "textfield",
								   "holder" => "div",
								   "class" => "",
								   "heading" => __('Sub Title', BUNCH_NAME ),
								   "param_name" => "sub_title",
								   "description" => __('Enter The Sub Title to Show.', BUNCH_NAME ),
								   'group' => esc_html__('Newsletter', BUNCH_NAME),
								),
								array(
									"type" => "textfield",
									"holder" => "div",
									"class" => "",
									"heading" => __('FeedBurner ID', BUNCH_NAME ),
									"param_name" => "id",
									'value' => 'themeforest',
									"description" => __('Enter feedburner id for newsletter', BUNCH_NAME ),
									'group' => esc_html__('Newsletter', BUNCH_NAME),
								),
								array(
								   "type" => "textfield",
								   "holder" => "div",
								   "class" => "",
								   "heading" => __('Lattitude', BUNCH_NAME ),
								   "param_name" => "lat",
								   "description" => __('Enter Lattitude for map', BUNCH_NAME ),
								   "default" => '23.815811',
								   'group' => esc_html__('Google Map Info', BUNCH_NAME),
								),
								array(
								   "type" => "textfield",
								   "holder" => "div",
								   "class" => "",
								   "heading" => __('Longitude', BUNCH_NAME ),
								   "param_name" => "long",
								   "description" => __('Enter Longitude for map', BUNCH_NAME ),
								   "default" => '90.412580',
								   'group' => esc_html__('Google Map Info', BUNCH_NAME),
								),
								array(
								   "type" => "textfield",
								   "holder" => "div",
								   "class" => "",
								   "heading" => __('Mark Title', BUNCH_NAME ),
								   "param_name" => "mark_title",
								   "description" => __('Enter Mark Title for map', BUNCH_NAME ),
								   "default" => 'Dhaka',
								   'group' => esc_html__('Google Map Info', BUNCH_NAME),
								),
								array(
								   "type" => "textfield",
								   "holder" => "div",
								   "class" => "",
								   "heading" => __('Mark Address', BUNCH_NAME ),
								   "param_name" => "mark_address",
								   "description" => __('Enter Mark Address for map', BUNCH_NAME ),
								   "default" => ' Dhaka 1000-1200, Bangladesh ',
								   'group' => esc_html__('Google Map Info', BUNCH_NAME),
								),
								array(
								   "type" => "textfield",
								   "holder" => "div",
								   "class" => "",
								   "heading" => __('Email', BUNCH_NAME ),
								   "param_name" => "email",
								   "description" => __('Enter The Email for map', BUNCH_NAME ),
								   "default" => 'info@youremail.com',
								   'group' => esc_html__('Google Map Info', BUNCH_NAME),
								),
							)
						);
$bunch_sc['bunch_farm_products']	=	array(
					"name" => __("Fram Products", BUNCH_NAME),
					"base" => "bunch_farm_products",
					"class" => "",
					"category" => __('Warsaw', BUNCH_NAME),
					"icon" => 'fa-briefcase' ,
					'description' => __('Show Fram Products.', BUNCH_NAME),
					"params" => array(
								array(
								   "type" => "textfield",
								   "holder" => "div",
								   "class" => "",
								   "heading" => __('Title', BUNCH_NAME ),
								   "param_name" => "title",
								   "description" => __('Enter The Title to Show.', BUNCH_NAME ),
								   'group' => esc_html__('Newsletter', BUNCH_NAME),
								),
								array(
								   "type" => "attach_image",
								   "holder" => "div",
								   "class" => "",
								   "heading" => __("Background Image", BUNCH_NAME),
								   "param_name" => "bg_img1",
								   "description" => __("Enter the Section Image to show.", BUNCH_NAME),
								   'group' => esc_html__('Save Info', BUNCH_NAME),
								),
								array(
								   "type" => "textfield",
								   "holder" => "div",
								   "class" => "",
								   "heading" => __('Title', BUNCH_NAME ),
								   "param_name" => "title1",
								   "description" => __('Enter The Title to Show.', BUNCH_NAME ),
								   'group' => esc_html__('Save Info', BUNCH_NAME),
								),
								array(
								   "type" => "textfield",
								   "holder" => "div",
								   "class" => "",
								   "heading" => __('Price', BUNCH_NAME ),
								   "param_name" => "price1",
								   "description" => __('Enter The Price to Show.', BUNCH_NAME ),
								   'group' => esc_html__('Save Info', BUNCH_NAME),
								),
								array(
								   "type" => "textfield",
								   "holder" => "div",
								   "class" => "",
								   "heading" => __('External Link', BUNCH_NAME ),
								   "param_name" => "btn_link1",
								   "description" => __('Enter The External Link to Show.', BUNCH_NAME ),
								   'group' => esc_html__('Save Info', BUNCH_NAME),
								),
								array(
								   "type" => "attach_image",
								   "holder" => "div",
								   "class" => "",
								   "heading" => __("Image", BUNCH_NAME),
								   "param_name" => "image1",
								   "description" => __("Enter the Section Image to show.", BUNCH_NAME),
								   'group' => esc_html__('Save Info', BUNCH_NAME),
								),
								array(
								   "type" => "attach_image",
								   "holder" => "div",
								   "class" => "",
								   "heading" => __("Background Image", BUNCH_NAME),
								   "param_name" => "bg_img2",
								   "description" => __("Enter the Section Image to show.", BUNCH_NAME),
								   'group' => esc_html__('Fresh Products', BUNCH_NAME),
								),
								array(
								   "type" => "textfield",
								   "holder" => "div",
								   "class" => "",
								   "heading" => __('Title', BUNCH_NAME ),
								   "param_name" => "title2",
								   "description" => __('Enter The Title to Show.', BUNCH_NAME ),
								   'group' => esc_html__('Fresh Products', BUNCH_NAME),
								),
								array(
								   "type" => "textfield",
								   "holder" => "div",
								   "class" => "",
								   "heading" => __('Price', BUNCH_NAME ),
								   "param_name" => "price2",
								   "description" => __('Enter The Price to Show.', BUNCH_NAME ),
								   'group' => esc_html__('Fresh Products', BUNCH_NAME),
								),
								array(
								   "type" => "textfield",
								   "holder" => "div",
								   "class" => "",
								   "heading" => __('External Link', BUNCH_NAME ),
								   "param_name" => "btn_link2",
								   "description" => __('Enter The External Link to Show.', BUNCH_NAME ),
								   'group' => esc_html__('Fresh Products', BUNCH_NAME),
								),
								array(
								   "type" => "attach_image",
								   "holder" => "div",
								   "class" => "",
								   "heading" => __("Image", BUNCH_NAME),
								   "param_name" => "image2",
								   "description" => __("Enter the Section Image to show.", BUNCH_NAME),
								   'group' => esc_html__('Fresh Products', BUNCH_NAME),
								),
								array(
								   "type" => "attach_image",
								   "holder" => "div",
								   "class" => "",
								   "heading" => __("Background Image", BUNCH_NAME),
								   "param_name" => "bg_img3",
								   "description" => __("Enter the Section Image to show.", BUNCH_NAME),
								   'group' => esc_html__('Free Shipping', BUNCH_NAME),
								),
								array(
								   "type" => "textfield",
								   "holder" => "div",
								   "class" => "",
								   "heading" => __('Title', BUNCH_NAME ),
								   "param_name" => "title3",
								   "description" => __('Enter The Title to Show.', BUNCH_NAME ),
								   'group' => esc_html__('Free Shipping', BUNCH_NAME),
								),
								array(
								   "type" => "textfield",
								   "holder" => "div",
								   "class" => "",
								   "heading" => __('Price', BUNCH_NAME ),
								   "param_name" => "price3",
								   "description" => __('Enter The Price to Show.', BUNCH_NAME ),
								   'group' => esc_html__('Free Shipping', BUNCH_NAME),
								),
								array(
								   "type" => "textfield",
								   "holder" => "div",
								   "class" => "",
								   "heading" => __('External Link', BUNCH_NAME ),
								   "param_name" => "btn_link3",
								   "description" => __('Enter The External Link to Show.', BUNCH_NAME ),
								   'group' => esc_html__('Free Shipping', BUNCH_NAME),
								),
								array(
								   "type" => "attach_image",
								   "holder" => "div",
								   "class" => "",
								   "heading" => __("Image", BUNCH_NAME),
								   "param_name" => "image3",
								   "description" => __("Enter the Section Image to show.", BUNCH_NAME),
								   'group' => esc_html__('Free Shipping', BUNCH_NAME),
								),
							)
						);
$bunch_sc['bunch_our_features']	=	array(
					"name" => __("Our Features", BUNCH_NAME),
					"base" => "bunch_our_features",
					"class" => "",
					"category" => __('Warsaw', BUNCH_NAME),
					"icon" => 'fa-briefcase' ,
					'description' => __('Show Our Features.', BUNCH_NAME),
					"params" => array(
								array(
								   "type" => "attach_image",
								   "holder" => "div",
								   "class" => "",
								   "heading" => __("Image", BUNCH_NAME),
								   "param_name" => "image",
								   "description" => __("Enter the Section Image to show.", BUNCH_NAME),
								),
								array(
								   "type" => "textfield",
								   "holder" => "div",
								   "class" => "",
								   "heading" => __('Upper Title', BUNCH_NAME ),
								   "param_name" => "sub_title",
								   "description" => __('Enter The Upper Title to Show.', BUNCH_NAME ),
								),
								array(
								   "type" => "textfield",
								   "holder" => "div",
								   "class" => "",
								   "heading" => __('Title', BUNCH_NAME ),
								   "param_name" => "title",
								   "description" => __('Enter The Title to Show.', BUNCH_NAME ),
								),
								array(
								   "type" => "textarea",
								   "holder" => "div",
								   "class" => "",
								   "heading" => __('Price', BUNCH_NAME ),
								   "param_name" => "content",
								   "description" => __('Enter The Price to Show.', BUNCH_NAME ),
								),
								array(
								   "type" => "textarea",
								   "holder" => "div",
								   "class" => "",
								   "heading" => __('Text', BUNCH_NAME ),
								   "param_name" => "text",
								   "description" => __('Enter The Text to Show.', BUNCH_NAME ),
								),
								array(
								   "type" => "textfield",
								   "holder" => "div",
								   "class" => "",
								   "heading" => __('Date', BUNCH_NAME ),
								   "param_name" => "date",
								   "description" => __('Enter The Date Pattern(year/Month/day) to Show.', BUNCH_NAME ),
								),
								array(
								   "type" => "textfield",
								   "holder" => "div",
								   "class" => "",
								   "heading" => __('Button Link', BUNCH_NAME ),
								   "param_name" => "btn_link",
								   "description" => __('Enter The Button Link to Show.', BUNCH_NAME ),
								),
							)
						);
$bunch_sc['bunch_our_gallery']	=	array(
					"name" => __("Our Gallery", BUNCH_NAME),
					"base" => "bunch_our_gallery",
					"class" => "",
					"category" => __('Warsaw', BUNCH_NAME),
					"icon" => 'fa-briefcase' ,
					'description' => __('Show Our Gallery.', BUNCH_NAME),
					"params" => array(
								array(
								   "type" => "textfield",
								   "holder" => "div",
								   "class" => "",
								   "heading" => __('Title', BUNCH_NAME ),
								   "param_name" => "title",
								   "description" => __('Enter The Title to Show.', BUNCH_NAME )
								),
								array(
								   "type" => "textfield",
								   "holder" => "div",
								   "class" => "",
								   "heading" => __('Number', BUNCH_NAME ),
								   "param_name" => "num",
								   "description" => __('Enter Number of Items to Show.', BUNCH_NAME )
								),
								array(
								   "type" => "dropdown",
								   "holder" => "div",
								   "class" => "",
								   "heading" => __( 'Category', BUNCH_NAME ),
								   "param_name" => "cat",
								   "value" => array_flip( (array)bunch_get_categories( array( 'taxonomy' => 'gallery_category', 'hide_empty' => FALSE ), true ) ),
								   "description" => __( 'Choose Category.', BUNCH_NAME )
								),
								array(
								   "type" => "dropdown",
								   "holder" => "div",
								   "class" => "",
								   "heading" => __("Order By", BUNCH_NAME),
								   "param_name" => "sort",
								   'value' => array_flip( array('select'=>__('Select Options', BUNCH_NAME),'date'=>__('Date', BUNCH_NAME),'title'=>__('Title', BUNCH_NAME) ,'name'=>__('Name', BUNCH_NAME) ,'author'=>__('Author', BUNCH_NAME),'comment_count' =>__('Comment Count', BUNCH_NAME),'random' =>__('Random', BUNCH_NAME) ) ),			
								   "description" => __("Enter the sorting order.", BUNCH_NAME)
								),
								array(
								   "type" => "dropdown",
								   "holder" => "div",
								   "class" => "",
								   "heading" => __("Order", BUNCH_NAME),
								   "param_name" => "order",
								   'value' => array_flip(array('select'=>__('Select Options', BUNCH_NAME),'ASC'=>__('Ascending', BUNCH_NAME),'DESC'=>__('Descending', BUNCH_NAME) ) ),			
								   "description" => __("Enter the sorting order.", BUNCH_NAME)
								),
								array(
								   "type" => "textfield",
								   "holder" => "div",
								   "class" => "",
								   "heading" => __('Button Text', BUNCH_NAME ),
								   "param_name" => "btn_text",
								   "description" => __('Enter The Button Text to Show.', BUNCH_NAME )
								),
								array(
								   "type" => "textfield",
								   "holder" => "div",
								   "class" => "",
								   "heading" => __('Button Link', BUNCH_NAME ),
								   "param_name" => "btn_link",
								   "description" => __('Enter The Button Link to Show.', BUNCH_NAME )
								),
							)
						);
$bunch_sc['bunch_latest_blog']	=	array(
					"name" => __("Latest Blog", BUNCH_NAME),
					"base" => "bunch_latest_blog",
					"class" => "",
					"category" => __('Warsaw', BUNCH_NAME),
					"icon" => 'fa-briefcase' ,
					'description' => __('Show Latest Blog.', BUNCH_NAME),
					"params" => array(
								array(
								   "type" => "textfield",
								   "holder" => "div",
								   "class" => "",
								   "heading" => __('Title', BUNCH_NAME ),
								   "param_name" => "title",
								   "description" => __('Enter The Title to Show.', BUNCH_NAME )
								),
								array(
								   "type" => "textfield",
								   "holder" => "div",
								   "class" => "",
								   "heading" => __('Text Limit', BUNCH_NAME ),
								   "param_name" => "text_limit",
								   "description" => __('Enter The Text Limit to Show.', BUNCH_NAME )
								),
								array(
								   "type" => "textfield",
								   "holder" => "div",
								   "class" => "",
								   "heading" => __('Number', BUNCH_NAME ),
								   "param_name" => "num",
								   "description" => __('Enter Number of Items to Show.', BUNCH_NAME )
								),
								array(
								   "type" => "dropdown",
								   "holder" => "div",
								   "class" => "",
								   "heading" => __( 'Category', BUNCH_NAME ),
								   "param_name" => "cat",
								   "value" => array_flip( (array)bunch_get_categories( array( 'taxonomy' => 'category', 'hide_empty' => FALSE ), true ) ),
								   "description" => __( 'Choose Category.', BUNCH_NAME )
								),
								array(
								   "type" => "dropdown",
								   "holder" => "div",
								   "class" => "",
								   "heading" => __("Order By", BUNCH_NAME),
								   "param_name" => "sort",
								   'value' => array_flip( array('select'=>__('Select Options', BUNCH_NAME),'date'=>__('Date', BUNCH_NAME),'title'=>__('Title', BUNCH_NAME) ,'name'=>__('Name', BUNCH_NAME) ,'author'=>__('Author', BUNCH_NAME),'comment_count' =>__('Comment Count', BUNCH_NAME),'random' =>__('Random', BUNCH_NAME) ) ),			
								   "description" => __("Enter the sorting order.", BUNCH_NAME)
								),
								array(
								   "type" => "dropdown",
								   "holder" => "div",
								   "class" => "",
								   "heading" => __("Order", BUNCH_NAME),
								   "param_name" => "order",
								   'value' => array_flip(array('select'=>__('Select Options', BUNCH_NAME),'ASC'=>__('Ascending', BUNCH_NAME),'DESC'=>__('Descending', BUNCH_NAME) ) ),			
								   "description" => __("Enter the sorting order.", BUNCH_NAME)
								),
							)
						);
$bunch_sc['bunch_call_to_action']	=	array(
					"name" => __("Call To Action", BUNCH_NAME),
					"base" => "bunch_call_to_action",
					"class" => "",
					"category" => __('Warsaw', BUNCH_NAME),
					"icon" => 'fa-briefcase' ,
					'description' => __('Show Call To Action.', BUNCH_NAME),
					"params" => array(
								array(
								   "type" => "attach_image",
								   "holder" => "div",
								   "class" => "",
								   "heading" => __("Background Image", BUNCH_NAME),
								   "param_name" => "bg_img",
								   "description" => __("Enter the Background Image to show.", BUNCH_NAME),
								),
								array(
								   "type" => "attach_image",
								   "holder" => "div",
								   "class" => "",
								   "heading" => __("Man Image", BUNCH_NAME),
								   "param_name" => "image1",
								   "description" => __("Enter the Man Image to show.", BUNCH_NAME),
								),
								array(
								   "type" => "attach_image",
								   "holder" => "div",
								   "class" => "",
								   "heading" => __("Fruit Image", BUNCH_NAME),
								   "param_name" => "image2",
								   "description" => __("Enter the fruit Image to show.", BUNCH_NAME),
								),
								array(
								   "type" => "textfield",
								   "holder" => "div",
								   "class" => "",
								   "heading" => __('Title', BUNCH_NAME ),
								   "param_name" => "title",
								   "description" => __('Enter The Title to Show.', BUNCH_NAME )
								),
								array(
								   "type" => "textfield",
								   "holder" => "div",
								   "class" => "",
								   "heading" => __('Phone No', BUNCH_NAME ),
								   "param_name" => "phone_no",
								   "description" => __('Enter The Phone No to Show.', BUNCH_NAME )
								),
								array(
								   "type" => "textarea",
								   "holder" => "div",
								   "class" => "",
								   "heading" => __('Text', BUNCH_NAME ),
								   "param_name" => "text",
								   "description" => __('Enter The Section Text to Show.', BUNCH_NAME )
								),
							)
						);
$bunch_sc['bunch_our_services']	=	array(
					"name" => __("Our Services", BUNCH_NAME),
					"base" => "bunch_our_services",
					"class" => "",
					"category" => __('Warsaw', BUNCH_NAME),
					"icon" => 'fa-briefcase' ,
					'description' => __('Show Our Services.', BUNCH_NAME),
					"params" => array(
								array(
								   "type" => "textfield",
								   "holder" => "div",
								   "class" => "",
								   "heading" => __('Title', BUNCH_NAME ),
								   "param_name" => "title",
								   "description" => __('Enter The Title to Show.', BUNCH_NAME )
								),
								array(
								   "type" => "textfield",
								   "holder" => "div",
								   "class" => "",
								   "heading" => __('Text Limit', BUNCH_NAME ),
								   "param_name" => "text_limit",
								   "description" => __('Enter The Limit Of Text to Show.', BUNCH_NAME )
								),
								array(
								   "type" => "textfield",
								   "holder" => "div",
								   "class" => "",
								   "heading" => __('Number', BUNCH_NAME ),
								   "param_name" => "num",
								   "description" => __('Enter Number of Items to Show.', BUNCH_NAME )
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
								   'value' => array_flip( array('select'=>__('Select Options', BUNCH_NAME),'date'=>__('Date', BUNCH_NAME),'title'=>__('Title', BUNCH_NAME) ,'name'=>__('Name', BUNCH_NAME) ,'author'=>__('Author', BUNCH_NAME),'comment_count' =>__('Comment Count', BUNCH_NAME),'random' =>__('Random', BUNCH_NAME) ) ),			
								   "description" => __("Enter the sorting order.", BUNCH_NAME)
								),
								array(
								   "type" => "dropdown",
								   "holder" => "div",
								   "class" => "",
								   "heading" => __("Order", BUNCH_NAME),
								   "param_name" => "order",
								   'value' => array_flip(array('select'=>__('Select Options', BUNCH_NAME),'ASC'=>__('Ascending', BUNCH_NAME),'DESC'=>__('Descending', BUNCH_NAME) ) ),			
								   "description" => __("Enter the sorting order.", BUNCH_NAME)
								),
							)
						);
$bunch_sc['bunch_gallery_masonry']	=	array(
					"name" => __("Gallery Masonry", BUNCH_NAME),
					"base" => "bunch_gallery_masonry",
					"class" => "",
					"category" => __('Warsaw', BUNCH_NAME),
					"icon" => 'fa-briefcase' ,
					'description' => __('Show Gallery Masonry.', BUNCH_NAME),
					"params" => array(
								array(
								   "type" => "textfield",
								   "holder" => "div",
								   "class" => "",
								   "heading" => __('Title', BUNCH_NAME ),
								   "param_name" => "title",
								   "description" => __('Enter The Title to Show.', BUNCH_NAME )
								),
								array(
								   "type" => "attach_image",
								   "holder" => "div",
								   "class" => "",
								   "heading" => __("Image", BUNCH_NAME),
								   "param_name" => "image",
								   "description" => __("Enter the Section Image to show.", BUNCH_NAME),
								),
								array(
								   "type" => "textfield",
								   "holder" => "div",
								   "class" => "",
								   "heading" => __('External Link', BUNCH_NAME ),
								   "param_name" => "btn_link",
								   "description" => __('Enter The External Link to Show.', BUNCH_NAME )
								),
								array(
								   "type" => "textfield",
								   "holder" => "div",
								   "class" => "",
								   "heading" => __('Number', BUNCH_NAME ),
								   "param_name" => "num",
								   "description" => __('Enter Number of Items to Show.', BUNCH_NAME )
								),
								array(
								   "type" => "dropdown",
								   "holder" => "div",
								   "class" => "",
								   "heading" => __( 'Category', BUNCH_NAME ),
								   "param_name" => "cat",
								   "value" => array_flip( (array)bunch_get_categories( array( 'taxonomy' => 'gallery_category', 'hide_empty' => FALSE ), true ) ),
								   "description" => __( 'Choose Category.', BUNCH_NAME )
								),
								array(
								   "type" => "dropdown",
								   "holder" => "div",
								   "class" => "",
								   "heading" => __("Order By", BUNCH_NAME),
								   "param_name" => "sort",
								   'value' => array_flip( array('select'=>__('Select Options', BUNCH_NAME),'date'=>__('Date', BUNCH_NAME),'title'=>__('Title', BUNCH_NAME) ,'name'=>__('Name', BUNCH_NAME) ,'author'=>__('Author', BUNCH_NAME),'comment_count' =>__('Comment Count', BUNCH_NAME),'random' =>__('Random', BUNCH_NAME) ) ),			
								   "description" => __("Enter the sorting order.", BUNCH_NAME)
								),
								array(
								   "type" => "dropdown",
								   "holder" => "div",
								   "class" => "",
								   "heading" => __("Order", BUNCH_NAME),
								   "param_name" => "order",
								   'value' => array_flip(array('select'=>__('Select Options', BUNCH_NAME),'ASC'=>__('Ascending', BUNCH_NAME),'DESC'=>__('Descending', BUNCH_NAME) ) ),			
								   "description" => __("Enter the sorting order.", BUNCH_NAME)
								),
							)
						);
$bunch_sc['bunch_free_shipping_2']	=	array(
					"name" => __("Free Shipping 2", BUNCH_NAME),
					"base" => "bunch_free_shipping_2",
					"class" => "",
					"category" => __('Warsaw', BUNCH_NAME),
					"icon" => 'fa-briefcase' ,
					'description' => __('Show Free Shipping 2.', BUNCH_NAME),
					"params" => array(
								array(
								   "type" => "attach_image",
								   "holder" => "div",
								   "class" => "",
								   "heading" => __("Background Image", BUNCH_NAME),
								   "param_name" => "bg_img",
								   "description" => __("Enter the Background Image to show.", BUNCH_NAME),
								),
								array(
								   "type" => "textfield",
								   "holder" => "div",
								   "class" => "",
								   "heading" => __('Title', BUNCH_NAME ),
								   "param_name" => "title1",
								   "description" => __('Enter The Title to Show.', BUNCH_NAME )
								),
								array(
								   "type" => "textfield",
								   "holder" => "div",
								   "class" => "",
								   "heading" => __('Price ', BUNCH_NAME ),
								   "param_name" => "price1",
								   "description" => __('Enter The Price to Show.', BUNCH_NAME )
								),
								array(
								   "type" => "attach_image",
								   "holder" => "div",
								   "class" => "",
								   "heading" => __("Image", BUNCH_NAME),
								   "param_name" => "image",
								   "description" => __("Enter the Image to show.", BUNCH_NAME),
								),
								array(
								   "type" => "textfield",
								   "holder" => "div",
								   "class" => "",
								   "heading" => __('Title', BUNCH_NAME ),
								   "param_name" => "title2",
								   "description" => __('Enter The Title to Show.', BUNCH_NAME )
								),
								array(
								   "type" => "textfield",
								   "holder" => "div",
								   "class" => "",
								   "heading" => __('Price ', BUNCH_NAME ),
								   "param_name" => "price2",
								   "description" => __('Enter The Price to Show.', BUNCH_NAME )
								),
							)
						);
$bunch_sc['bunch_fresh_products']	=	array(
					"name" => __("Fresh Products", BUNCH_NAME),
					"base" => "bunch_fresh_products",
					"class" => "",
					"category" => __('Warsaw', BUNCH_NAME),
					"icon" => 'fa-briefcase' ,
					'description' => __('Show Fresh Products.', BUNCH_NAME),
					"params" => array(
								array(
								   "type" => "attach_image",
								   "holder" => "div",
								   "class" => "",
								   "heading" => __("Background Image", BUNCH_NAME),
								   "param_name" => "bg_img1",
								   "description" => __("Enter the Background Image to show.", BUNCH_NAME),
								   'group' => esc_html__('Natural Fruits Product', BUNCH_NAME)
								),
								array(
								   "type" => "textfield",
								   "holder" => "div",
								   "class" => "",
								   "heading" => __('Upper Title', BUNCH_NAME ),
								   "param_name" => "upper_title1",
								   "description" => __('Enter The Upper Title to Show.', BUNCH_NAME ),
								   'group' => esc_html__('Natural Fruits Product', BUNCH_NAME)
								),
								array(
								   "type" => "textfield",
								   "holder" => "div",
								   "class" => "",
								   "heading" => __('Sub Title', BUNCH_NAME ),
								   "param_name" => "sub_title1",
								   "description" => __('Enter The Sub Title to Show.', BUNCH_NAME ),
								   'group' => esc_html__('Natural Fruits Product', BUNCH_NAME)
								),
								array(
								   "type" => "textfield",
								   "holder" => "div",
								   "class" => "",
								   "heading" => __('Title', BUNCH_NAME ),
								   "param_name" => "title1",
								   "description" => __('Enter The Title to Show.', BUNCH_NAME ),
								   'group' => esc_html__('Natural Fruits Product', BUNCH_NAME)
								),
								array(
								   "type" => "textfield",
								   "holder" => "div",
								   "class" => "",
								   "heading" => __('External Link', BUNCH_NAME ),
								   "param_name" => "btn_link1",
								   "description" => __('Enter The External Link to Show.', BUNCH_NAME ),
								   'group' => esc_html__('Natural Fruits Product', BUNCH_NAME)
								),
								array(
								   "type" => "attach_image",
								   "holder" => "div",
								   "class" => "",
								   "heading" => __("Fruit Image", BUNCH_NAME),
								   "param_name" => "fruit_img",
								   "description" => __("Enter the Fruit Image to show.", BUNCH_NAME),
								   'group' => esc_html__('Natural Fruits Product', BUNCH_NAME)
								),
								array(
								   "type" => "attach_image",
								   "holder" => "div",
								   "class" => "",
								   "heading" => __("Fruit Box Image One", BUNCH_NAME),
								   "param_name" => "image1",
								   "description" => __("Enter the Fruit Image Box First to show.", BUNCH_NAME),
								   'group' => esc_html__('Natural Fruits Product', BUNCH_NAME)
								),
								array(
								   "type" => "textfield",
								   "holder" => "div",
								   "class" => "",
								   "heading" => __('Fruit Title', BUNCH_NAME ),
								   "param_name" => "fruit_title1",
								   "description" => __('Enter The Title to Show.', BUNCH_NAME ),
								   'group' => esc_html__('Natural Fruits Product', BUNCH_NAME)
								),
								array(
								   "type" => "textfield",
								   "holder" => "div",
								   "class" => "",
								   "heading" => __('External Link', BUNCH_NAME ),
								   "param_name" => "ext_url1",
								   "description" => __('Enter The External Link to Show.', BUNCH_NAME ),
								   'group' => esc_html__('Natural Fruits Product', BUNCH_NAME)
								),
								array(
								   "type" => "textfield",
								   "holder" => "div",
								   "class" => "",
								   "heading" => __('Fruit Price', BUNCH_NAME ),
								   "param_name" => "price1",
								   "description" => __('Enter The Fruit Price to Show.', BUNCH_NAME ),
								   'group' => esc_html__('Natural Fruits Product', BUNCH_NAME)
								),
								array(
								   "type" => "attach_image",
								   "holder" => "div",
								   "class" => "",
								   "heading" => __("Fruit Box Image One", BUNCH_NAME),
								   "param_name" => "image2",
								   "description" => __("Enter the Fruit Image Box First to show.", BUNCH_NAME),
								   'group' => esc_html__('Natural Fruits Product', BUNCH_NAME)
								),
								array(
								   "type" => "textfield",
								   "holder" => "div",
								   "class" => "",
								   "heading" => __('Fruit Title', BUNCH_NAME ),
								   "param_name" => "fruit_title2",
								   "description" => __('Enter The Title to Show.', BUNCH_NAME ),
								   'group' => esc_html__('Natural Fruits Product', BUNCH_NAME)
								),
								array(
								   "type" => "textfield",
								   "holder" => "div",
								   "class" => "",
								   "heading" => __('External Link', BUNCH_NAME ),
								   "param_name" => "ext_url2",
								   "description" => __('Enter The External Link to Show.', BUNCH_NAME ),
								   'group' => esc_html__('Natural Fruits Product', BUNCH_NAME)
								),
								array(
								   "type" => "textfield",
								   "holder" => "div",
								   "class" => "",
								   "heading" => __('Fruit Price', BUNCH_NAME ),
								   "param_name" => "price2",
								   "description" => __('Enter The Fruit Price to Show.', BUNCH_NAME ),
								   'group' => esc_html__('Natural Fruits Product', BUNCH_NAME)
								),
								array(
								   "type" => "attach_image",
								   "holder" => "div",
								   "class" => "",
								   "heading" => __("Background Image", BUNCH_NAME),
								   "param_name" => "bg_img2",
								   "description" => __("Enter the Background Image to show.", BUNCH_NAME),
								   'group' => esc_html__('Natural Vegetables Product', BUNCH_NAME)
								),
								array(
								   "type" => "textfield",
								   "holder" => "div",
								   "class" => "",
								   "heading" => __('Upper Title', BUNCH_NAME ),
								   "param_name" => "upper_title2",
								   "description" => __('Enter The Upper Title to Show.', BUNCH_NAME ),
								   'group' => esc_html__('Natural Vegetables Product', BUNCH_NAME)
								),
								array(
								   "type" => "textfield",
								   "holder" => "div",
								   "class" => "",
								   "heading" => __('Sub Title', BUNCH_NAME ),
								   "param_name" => "sub_title2",
								   "description" => __('Enter The Sub Title to Show.', BUNCH_NAME ),
								   'group' => esc_html__('Natural Vegetables Product', BUNCH_NAME)
								),
								array(
								   "type" => "textfield",
								   "holder" => "div",
								   "class" => "",
								   "heading" => __('Title', BUNCH_NAME ),
								   "param_name" => "title2",
								   "description" => __('Enter The Title to Show.', BUNCH_NAME ),
								   'group' => esc_html__('Natural Vegetables Product', BUNCH_NAME)
								),
								array(
								   "type" => "textfield",
								   "holder" => "div",
								   "class" => "",
								   "heading" => __('External Link', BUNCH_NAME ),
								   "param_name" => "btn_link2",
								   "description" => __('Enter The External Link to Show.', BUNCH_NAME ),
								   'group' => esc_html__('Natural Vegetables Product', BUNCH_NAME)
								),
								array(
								   "type" => "attach_image",
								   "holder" => "div",
								   "class" => "",
								   "heading" => __("Vegetables Image", BUNCH_NAME),
								   "param_name" => "vegi_img",
								   "description" => __("Enter the Vegetables Image to show.", BUNCH_NAME),
								   'group' => esc_html__('Natural Vegetables Product', BUNCH_NAME)
								),
								array(
								   "type" => "attach_image",
								   "holder" => "div",
								   "class" => "",
								   "heading" => __("Vegetables Box Image One", BUNCH_NAME),
								   "param_name" => "image3",
								   "description" => __("Enter the Vegetables Image Box First to show.", BUNCH_NAME),
								   'group' => esc_html__('Natural Vegetables Product', BUNCH_NAME)
								),
								array(
								   "type" => "textfield",
								   "holder" => "div",
								   "class" => "",
								   "heading" => __('Vegetables Title', BUNCH_NAME ),
								   "param_name" => "vegi_title1",
								   "description" => __('Enter The Title to Show.', BUNCH_NAME ),
								   'group' => esc_html__('Natural Vegetables Product', BUNCH_NAME)
								),
								array(
								   "type" => "textfield",
								   "holder" => "div",
								   "class" => "",
								   "heading" => __('External Link', BUNCH_NAME ),
								   "param_name" => "ext_url3",
								   "description" => __('Enter The External Link to Show.', BUNCH_NAME ),
								   'group' => esc_html__('Natural Vegetables Product', BUNCH_NAME)
								),
								array(
								   "type" => "textfield",
								   "holder" => "div",
								   "class" => "",
								   "heading" => __('Vegetables Price', BUNCH_NAME ),
								   "param_name" => "price3",
								   "description" => __('Enter The Vegetables Price to Show.', BUNCH_NAME ),
								   'group' => esc_html__('Natural Vegetables Product', BUNCH_NAME)
								),
								array(
								   "type" => "attach_image",
								   "holder" => "div",
								   "class" => "",
								   "heading" => __("Vegetables Box Image One", BUNCH_NAME),
								   "param_name" => "image4",
								   "description" => __("Enter the Vegetables Image Box First to show.", BUNCH_NAME),
								   'group' => esc_html__('Natural Vegetables Product', BUNCH_NAME)
								),
								array(
								   "type" => "textfield",
								   "holder" => "div",
								   "class" => "",
								   "heading" => __('Vegetables Title', BUNCH_NAME ),
								   "param_name" => "vegi_title2",
								   "description" => __('Enter The Title to Show.', BUNCH_NAME ),
								   'group' => esc_html__('Natural Vegetables Product', BUNCH_NAME)
								),
								array(
								   "type" => "textfield",
								   "holder" => "div",
								   "class" => "",
								   "heading" => __('External Link', BUNCH_NAME ),
								   "param_name" => "ext_url4",
								   "description" => __('Enter The External Link to Show.', BUNCH_NAME ),
								   'group' => esc_html__('Natural Vegetables Product', BUNCH_NAME)
								),
								array(
								   "type" => "textfield",
								   "holder" => "div",
								   "class" => "",
								   "heading" => __('Vegetables Price', BUNCH_NAME ),
								   "param_name" => "price4",
								   "description" => __('Enter The Vegetables Price to Show.', BUNCH_NAME ),
								   'group' => esc_html__('Natural Vegetables Product', BUNCH_NAME)
								),
							)
						);
								
$bunch_sc['bunch_testimonial_2']	=	array(
					"name" => __("Our Testimonials 2", BUNCH_NAME),
					"base" => "bunch_testimonial_2",
					"class" => "",
					"category" => __('Warsaw', BUNCH_NAME),
					"icon" => 'fa-briefcase' ,
					'description' => __('Show Our Testimonials.', BUNCH_NAME),
					"params" => array(
								array(
								   "type" => "attach_image",
								   "holder" => "div",
								   "class" => "",
								   "heading" => __("Background Image", BUNCH_NAME),
								   "param_name" => "bg_img",
								   "description" => __("Enter the Section Image to show.", BUNCH_NAME),
								),
								array(
								   "type" => "textfield",
								   "holder" => "div",
								   "class" => "",
								   "heading" => __('Text Limit', BUNCH_NAME ),
								   "param_name" => "text_limit",
								   "description" => __('Enter The Limit Of Text to Show.', BUNCH_NAME )
								),
								array(
								   "type" => "textfield",
								   "holder" => "div",
								   "class" => "",
								   "heading" => __('Number', BUNCH_NAME ),
								   "param_name" => "num",
								   "description" => __('Enter Number of Items to Show.', BUNCH_NAME )
								),
								array(
								   "type" => "dropdown",
								   "holder" => "div",
								   "class" => "",
								   "heading" => __( 'Category', BUNCH_NAME ),
								   "param_name" => "cat",
								   "value" => array_flip( (array)bunch_get_categories( array( 'taxonomy' => 'testimonials_category', 'hide_empty' => FALSE ), true ) ),
								   "description" => __( 'Choose Category.', BUNCH_NAME )
								),
								array(
								   "type" => "dropdown",
								   "holder" => "div",
								   "class" => "",
								   "heading" => __("Order By", BUNCH_NAME),
								   "param_name" => "sort",
								   'value' => array_flip( array('select'=>__('Select Options', BUNCH_NAME),'date'=>__('Date', BUNCH_NAME),'title'=>__('Title', BUNCH_NAME) ,'name'=>__('Name', BUNCH_NAME) ,'author'=>__('Author', BUNCH_NAME),'comment_count' =>__('Comment Count', BUNCH_NAME),'random' =>__('Random', BUNCH_NAME) ) ),			
								   "description" => __("Enter the sorting order.", BUNCH_NAME)
								),
								array(
								   "type" => "dropdown",
								   "holder" => "div",
								   "class" => "",
								   "heading" => __("Order", BUNCH_NAME),
								   "param_name" => "order",
								   'value' => array_flip(array('select'=>__('Select Options', BUNCH_NAME),'ASC'=>__('Ascending', BUNCH_NAME),'DESC'=>__('Descending', BUNCH_NAME) ) ),			
								   "description" => __("Enter the sorting order.", BUNCH_NAME)
								),
							)
						);
$bunch_sc['bunch_our_partners_2'] = array(
			"name" => __("Our Partners 2", BUNCH_NAME),
			"base" => "bunch_our_partners_2",
			"class" => "",
			"category" => __('Warsaw', BUNCH_NAME),
			"icon" => 'icon-wpb-layer-shape-text' ,
			'description' => __('Show Our Partners 2', BUNCH_NAME),
			"params" => array(
						array(
						   "type" => "attach_image",
						   "holder" => "div",
						   "class" => "",
						   "heading" => __("Image", BUNCH_NAME),
						   "param_name" => "image",
						   "description" => __("Enter the Section Image to show.", BUNCH_NAME),
						),
						// params group
			            array(
			                'type' => 'param_group',
			                'value' => '',
			                'param_name' => 'partners',
							'group' => esc_html__('Our Partners', BUNCH_NAME),
			                'params' => array(
										array(
										   "type" => "attach_image",
										   "holder" => "div",
										   "heading" => __("Image", BUNCH_NAME),
										   "param_name" => "image",
										),
										array(
											'type' => 'textfield',
											'value' => '',
											'heading' => esc_html__('External Link', BUNCH_NAME ),
											'param_name' => 'ext_url',
										),
									)
								),
							)
						);
$bunch_sc['bunch_our_healthy_form'] = array(
			"name" => __("Our Healthy Form", BUNCH_NAME),
			"base" => "bunch_our_healthy_form",
			"class" => "",
			"category" => __('Warsaw', BUNCH_NAME),
			"icon" => 'icon-wpb-layer-shape-text' ,
			'description' => __('Show Our Healthy Form', BUNCH_NAME),
			"params" => array(
						array(
						   "type" => "textfield",
						   "holder" => "div",
						   "class" => "",
						   "heading" => __('Title', BUNCH_NAME ),
						   "param_name" => "title",
						   "description" => __('Enter The Title to Show.', BUNCH_NAME ),
						),
						array(
						   "type" => "attach_image",
						   "holder" => "div",
						   "class" => "",
						   "heading" => __("Image", BUNCH_NAME),
						   "param_name" => "image",
						   "description" => __("Enter the Section Image to show.", BUNCH_NAME)
						),
						// params group
			            array(
			                'type' => 'param_group',
			                'value' => '',
			                'param_name' => 'about_form',
							'group' => esc_html__('About Our Form', BUNCH_NAME),
			                'params' => array(
										array(
											'type' => 'textfield',
											'value' => '',
											'heading' => esc_html__('Year', BUNCH_NAME ),
											'param_name' => 'year',
										),
										array(
											'type' => 'textfield',
											'value' => '',
											'heading' => esc_html__('Upper Title', BUNCH_NAME ),
											'param_name' => 'sub_title',
										),
										array(
											'type' => 'textfield',
											'value' => '',
											'heading' => esc_html__('Title', BUNCH_NAME ),
											'param_name' => 'title1',
										),
										array(
											'type' => 'textarea',
											'value' => '',
											'heading' => esc_html__('Text', BUNCH_NAME ),
											'param_name' => 'text',
										),
										array(
											'type' => 'textfield',
											'value' => '',
											'heading' => esc_html__('Author Name', BUNCH_NAME ),
											'param_name' => 'author_name',
										),
									)
								),
							)
						);
$bunch_sc['bunch_gallery_3_col']	=	array(
					"name" => __("Gallery 3 Column", BUNCH_NAME),
					"base" => "bunch_gallery_3_col",
					"class" => "",
					"category" => __('Warsaw', BUNCH_NAME),
					"icon" => 'fa-briefcase' ,
					'description' => __('Show Gallery 3 Column.', BUNCH_NAME),
					"params" => array(
								array(
								   "type" => "textfield",
								   "holder" => "div",
								   "class" => "",
								   "heading" => __('Number', BUNCH_NAME ),
								   "param_name" => "num",
								   "description" => __('Enter The Number of Items to Show.', BUNCH_NAME )
								),
								array(
								   "type" => "textfield",
								   "holder" => "div",
								   "class" => "",
								   "heading" => __('Excluded Categories ID', BUNCH_NAME ),
								   "param_name" => "exclude_cats",
								   "description" => __('Enter Excluded Categories ID seperated by commas(13,14).', BUNCH_NAME )
								),
								array(
								   "type" => "dropdown",
								   "holder" => "div",
								   "class" => "",
								   "heading" => __("Order By", BUNCH_NAME),
								   "param_name" => "sort",
								   'value' => array_flip( array('select'=>__('Select Options', BUNCH_NAME),'date'=>__('Date', BUNCH_NAME),'title'=>__('Title', BUNCH_NAME) ,'name'=>__('Name', BUNCH_NAME) ,'author'=>__('Author', BUNCH_NAME),'comment_count' =>__('Comment Count', BUNCH_NAME),'random' =>__('Random', BUNCH_NAME) ) ),			
								   "description" => __("Enter the sorting order.", BUNCH_NAME)
								),
								array(
								   "type" => "dropdown",
								   "holder" => "div",
								   "class" => "",
								   "heading" => __("Order", BUNCH_NAME),
								   "param_name" => "order",
								   'value' => array_flip(array('select'=>__('Select Options', BUNCH_NAME),'ASC'=>__('Ascending', BUNCH_NAME),'DESC'=>__('Descending', BUNCH_NAME) ) ),			
								   "description" => __("Enter the sorting order.", BUNCH_NAME)
								),
								
							)
						);
$bunch_sc['bunch_gallery_masonry_2']	=	array(
					"name" => __("Gallery Masonry 2", BUNCH_NAME),
					"base" => "bunch_gallery_masonry_2",
					"class" => "",
					"category" => __('Warsaw', BUNCH_NAME),
					"icon" => 'fa-briefcase' ,
					'description' => __('Show Gallery Masonry 2.', BUNCH_NAME),
					"params" => array(
								array(
								   "type" => "textfield",
								   "holder" => "div",
								   "class" => "",
								   "heading" => __('Number', BUNCH_NAME ),
								   "param_name" => "num",
								   "description" => __('Enter The Number of Items to Show.', BUNCH_NAME )
								),
								array(
								   "type" => "textfield",
								   "holder" => "div",
								   "class" => "",
								   "heading" => __('Excluded Categories ID', BUNCH_NAME ),
								   "param_name" => "exclude_cats",
								   "description" => __('Enter Excluded Categories ID seperated by commas(13,14).', BUNCH_NAME )
								),
								array(
								   "type" => "dropdown",
								   "holder" => "div",
								   "class" => "",
								   "heading" => __("Order By", BUNCH_NAME),
								   "param_name" => "sort",
								   'value' => array_flip( array('select'=>__('Select Options', BUNCH_NAME),'date'=>__('Date', BUNCH_NAME),'title'=>__('Title', BUNCH_NAME) ,'name'=>__('Name', BUNCH_NAME) ,'author'=>__('Author', BUNCH_NAME),'comment_count' =>__('Comment Count', BUNCH_NAME),'random' =>__('Random', BUNCH_NAME) ) ),			
								   "description" => __("Enter the sorting order.", BUNCH_NAME)
								),
								array(
								   "type" => "dropdown",
								   "holder" => "div",
								   "class" => "",
								   "heading" => __("Order", BUNCH_NAME),
								   "param_name" => "order",
								   'value' => array_flip(array('select'=>__('Select Options', BUNCH_NAME),'ASC'=>__('Ascending', BUNCH_NAME),'DESC'=>__('Descending', BUNCH_NAME) ) ),			
								   "description" => __("Enter the sorting order.", BUNCH_NAME)
								),
								
							)
						);
$bunch_sc['bunch_gallery_full_width']	=	array(
					"name" => __("Gallery Full Width", BUNCH_NAME),
					"base" => "bunch_gallery_full_width",
					"class" => "",
					"category" => __('Warsaw', BUNCH_NAME),
					"icon" => 'fa-briefcase' ,
					'description' => __('Show Gallery Full Width.', BUNCH_NAME),
					"params" => array(
								array(
								   "type" => "textfield",
								   "holder" => "div",
								   "class" => "",
								   "heading" => __('Number', BUNCH_NAME ),
								   "param_name" => "num",
								   "description" => __('Enter The Number of Items to Show.', BUNCH_NAME )
								),
								array(
								   "type" => "textfield",
								   "holder" => "div",
								   "class" => "",
								   "heading" => __('Excluded Categories ID', BUNCH_NAME ),
								   "param_name" => "exclude_cats",
								   "description" => __('Enter Excluded Categories ID seperated by commas(13,14).', BUNCH_NAME )
								),
								array(
								   "type" => "dropdown",
								   "holder" => "div",
								   "class" => "",
								   "heading" => __("Order By", BUNCH_NAME),
								   "param_name" => "sort",
								   'value' => array_flip( array('select'=>__('Select Options', BUNCH_NAME),'date'=>__('Date', BUNCH_NAME),'title'=>__('Title', BUNCH_NAME) ,'name'=>__('Name', BUNCH_NAME) ,'author'=>__('Author', BUNCH_NAME),'comment_count' =>__('Comment Count', BUNCH_NAME),'random' =>__('Random', BUNCH_NAME) ) ),			
								   "description" => __("Enter the sorting order.", BUNCH_NAME)
								),
								array(
								   "type" => "dropdown",
								   "holder" => "div",
								   "class" => "",
								   "heading" => __("Order", BUNCH_NAME),
								   "param_name" => "order",
								   'value' => array_flip(array('select'=>__('Select Options', BUNCH_NAME),'ASC'=>__('Ascending', BUNCH_NAME),'DESC'=>__('Descending', BUNCH_NAME) ) ),			
								   "description" => __("Enter the sorting order.", BUNCH_NAME)
								),
								
							)
						);
$bunch_sc['bunch_contact_form']	=	array(
					"name" => __("Contact Form", BUNCH_NAME),
					"base" => "bunch_contact_form",
					"class" => "",
					"category" => __('Warsaw', BUNCH_NAME),
					"icon" => 'fa-briefcase' ,
					'description' => __('Show Contact Form.', BUNCH_NAME),
					"params" => array(
								array(
								   "type" => "textfield",
								   "holder" => "div",
								   "class" => "",
								   "heading" => __('Form Title', BUNCH_NAME ),
								   "param_name" => "title",
								   "description" => __('Enter The Form Title to Show.', BUNCH_NAME )
								),
								array(
								   "type" => "textarea_raw_html",
								   "holder" => "div",
								   "class" => "",
								   "heading" => __('Contact Form7 Shortcode', BUNCH_NAME ),
								   "param_name" => "contact_form",
								   "description" => __('Enter Contact Form7 Shortcode', BUNCH_NAME )
								),
							)
						);
$bunch_sc['bunch_contact_info']	=	array(
					"name" => __("Contact Information", BUNCH_NAME),
					"base" => "bunch_contact_info",
					"class" => "",
					"category" => __('Warsaw', BUNCH_NAME),
					"icon" => 'fa-briefcase' ,
					'description' => __('Show Contact Information.', BUNCH_NAME),
					"params" => array(
								array(
								   "type" => "textfield",
								   "holder" => "div",
								   "class" => "",
								   "heading" => __('Address', BUNCH_NAME ),
								   "param_name" => "address",
								   "description" => __('Enter The Address to Show.', BUNCH_NAME )
								),
								array(
								   "type" => "textfield",
								   "holder" => "div",
								   "class" => "",
								   "heading" => __('Phone No', BUNCH_NAME ),
								   "param_name" => "phone_no",
								   "description" => __('Enter The Phone No to Show.', BUNCH_NAME )
								),
								array(
								   "type" => "textfield",
								   "holder" => "div",
								   "class" => "",
								   "heading" => __('Fax No', BUNCH_NAME ),
								   "param_name" => "fax",
								   "description" => __('Enter The Fax No to Show.', BUNCH_NAME )
								),
								array(
								   "type" => "textfield",
								   "holder" => "div",
								   "class" => "",
								   "heading" => __('Email', BUNCH_NAME ),
								   "param_name" => "email",
								   "description" => __('Enter The Email to Show.', BUNCH_NAME )
								),
							)
						);
$bunch_sc['bunch_google_map_2']	=	array(
					"name" => __("Google map 2", BUNCH_NAME),
					"base" => "bunch_google_map_2",
					"class" => "",
					"category" => __('Warsaw', BUNCH_NAME),
					"icon" => 'fa-briefcase' ,
					'description' => __('Show Google map 2.', BUNCH_NAME),
					"params" => array(
								array(
								   "type" => "textfield",
								   "holder" => "div",
								   "class" => "",
								   "heading" => __('Lattitude', BUNCH_NAME ),
								   "param_name" => "lat",
								   "description" => __('Enter Lattitude for map', BUNCH_NAME ),
								   "default" => '23.815811',
								),
								array(
								   "type" => "textfield",
								   "holder" => "div",
								   "class" => "",
								   "heading" => __('Longitude', BUNCH_NAME ),
								   "param_name" => "long",
								   "description" => __('Enter Longitude for map', BUNCH_NAME ),
								   "default" => '90.412580',
								),
								array(
								   "type" => "textfield",
								   "holder" => "div",
								   "class" => "",
								   "heading" => __('Mark Title', BUNCH_NAME ),
								   "param_name" => "mark_title",
								   "description" => __('Enter Mark Title for map', BUNCH_NAME ),
								   "default" => 'Dhaka',
								),
								array(
								   "type" => "textfield",
								   "holder" => "div",
								   "class" => "",
								   "heading" => __('Mark Address', BUNCH_NAME ),
								   "param_name" => "mark_address",
								   "description" => __('Enter Mark Address for map', BUNCH_NAME ),
								   "default" => ' Dhaka 1000-1200, Bangladesh',
								),
								array(
								   "type" => "textfield",
								   "holder" => "div",
								   "class" => "",
								   "heading" => __('Email', BUNCH_NAME ),
								   "param_name" => "email",
								   "description" => __('Enter The Email for map', BUNCH_NAME ),
								   "default" => ' info@youremail.com',
								),
							)
						);
$bunch_sc['bunch_news_letter']	=	array(
					"name" => __("Subscribe News Letter", BUNCH_NAME),
					"base" => "bunch_news_letter",
					"class" => "",
					"category" => __('Warsaw', BUNCH_NAME),
					"icon" => 'fa-briefcase' ,
					'description' => __('Show Subscribe News Letter.', BUNCH_NAME),
					"params" => array(
								array(
								   "type" => "attach_image",
								   "holder" => "div",
								   "class" => "",
								   "heading" => __("Background Image", BUNCH_NAME),
								   "param_name" => "bg_img",
								   "description" => __("Enter the Section Image to show.", BUNCH_NAME),
								),
								array(
								   "type" => "textfield",
								   "holder" => "div",
								   "class" => "",
								   "heading" => __('Title', BUNCH_NAME ),
								   "param_name" => "title",
								   "description" => __('Enter The Title to Show.', BUNCH_NAME ),
								),
								array(
								   "type" => "textfield",
								   "holder" => "div",
								   "class" => "",
								   "heading" => __('Sub Title', BUNCH_NAME ),
								   "param_name" => "sub_title",
								   "description" => __('Enter The Sub Title to Show.', BUNCH_NAME ),
								),
								array(
									"type" => "textfield",
									"holder" => "div",
									"class" => "",
									"heading" => __('FeedBurner ID', BUNCH_NAME ),
									"param_name" => "id",
									'value' => 'themeforest',
									"description" => __('Enter feedburner id for newsletter', BUNCH_NAME ),
								),
							)
						);
//popular products
$bunch_sc['bunch_our_shop_filter']	=	array(
					"name" => __("Shop Filtration", BUNCH_NAME),
					"base" => "bunch_our_shop_filter",
					"class" => "",
					"category" => __('Warsaw', BUNCH_NAME),
					"icon" => 'fa-briefcase' ,
					'description' => __('Show Shop Filtration.', BUNCH_NAME),
					"params" => array(
								array(
								   "type" => "textfield",
								   "holder" => "div",
								   "class" => "",
								   "heading" => __('Title', BUNCH_NAME ),
								   "param_name" => "title",
								   "description" => __('Enter The Title to Show.', BUNCH_NAME )
								),
								array(
								   "type" => "textfield",
								   "holder" => "div",
								   "class" => "",
								   "heading" => __('Number', BUNCH_NAME ),
								   "param_name" => "num",
								   "description" => __('Enter The Number of Items to Show.', BUNCH_NAME )
								),
								array(
								   "type" => "textfield",
								   "holder" => "div",
								   "class" => "",
								   "heading" => __('Excluded Categories ID', BUNCH_NAME ),
								   "param_name" => "exclude_cats",
								   "description" => __('Enter Excluded Categories ID seperated by commas(13,14).', BUNCH_NAME )
								),
								array(
								   "type" => "dropdown",
								   "holder" => "div",
								   "class" => "",
								   "heading" => __("Order By", BUNCH_NAME),
								   "param_name" => "sort",
								   'value' => array_flip( array('select'=>__('Select Options', BUNCH_NAME),'date'=>__('Date', BUNCH_NAME),'title'=>__('Title', BUNCH_NAME) ,'name'=>__('Name', BUNCH_NAME) ,'author'=>__('Author', BUNCH_NAME),'comment_count' =>__('Comment Count', BUNCH_NAME),'random' =>__('Random', BUNCH_NAME) ) ),			
								   "description" => __("Enter the sorting order.", BUNCH_NAME)
								),
								array(
								   "type" => "dropdown",
								   "holder" => "div",
								   "class" => "",
								   "heading" => __("Order", BUNCH_NAME),
								   "param_name" => "order",
								   'value' => array_flip(array('select'=>__('Select Options', BUNCH_NAME),'ASC'=>__('Ascending', BUNCH_NAME),'DESC'=>__('Descending', BUNCH_NAME) ) ),			
								   "description" => __("Enter the sorting order.", BUNCH_NAME)
								),
								
							)
						);
$bunch_sc['bunch_our_shop'] = array(
	   "name" => __("Our Shop", BUNCH_NAME),
	   "base" => "bunch_our_shop",
	   "class" => "",
	   "category" => __('Warsaw', BUNCH_NAME),
	   "icon" => 'fa-briefcase' ,
	   'description' => __('Our Shop', BUNCH_NAME),
	   "params" => array(
						array(
						   "type" => "textfield",
						   "holder" => "div",
						   "class" => "",
						   "heading" => __('Title', BUNCH_NAME ),
						   "param_name" => "title",
						   "description" => __('Enter The Title to Show.', BUNCH_NAME )
						),
						// params group
			            array(
			                'type' => 'param_group',
			                'value' => '',
			                'param_name' => 'prdct_tabs',
			                // Note params is mapped inside param-group:
			                'params' => array(
			                    array(
								   "type" => "textfield",
								   "holder" => "div",
								   "class" => "",
								   "heading" => __('Tab Title', BUNCH_NAME ),
								   "param_name" => "tab_title",
								   "description" => __('Enter The Title to Show.', BUNCH_NAME )
								),
								array(
								   "type" => "textfield",
								   "holder" => "div",
								   "class" => "",
								   "heading" => __('Number', BUNCH_NAME ),
								   "param_name" => "num",
								   "description" => __('Enter Number of Items to Show.', BUNCH_NAME )
								),
								array(
								   "type" => "dropdown",
								   "holder" => "div",
								   "class" => "",
								   "heading" => __( 'Category', BUNCH_NAME ),
								   "param_name" => "cat",
								   "value" => array_flip( (array)bunch_get_categories( array( 'taxonomy' => 'product_cat', 'hide_empty' => FALSE ), true ) ),
								   "description" => __( 'Choose Category.', BUNCH_NAME )
								),
								array(
								   "type" => "dropdown",
								   "holder" => "div",
								   "class" => "",
								   "heading" => __("Order By", BUNCH_NAME),
								   "param_name" => "sort",
								   'value' => array_flip( array('select'=>__('Select Options', BUNCH_NAME),'date'=>__('Date', BUNCH_NAME),'title'=>__('Title', BUNCH_NAME) ,'name'=>__('Name', BUNCH_NAME) ,'author'=>__('Author', BUNCH_NAME),'comment_count' =>__('Comment Count', BUNCH_NAME),'random' =>__('Random', BUNCH_NAME) ) ),			
								   "description" => __("Enter the sorting order.", BUNCH_NAME)
								),
								array(
								   "type" => "dropdown",
								   "holder" => "div",
								   "class" => "",
								   "heading" => __("Order", BUNCH_NAME),
								   "param_name" => "order",
								   'value' => array_flip(array('select'=>__('Select Options', BUNCH_NAME),'ASC'=>__('Ascending', BUNCH_NAME),'DESC'=>__('Descending', BUNCH_NAME) ) ),			
								   "description" => __("Enter the sorting order.", BUNCH_NAME)
								),
			                )
			            ),
	   		),
	   
	  );
//Our Shop Home
/*$bunch_sc['bunch_our_shop']	=	array(
					"name" => __("Our Shop", BUNCH_NAME),
					"base" => "bunch_our_shop",
					"class" => "",
					"category" => __('Warsaw', BUNCH_NAME),
					"icon" => 'fa-briefcase' ,
					'description' => __('Show Our Shop Home.', BUNCH_NAME),
					"params" => array(
					   	
						array(
						   "type" => "textfield",
						   "holder" => "div",
						   "class" => "",
						   "heading" => __('Title', BUNCH_NAME ),
						   "param_name" => "title",
						   "description" => __('Enter The Title to Show.', BUNCH_NAME )
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
						   "value" => array_flip( (array)bunch_get_categories( array( 'taxonomy' => 'product_cat', 'hide_empty' => FALSE ), true ) ),
						   "description" => __( 'Choose Category.', BUNCH_NAME )
						),
						array(
						   "type" => "dropdown",
						   "holder" => "div",
						   "class" => "",
						   "heading" => __("Order By", BUNCH_NAME),
						   "param_name" => "sort",
						   'value' => array_flip( array('select'=>__('Select Options', BUNCH_NAME),'date'=>__('Date', BUNCH_NAME),'title'=>__('Title', BUNCH_NAME) ,'name'=>__('Name', BUNCH_NAME) ,'author'=>__('Author', BUNCH_NAME),'comment_count' =>__('Comment Count', BUNCH_NAME),'random' =>__('Random', BUNCH_NAME) ) ),			
						   "description" => __("Enter the sorting order.", BUNCH_NAME)
						),
						array(
						   "type" => "dropdown",
						   "holder" => "div",
						   "class" => "",
						   "heading" => __("Order", BUNCH_NAME),
						   "param_name" => "order",
						   'value' => array_flip(array('select'=>__('Select Options', BUNCH_NAME),'ASC'=>__('Ascending', BUNCH_NAME),'DESC'=>__('Descending', BUNCH_NAME) ) ),			
						   "description" => __("Enter the sorting order.", BUNCH_NAME)
						),
						
					)
				);*/
//Our Shop Home Carousel
$bunch_sc['bunch_our_shop_caresoul']	=	array(
					"name" => __("Our Shop Carousel", BUNCH_NAME),
					"base" => "bunch_our_shop_caresoul",
					"class" => "",
					"category" => __('Warsaw', BUNCH_NAME),
					"icon" => 'fa-briefcase' ,
					'description' => __('Show Our Shop Home Caresoul.', BUNCH_NAME),
					"params" => array(
					   	array(
						   "type" => "textfield",
						   "holder" => "div",
						   "class" => "",
						   "heading" => __('Sub Title', BUNCH_NAME ),
						   "param_name" => "sub_title",
						   "description" => __('Enter The Sub Title to Show.', BUNCH_NAME )
						),
						array(
						   "type" => "textfield",
						   "holder" => "div",
						   "class" => "",
						   "heading" => __('Title', BUNCH_NAME ),
						   "param_name" => "title",
						   "description" => __('Enter The Title to Show.', BUNCH_NAME )
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
						   "value" => array_flip( (array)bunch_get_categories( array( 'taxonomy' => 'product_cat', 'hide_empty' => FALSE ), true ) ),
						   "description" => __( 'Choose Category.', BUNCH_NAME )
						),
						array(
						   "type" => "dropdown",
						   "holder" => "div",
						   "class" => "",
						   "heading" => __("Order By", BUNCH_NAME),
						   "param_name" => "sort",
						   'value' => array_flip( array('select'=>__('Select Options', BUNCH_NAME),'date'=>__('Date', BUNCH_NAME),'title'=>__('Title', BUNCH_NAME) ,'name'=>__('Name', BUNCH_NAME) ,'author'=>__('Author', BUNCH_NAME),'comment_count' =>__('Comment Count', BUNCH_NAME),'random' =>__('Random', BUNCH_NAME) ) ),			
						   "description" => __("Enter the sorting order.", BUNCH_NAME)
						),
						array(
						   "type" => "dropdown",
						   "holder" => "div",
						   "class" => "",
						   "heading" => __("Order", BUNCH_NAME),
						   "param_name" => "order",
						   'value' => array_flip(array('select'=>__('Select Options', BUNCH_NAME),'ASC'=>__('Ascending', BUNCH_NAME),'DESC'=>__('Descending', BUNCH_NAME) ) ),			
						   "description" => __("Enter the sorting order.", BUNCH_NAME)
						),
						
					)
				);
//Our Shop Home 2
$bunch_sc['bunch_our_shop_2']	=	array(
					"name" => __("Our Shop 2", BUNCH_NAME),
					"base" => "bunch_our_shop_2",
					"class" => "",
					"category" => __('Warsaw', BUNCH_NAME),
					"icon" => 'fa-briefcase' ,
					'description' => __('Show Our Shop Home 2.', BUNCH_NAME),
					"params" => array(
					   	
						array(
						   "type" => "textfield",
						   "holder" => "div",
						   "class" => "",
						   "heading" => __('Title', BUNCH_NAME ),
						   "param_name" => "title",
						   "description" => __('Enter The Title to Show.', BUNCH_NAME )
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
						   "value" => array_flip( (array)bunch_get_categories( array( 'taxonomy' => 'product_cat', 'hide_empty' => FALSE ), true ) ),
						   "description" => __( 'Choose Category.', BUNCH_NAME )
						),
						array(
						   "type" => "dropdown",
						   "holder" => "div",
						   "class" => "",
						   "heading" => __("Order By", BUNCH_NAME),
						   "param_name" => "sort",
						   'value' => array_flip( array('select'=>__('Select Options', BUNCH_NAME),'date'=>__('Date', BUNCH_NAME),'title'=>__('Title', BUNCH_NAME) ,'name'=>__('Name', BUNCH_NAME) ,'author'=>__('Author', BUNCH_NAME),'comment_count' =>__('Comment Count', BUNCH_NAME),'random' =>__('Random', BUNCH_NAME) ) ),			
						   "description" => __("Enter the sorting order.", BUNCH_NAME)
						),
						array(
						   "type" => "dropdown",
						   "holder" => "div",
						   "class" => "",
						   "heading" => __("Order", BUNCH_NAME),
						   "param_name" => "order",
						   'value' => array_flip(array('select'=>__('Select Options', BUNCH_NAME),'ASC'=>__('Ascending', BUNCH_NAME),'DESC'=>__('Descending', BUNCH_NAME) ) ),			
						   "description" => __("Enter the sorting order.", BUNCH_NAME)
						),
						
					)
				);
/*----------------------------------------------------------------------------*/
$bunch_sc = apply_filters( '_bunch_shortcodes_array', $bunch_sc );
	
return $bunch_sc;