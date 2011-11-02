<?php 

	$guid = get_input('guid');
	$includesubpages = get_input('includesubpages');
	$includeindex = get_input('includeindex');
	$format = get_input('format', 'A4');
	$font = get_input('font', 'times');
	
	
	if(!empty($guid) && ($entity = get_entity($guid)))
	{
		if($entity->getSubtype() == 'page_top' || $entity->getSubtype() == 'page')
		{
			$page = $entity;
		}
	}
	
	if($page)
	{		
		if($includeindex)
		{
			$html .= '<h3>'.elgg_echo('page_export:export:index').'</h3>';
			$html .= '<ul><li><a id="' . $page->getGUID() . '" title="' . $page->title . '"  href="#page_' . $page->getGUID().'">' . $page->title . '</a>';
			
			if($includesubpages)
			{
				$html .= pages_export_generate_index($page);
			}
			
			$html .= '</li></ul><p style="page-break-after:always;"></p>';
		}
		
		$html .= '<a name="page_'.$page->getGUID().'"><h3>'.$page->title.'</h3></a>';
		$html .= $page->description;
		$html .= '<p style="page-break-after:always;"></p>';
		
		if($includesubpages)
		{
			$html .= pages_export_get_pages_pdf($page);
		}
		
		$dompdf = new DOMPDF();
		$dompdf->set_paper($format);
		$dompdf->load_html($html);	
		$dompdf->render();
		$dompdf->stream(pages_export_sanitize_file_name($page->title).".pdf");
		
		exit;
		
	}
	else
	{
		register_error("no guid");
		forward(REFERER);
	}