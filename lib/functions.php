<?php 


		
	function hasSubpages($page)
	{
		$children = elgg_get_entities_from_metadata(array('metadata_names' => 'parent_guid', 'metadata_values' =>$page->getGUID(), 'limit' => 9999));
		
		return count($children);
	}
	
	function getSubpages($page)
	{
		$children = elgg_get_entities_from_metadata(array('metadata_names' => 'parent_guid', 'metadata_values' => $page->getGUID(), 'limit' => 9999));
		
		return $children;
	}
	
	function generateIndex($page)
	{
		$result = "";
		$options = array(
				"type" => "object",
				"subtype" => "page",
				"limit" => false,
				"metadata_name_value_pairs" => array("parent_guid" => $page->getGUID()),
			);
			
		
			if(is_plugin_enabled('pages_tree'))
			{
				if($children = elgg_get_entities_from_metadata($options))
				{
					$ordered_children = array();
					
					foreach($children as $key => $child)
					{
						if(isset($child->order))
						{
							$order = (int) $child->order;
							$ordered_children[$order] = $child;
							unset($children[$key]);
						}
						else
						{
							$no_order = true;
							break;
						}
					}
					
					if(!$no_order)
					{
						ksort($ordered_children);
						
						$ordered_children = array_merge($ordered_children, $children);
					}
					else
					{
						$options['order_by'] = 'e.time_created ASC';
						if($children = elgg_get_entities_from_metadata($options))
						{
							$ordered_children = $children;
						}
					}
				}
			}
			else
			{
				$options['order_by'] = 'e.time_created ASC';
				if($children = elgg_get_entities_from_metadata($options))
				{
					$ordered_children = $children;
				}
			}
			
			// build result
			$result .= "<ul>";
			foreach($ordered_children as $child_page)
			{
				$result .= '<li><a href="#page_'.$child_page->getGUID().'">'. $child_page->title.'</a>';
				$result .= generateIndex($child_page);
				$result .= "</li>";	
			}	
			$result .= "</ul>";
		
		return $result;
	}
	
	
	function getPages($page)
	{		
		$options = array(
				"type" => "object",
				"subtype" => "page",
				"metadata_name_value_pairs" => array("parent_guid" => $page->getGUID()),
			);
		
		
			if(is_plugin_enabled('pages_tree'))
			{
				if($children = elgg_get_entities_from_metadata($options))
				{
					$ordered_children = array();
					
					foreach($children as $key => $child)
					{
						if(isset($child->order))
						{
							$order = (int) $child->order;
							$ordered_children[$order] = $child;
							unset($children[$key]);
						}
						else
						{
							$no_order = true;
							break;
						}
					} 
					
					if(!$no_order)
					{
						ksort($ordered_children);
						
						$ordered_children = array_merge($ordered_children, $children);
					}
					else
					{
						$options['order_by'] = 'e.time_created ASC';
						if($children = elgg_get_entities_from_metadata($options))
						{
							$ordered_children = $children;
						}
					}
				}
			}
			else
			{
				$options['order_by'] = 'e.time_created ASC';
				if($children = elgg_get_entities_from_metadata($options))
				{
					$ordered_children = $children;
				}
			}
			
			// build result
			
			foreach($ordered_children as $child_page)
			{
				$result[] = $child_page->getGUID();
				
				if($kids = getPages($child_page))
				{
					foreach($kids as $key => $value)
					{
						$result[] = $value;
					}
				}
			}	
		
		return $result;
	}
	
	function sanitize_file_name($string, $force_lowercase = true, $anal = false) 
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