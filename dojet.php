<?php
define('DOJET', __DIR__.'/');

require DOJET.'kernel/global_function.php';
require DOJET.'kernel/Autoloader.php';

$autoloader = Autoloader::getInstance();
$autoloader->addAutoloadPath([
    DOJET.'kernel/',
    DOJET.'util/',
]);
Autoloader::addAutoloader([$autoloader, 'autoload']);
