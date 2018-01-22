<?php
/**
 * web service
 *
 * @author liyan
 * @since 2014
 */
abstract class WebService extends Service implements IRouteDelegate {

    protected function router() {
        return new Router($this);
    }

    public function uriWillRoute($uri) {
        $uri = substr($uri, 1);
        return $uri;
    }

    public function dojetDidStart() {
        $uri = $this->uriWillRoute($_SERVER['REQUEST_URI']);
        $router = $this->router();
        Assert::assert_($router instanceof IRoutable, 'illegal router');
        $action = $router->route($uri);
    }

    public function routeFinished($action) {
        $classFile = $action.'.class.php';
        Assert::assertFileExists($classFile, 'action class not exists. file: '.$classFile);
        require $classFile;

        $className = basename($action);
        $actionIns = new $className;

        Assert::assert_($actionIns instanceof BaseAction, 'illegal action');

        $actionIns->execute();
    }

    public function notFound($uri) {
        header('HTTP/1.1 404 Not Found');
    }

}
