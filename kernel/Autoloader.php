<?php
/**
 * autoload
 *
 * @author liyan
 * @since  2013
 */
class Autoloader {

    protected $autoloadPath = [];
    protected $namespacePath = [];

    protected static $instance;

    public static function getInstance() {
        if (!self::$instance) {
            self::$instance = new Autoloader();
        }
        return self::$instance;
    }

    function __construct() {
        $this->autoloadPath = [];
        $this->namespacePath = [];
    }

    public static function addAutoloader($autoloader) {
        if (!is_callable($autoloader)) {
            throw new Exception("autoloader $autoloader is not callable", 1);
        }
        spl_autoload_register($autoloader);
    }

    public static function removeAutoloader($autoloader) {
        spl_autoload_register($autoloader);
    }

    private function _addAutoloadPath($path) {
        $key = md5(serialize($path));
        $this->autoloadPath[$key] = $path;
    }

    public function addAutoloadPath($path) {
        if (is_array($path)) {
            foreach ($path as $_path) {
                $this->addAutoloadPath($_path);
            }
        } else {
            $this->_addAutoloadPath($path);
        }
    }

    public function autoloadPaths() {
        return array_values($this->autoloadPath);
    }

    public function autoload($className) {
        foreach ($this->autoloadPaths() as $path) {
            $filepath = $path.$className.'.class.php';
            if (file_exists($filepath)) {
                require_once($filepath);
                return true;
            }
        }
        return false;
    }

}
