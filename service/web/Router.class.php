<?php
/**
 * web router
 *
 * @author liyan
 * @since  2018 1 22
 */
class Router implements IRoutable {

    private static $routes = [];
    private $delegate;

    function __construct(IRouteDelegate $delegate) {
        $this->delegate = $delegate;
    }

    public static function add($routes) {
        self::$routes = array_merge(self::$routes, $routes);
    }

    public function route($uri) {
        $action = null;
        foreach (self::$routes as $routeRegx => $actionInfo) {
            if ( preg_match($routeRegx, $uri, $reg) ) {
                foreach ($reg as $key => $value) {
                    MRequest::param($key, $value);
                }
                $action = $actionInfo;
                break;
            }
        }

        if (is_null($action)) {
            $this->delegate->notFound($uri);
            return;
        }
        $this->delegate->routeFinished($action);
    }

}
