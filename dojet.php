<?php
define('DOJET', __DIR__.'/');

require DOJET.'kernel/global_function.php';
require DOJET.'kernel/Autoloader.php';

$autoloader = Autoloader::getInstance();
$autoloader->addAutoloadPath([
    'kernel/',
]);
Autoloader::addAutoloader([$autoloader, 'autoload']);

Assert::assert_(false, 'aaa');
