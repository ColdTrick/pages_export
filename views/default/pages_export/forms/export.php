<?php 
$page = $vars['entity'];

if($page)
{
	$guid 	= $page->getGUID();
	$title 	= $page->title;
	
	$form_body .= 	'<h3 class="settings">'.elgg_echo('page_export:export:form:exporting').' "'.$title.'"</h3>';
	$form_body .= 	elgg_view('input/hidden', array('internalname' => 'guid', 'value' => $guid));
	
	$form_body .= 	'<label>'.elgg_echo('page_export:export:form:format').': </label><br />'.
					elgg_view('input/pulldown', array(	'internalname' => 'format',
														'value' => 'A4',
														'options_values' => array(	'A3' => 'A3',
																					'A4' => 'A4',
																					'A5' => 'A5',
																					'letter' => elgg_echo('page_export:export:form:format:letter')))).'<br /><br />';

	if(pages_export_get_subpages($page))
	{
		$form_body .= 	elgg_view('input/checkboxes', array('internalname' => 'includesubpages', 'value' => 0, 'options' => array(elgg_echo('page_export:export:form:includesubpages')=>'1')));
		$form_body .= 	elgg_view('input/checkboxes', array('internalname' => 'includeindex', 'value' => 0, 'options' => array(elgg_echo('page_export:export:form:includeindex')=>'1')));
	}
	
	$form_body .= 	elgg_view('input/submit', array('internalname' => 'submit', 'internalid' => 'page_export_submit', 'value' => elgg_echo('page_export:export:form:export')));
	
	echo elgg_view('input/form', array(	'internalid' 	=> 'page_export_form', 
											'internalname' 	=> 'page_export_form', 
											'action' 		=> $vars['url'].'action/pages_export/export/page',
											'body' 			=> $form_body));
}
else
{
	register_error('no guid');
}