<?php
return array(
    'title' => __( 'Warsaw Theme Options', BUNCH_NAME ),
    'logo' => get_template_directory_uri() . '/images/logo.png',
    'menus' => array(
        // General Settings
         array(
             'title' => __( 'General Settings', BUNCH_NAME ),
            'name' => 'general_settings',
            'icon' => 'font-awesome:fa fa-cogs',
            'menus' => array(
                 
				array(
                    'title' => __( 'general Settings', BUNCH_NAME ),
                    'name' => 'general_sub_settings',
                    'icon' => 'font-awesome:fa fa-dashboard',
                    'controls' => array(
                        array(
                            'type' => 'toggle',
                            'name' => 'preloader',
                            'label' => __( 'Preloader', BUNCH_NAME ),
							'default' => 0,
							'description' => __('show or hide Preloader', BUNCH_NAME)
							
                        ),
						array(
							'type' => 'textbox',
							'name' => 'map_api_key',
							'label' => __( 'Map Api Key', BUNCH_NAME ),
							'default' => '',
							'description' => __('Enter the map Api key', BUNCH_NAME)
						),
						array(
                            'type' => 'toggle',
                            'name' => 'boxed',
                            'label' => __( 'Boxed Layout', BUNCH_NAME ),
							'default' => 0,
							'description' => __('show or hide boxed', BUNCH_NAME)
							
                        ),
						array(
                            'type' => 'section',
                            'repeating' => true,
                            'sortable' => true,
                            'title' => __( 'Color Scheme', BUNCH_NAME ),
                            'name' => 'color_schemes',
                            'description' => __( 'This section is used for theme color settings', BUNCH_NAME ),
                            'fields' => array(
                                 
                                array(
                                    'type' => 'color',
                                    'name' => 'main_color_scheme',
                                    'label' => __( 'Main Color Scheme', BUNCH_NAME ),
                                    'description' => __( 'Choose the Custom color scheme for the theme.', BUNCH_NAME ),
                                    'default' => '#5ec79c',
                                    
                                ),
								array(
                                    'type' => 'color',
                                    'name' => 'second_color_scheme',
                                    'label' => __( 'Second Color Scheme', BUNCH_NAME ),
                                    'description' => __( 'Choose the Custom color scheme for the theme.', BUNCH_NAME ),
                                    'default' => '#f5b062',
								),
							)
						)
					) 
                    
                ),
				/** Submenu for heading settings */
                array(
                     'title' => __( 'Header Settings', BUNCH_NAME ),
                    'name' => 'header_settings',
                    'icon' => 'font-awesome:fa fa-dashboard',
                    'controls' => array(
                        array(
                             'type' => 'upload',
                            'name' => 'site_favicon',
                            'label' => __( 'Favicon', BUNCH_NAME ),
                            'description' => __( 'Upload the favicon, should be 16x16', BUNCH_NAME ),
                            'default' => '' 
                        ),
						array(
							'type' => 'select',
							'name' => 'header_style',
							'label' => __( 'Choose Header Style', BUNCH_NAME ),
							'items' => array(
								 array(
									'value' => 'header_v1',
									'label' => __( 'Header Default', BUNCH_NAME ),
								),
								array(
									'value' => 'header_v2',
									'label' => __( 'Header Style 2', BUNCH_NAME ),
								),
								array(
									'value' => 'header_v3',
									'label' => __( 'Header Style 3', BUNCH_NAME ),
								),
							),
							'default' => 'header_v1'
						),
						array(
							'type' => 'section',
							'title' => __('Header Default Settings', BUNCH_NAME),
							'name' => 'header_default_settings',
							'dependency' => array(
								'field' => 'header_style',
								'function' => 'vp_dep_style1',
							),
							'fields' => array(
								
								array(
									'type' => 'upload',
									'name' => 'logo_image',
									'label' => __('Logo Image', BUNCH_NAME),
									'description' => __('Insert the logo image', BUNCH_NAME),
									'default' => get_template_directory_uri().'/images/logo.png'
								),
								
							),
						),
						array(
							'type' => 'section',
							'title' => __('Header Style2 Settings', BUNCH_NAME),
							'name' => 'header_style2_settings',
							'dependency' => array(
								'field' => 'header_style',
								'function' => 'vp_dep_style2',
							),
							'fields' => array(
								array(
									'type' => 'upload',
									'name' => 'logo_v2_image',
									'label' => __('Logo Image', BUNCH_NAME),
									'description' => __('Insert the logo image', BUNCH_NAME),
									'default' => get_template_directory_uri().'/images/logo.png'
								),
								array(
									'type' => 'textbox',
									'name' => 'get_quote',
									'label' => __( 'Get A Quote', BUNCH_NAME ),
									'description' => __( 'Enter the url', BUNCH_NAME ),
									'default' => '#' 
								),
							),
						),
						array(
							'type' => 'section',
							'title' => __('Header Style3 Settings', BUNCH_NAME),
							'name' => 'header_style3_settings',
							'dependency' => array(
								'field' => 'header_style',
								'function' => 'vp_dep_style3',
							),
							'fields' => array(
								array(
									'type' => 'upload',
									'name' => 'logo_v3_image',
									'label' => __('Logo Image', BUNCH_NAME),
									'description' => __('Insert the logo image', BUNCH_NAME),
									'default' => get_template_directory_uri().'/images/logo.png'
								),
							),
						),
						array(
                            'type' => 'toggle',
                            'name' => 'show_social_icons',
                            'label' => __( 'Hide Social Icons', BUNCH_NAME ),
							'default' => 0,
							'description' => __('show or hide Social Icon', BUNCH_NAME)
						),
						// Custom HEader Style End
                        array(
                             'type' => 'codeeditor',
                            'name' => 'header_css',
                            'label' => __( 'Header CSS', BUNCH_NAME ),
                            'description' => __( 'Write your custom css to include in header.', BUNCH_NAME ),
                            'theme' => 'github',
                            'mode' => 'css' 
                        ) 
                    ) 
                    
                ),
				                
                /** Submenu for footer area */
                array(
                     'title' => __( 'Footer Settings', BUNCH_NAME ),
                    'name' => 'sub_footer_settings',
                    'icon' => 'font-awesome:fa fa-edit',
                    'controls' => array(
						array(
							'type' => 'textbox',
							'name' => 'copyright',
							'label' => __( 'Copy Right Text', BUNCH_NAME ),
							'description' => __( 'Enter the copyright', BUNCH_NAME ),
							'default' => 'Copyrights &copy; Warsaw 2017. All rights reserved.'
						),
                        
                    ) 
                ), //End of submenu
			) 
        ),
		
		// Dynamic Clients Creator
        array(
             'title' => __( 'Clients', BUNCH_NAME ),
            'name' => 'clients',
            'icon' => 'font-awesome:fa fa-share-square',
            'controls' => array(
                 array(
                     'type' => 'builder',
                    'repeating' => true,
                    'sortable' => true,
                    'label' => __( 'Clients', BUNCH_NAME ),
                    'name' => 'clients',
                    'description' => __( 'This section is used to add Clients.', BUNCH_NAME ),
                    'fields' => array(
                         array(
                             'type' => 'textbox',
                            'name' => 'title',
                            'label' => __( 'Title', BUNCH_NAME ),
                            'description' => __( 'Enter the title of the client.', BUNCH_NAME ), 
                        ),
						 array(
                             'type' => 'textbox',
                            'name' => 'client_link',
                            'label' => __( 'Link', BUNCH_NAME ),
                            'description' => __( 'Enter the Link for client.', BUNCH_NAME ),
                            'default' => '#'
                        ),
                        array(
                            'type' => 'upload',
                            'name' => 'client_img',
                            'label' => __( 'Logo', BUNCH_NAME ),
                            'description' => __( 'choose the brand logo.', BUNCH_NAME ),
                            'default' => '',
							
                         ),  
                    ) 
                ) 
            ) 
        ),
		
		
		// Pages , Blog Pages Settings
        array(
             'title' => __( 'Page Settings', BUNCH_NAME ),
            'name' => 'general_settings',
            'icon' => 'font-awesome:fa fa-file',
            'menus' => array(
                
                // Search Page Settings 
                 array(
                     'title' => __( 'Search Page', BUNCH_NAME ),
                    'name' => 'search_page_settings',
                    'icon' => 'font-awesome:fa fa-search',
                    'controls' => array(
                        
						array(
                            'type' => 'textbox',
                            'name' => 'search_page_header_title',
                            'label' => __( 'Title', BUNCH_NAME ),
                            'description' => __( 'Enter Search Page Title .', BUNCH_NAME ),
                            'default' => '',
						),
						array(
                            'type' => 'upload',
                            'name' => 'search_page_header_img',
                            'label' => __( 'Header image', BUNCH_NAME ),
                            'description' => __( 'Enter Search Header image .', BUNCH_NAME ),
                            'default' => '',
						),
						array(
                             'type' => 'select',
                            'name' => 'search_page_sidebar',
                            'label' => __( 'Sidebar', BUNCH_NAME ),
                            'items' => array(
                                 'data' => array(
                                     array(
                                         'source' => 'function',
                                        'value' => 'bunch_get_sidebars_2' 
                                    ) 
                                ) 
                            ),
                            'default' => array(
                                 '{{first}}' 
                            ) 
                        ),
                        array(
                             'type' => 'radioimage',
                            'name' => 'search_page_layout',
                            'label' => __( 'Page Layout', BUNCH_NAME ),
                            'description' => __( 'Choose the layout for blog pages', BUNCH_NAME ),
                            
                            'items' => array(
                                 array(
                                     'value' => 'left',
                                    'label' => __( 'Left Sidebar', BUNCH_NAME ),
                                    'img' => BUNCH_TH_URL.'/includes/vafpress/public/img/2cl.png', 
                                ),
                                
                                array(
                                     'value' => 'right',
                                    'label' => __( 'Right Sidebar', BUNCH_NAME ),
                                    'img' => BUNCH_TH_URL.'/includes/vafpress/public/img/2cr.png', 
                                ),
                                array(
                                     'value' => 'full',
                                    'label' => __( 'Full Width', BUNCH_NAME ),
                                    'img' => BUNCH_TH_URL.'/includes/vafpress/public/img/1col.png', 
                                ),
                                
                            ) 
                        ),
                    ) 
                ), // End of submenu
                
                // Archive Page Settings 
                array(
                     'title' => __( 'Archive Page', BUNCH_NAME ),
                    'name' => 'archive_page_settings',
                    'icon' => 'font-awesome:fa fa-archive',
                    'controls' => array(
                        array(
                            'type' => 'textbox',
                            'name' => 'archive_page_header_title',
                            'label' => __( 'Title', BUNCH_NAME ),
                            'description' => __( 'Enter Blog Page Title .', BUNCH_NAME ),
                            'default' => '',
						),
						array(
                            'type' => 'upload',
                            'name' => 'archive_page_header_img',
                            'label' => __( 'Header Image', BUNCH_NAME ),
                            'description' => __( 'Enter Header image url .', BUNCH_NAME ),
                            'default' => '',
						),
					    array(
                             'type' => 'select',
                            'name' => 'archive_page_sidebar',
                            'label' => __( 'Sidebar', BUNCH_NAME ),
                            'items' => array(
                                 'data' => array(
                                     array(
                                         'source' => 'function',
                                        'value' => 'bunch_get_sidebars_2' 
                                    ) 
                                ) 
                            ),
                            'default' => array(
                                 '{{first}}' 
                            ) 
                        ),
                        array(
                             'type' => 'radioimage',
                            'name' => 'archive_page_layout',
                            'label' => __( 'Page Layout', BUNCH_NAME ),
                            'description' => __( 'Choose the layout for blog pages', BUNCH_NAME ),
                            
                            'items' => array(
                                 array(
                                     'value' => 'left',
                                    'label' => __( 'Left Sidebar', BUNCH_NAME ),
                                    'img' => BUNCH_TH_URL.'/includes/vafpress/public/img/2cl.png', 
                                ),
                                array(
                                     'value' => 'right',
                                    'label' => __( 'Right Sidebar', BUNCH_NAME ),
                                    'img' => BUNCH_TH_URL.'/includes/vafpress/public/img/2cr.png', 
                                ),
                                array(
                                     'value' => 'full',
                                    'label' => __( 'Full Width', BUNCH_NAME ),
                                    'img' => BUNCH_TH_URL.'/includes/vafpress/public/img/1col.png',
                                ), 
                                
                            ) 
                        ) 
                        
                        
                    ) 
                ),
                
                // Author Page Settings 
                array(
                     'title' => __( 'Author Page', BUNCH_NAME ),
                    'name' => 'author_page_settings',
                    'icon' => 'font-awesome:fa fa-user',
                    'controls' => array(
                        array(
                            'type' => 'textbox',
                            'name' => 'author_page_header_title',
                            'label' => __( 'Title', BUNCH_NAME ),
                            'description' => __( 'Enter Author Page Title .', BUNCH_NAME ),
                            'default' => '',
							
                        ),
						array(
                            'type' => 'upload',
                            'name' => 'author_page_header_img',
                            'label' => __( 'Header Image', BUNCH_NAME ),
                            'description' => __( 'Enter Header image url .', BUNCH_NAME ),
                            'default' => '',
						),
						array(
                             'type' => 'select',
                            'name' => 'author_page_sidebar',
                            'label' => __( 'Sidebar', BUNCH_NAME ),
                            'items' => array(
                                 'data' => array(
                                     array(
                                         'source' => 'function',
                                        'value' => 'bunch_get_sidebars_2' 
                                    ) 
                                ) 
                            ),
                            'default' => array(
                                 '{{first}}' 
                            ) 
                        ),
                        array(
                             'type' => 'radioimage',
                            'name' => 'author_page_layout',
                            'label' => __( 'Page Layout', BUNCH_NAME ),
                            'description' => __( 'Choose the layout for blog pages', BUNCH_NAME ),
                            
                            'items' => array(
                                 array(
                                     'value' => 'left',
                                    'label' => __( 'Left Sidebar', BUNCH_NAME ),
                                    'img' => BUNCH_TH_URL.'/includes/vafpress/public/img/2cl.png', 
                                ),
                                
                                array(
                                     'value' => 'right',
                                    'label' => __( 'Right Sidebar', BUNCH_NAME ),
                                    'img' => BUNCH_TH_URL.'/includes/vafpress/public/img/2cr.png', 
                                ),
                                array(
                                     'value' => 'full',
                                    'label' => __( 'Full Width', BUNCH_NAME ),
                                    'img' => BUNCH_TH_URL.'/includes/vafpress/public/img/1col.png', 
                                ),
                                
                            ) 
                        ) 
                        
                    ) 
                ),
                
                // 404 Page Settings 
               /* array(
                     'title' => __( '404 Page Settings', BUNCH_NAME ),
                    'name' => '404_page_settings',
                    'icon' => 'font-awesome:fa fa-exclamation-triangle',
                    'controls' => array(
                         array(
                             'type' => 'textbox',
                            'name' => '404_page_title',
                            'label' => __( 'Page Title', BUNCH_NAME ),
                            'description' => __( 'Enter the Title you want to show on 404 page', BUNCH_NAME ),
                            'default' => '404 Page not Found' 
                        ),
                        array(
                             'type' => 'textbox',
                            'name' => '404_page_heading',
                            'label' => __( 'Page Heading', BUNCH_NAME ),
                            'description' => __( 'Enter the Heading you want to show on 404 page', BUNCH_NAME ),
                            'default' => '404 Page not Found' 
                        ),
                        array(
                             'type' => 'textbox',
                            'name' => '404_page_tag_line',
                            'label' => __( 'Page Tagline', BUNCH_NAME ),
                            'description' => __( 'Enter the Tagline you want to show on 404 page', BUNCH_NAME ),
                            'default' => '404 Page not Found' 
                        ),
                        array(
                             'type' => 'textarea',
                            'name' => '404_page_text',
                            'label' => __( '404 Page Text', BUNCH_NAME ),
                            'description' => __( 'Enter the Text you want to show on 404 page', BUNCH_NAME ),
                            'default' => '' 
                        ),
                        array(
                             'type' => 'select',
                            'name' => '404_page_sidebar',
                            'label' => __( 'Sidebar', BUNCH_NAME ),
                            'items' => array(
                                 'data' => array(
                                     array(
                                         'source' => 'function',
                                        'value' => 'bunch_get_sidebars_2' 
                                    ) 
                                ) 
                            ),
                            'default' => array(
                                 '{{first}}' 
                            ) 
                        ),
                        array(
                             'type' => 'radioimage',
                            'name' => 'layout',
                            'label' => __( 'Page Layout', BUNCH_NAME ),
                            'description' => __( 'Choose the layout for blog pages', BUNCH_NAME ),
                            
                            'items' => array(
                                 array(
                                     'value' => 'left',
                                    'label' => __( 'Left Sidebar', BUNCH_NAME ),
                                    'img' => get_template_directory_uri() . '/includes/vafpress/public/img/2cl.png' 
                                ),
                                
                                array(
                                     'value' => 'right',
                                    'label' => __( 'Right Sidebar', BUNCH_NAME ),
                                    'img' => get_template_directory_uri() . '/includes/vafpress/public/img/2cr.png' 
                                ),
                                array(
                                     'value' => 'full',
                                    'label' => __( 'Full Width', BUNCH_NAME ),
                                    'img' => get_template_directory_uri() . '/includes/vafpress/public/img/1col.png' 
                                ) 
                                
                            ) 
                        ),
                        array(
                             'type' => 'upload',
                            'name' => '404_page_bg',
                            'label' => __( 'Background  Image', BUNCH_NAME ),
                            'description' => __( 'Upload Image for 404 Page Background', BUNCH_NAME ),
                            'default' => get_template_directory_uri() . '/images/logo.png' 
                        ) 
                    ) 
                ) */
            ) 
        ),
        
        // Sidebar Creator
        array(
             'title' => __( 'Sidebar Settings', BUNCH_NAME ),
            'name' => 'sidebar-settings',
            'icon' => 'font-awesome:fa fa-bars',
            'controls' => array(
                 array(
                     'type' => 'builder',
                    'repeating' => true,
                    'sortable' => true,
                    'label' => __( 'Dynamic Sidebar', BUNCH_NAME ),
                    'name' => 'dynamic_sidebar',
                    'description' => __( 'This section is used for theme color settings', BUNCH_NAME ),
                    'fields' => array(
                         array(
                             'type' => 'textbox',
                            'name' => 'sidebar_name',
                            'label' => __( 'Sidebar Name', BUNCH_NAME ),
                            'description' => __( 'Choose the default color scheme for the theme.', BUNCH_NAME ),
                            'default' => __( 'Dynamic Sidebar', BUNCH_NAME ) 
                        ) 
                    ) 
                ) 
            ) 
        ),
        
        // Dynamic Social Media Creator
        array(
             'title' => __( 'Social Media ', BUNCH_NAME ),
            'name' => 'social_media',
            'icon' => 'font-awesome:fa fa-share-square',
            'controls' => array(
                 array(
                     'type' => 'builder',
                    'repeating' => true,
                    'sortable' => true,
                    'label' => __( 'Social Media', BUNCH_NAME ),
                    'name' => 'social_media',
                    'description' => __( 'This section is used to add Social Media.', BUNCH_NAME ),
                    'fields' => array(
                         array(
                             'type' => 'textbox',
                            'name' => 'title',
                            'label' => __( 'Title', BUNCH_NAME ),
                            'description' => __( 'Enter the title of the social media.', BUNCH_NAME ), 
                        ),
						 array(
                             'type' => 'textbox',
                            'name' => 'social_link',
                            'label' => __( 'Link', BUNCH_NAME ),
                            'description' => __( 'Enter the Link for Social Media.', BUNCH_NAME ),
                            'default' => '#'
                        ),
                        array(
                            'type' => 'select',
                            'name' => 'social_icon',
                            'label' => __( 'Icon', BUNCH_NAME ),
                            'description' => __( 'Choose Icon for Social Media.', BUNCH_NAME ),
							'items' => array(
								'data' => array(
									array(
										'source' => 'function',
										'value' => 'vp_get_social_medias',
									),
								),
							),
                        )  
                    ) 
                ) 
            ) 
        ),
        
        
        /* Font settings */
        array(
             'title' => __( 'Font Settings', BUNCH_NAME ),
            'name' => 'font_settings',
            'icon' => 'font-awesome:fa fa-font',
            'menus' => array(
                /** heading font settings */
                 array(
                     'title' => __( 'Heading Font', BUNCH_NAME ),
                    'name' => 'heading_font_settings',
                    'icon' => 'font-awesome:fa fa-text-height',
                    
                    'controls' => array(
                        
                         array(
                             'type' => 'toggle',
                            'name' => 'use_custom_font',
                            'label' => __( 'Use Custom Font', BUNCH_NAME ),
                            'description' => __( 'Use custom font or not', BUNCH_NAME ),
                            'default' => 0 
                        ),
                        array(
                            'type' => 'section',
                            'title' => __( 'H1 Settings', BUNCH_NAME ),
                            'name' => 'h1_settings',
                            'description' => __( 'heading 1 font settings', BUNCH_NAME ),
                            'dependency' => array(
                                 'field' => 'use_custom_font',
                                'function' => 'vp_dep_boolean' 
                            ),
                            'fields' => array(
                                 array(
                                     'type' => 'select',
                                    'label' => __( 'Font Family', BUNCH_NAME ),
                                    'name' => 'h1_font_family',
                                    'description' => __( 'Select the font family to use for h1', BUNCH_NAME ),
                                    'items' => array(
                                         'data' => array(
                                             array(
                                                 'source' => 'function',
                                                'value' => 'vp_get_gwf_family' 
                                            ) 
                                        ) 
                                    ) 
                                    
                                ),
                                
                                array(
                                     'type' => 'color',
                                    'name' => 'h1_font_color',
                                    'label' => __( 'Font Color', BUNCH_NAME ),
                                    'description' => __( 'Choose the font color for heading h1', BUNCH_NAME ),
                                    'default' => '#98ed28' 
                                ) 
                            ) 
                        ),
                        array(
                             'type' => 'section',
                            'title' => __( 'H2 Settings', BUNCH_NAME ),
                            'name' => 'h2_settings',
                            'description' => __( 'heading h2 font settings', BUNCH_NAME ),
                            'dependency' => array(
                                 'field' => 'use_custom_font',
                                'function' => 'vp_dep_boolean' 
                            ),
                            'fields' => array(
                                 array(
                                     'type' => 'select',
                                    'label' => __( 'Font Family', BUNCH_NAME ),
                                    'name' => 'h2_font_family',
                                    'description' => __( 'Select the font family to use for h2', BUNCH_NAME ),
                                    'items' => array(
                                         'data' => array(
                                             array(
                                                 'source' => 'function',
                                                'value' => 'vp_get_gwf_family' 
                                            ) 
                                        ) 
                                    ) 
                                ),
                                array(
                                     'type' => 'color',
                                    'name' => 'h2_font_color',
                                    'label' => __( 'Font Color', BUNCH_NAME ),
                                    'description' => __( 'Choose the font color for heading h1', BUNCH_NAME ),
                                    'default' => '#98ed28' 
                                ) 
                            ) 
                        ),
                        array(
                             'type' => 'section',
                            'title' => __( 'H3 Settings', BUNCH_NAME ),
                            'name' => 'h3_settings',
                            'description' => __( 'heading h3 font settings', BUNCH_NAME ),
                            'dependency' => array(
                                 'field' => 'use_custom_font',
                                'function' => 'vp_dep_boolean' 
                            ),
                            'fields' => array(
                                
                                 array(
                                     'type' => 'select',
                                    'label' => __( 'Font Family', BUNCH_NAME ),
                                    'name' => 'h3_font_family',
                                    'description' => __( 'Select the font family to use for h3', BUNCH_NAME ),
                                    'items' => array(
                                         'data' => array(
                                             array(
                                                 'source' => 'function',
                                                'value' => 'vp_get_gwf_family' 
                                            ) 
                                        ) 
                                    ) 
                                    
                                ),
                                array(
                                     'type' => 'color',
                                    'name' => 'h3_font_color',
                                    'label' => __( 'Font Color', BUNCH_NAME ),
                                    'description' => __( 'Choose the font color for heading h3', BUNCH_NAME ),
                                    'default' => '#98ed28' 
                                ) 
                            ) 
                        ),
                        
                        array(
                             'type' => 'section',
                            'title' => __( 'H4 Settings', BUNCH_NAME ),
                            'name' => 'h4_settings',
                            'description' => __( 'heading h4 font settings', BUNCH_NAME ),
                            'dependency' => array(
                                 'field' => 'use_custom_font',
                                'function' => 'vp_dep_boolean' 
                            ),
                            'fields' => array(
                                
                                 array(
                                     'type' => 'select',
                                    'label' => __( 'Font Family', BUNCH_NAME ),
                                    'name' => 'h4_font_family',
                                    'description' => __( 'Select the font family to use for h4', BUNCH_NAME ),
                                    'items' => array(
                                         'data' => array(
                                             array(
                                                 'source' => 'function',
                                                'value' => 'vp_get_gwf_family' 
                                            ) 
                                        ) 
                                    ) 
                                    
                                ),
                                array(
                                     'type' => 'color',
                                    'name' => 'h4_font_color',
                                    'label' => __( 'Font Color', BUNCH_NAME ),
                                    'description' => __( 'Choose the font color for heading h4', BUNCH_NAME ),
                                    'default' => '#98ed28' 
                                ) 
                            ) 
                        ),
                        
                        array(
                             'type' => 'section',
                            'title' => __( 'H5 Settings', BUNCH_NAME ),
                            'name' => 'h5_settings',
                            'description' => __( 'heading h5 font settings', BUNCH_NAME ),
                            'dependency' => array(
                                 'field' => 'use_custom_font',
                                'function' => 'vp_dep_boolean' 
                            ),
                            'fields' => array(
                                
                                 array(
                                     'type' => 'select',
                                    'label' => __( 'Font Family', BUNCH_NAME ),
                                    'name' => 'h5_font_family',
                                    'description' => __( 'Select the font family to use for h5', BUNCH_NAME ),
                                    'items' => array(
                                         'data' => array(
                                             array(
                                                 'source' => 'function',
                                                'value' => 'vp_get_gwf_family' 
                                            ) 
                                        ) 
                                    ) 
                                    
                                ),
                                array(
                                     'type' => 'color',
                                    'name' => 'h5_font_color',
                                    'label' => __( 'Font Color', BUNCH_NAME ),
                                    'description' => __( 'Choose the font color for heading h5', BUNCH_NAME ),
                                    'default' => '#98ed28' 
                                ) 
                            ) 
                        ),
                        
                        array(
                             'type' => 'section',
                            'title' => __( 'H6 Settings', BUNCH_NAME ),
                            'name' => 'h6_settings',
                            'description' => __( 'heading h6 font settings', BUNCH_NAME ),
                            'dependency' => array(
                                 'field' => 'use_custom_font',
                                'function' => 'vp_dep_boolean' 
                            ),
                            'fields' => array(
                                
                                 array(
                                     'type' => 'select',
                                    'label' => __( 'Font Family', BUNCH_NAME ),
                                    'name' => 'h6_font_family',
                                    'description' => __( 'Select the font family to use for h6', BUNCH_NAME ),
                                    'items' => array(
                                         'data' => array(
                                             array(
                                                 'source' => 'function',
                                                'value' => 'vp_get_gwf_family' 
                                            ) 
                                        ) 
                                    ) 
                                    
                                ),
                                array(
                                     'type' => 'color',
                                    'name' => 'h6_font_color',
                                    'label' => __( 'Font Color', BUNCH_NAME ),
                                    'description' => __( 'Choose the font color for heading h6', BUNCH_NAME ),
                                    'default' => '#98ed28' 
                                ) 
                            ) 
                        ) 
                    ) 
                ),
                
                /** body font settings */
                array(
                     'title' => __( 'Body Font', BUNCH_NAME ),
                    'name' => 'body_font_settings',
                    'icon' => 'font-awesome:fa fa-text-width',
                    'controls' => array(
                         array(
                             'type' => 'toggle',
                            'name' => 'body_custom_font',
                            'label' => __( 'Use Custom Font', BUNCH_NAME ),
                            'description' => __( 'Use custom font or not', BUNCH_NAME ),
                            'default' => 0 
                        ),
                        array(
                             'type' => 'section',
                            'title' => __( 'Body Font Settings', BUNCH_NAME ),
                            'name' => 'body_font_settings1',
                            'description' => __( 'body font settings', BUNCH_NAME ),
                            'dependency' => array(
                                 'field' => 'body_custom_font',
                                'function' => 'vp_dep_boolean' 
                            ),
                            'fields' => array(
                                
                                 array(
                                     'type' => 'select',
                                    'label' => __( 'Font Family', BUNCH_NAME ),
                                    'name' => 'body_font_family',
                                    'description' => __( 'Select the font family to use for body', BUNCH_NAME ),
                                    'items' => array(
                                         'data' => array(
                                             array(
                                                 'source' => 'function',
                                                'value' => 'vp_get_gwf_family' 
                                            ) 
                                        ) 
                                    ) 
                                    
                                ),
                                
                                array(
                                     'type' => 'color',
                                    'name' => 'body_font_color',
                                    'label' => __( 'Font Color', BUNCH_NAME ),
                                    'description' => __( 'Choose the font color for heading body', BUNCH_NAME ),
                                    'default' => '#686868' 
                                ) 
                            ) 
                        ) 
                    ) 
                ) 
            ) 
        ), 
		
		
    ) 
);
/**
 *EOF
 */