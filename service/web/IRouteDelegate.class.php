<?php
/**
 * route delegate
 *
 * @author liyan
 * @since  2018 1 22
 */
interface IRouteDelegate {

    public function notFound($uri);
    public function routeFinished($action);

}
