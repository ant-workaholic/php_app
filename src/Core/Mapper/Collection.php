<?php
namespace Core\Mapper;
/**
 * Class Collection
 * @package Core\Mapper
 */
abstract class Collection implements \Iterator
{
    protected $mapper;
    protected $total = 0;
    protected $raw = [];

    protected $result;
    protected $pointer = 0;
    protected $objects = [];

    /**
     * Collection constructor.
     * @param array|null $raw
     * @param MapperAbstract|null $mapper
     */
    public function __construct(array $raw = null, MapperAbstract $mapper = null)
    {
        if (!is_null($raw) && !is_null($mapper)) {
            $this->raw = $raw;
            $this->total = count($raw);
        }
        $this->mapper = $mapper;
    }

    /**
     * @param \Core\Domain\DomainObject $object
     */
    public function add(\Core\Domain\DomainObject $object)
    {
        $class = $this->targetClass();
        if (!($object instanceof $class)) {
            throw \Exception("This collection is of the {$class}");
        }
        $this->notifyAccess();
        $this->objects[$this->total] = $object;
        $this->total++;
    }

    /**
     * @return mixed
     */
    abstract function targetClass();

    protected function notifyAccess()
    {
        //TODO: Need to add a custom logic
    }

    /**
     * @param $num
     * @return mixed|null
     */
    private function getRow($num)
    {
        $this->notifyAccess();
        if ($num >= $this->total || $num < 0) {
            return null;
        }
        if (isset($this->objects[$num])) {
            return $this->objects[$num];
        }
        if (isset($this->raw[$num])) {
            $this->objects[$num] = $this->mapper->createObject($this->raw[$num]);
            return $this->objects[$num];
        }
        return null;
    }

    /**
     *
     */
    public function rewind()
    {
        $this->pointer = 0;
    }

    /**
     * @return mixed|null
     */
    public function current()
    {
        return $this->getRow($this->pointer);
    }

    /**
     * @return int
     */
    public function key()
    {
        return $this->pointer;
    }

    /**
     * @return mixed|null
     */
    public function next()
    {
        $row = $this->getRow($this->pointer);
        if ($row) {
            $this->pointer++;
        }
        return $row;
    }

    /**
     * This is an alternative way to use generator insteadof
     * iterators.
     *
     * @return \Generator
     */
    public function getGenerator()
    {
        for($i = 0; $i < $this->total; $i++) {
            yield($this->getRow($i));
        }
    }

    /**
     * @return bool
     */
    public function valid()
    {
        return (!is_null($this->current()));
    }
}