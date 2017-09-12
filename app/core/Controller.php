<?php

/**
 * Controller
 *
 * @package MVC
 * @author spool
 */

namespace Core;

class Controller {
	
	/**
	 * HTML output
	 * 
	 * @param View|String $content
	 * @return HtmlResponse
	 */
	public function html($content) {
		if ($content instanceof View) {
			$content = $content->render();
		}
		return Container::build('\Core\Response', array('content' => $content));
	}
	
	/**
	 * JSON Output
	 * 
	 * @param array $data
	 * @return JsonResponse
	 */
	public function json(array $data) {
		return Container::build('\Core\JsonResponse', array('content' => $data));
	}
	
	/**
	 * Render output using template view and content view
	 * 
	 * @param String $content
	 * @param String $template
	 * @param array $data
	 * @return String
	 */
	public function template($content, $template, array $data = array()) {
		$contentView = View::load($content);
		$contentView->setParams($data);
		$templateView = View::load($template);
		$templateView->setParams($data);
		$templateView->setParam('content', $contentView->render());
		return $templateView->render();
	}
}
