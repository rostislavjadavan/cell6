<?php


namespace Core;

/**
 * Class Controller
 * @package Core
 */
class Controller {

    protected $container = null;
    protected $config = null;
    protected $router = null;

    /**
     * Controller constructor.
     * @param Container $container
     * @param Config $config
     */
    public function __construct(Container $container, Config $config) {
        $this->container = $container;
        $this->config = $config;
        $this->router = $this->container->make("\Core\Router");
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
     * View output
     *
     * @param $name
     * @param array $data
     * @param int $code
     * @return HtmlResponse
     */
    public function view($name, array $data =[], $code = 200) {
        return $this->html(View::load($this->container, $name, $data), $code);
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

    /**
     * Create url based on given route name
     *
     * @param $name
     * @param array $params
     * @param array $query
     * @return mixed
     */
    public function url($name, array $params = [], array $query = []) {
        return $this->router->url($name, $params, $query);
    }
}
