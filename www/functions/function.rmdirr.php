<?php
/**
 * Removes the directory recursively.
 *
 * @author      Aleksey Gureev <spyromus@noizeramp.com>
 * @version     1.0
 * @param       string   $filename	the name of a directory or a file. 
 */
function rmdirr($filename)
{
	$deleted = false;
	
	if (is_dir($filename)) {
		// Remove everything inside first
	    $dir = dir($filename);
	    while (false !== $entry = $dir->read()) {

	        // Skip pointers
	        if ($entry == '.' || $entry == '..') {
	            continue;
	        }
	
	        $path = $filename . DIR_SEP . $entry;

			// Delete files and recurse into directories
	        if (is_file($path)) {
	        	unlink($path);
	        } else rmdirr($path);
	    }
	    
	    // Cleanup
	    $dir->close();
	    
	    // Remove the directory itself
		$deleted = rmdir($filename);	    
	}

	return $deleted;
}

?>