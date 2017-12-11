<?php



return array(



	'About' => array(

		'elements'=> array(



			'all_controls' => array(

				'title'   => 'About Controls',

				'code'    => '[bunch_about]',

				'attributes' => array(

					array(

						 'type' => 'textbox',

						'name' => 'author',

						'label' => __( 'Author', BUNCH_NAME ),

						'description' => __( 'Enter the Author name', BUNCH_NAME ),

						'default' => 'Fliz' 

					),

					array(

						'type' => 'wpeditor',

						'name' => 'title',

						'label' => __('Title', 'vp_textdomain'),

						'description' => __('Wordpress tinyMCE editor.', 'vp_textdomain'),

						'use_external_plugins' => '0',

						'disabled_externals_plugins' => '',

						'disabled_internals_plugins' => '',

					),

					array(

						 'type' => 'textbox',

						'name' => 'title_link',

						'label' => __( 'Title Link', BUNCH_NAME ),

						'description' => __( 'Enter the Title Link', BUNCH_NAME ),

						'default' => 'abc.com' 

					),

					array(

						'type' => 'wpeditor',

						'name' => 'tagline',

						'label' => __('Tagline', 'vp_textdomain'),

						'description' => __('Wordpress tinyMCE editor.', 'vp_textdomain'),

						'use_external_plugins' => '0',

						'disabled_externals_plugins' => '',

						'disabled_internals_plugins' => '',

					),

					array(

						'type' => 'upload',

						'name' => 'img',

						'label' => __('Image', 'vp_textdomain'),

						'description' => __('Image for the section', 'vp_textdomain'),

						'default' => 'http://placehold.it/70x70',

					),

					array(

						'type' => 'wpeditor',

						'name' => 'content',

						'label' => __('Short html Text', 'vp_textdomain'),

						'description' => __('Enter content, you can use html tags', 'vp_textdomain'),

						'use_external_plugins' => '0',

						'disabled_externals_plugins' => '',

						'disabled_internals_plugins' => '',

					),

					

					

				),

			),

		),

	),

	'Contact' => array(

		'elements'=> array(



			'all_controls' => array(

				'title'   => 'Contact Controls',

				'code'    => '[bunch_contact]',

				'attributes' => array(

					array(

						'name'  => 'small_title',

						'type'  => 'textbox',

						'label' => __('Small Title', BUNCH_NAME),

						'description' => __("Enter title or leave blank if don\'t want to show title.", BUNCH_NAME),

						'default' => '',

					),

					array(

						'name'  => 'main_title',

						'type'  => 'textbox',

						'label' => __('Main Title', BUNCH_NAME),

						'description' => __("Enter title or leave blank if don\'t want to show title.", BUNCH_NAME),

						'default' => '',

					),

					array(

						'name'  => 'title_link',

						'type'  => 'textbox',

						'label' => __('Title Link', BUNCH_NAME),

						'description' => __('Enter Title Link', BUNCH_NAME),

						'default' => '',

					),

					array(

						'type' => 'wpeditor',

						'name' => 'tagline',

						'label' => __('Tagline', 'vp_textdomain'),

						'description' => __('Wordpress tinyMCE editor.', 'vp_textdomain'),

						'use_external_plugins' => '0',

						'disabled_externals_plugins' => '',

						'disabled_internals_plugins' => '',

					),

					array(

						'type' => 'wpeditor',

						'name' => 'content',

						'label' => __('Short html Text', 'vp_textdomain'),

						'description' => __('Enter content, you can use html tags', 'vp_textdomain'),

						'use_external_plugins' => '0',

						'disabled_externals_plugins' => '',

						'disabled_internals_plugins' => '',

					),

					

				),

			),

		),

	),



);



/**

 * EOF

 */