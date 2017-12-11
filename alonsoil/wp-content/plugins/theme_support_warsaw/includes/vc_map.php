<?php
if( function_exists('bbpress') ) {
//Services
vc_map( array(
			"name" => __("bbPress Forums", BUNCH_NAME),
			"base" => "bbp-forum-index",
			"class" => "",
			"category" => __('Jollyall', BUNCH_NAME),
			"icon" => 'faqs' ,
			"params" => array(				
				array(
				   "type" => "textfield",
				   "holder" => "div",
				   "class" => "",
				   "heading" => __("Forum", BUNCH_NAME),
				   "param_name" => "forum",
				   "description" => ''
				),
				
			)
	    )
);
}
class WPBakeryShortCode_Bunch_Fun_Facts extends WPBakeryShortCodesContainer {
}
class WPBakeryShortCode_Bunch_Fun_Fact extends WPBakeryShortCode {
}
class WPBakeryShortCode_Bunch_Skills extends WPBakeryShortCodesContainer {
}
class WPBakeryShortCode_Bunch_Skill_Graph extends WPBakeryShortCode {
}

function bunch_custom_css_classes_for_vc_row_and_vc_column($class_string, $tag) {
	if($tag=='vc_row' || $tag=='vc_row_inner') {
		$class_string = str_replace('vc_row-fluid', 'row-fluid', $class_string);
	}
	if($tag=='vc_column' || $tag=='vc_column_inner') {
		$class_string = str_replace('vc_span1', 'col-md-1', $class_string);
		$class_string = str_replace('vc_span2', 'col-md-2', $class_string);
		$class_string = str_replace('vc_span3', 'col-md-3', $class_string);
		$class_string = str_replace('vc_span4', 'col-md-4', $class_string);
		$class_string = str_replace('vc_span5', 'col-md-5', $class_string);
		$class_string = str_replace('vc_span6', 'col-md-6', $class_string);
		$class_string = str_replace('vc_span7', 'col-md-7', $class_string);
		$class_string = str_replace('vc_span8', 'col-md-8', $class_string);
		$class_string = str_replace('vc_span9', 'col-md-9', $class_string);
		$class_string = str_replace('vc_span10', 'col-md-10', $class_string);
		$class_string = str_replace('vc_span11', 'col-md-11', $class_string);
		$class_string = str_replace('vc_span12', 'col-md-12', $class_string);
	}
	return $class_string;
}
// Filter to Replace default css class for vc_row shortcode and vc_column
//add_filter('vc_shortcodes_css_class', 'bunch_custom_css_classes_for_vc_row_and_vc_column', 10, 2);
$param = array(
  "type" => "checkbox",
  "holder" => "div",
  "class" => "",
  "heading" => __("Container", BUNCH_NAME),
  "param_name" => "container",
  "value" => array('Center Page'=>true),
  "description" => __("Choose whether you want to add a container before row or not.", BUNCH_NAME)
);
vc_add_param('vc_row', $param);
vc_add_param('vc_row_inner', $param);
