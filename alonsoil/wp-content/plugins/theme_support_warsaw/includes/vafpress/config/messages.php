<?php



return array(



	////////////////////////////////////////

	// Localized JS Message Configuration //

	////////////////////////////////////////



	/**

	 * Validation Messages

	 */

	'validation' => array(

		'alphabet'     => __('Value needs to be Alphabet', BUNCH_NAME),

		'alphanumeric' => __('Value needs to be Alphanumeric', BUNCH_NAME),

		'numeric'      => __('Value needs to be Numeric', BUNCH_NAME),

		'email'        => __('Value needs to be Valid Email', BUNCH_NAME),

		'url'          => __('Value needs to be Valid URL', BUNCH_NAME),

		'maxlength'    => __('Length needs to be less than {0} characters', BUNCH_NAME),

		'minlength'    => __('Length needs to be more than {0} characters', BUNCH_NAME),

		'maxselected'  => __('Select no more than {0} items', BUNCH_NAME),

		'minselected'  => __('Select at least {0} items', BUNCH_NAME),

		'required'     => __('This is required', BUNCH_NAME),

	),



	/**

	 * Import / Export Messages

	 */

	'util' => array(

		'import_success'    => __('Import succeed, option page will be refreshed..', BUNCH_NAME),

		'import_failed'     => __('Import failed', BUNCH_NAME),

		'export_success'    => __('Export succeed, copy the JSON formatted options', BUNCH_NAME),

		'export_failed'     => __('Export failed', BUNCH_NAME),

		'restore_success'   => __('Restoration succeed, option page will be refreshed..', BUNCH_NAME),

		'restore_nochanges' => __('Options identical to default', BUNCH_NAME),

		'restore_failed'    => __('Restoration failed', BUNCH_NAME),

	),



	/**

	 * Control Fields String

	 */

	'control' => array(

		// select2 select box

		'select2_placeholder' => __('Select option(s)', BUNCH_NAME),

		// fontawesome chooser

		'fac_placeholder'     => __('Select an Icon', BUNCH_NAME),

	),



);



/**

 * EOF

 */