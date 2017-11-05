<?php

namespace Core;

/**
 * Class View
 * @package Core
 */
class View {

    /**
     * @var null Container
     */
    protected $container = null;

    /**
     * @var string Filename
     */
    protected $file = null;

    /**
     * @var array Params passed into view
     */
    protected $params = [];

    /**
     * @var string View content
     */
    protected $content = "";

    /**
     * Load view by given full class path
     * @param Container $container
     * @param type Name
     * @param array $params
     * @return View
     */
    public static function load(Container $container, $name, $params = []) {
        $filename = $container->make("\Core\ClassAutoLoader")->getClassPath('\views\\' . $name);
        if (!file_exists($filename)) {
            return new View($container, '<span style="color:red">View ' . $name . ' not found.</span>', $params);

        }
        return new View($container, file_get_contents($filename), $params);
    }

    /**
     * View constructor.
     *
     * @param Container $container
     * @param $content
     * @param array $params
     */
    public function __construct(Container $container, $content, $params = []) {
        $this->container = $container;
        $this->content = $content;
        $this->params = $params;
    }

    /**
     * Set param
     *
     * @param string $key Key
     * @param mixed $value Value
     */
    public function setParam($key, $value) {
        $this->params[$key] = $value;
    }

    /**
     * Set params
     *
     * @param array $params Params
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

    /**
     * Translate view placeholders
     *
     * @param $content
     * @return string
     */
    protected function translateCommonVars($content) {
        $baseUrl = $this->container->make("\Core\Request")->getBaseUrl();
        $dict = ['{BASEURL}' => $baseUrl, '{PUBURL}' => $baseUrl . PUBDIR];
        return strtr($content, $dict);
    }
}
