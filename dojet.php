<?php
define('DOJET', __DIR__.'/');

require DOJET.'util/global_function.php';
require DOJET.'kernel/Autoloader.php';

$autoloader = Autoloader::getInstance();
$autoloader->addAutoloadPath([
    DOJET.'kernel/',
    DOJET.'util/',
    DOJET.'service/',
    DOJET.'service/web/',
]);
Autoloader::addAutoloader([$autoloader, 'autoload']);

function startWebService(WebService $service) {
    $dojet = new Dojet();
    try {
        $dojet->start($service);
    } catch (Exception $e) {
        $error = 'exception occured, msg: '.$e->getMessage().' errno: '.$e->getCode();
        println($error);
        Trace::fatal($error);
    }
}
