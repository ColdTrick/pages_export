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
		$pdf = new HTML2FPDF('P', 'mm', $format);
		
		$pages[] = $page->getGUID();
		
		if($includesubpages)
		{
			if(hasSubpages($page))
			{
				$subs = true;
				
				$pages = array_merge($pages, getPages($page));
				
				if($includeindex)
				{
					$index = '<h3>'.elgg_echo('page_export:export:index').'</h3>';
					$index .= '<ul>
								<li><a href="#page_'.$page->getGUID().'">'.$page->title.'</a>';
						
						$index .= generateIndex($page);
						
					$index .= '</li>
							</ul>'; 
					
					$pdf->AddPage();
					$pdf->WriteHTML($index);
				}
			}
		}
		
		foreach($pages as $subpage)
		{
			$subpage = get_entity($subpage);
			$html = '<a name="page_'.$subpage->getGUID().'"></a><h3>'.$subpage->title.'</h3>';
			$html .= $subpage->description;
			
			$pdf->AddPage();
			$pdf->WriteHTML($html);
		}
		
		$pdf->Output(sanitize_file_name($page->title).".pdf", 'D');
		exit;
	}
	else
	{
		register_error("no guid");
		forward(REFERER);
	}