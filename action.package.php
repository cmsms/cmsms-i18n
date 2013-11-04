<pre><?php
if (!isset($gCms)) exit;

if (! $this->CheckAccess())
	{
	return $this->DisplayErrorPage($id, $params, $returnid,$this->Lang('accessdenied'));
	}

/* -=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-

   Code for I18n "defaultadmin" admin action
   
   -=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-
   
   Typically, this will display something from a template
   or do some other task.
   
*/
$dir = dirname(__FILE__) . '/libraries/prado/I18N/core/data/';
$package = dirname(__FILE__) . '/libraries/prado_datas.pkg';
$datas = array();

if (is_dir($dir)) 
{
	$prefiles = scandir($dir);
	$files = array();
	foreach($prefiles as $file)
	{
		if ($file != '.' && $file != '..')
		{
			$files[] = $file;
		}
	}
	
	if (count($files) > 0)
	{
		// We do the packaging
		echo 'Packaging it';
		
		// Make the datas
		foreach($files as $file)
		{
			$datas[$file] = base64_encode(file_get_contents($dir . $file));
		}
		// Write datas in file
		file_put_contents($package, base64_encode(serialize($datas)));
		
	}
	
	
	/*
	
	
    if ($dh = opendir($dir)) {
        while (($file = readdir($dh)) !== false) {
		
			$datas[$file] = base64_encode(file_get_contents($dir . $file));
	
            //echo "filename: $file : filetype: " . filetype($dir . $file) . "\n";
        }
        closedir($dh);
    }
	*/
	

}

?></pre>