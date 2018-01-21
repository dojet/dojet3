<?php
/**
 * Trace
 *
 * @author liyan
 * @since  2009
 */
class Trace {

    const DEBUG = 0x1;
    const NOTICE= 0x2;
    const WARN  = 0x4;
    const ERROR = 0x8;
    const VERBOSE = 0x10;

    const TRACE_ALL = 0xffff;
    const TRACE_OFF = 0x0;

    protected $traceLevel;

    public static function getInstance() {
        if (is_null(self::$instance)) {

            self::$instance = new DTrace();
        }
        return self::$instance;
    }

    function __construct($traceLevel) {
        $this->traceLevel = $traceLevel;
    }

    public static function traceLevel() {
        return $this->traceLevel;
    }

    public static function debug($info) {

    }

}