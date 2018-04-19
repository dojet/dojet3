<?php
/**
 * Action基类
 *
 * @author liyan
 * @since 2012.12.24
 */
abstract class BaseAction {

    protected $tplData;

    final function __construct(WebService $webService) {
        $this->tplData = [];
        $this->init($webService);
    }

    protected function init(WebService $webService) {

    }

    /**
     * 为模板变量赋值
     *
     * @param string $key
     * @param mix $value
     */
    protected function assign($key, $value) {
        $this->tplData[$key] = $value;
    }

    /**
     * 渲染模板
     *
     * @param string $template
     */
    protected function display($template) {
        if (Config::get('is_debug')) {
            return $this->displayDebug();
        }

        $templateFile = $template;
        DAssert::assertFileExists($templateFile, 'template not exist. '.$template);

        $collision = extract($this->tplData, EXTR_PREFIX_ALL, 'tpl');

        include($templateFile);
    }

    protected function display404() {
        header('HTTP/1.1 404 Not Found', true);
        exit();
    }

    private function displayDebug() {
        printa($this->tplData);
    }

    protected function templatePrefix($template) {
        return '';
    }

    protected function displayTemplate($template) {
        $prefix = $this->templatePrefix($template);
        $template = $prefix.$template;
        return $this->display($template);
    }

    abstract public function execute();

}
