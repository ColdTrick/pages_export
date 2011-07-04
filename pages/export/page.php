<?php 

	gatekeeper();
	
	$guid = get_input('guid');
	
	if(!empty($guid) && ($entity = get_entity($guid)))
	{
		if($entity->getSubtype() == 'page_top' || $entity->getSubtype() == 'page')
		{
			$page = $entity;
			
			if($page->canEdit())
			{	
				add_submenu_item(elgg_echo('page_export:export:backtopage'), $page->getURL());
			
				$form = elgg_view('pages_export/forms/export', array('entity' => $page));
				
				$form_body = elgg_view('page_elements/contentwrapper', array('body' => $form));
				
				$title = elgg_view_title($title_text);
				
				echo $form_body;
				
			}		
		}
	}
	else 
	{
		echo elgg_echo('error');
	}