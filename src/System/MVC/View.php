<?php

/**
 * View
 *
 * @author spool
 */

namespace System\MVC;

class View {

	/**
	 * @var string Filename
	 */
	protected $file = null;

	/**
	 * @var array Params passed into view
	 */
	protected $params = array();

	/**
	 * @var string View content
	 */
	protected $content = "";

	/**
	 * Load view by given full class path
	 * @param type Name
	 * @param type Params
	 * @return \Core\View
	 */
	public static function load($name, $params = array()) {
		$filename = \System\Core\Container::get('loader')->getClassPath($name);
		if (!file_exists($filename)) {
			return View::factory(DEBUG_MODE ? "View $name not found." : "", $params);
		}
		$content = file_get_contents($filename);
		return View::factory($content, $params);
	}

	/**
	 * Load view
	 *
	 * @param String Name
	 * @param Array Params
	 * @return \Core\View
	 */
	public static function factory($content, $params = array()) {
		return new View($content, $params);
	}

	/**
	 * Init
	 * 
	 * @param string View name
	 * @param array Params
	 */
	public function __construct($content, $params = array()) {
		//$this->load($name);
		$this->content = $content;
		$this->params = $params;
	}

	/**
	 * Set param
	 * 
	 * @param string Key
	 * @param mixed Value
	 */
	public function setParam($key, $value) {
		$this->params[$key] = $value;
	}

	/**
	 * Set params
	 * 
	 * @param array Params	 
	 */
	public function setParams(array $params) {
		$this->params = array_merge($this->params, $params);
	}

	/**
	 * Get params
	 * 
	 * @return array Params
	 */
	public function getParams() {
		return $this->params;
	}

	/**
	 * Render view
	 * 
	 * @return string Output
	 */
	public function render() {
		ob_start();
		extract($this->params);
		echo eval('?>' . preg_replace("/;*\s*\?>/", "; ?>", str_replace('<?=', '<?php echo ', $this->translateCommonVars($this->content))) . '<?php ');
		$content = ob_get_contents();
		ob_end_clean();

		return $content;
	}

	/**
	 * Render view
	 * 
	 * @return string Output
	 */
	public function __toString() {
		return $this->render();
	}

	
	protected function translateCommonVars($content) {
		$baseUrl = \System\Core\Container::get('request')->getBaseUrl();
		$dict = array(
			'{BASEURL}' => $baseUrl,
			'{PUBURL}' => $baseUrl.PUBDIR,
			'{ASSESTSURL}' => $baseUrl.SYSDIR.US.'assets'
		);
		return strtr($content, $dict);
	}
}
