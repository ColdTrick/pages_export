<?php

$entity = $vars['entity'];

if($entity->canEdit())
{	
	$link = elgg_echo('pages_export:exportthispageto').' <a id="openExportPageForm" href="'.PAGE_EXPORT_BASEURL.'/export/page/'.$entity->getGUID().'"><img border="0" src="'.$vars['url'].'mod/pages_export/_graphics/icons/pdf_icon.gif" /></a>';
	
	echo elgg_view('page_elements/contentwrapper', array('body' => $link));
}