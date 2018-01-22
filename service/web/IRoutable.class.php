<?php
/**
 * router interface
 *
 * @author liyan
 * @since  2018 1 22
 */
interface IRoutable {

    function __construct(IRouteDelegate $delegate);

    public function route($uri);

}
