<?php
	global $CONFIG;
	
	define("PAGE_EXPORT_BASEURL", 	$CONFIG->wwwroot."pg/pages_export");

	function pages_export_init()
	{
		global $CONFIG;
		
		if(is_plugin_enabled('pages'))
		{

			require_once(dirname(__FILE__)."/vendors/dompdf/dompdf_config.inc.php");
			include_once(dirname(__FILE__)."/lib/functions.php");
			include_once(dirname(__FILE__)."/lib/hooks.php");
			
			elgg_extend_view("css", 				"pages_export/css");
			elgg_extend_view("css", 				"fancybox/css");
			elgg_extend_view("js/initialise_elgg", 	"pages_export/js");
			elgg_extend_view("metatags", 			"pages_export/metatags");
			
			elgg_extend_view("pages/pageprofile", 	"pages_export/page");
			
		}
		
		register_page_handler("pages_export", 	"pages_export_page_handler");
	}
	
	function pages_export_page_handler($page)
	{		
		global $CONFIG;
		
		if(!empty($page[2]))
		{
			set_input("guid", $page[2]);
		}
		
		if(file_exists(dirname(__FILE__)."/pages/".$page[0]."/".$page[1].".php"))
		{
			include(dirname(__FILE__)."/pages/".$page[0]."/".$page[1].".php");
		}
		else
		{
			forward(REFERER);
		}
	}

	function pages_export_pagesetup()
	{
		
	}

	// register default elgg events
	register_elgg_event_handler("init", "system", "pages_export_init");
	register_elgg_event_handler("pagesetup", "system", "pages_export_pagesetup");
	
	register_action("pages_export/export/page", false,dirname(__FILE__)."/actions/export/page.php");