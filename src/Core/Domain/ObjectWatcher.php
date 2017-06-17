<?php

namespace Core\Domain;

/**
 * This class represents an identity map
 * design pattern.
 */
class ObjectWatcher
{
    private $all = array();
    private static $instance = null;

    private function __construct() {}

    /**
     * Retrieves an object watcher instance.
     *
     * @return null|ObjectWatcher
     */
    static function instance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new ObjectWatcher();
        }
        return self::$instance;
    }

    /**
     * Generates unique global key.
     *
     * @param \Core\Domain\DomainObject $object
     * @return string
     */
    public function globalKey(\Core\Domain\DomainObject $object)
    {
        $key = get_class($object) . "." . $object->getId();
        return $key;
    }

    /**
     * Adds a new object instance to the identity map.
     *
     * @param \Core\Domain\DomainObject $object
     */
    static function add(\Core\Domain\DomainObject $object)
    {
        $inst = self::$instance();
        $inst->all[$inst->globalKey($object)];
    }

    /**
     * Verifies if the object already exists.
     *
     * @param $classname
     * @param $id
     * @return mixed|null
     */
    public static function exists($classname, $id)
    {
        $inst = self::instance();
        $key = "{$classname}.{$id}";
        if (isset($inst->all[$key])) {
            return $inst->all[$key];
        }
        return null;
    }
}
