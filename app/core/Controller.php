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
     * @param int $code
     * @return HtmlResponse
     */
    public function html($content, $code = 200) {
        if ($content instanceof View) {
            $content = $content->render();
        }
        return $this->container->make('\Core\HtmlResponse', ['content' => $content, 'code' => $code]);
    }

    /**
     * JSON Output
     *
     * @param array $data
     * @param int $code
     * @return JsonResponse
     */
    public function json(array $data, $code = 200) {
        return $this->container->make('\Core\JsonResponse', ['content' => $data, 'code' => $code]);
    }

    /**
     * Render output using template view and content view
     *
     * @param String $content
     * @param String $template
     * @param array $data
     * @param int $code
     * @return String
     */
    public function template($content, $template, array $data = [], $code = 200) {
        $contentView = View::load($this->container, $content);
        $contentView->setParams($data);
        $templateView = View::load($this->container, $template);
        $templateView->setParams($data);
        $templateView->setParam('content', $contentView->render());
        return $this->html($templateView->render(), $code);
    }
}
