<?php
$options = array();
$options[] =  array(
	'id'          => '_bunch_tax_seo_settings',
	'types'       => array('category', 'post_tag'),
	'title'       => __('SEO Settings', BUNCH_NAME),
	'priority'    => 'high',
	'template'    => 
			array(
					array(
						'type' => 'toggle',
						'name' => 'seo_status',
						'label' => __('Enable SEO', BUNCH_NAME),
						//'description' => __('Enable / disable seo settings for this post', BUNCH_NAME),
					),
					array(
						'type' => 'textbox',
						'name' => 'title',
						'label' => __('Meta Title', BUNCH_NAME),
						//'description' => __('Enter meta title or leave it empty to use default title', BUNCH_NAME),
					),
					
					array(
						'type' => 'textarea',
						'name' => 'description',
						'label' => __('Meta Description', BUNCH_NAME),
						'default' => '',
						//'description' => __('Enter meta description', BUNCH_NAME),
					),
					array(
						'type' => 'textarea',
						'name' => 'keywords',
						'label' => __('Meta Keywords', BUNCH_NAME),
						'default' => '',
						//'description' => __('Enter meta keywords', BUNCH_NAME),
					),
					
				),
);
$options[] =  array(
	'id'          => _WSH()->set_term_key('category'),
	'types'       => array('category', 'post_tag'),
	'title'       => __('Post Category Settings', BUNCH_NAME),
	'priority'    => 'high',
	'template'    => 
			array(
					array(
						'type' => 'textbox',
						'name' => 'header_title',
						'label' => __( 'Header Title', BUNCH_NAME ),
					),
					array(
						'type' => 'upload',
						'name' => 'header_img',
						'label' => __( 'Header image', BUNCH_NAME ),
					),
					array(
						'type' => 'radioimage',
						'name' => 'layout',
						'label' => __('Page Layout', BUNCH_NAME),
						//'description' => __('Choose the layout for blog pages', BUNCH_NAME),
						'items' => array(
							array(
								'value' => 'left',
								'label' => __('Left Sidebar', BUNCH_NAME),
								'img' => get_template_directory_uri().'/images/vafpress/2cl.png',
							),
							array(
								'value' => 'right',
								'label' => __('Right Sidebar', BUNCH_NAME),
								'img' => get_template_directory_uri().'/images/vafpress/2cr.png',
							),
							array(
								'value' => 'full',
								'label' => __('Full Width', BUNCH_NAME),
								'img' => get_template_directory_uri().'/images/vafpress/1col.png',
							),
							array(
								'value' => 'both',
								'label' => __( 'Both Sidebars', BUNCH_NAME ),
								'img' => get_template_directory_uri() . '/images/vafpress/3_col.png' 
							),
							
						),
					),
					
					array(
						'type' => 'select',
						'name' => 'sidebar',
						'label' => __('Sidebar', BUNCH_NAME),
						'default' => '',
						'items' => bunch_get_sidebars(true)	
					),
					
				),
);
$options[] =  array(
	'id'          => _WSH()->set_term_key('category'),
	'types'       => array('product_cat', 'product_tag'),
	'title'       => __('Post Category Settings', BUNCH_NAME),
	'priority'    => 'high',
	'template'    => 
			array(
	
					array(
						'type' => 'textbox',
						'name' => 'header_title',
						'label' => __( 'Header Title', BUNCH_NAME ),
					),
					array(
						'type' => 'upload',
						'name' => 'header_img',
						'label' => __( 'Header image', BUNCH_NAME ),
					),
					array(
						'type' => 'radioimage',
						'name' => 'layout',
						'label' => __('Page Layout', BUNCH_NAME),
						//'description' => __('Choose the layout for blog pages', BUNCH_NAME),
						'items' => array(
							array(
								'value' => 'left',
								'label' => __('Left Sidebar', BUNCH_NAME),
								'img' => get_template_directory_uri().'/includes/vafpress/public/img/2cl.png',
							),
							array(
								'value' => 'right',
								'label' => __('Right Sidebar', BUNCH_NAME),
								'img' => get_template_directory_uri().'/includes/vafpress/public/img/2cr.png',
							),
							array(
								'value' => 'full',
								'label' => __('Full Width', BUNCH_NAME),
								'img' => get_template_directory_uri().'/includes/vafpress/public/img/1col.png',
							),
							
						),
					),
					
					array(
						'type' => 'select',
						'name' => 'sidebar',
						'label' => __('Sidebar', BUNCH_NAME),
						'default' => '',
						'items' => bunch_get_sidebars(true)	
					),
					
				),
);
 return $options;
/**
 * EOF
 */
 
 
