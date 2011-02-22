<?php
/**
 * Set permissions to a file, or folder recursively.
 *
 * @author      Aleksey Gureev <spyromus@noizeramp.com>
 * @version     1.0
 * @param       string   $filename	the name of a directory or a file. 
 * @param       int   	 $fmode		the mode to assign to files.
 * @param 		int		 $dmode		the mode to assign to directories.
 */
function chmodr($filename, $fmode, $dmode)
{
	if (is_file($filename)) {
		// Set file permissions
		chown($filename, $fmode);
	} else if (is_dir($filename)) {
		// Set directory permissions
		chown($filename, $dmode);
		
		// Recurse
	    $dir = dir($filename);
	    while (false !== $entry = $dir->read()) {

	        // Skip pointers
	        if ($entry == '.' || $entry == '..') {
	            continue;
	        }
	
	        chmodr("$filename/$entry", $fmode, $dmode);
	    }

	    // Clean up
	    $dir->close();
	}
}

?>