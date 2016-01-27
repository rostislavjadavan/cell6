<?php

/**
 * Filesystem Helper
 *
 * @author spool
 */

namespace Core\Utils;

class Filesystem {

	/**
	 * Map
	 *
	 * Recusrively gets all files and folders in the directory, with an optional depth limit
	 *
	 * @param	string	the path to the folder
	 * @param	number	how many levels to process
	 * @return	array	The list of files and folders
	 */
	public static function map($path, $levels = NULL, $structured = FALSE, $files_first = FALSE) {
		// trim trailing slashes
		$levels = is_null($levels) ? -1 : $levels;
		$path = preg_replace('|/+$|', '', $path);

		// filesystem objects
		$files = array();
		$folders = array();
		$objects = array_diff(scandir($path), array('.', '..'));

		// check through
		foreach ($objects as $v) {
			$dir = $path . '/' . $v;
			if (is_dir($dir)) {
				$folders[$v] = $levels != 0 ? filesystem::map($dir, $levels - 1, $structured, $files_first) : $v;
			} else {
				array_push($files, $v);
			}
		}

		// return
		if ($structured) {
			return array('/folders' => $folders, '/files' => $files);
		} else {
			return $files_first ? array_merge($files, $folders) : array_merge($folders, $files);
		}
	}

	/**
	 * Get folders
	 *
	 * Returns all folders in the directory, excluding . and ..
	 *
	 * @param	string	path to the folder
	 * @param	string	Append the initial path to the return values
	 * @return	array	The list of folders
	 */
	public static function getFolders($path, $appendPath = false) {
		// objects
		$folders = array();
		$objects = array_diff(scandir($path), array('.', '..'));

		// match
		foreach ($objects as $object) {
			if (is_dir($path . $object)) {
				array_push($folders, $appendPath ? $path . $object : $object);
			}
		}

		// return
		return $folders;
	}

	/**
	 * Get files
	 *
	 * Returns all files in the directory with an optional regexp OR file extension mask
	 *
	 * @param	string	path to the folder
	 * @param	string	Regular expression or file extension to limit the search to	 
	 * @return	array	The list of files
	 */
	//print_r(filesystem::getFiles('/', array('ico', 'xml')));
	//print_r(filesystem::getFiles('/', '/(\.ico|\.xml)$/'));
	//print_r(filesystem::getFiles('/'));
	public static function getFiles($path, $mask = NULL) {
		// objects
		$files = array();
		//$path		= preg_replace('%/+$%', '/', $path . '/'); // add trailing slash
		$objects = array_diff(scandir($path), array('.', '..'));

		// mask
		if ($mask != NULL) {
			// regular expression for detecing a regular expression
			$rxIsRegExp = '/^([%|\/]|{).+(\1|})[imsxeADSUXJu]*$/';

			// an array of file extenstions
			if (is_array($mask)) {
				$mask = '%\.(' . implode('|', $mask) . ')$%i';
			}

			// if the supplied mask is NOT a regular expression...
			// assume it's a file extension and make it a regular expression
			else if (!preg_match($rxIsRegExp, $mask)) {
				$mask = "/\.$mask$/i";
			}
		}

		// match
		foreach ($objects as $object) {
			if (is_file($path . $object) && ($mask != NULL ? preg_match($mask, $object) : TRUE)) {
				array_push($files, $object);
			}
		}

		// Create objects
		$fileObjects = array();

		foreach ($files as $file) {
			$fileObjects[] = new File($path . $file);
		}

		return $fileObjects;
	}

	/**
	 * Delete Files
	 *
	 * Deletes all files and optionally folders from the path specfied
	 *
	 * @param	string	path to file
	 * @param	bool	delete contained directories
	 * @param	bool	delete root directory (this is the same as a recursive delete_all - use with caution!)
	 * @return	void
	 */
	public static function deleteFiles($path, $mask = NULL, $del_dir = FALSE, $del_root = FALSE, $level = 0) {
		// Trim the trailing slash
		$path = preg_replace('|/+$|', '', $path);

		// fail if a leading slash is encountered
		if ($level == 0 && preg_match('%^[\\\\/]+%', $path)) {
			trigger_error('filesystem::deletefiles - <span style="color:red">Absolute paths not allowed</span>', E_USER_WARNING);
			return;
		}

		// fail on directory error
		if (!$current_dir = @opendir($path)) {
			return;
		}

		// file mask
		if ($level == 0 && $mask != NULL) {
			// regular expression for detecing a regular expression
			$rxIsRegExp = '/^([%|\/]|{).+(\1|})[imsxeADSUXJu]*$/';

			// an array of file extenstions
			if (is_array($mask)) {
				$mask = '%\.(' . implode('|', $mask) . ')$%';
			}

			// if the supplied mask is NOT a regular expression...
			// assume it's a file extension and make it a regular expression
			else if (!preg_match($rxIsRegExp, $mask)) {
				$mask = "/\.$mask$/";
			}
		}

		// loop through files
		while (FALSE !== ($filename = @readdir($current_dir))) {
			if ($filename != "." and $filename != "..") {
				if (is_dir($path . '/' . $filename)) {
					filesystem::deleteFiles($path . '/' . $filename, $mask, $del_dir, $del_root, $level + 1);
				} else {
					if ($mask == NULL || preg_match($mask, $filename)) {
						unlink($path . '/' . $filename);
					}
				}
			}
		}
		@closedir($current_dir);

		// remove folders
		if ($del_dir && $level > 0) {
			@rmdir($path);
		}
		if ($del_root && $level == 0) {
			@rmdir($path);
		}
	}

}
