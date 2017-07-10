<?php

namespace Core\Domain;

/**
 * This class represents an identity map
 * design pattern.
 */
class ObjectWatcher
{
    private $all    = array();
    private $dirty  = array();
    private $new    = array();
    private $delete = array();

    /**
     * @var \Core\Domain\ObjectWatcher $instance
     */
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
        $inst = self::instance();
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

    /**
     * @param DomainObject $obj
     */
    public static function addDelete(DomainObject $obj)
    {
        $self = self::$instance;
        $self->delete[$self->globalKey($obj)] = $obj;
    }

    /**
     * @param DomainObject $obj
     */
    public static function addDirty(DomainObject $obj)
    {
        $inst = self::instance();
        if (!in_array($obj, $inst->new, true)) {
            $inst->dirty[$inst->globalKey($obj)] = $obj;
        }
    }

    /**
     * @param DomainObject $obj
     */
    public static function addNew(DomainObject $obj)
    {
        $inst = self::instance();
        $inst->new[] = $obj;
    }

    /**
     * @param DomainObject $obj
     */
    public static function addClean(DomainObject $obj)
    {
        $self = self::instance();
        unset($self->delete[$self->globalKey($obj)]);
        unset($self->dirty[$self->globalKey($obj)]);
        $self->new = array_filter($self->new, function ($a) use ($obj) {
            return !($a === $obj);
        });
    }

    /**
     * Perform operations.
     */
    public function performOperations()
    {
        foreach ($this->dirty as $key => $obj) {
            $obj->finder()->update($obj);
        }
        foreach ($this->new as $key => $obj) {
            $obj->finder()->insert($obj);
        }
        foreach ($this->delete as $key => $obj) {
            $obj->finder()->delete($obj);
        }
        $this->delete = [];
        $this->dirty  = [];
        $this->new    = [];
    }
}
