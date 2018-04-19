<?php
require __DIR__.DIRECTORY_SEPARATOR.'util/global_function.php';
require __DIR__.DIRECTORY_SEPARATOR.'kernel/Autoloader.php';

$autoloader = Autoloader::getInstance();
$autoloader->addAutoloadPath([
    __DIR__.DIRECTORY_SEPARATOR.'kernel/',
    __DIR__.DIRECTORY_SEPARATOR.'util/',
    __DIR__.DIRECTORY_SEPARATOR.'service/',
    __DIR__.DIRECTORY_SEPARATOR.'service/web/',
    __DIR__.DIRECTORY_SEPARATOR.'service/cli/',
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
