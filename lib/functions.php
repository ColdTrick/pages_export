<?php 
	
	function pages_export_get_subpages($page)
	{
		$children = elgg_get_entities_from_metadata(array('metadata_names' => 'parent_guid', 'metadata_values' => $page->getGUID(), 'limit' => 9999));
		
		return $children;
	}
	
	function pages_export_sanitize_file_name($string, $force_lowercase = true, $anal = false) 
	{
	    $strip = array("~", "`", "!", "@", "#", "$", "%", "^", "&", "*", "(", ")", "_", "=", "+", "[", "{", "]",
	                   "}", "\\", "|", ";", ":", "\"", "'", "&#8216;", "&#8217;", "&#8220;", "&#8221;", "&#8211;", "&#8212;",
	                   "—", "–", ",", "<", ">", "/", "?");
	    $clean = trim(str_replace($strip, "", strip_tags($string)));
	    $clean = preg_replace('/\s+/', "-", $clean);
	    $clean = ($anal) ? preg_replace("/[^a-zA-Z0-9]/", "", $clean) : $clean ;
	    return ($force_lowercase) ?
	        (function_exists('mb_strtolower')) ?
	            mb_strtolower($clean, 'UTF-8') :
	            strtolower($clean) :
	        $clean;
	}
	
	function pages_export_generate_index($page)
	{		
		$result = "";
		$options = array(
				"type" => "object",
				"subtype" => "page",
				"limit" => false,
				"metadata_name_value_pairs" => array("parent_guid" => $page->getGUID()),
				"order_by" => "e.time_created asc"
			);
			
		if($children = elgg_get_entities_from_metadata($options))
		{
			// apply optional sorting
			$ordered_children = array();
			
			foreach($children as $key => $child)
			{
				if(isset($child->order))
				{
					$order = (int) $child->order;
					$ordered_children[$order] = $child;
					unset($children[$key]);
				}
			} 
			ksort($ordered_children);
			
			$ordered_children = array_merge($ordered_children, $children);
			
			// build result
			$result .= "<ul>";
			foreach($ordered_children as $child_page)
			{
				$result .= "<li";
				 
				$can_edit = $child_page->canWriteToContainer();
				
				if(!$can_edit)
				{
					$result .= " rel='non_edit'";
				}
				$result .= "><a id='" . $child_page->getGUID() . "' title='" . $child_page->title . "'  href='#page_".$child_page->getGUID()."'>" . $child_page->title . "</a>";
				$result .= pages_export_generate_index($child_page, $can_edit); // traverse deeper into the tree
				$result .= "</li>";	
			}	
			$result .= "</ul>";
		}
		
		return $result;
	}
	
	function pages_export_get_pages_pdf($page)
	{		
		$result = "";
		$options = array(
				"type" => "object",
				"subtype" => "page",
				"limit" => false,
				"metadata_name_value_pairs" => array("parent_guid" => $page->getGUID()),
				"order_by" => "e.time_created asc"
			);
			
		if($children = elgg_get_entities_from_metadata($options))
		{
			// apply optional sorting
			$ordered_children = array();
			
			foreach($children as $key => $child)
			{
				if(isset($child->order))
				{
					$order = (int) $child->order;
					$ordered_children[$order] = $child;
					unset($children[$key]);
				}
			} 
			ksort($ordered_children);
			
			$ordered_children = array_merge($ordered_children, $children);
			
			// build result
			foreach($ordered_children as $child_page)
			{
				$result .= '<a name="page_'.$child_page->getGUID().'"><h3>'.$child_page->title.'</h3></a>';
				$result .= $child_page->description;
				$result .= '<p style="page-break-after:always;"></p>';
				$result .= pages_export_get_pages_pdf($child_page, $can_edit);
			}	
		}
		
		return $result;
	}