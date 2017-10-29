<?php

namespace Core;

/**
 * Class Filesystem
 * @package Core
 */
class Filesystem {

	/**
	 * Map
	 *
	 * Recursively gets all files and folders in the directory, with an optional depth limit
	 *
	 * @param	string	the path to the folder
	 * @param	number	how many levels to process
	 * @return	array	The list of files and folders
	 */
	public static function map($path, $levels = NULL, $structured = FALSE, $files_first = FALSE) {
		$levels = is_null($levels) ? -1 : $levels;
		$path = preg_replace('|/+$|', '', $path);

		$files = array();
		$folders = array();
		$objects = array_diff(scandir($path), array('.', '..'));

		foreach ($objects as $v) {
			$dir = $path . '/' . $v;
			if (is_dir($dir)) {
				$folders[$v] = $levels != 0 ? filesystem::map($dir, $levels - 1, $structured, $files_first) : $v;
			} else {
				array_push($files, $v);
			}
		}

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
     * @param    string    path to the folder
     * @param bool $appendPath
     * @return array The list of folders
     */
	public static function getFolders($path, $appendPath = false) {
		$folders = array();
		$objects = array_diff(scandir($path), array('.', '..'));

		foreach ($objects as $object) {
			if (is_dir($path . $object)) {
				array_push($folders, $appendPath ? $path . $object : $object);
			}
		}

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
		$files = array();
		//$path		= preg_replace('%/+$%', '/', $path . '/'); // add trailing slash
		$objects = array_diff(scandir($path), array('.', '..'));

		if ($mask != NULL) {
			$rxIsRegExp = '/^([%|\/]|{).+(\1|})[imsxeADSUXJu]*$/';

			if (is_array($mask)) {
				$mask = '%\.(' . implode('|', $mask) . ')$%i';
			}

			else if (!preg_match($rxIsRegExp, $mask)) {
				$mask = "/\.$mask$/i";
			}
		}

		foreach ($objects as $object) {
			if (is_file($path . $object) && ($mask != NULL ? preg_match($mask, $object) : TRUE)) {
				array_push($files, $object);
			}
		}

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
     * @param $path
     * @param null $mask
     * @param bool $del_dir
     * @param bool $del_root
     * @param int $level
     * @return void
     * @throws RuntimeException
     * @internal param path $string to file
     * @internal param delete $bool contained directories
     * @internal param delete $bool root directory (this is the same as a recursive delete_all - use with caution!)
     */
	public static function deleteFiles($path, $mask = NULL, $del_dir = FALSE, $del_root = FALSE, $level = 0) {
		$path = preg_replace('|/+$|', '', $path);

		if ($level == 0 && preg_match('%^[\\\\/]+%', $path)) {
			throw new RuntimeException('Absolute paths not allowed');
			return;
		}

		if (!$current_dir = @opendir($path)) {
			return;
		}

		if ($level == 0 && $mask != NULL) {
			$rxIsRegExp = '/^([%|\/]|{).+(\1|})[imsxeADSUXJu]*$/';

			if (is_array($mask)) {
				$mask = '%\.(' . implode('|', $mask) . ')$%';
			}

			else if (!preg_match($rxIsRegExp, $mask)) {
				$mask = "/\.$mask$/";
			}
		}

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
        
		if ($del_dir && $level > 0) {
			@rmdir($path);
		}
		if ($del_root && $level == 0) {
			@rmdir($path);
		}
	}

}
