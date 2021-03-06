<?php
/**
 * global functions
 *
 * @author liyan
 * @since  2009
 */

// charset convert

function gb2u($s) {
    return _iconvEx('GB18030', 'UTF-8', $s);
}

function u2gb($s) {
    return _iconvEx('UTF-8', 'GB18030', $s);
}

function _iconvEx($in_charset, $out_charset, $str) {
    $ret = null;
    if ( is_array($str) ) {
        foreach ( $str as $key => $value ) {
            $ret[$key] = _iconvEx($in_charset, $out_charset, $value);
        }
    } else {
        $ret = @iconv($in_charset, $out_charset, $str);
    }
    return $ret;
}

// array

function array2keyarray($array, $key) {
    $ret = array();
    foreach ($array as $item) {
        if (!isset($item[$key])) {
            throw new Exception('key not exist', -1);
        }
        $ret[$item[$key]] = $item;
    }
    return $ret;
}

function array_values_recursive($array) {
    $values = array();
    if (is_array($array)) {
        foreach ($array as $value) {
            $values = array_merge($values, array_values_recursive($value));
        }
    } else {
        $values[] = $array;
    }
    return $values;
}

function array_val($key, $array, $default = null) {
    if (key_exists($key, $array)) {
        return $array[$key];
    }
    return $default;
}

function array_column_recursive($array, $columnPath) {
    $keypath = explode('.', $columnPath);
    $key = array_shift($keypath);

    $columnValue = array_column($array, $key);
    if (count($keypath) === 0) {
        return $columnValue;
    }

    $ret = array();
    $nextKeypath = join('.', $keypath);
    foreach ($columnValue as $item) {
        $ret[] = array_column_recursive($item, $nextKeypath);
    }
    return $ret;
}

function array_group($array, $key) {
    $ret = array();
    foreach ($array as $value) {
        $ret[$value[$key]][] = $value;
    }
    return $ret;
}

function array_column_unique($array, $column) {
    $ret = array();
    foreach ($array as $value) {
        $ret[] = $value[$column];
    }
    return array_unique($ret);
}

function array_keypath($array, $keypath) {
    if (!is_array($array)) {
        return null;
    }
    $path = explode('.', $keypath);
    $key = array_shift($path);
    if (!isset($array[$key])) {
        return null;
    }
    if (count($path) === 0) {
        return $array[$key];
    }
    return array_keypath($array[$key], join('.', $path));
}

function array_column_keypath($array, $keypath) {
    $ret = array();
    foreach ($array as $item) {
        $val = array_keypath($item, $keypath);
        if (is_null($val)) {
            continue;
        }
        $ret[] = $val;
    }
    return $ret;
}

function array_node($array) {
    if (!is_array($array)) {
        return array($array);
    }

    $ret = array();
    foreach ($array as $e) {
        $ret = array_merge($ret, array_node($e));
    }
    return $ret;
}

// print

function printbr($str = '', $flush = true) {
    if ( is_array($str) ) {
        $str = print_r($str, true);
    }
    $str = str_replace(" ", "&nbsp;", $str);
    $str = nl2br($str);
    print $str."<br />";
    if ( $flush ) flush();
}

function println($str = '', $flush = true){
    if ( is_array($str) ) {
        $str = print_r($str, true);
    }
    print $str."\n";
    if ( $flush ) flush();
}

function printl($v) {
    println(join("\t", func_get_args()));
}

function printa($array) {
    print nl2br(str_replace(array(' ', "\t"), '&nbsp;', print_r($array, true)));
}

function printlog($str = '') {
    $log = sprintf("%s %s %s %s", date("c"), posix_getpid(), crc32(uniqid().microtime(true)), $str);
    println($log, true);
}

// date & time

function datetime($time = null) {
    is_null($time) && $time = time();
    return date("Y-m-d H:i:s", $time);
}

function timems() {
    return round(microtime_float() * 1000);
}

// http

function redirect($location) {
    @header("Location: $location");
    exit();
}

function safeHtml($html) {
    return htmlspecialchars($html, ENT_QUOTES);
}

function safeUrl($url) {
    $url = str_replace('&amp;', '&', $url);
    $url = str_replace('&', '&amp;', $url);

    return $url;
}

function safeUrlencode($str) {
    return urlencode($str);
}

// misc

function safeNew($className) {
    if (!class_exists($className, true)) {
        return null;
    }
    $obj = new $className;
    return $obj;
}

function safeCallMethod($obj, $func) {
    $args = array();
    for ($i = 2; $i < func_num_args(); $i++) {
        $arg = func_get_arg($i);
        $args[] = &$arg;
        unset($arg);
    }

    $funcCall = array($obj, $func);
    if (is_callable($funcCall, true)) {
        return call_user_func_array($funcCall, $args);
    }
    return null;
}

function setValueIfNull(&$var, $defaultValue) {
    $var = null === $var ? $defaultValue : $var;
}

function setValueIfEmpty(&$var, $defaultValue) {
    $var = empty($var) ? $defaultValue : $var;
}

function defaultNullValue($var, $defaultValue = null) {
    return is_null($var) ? $defaultValue : $var;
}

function defaultEmptyValue($var, $defaultValue = null) {
    $defaultValue = defaultNullValue($defaultValue, $var);
    return empty($var) ? $defaultValue : $var;
}

function is_empty($var) {
    for ($i = 0; $i < func_num_args(); $i++) {
        $arg = func_get_arg($i);
        if (empty($var)) {
            return true;
        }
    }
    return false;
}

if (!function_exists('fastcgi_finish_request')) {
    function fastcgi_finish_request() {
        Trace::warn('function fastcgi_finish_request() not exist', __FILE__, __LINE__);
    }
}

/**
 * 获取客户端IP
 *
 * @param string $strDefaultIp
 * @return string
 */
function getUserClientIp($strDefaultIp = '0.0.0.0')
{
    $strIp = '';
    if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $strIp = strip_tags($_SERVER['HTTP_X_FORWARDED_FOR']);
        $intPos = strpos($strIp, ',');
        if ($intPos > 0) {
            $strIp = substr($strIp, 0, $intPos);
        }
    } elseif (isset($_SERVER['HTTP_CLIENTIP'])) {
        $strIp = strip_tags($_SERVER['HTTP_CLIENTIP']);
    } elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
        $strIp = strip_tags($_SERVER['HTTP_CLIENT_IP']);
    } elseif (isset($_SERVER['REMOTE_ADDR'])) {
        $strIp = strip_tags($_SERVER['REMOTE_ADDR']);
    }
    $strIp = trim($strIp);
    if (empty($strIp) || !ip2long($strIp)) {
        $strIp = $strDefaultIp;
    }
    return $strIp;
}

function getRequestUrl() {
    if (isset($HTTP_SERVER_VARS['REQUEST_URI'])) {
        return $HTTP_SERVER_VARS['REQUEST_URI'];
    }

    if (isset($_SERVER['SCRIPT_URI'])) {
        return $_SERVER['SCRIPT_URI'];
    }

    if (isset($_SERVER['HTTP_HOST']) && isset($_SERVER['REQUEST_URI'])) {
        return 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
    }

    return '';
}

if (!function_exists('posix_getpid')) {
    function posix_getpid() {
        return 0;
    }
}
