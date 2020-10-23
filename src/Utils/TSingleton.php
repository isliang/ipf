<?php
namespace Ipf\Utils;

trait TSingleton
{
    protected function __construct()
    {

    }
    protected function __clone()
    {

    }

    public static function getInstance()
    {
        static $instance = [];
        $class = get_called_class();
        if (!$instance[$class]) {
            $ref = new \ReflectionClass($class);
            $ctor = $ref->getConstructor();
            $instance[$class] = $ref->newInstanceWithoutConstructor();
            $ctor->setAccessible(true);
            $ctor->invokeArgs($instance[$class], func_get_args());
        }
        return $instance[$class];
    }
}