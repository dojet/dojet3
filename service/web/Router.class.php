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
        foreach (self::$routes as $routeRegx => $actionInfo) {
            if ( preg_match($routeRegx, $uri, $reg) ) {
                foreach ($reg as $key => $value) {
                    MRequest::param($key, $value);
                }
                $action = $actionInfo;
                return $this->delegate->routeFinished($action);
            }
        }

        return $this->delegate->notFound($uri);
    }

}
