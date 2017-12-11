<?php

//include(get_template_directory().'/includes/resource/awesom_icons.php');

$settings = array();



$settings['post']['sidebar'] =  array(

													'type' => 'select', //builtin fields include:

													'id' => 'sidebar',

													'title' => __('Sidebar', BUNCH_NAME),

													'desc' => __('Choose an sidebar for this deal', BUNCH_NAME),

													'options' => bunch_get_sidebars(),

													'attributes' => array('style' => 'width:40%'),

												);

$settings['page']['sidebar'] =  array(

													'type' => 'select', //builtin fields include:

													'id' => 'sidebar',

													'title' => __('Sidebar', BUNCH_NAME),

													'desc' => __('Choose an sidebar for this deal', BUNCH_NAME),

													'options' => bunch_get_sidebars(),

													'attributes' => array('style' => 'width:40%'),

												);



$settings['bistro_service']['font_awesome'] =  array(

													'type' => 'select', //builtin fields include:

													'id' => 'font_awesome',

													'title' => __('Choos Font Awesome Icon', BUNCH_NAME),

													'desc' => __('Choose an icon fron the font icons list', BUNCH_NAME),

													'options' => array_values(array_map(create_function('$v', 'return ucwords($v);'), $GLOBALS['_font_awesome'])),

													'attributes' => array('style' => 'width:40%'),

												);

												

$settings['bistro_service']['sidebar'] =  array(

													'type' => 'select', //builtin fields include:

													'id' => 'sidebar',

													'title' => __('Sidebar', BUNCH_NAME),

													'desc' => __('Choose an sidebar for this service', BUNCH_NAME),

													'options' => bunch_get_sidebars(),

													'attributes' => array('style' => 'width:40%'),

												);



$settings['bistro_deal']['start_date'] =  array(

													'type' => 'date', //builtin fields include:

													'id' => 'start_date',

													'title' => __('Start Date', BUNCH_NAME),

													'desc' => __('Choose start date of the deal', BUNCH_NAME),

													'attributes' => array('style' => 'width:30%'),

												);

$settings['bistro_deal']['end_date'] =  array(

													'type' => 'date', //builtin fields include:

													'id' => 'end_date',

													'title' => __('End Date', BUNCH_NAME),

													'desc' => __('Choose end date of the deal', BUNCH_NAME),

													'attributes' => array('style' => 'width:30%'),

												);



$settings['bistro_deal']['products'] =  array(

													'type' => 'select', //builtin fields include:																	 

													'title' => __('Select Product', BUNCH_NAME),

													'id' => 'products',

													'desc' => __('Choose the products for this deal', BUNCH_NAME),

													'options' => bunch_get_posts_array('wpsc-product'),

													'attributes' => array('style' => 'width:45%'),



												);

$settings['bistro_deal']['sidebar'] =  array(

													'type' => 'select', //builtin fields include:

													'id' => 'sidebar',

													'title' => __('Sidebar', BUNCH_NAME),

													'desc' => __('Choose an sidebar for this deal', BUNCH_NAME),

													'options' => bunch_get_sidebars(),

													'attributes' => array('style' => 'width:40%'),

												);

$settings['bistro_team']['designation'] =  array(

													'type' => 'text', //builtin fields include:																	 

													'title' => __('Designation', BUNCH_NAME),

													'id' => 'designation',

													'desc' => __('Enter the designation of the member', BUNCH_NAME),

													'attributes' => array('style' => 'width:45%'),



												);

$settings['bistro_team']['facebook'] =  array(

													'type' => 'text', //builtin fields include:																	 

													'title' => __('Facebook', BUNCH_NAME),

													'id' => 'facebook',

													'desc' => __('Enter the facebook url', BUNCH_NAME),

													'attributes' => array('style' => 'width:45%'),

												);

$settings['bistro_team']['twitter'] =  array(

													'type' => 'text', //builtin fields include:																	 

													'title' => __('Twitter', BUNCH_NAME),

													'id' => 'twitter',

													'desc' => __('Enter the twitter url', BUNCH_NAME),

													'attributes' => array('style' => 'width:45%'),

												);

$settings['bistro_team']['google_plus'] =  array(

													'type' => 'text', //builtin fields include:																	 

													'title' => __('Google Plus', BUNCH_NAME),

													'id' => 'google_plus',

													'desc' => __('Enter the google Plus url', BUNCH_NAME),

													'attributes' => array('style' => 'width:45%'),

												);

$settings['bistro_portfolio']['project_url'] =  array(

													'type' => 'text', //builtin fields include:																	 

													'title' => __('Project URL', BUNCH_NAME),

													'id' => 'project_url',

													'desc' => __('Enter the project url', BUNCH_NAME),

													'attributes' => array('style' => 'width:45%'),

												);



$settings['bistro_portfolio']['video_url'] =  array(

													'type' => 'multi_text', //builtin fields include:																	 

													'title' => __('Videos URL', BUNCH_NAME),

													'id' => 'video_url',

													'attributes' => array('style' => 'width:45%'),

												);

























												