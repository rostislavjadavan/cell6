<?php


namespace Core;

/**
 * Class Controller
 * @package Core
 */
class Controller {

    protected $container = null;

    /**
     * Controller constructor.
     * @param Container $container
     */
    public function __construct(Container $container) {
        $this->container = $container;
    }

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
        return $this->container->make('\Core\HtmlResponse', array('content' => $content));
    }

    /**
     * JSON Output
     *
     * @param array $data
     * @return JsonResponse
     */
    public function json(array $data) {
        return $this->container->make('\Core\JsonResponse', array('content' => $data));
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
