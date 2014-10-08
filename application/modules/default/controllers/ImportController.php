<?php
class ImportController extends Controller{
	const URL_ROOT_UPLOAD = '/var/parameterSheet/';

	public function requestAction() {
		
		$file = $_FILES['filename']['name'];
		
		$content = file_get_contents($file);
		
		$content_x = explode("<staff-list>", $content);
		
		echo "<pre>";
		print_r($content_x);
		echo "</pre>";exit;
		
		$reset($content_x);
		
		foreach($content_x as $item)
		{
			
		}
		
		
		
		
	}
}