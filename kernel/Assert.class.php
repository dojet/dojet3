<?php
/**
 * Assert
 *
 * @author liyan
 * @since  2014
 */
class Assert {

    /**
     * 封装断言
     *
     * @param bool $condition
     * @param string $message
     */
    public static function assert_($condition, $message = null) {

        if ($condition) {
            return ;
        }

        $backtrace = debug_backtrace();
        $file = $line = '';
        foreach ($backtrace as $trace) {
            if ($trace['class'] !== 'DAssert') {
                break;
            }
            $file = $trace['file'];
            $line = $trace['line'];
        }

        Trace::fatal('assert failed. '.$message.', '.$file.', '.$line);
        println('assert failed. '.$message.', '.$file.', '.$line);

        // assert($condition);
        die();
    }

    public static function assertNumeric($var) {
        $args = func_get_args();
        foreach ($args as $var) {
            DAssert::assert_(is_numeric($var), 'nan, '.var_export($var, true));
        }
    }

    /**
     * 数字数组断言
     *
     * @param mix $array
     */
    public static function assertNumericArray($array) {
        DAssert::assertArray($array);
        foreach ($array as $val) {
            DAssert::assertNumeric($val);
        }
    }

    /**
     * 非空数字数组断言
     *
     * @param mix $array
     */
    public static function assertNotEmptyNumericArray($array) {
        DAssert::assert_(!empty($array), 'array should not be empty');
        DAssert::assertNumericArray($array);
    }

    public static function assertArray($var, $message = null) {
        DAssert::assert_(is_array($var), defaultNullValue($message, 'not an array'));
    }

    public static function assertKeyExists($key, $array, $message = null) {
        DAssert::assert_(array_key_exists($key, $array), defaultNullValue($message, 'key not exists'));
    }

    public static function assertFileExists($filename, $message = null) {
        DAssert::assert_(file_exists($filename), defaultNullValue($message, "$filename not exists"));
    }

    public static function assertNotFalse($condition, $message = null) {
        DAssert::assert_(false !== $condition, defaultNullValue($message, "value can not be false"));
    }

    public static function assertNotNull($condition, $message = null) {
        DAssert::assert_(null !== $condition, defaultNullValue($message, "value can not be null"));
    }

    public static function assertString($var, $message = null) {
        DAssert::assert_(is_string($var), defaultNullValue($message, 'not a string'));
    }

    public static function assertCallable() {
        # code...
    }

}
