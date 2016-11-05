<?php
/*
	Plugin Name: Category Description
	Plugin URI: 
	Plugin Description: Allows Category Description to be entered in HTML for the root Cateories
	Plugin Version: 1.0
	Plugin Date: 2016-07-23
	Plugin Author: Arjun Suresh
	Plugin Author URI: http://armi.in/arjun
	Plugin License: GPLv2
	Plugin Minimum Question2Answer Version: 1.5
	Plugin Update Check URI: 
*/

if (!defined('QA_VERSION')) { // don't allow this page to be requested directly from browser
	header('Location: ../../');
	exit;
}


qa_register_plugin_module(
        'widget', // type of module
        'qa-cat-desc-widget.php', // PHP file containing module class
        'qa_cat_descriptions_widget', // module class name in that PHP file
        'Category Descriptions' // human-readable name of module
);


/*register page module for 'creating'*/
qa_register_plugin_module(
'page', //type of module
'qa-cd-create.php',//php file containing module class
'qa_cd_create_page',//name of module class
'Category Description Creator Page'
);

qa_register_plugin_module(
'page', //type of module
'qa-cd-edit.php',//php file containing module class
'qa_cd_edit_page',//name of module class
'Category Description Edit Page'
);


qa_register_plugin_phrases(
        'qa-cat-desc-lang-*.php', // pattern for language files
        'plugin_cat_desc' // prefix to retrieve phrases
);






?>
