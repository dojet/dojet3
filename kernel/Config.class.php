<?php
/**
 * Config
 *
 * @author liyan
 * @since 2013
 */
class Config {

    private static $config;

    private static function _loadConfig($confFile) {
        $filename = sprintf("%s/%s.conf.php", $confFile, Config::get('runtime'));
        if (!file_exists($filename)) {
            $filename = $confFile.'.conf.php';
        }
        Assert::assertFileExists($filename, 'config file not exist! '.$filename);
        require($filename);
    }

    public static function load($files) {
        if (is_array($files)) {
            foreach ($files as $confFile) {
                self::_loadConfig($confFile);
            }
        } else {
            self::_loadConfig($files);
        }
    }

    public static function set($keyPath, $conf, &$config = null) {
        if (is_null($config)) {
            $config = &self::$config;
        }
        $key = strtok($keyPath, '.');
        while ($key) {
            if (!is_array($config)) {
                $config = [];
            }

            if (!key_exists($key, (array)$config)) {
                $config[$key] = [];
            }
            $config = &$config[$key];
            $key = strtok('.');
        }

        if (is_array($conf)) {
            $config = array_merge_recursive($config, $conf);
        } else {
            $config = $conf;
        }
    }

    /**
     * 通过keypath获取value
     * keypath是以'.'分割的字符串
     *
     * @param string $keyPath
     * @return mix
     */
    public static function get($keyPath, $config = null, $default = null) {
        if (is_null($config)) {
            $config = self::$config;
        }
        $value = XPath::path($keyPath, $config);
        if (is_null($value)) {
            $value = $default;
        }
        return $value;
    }

    /**
     * 获取runtime下的配置项信息
     *
     * @param string $keyPath
     * @param string $runtime
     * @return mix
     */
    public static function getByRuntime($keyPath, $runtime = null, $config = null, $default = null) {
        is_null($runtime) and $runtime = Config::get('runtime');
        if (is_null($runtime)) {
            throw new Exception("runtime not set", 1);
        }

        if (false !== strpos($keyPath, '.$.')) {
            $runtimeKeypath = str_replace('.$.', '.'.$runtime.'.', $keyPath);
        } else {
            $runtimeKeypath = $keyPath.'.'.$runtime;
        }

        return Config::get($runtimeKeypath, $config, $default);
    }

    public static function conf() {
        return self::$config;
    }

}
