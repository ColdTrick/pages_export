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
		$pages[] = $page->getGUID();
		
		if($includesubpages)
		{
			if(hasSubpages($page))
			{
				$subs = true;
				
				$pages = array_merge($pages, getPages($page));
				
				if($includeindex)
				{
					$html .= '<h3>'.elgg_echo('page_export:export:index').'</h3>';
					$html .= '<ul>
								<li><a href="#page_'.$page->getGUID().'">'.$page->title.'</a>';
						
						$html .= generateIndex($page);
						
					$html .= '</li>
							</ul>'; 
					$html .= '<p style="page-break-after:always;"></p>';
				}
			}
		}
		
		foreach($pages as $subpage)
		{
			$subpage = get_entity($subpage);
			$html .= '<a name="page_'.$subpage->getGUID().'"><h3>'.$subpage->title.'</h3></a>';
			$html .= $subpage->description;
			$html .= '<p style="page-break-after:always;"></p>';
		}		
		
		$dompdf = new DOMPDF();
		$dompdf->set_paper($format);
		$dompdf->load_html($html);	
		$dompdf->render();
		$dompdf->stream(sanitize_file_name($page->title).".pdf");
		exit;
		
	}
	else
	{
		register_error("no guid");
		forward(REFERER);
	}
	exit;