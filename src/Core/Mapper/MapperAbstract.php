<?php
/**
 * The base abstract mapper class
 * @package Core\Mapper
 */
namespace Core\Mapper;
use Core\Domain\DomainObject;

/**
 * Class MapperAbstract
 * @package Core\Mapper
 */
abstract class MapperAbstract
{
    protected static $PDO;

    protected $selectSmth;
    protected $updateSmth;
    protected $insertSmth;

    /**
     * Retrieve object from map
     */
    private function getFromMap($id)
    {
        return \Core\Domain\ObjectWatcher::exists($this->targetClass(), $id);
    }

    /**
     * Adds an object to a map
     *
     * @param \Core\Domain\DomainObject $object
     */
    private function addToMap(\Core\Domain\DomainObject $object)
    {
        return \Core\Domain\ObjectWatcher::add($object);
    }

    /**
     * MapperAbstract constructor.
     * @throws \Exception
     */
    public function __construct()
    {
        if (!isset(self::$PDO)) {
            $dsn = \Core\Registry\ApplicationRegistry::getDSN();
            if (is_null($dsn)) {
                throw new \Exception('DSN is not defined');
            }
            self::$PDO = new \PDO($dsn);
            self::$PDO->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        }
    }

    /**
     * Find an object by id
     *
     * @param $id
     * @return null
     */
    public function find($id)
    {
        $oldObject = $this->getFromMap($id);
        if (!is_null($oldObject)) {
            return $oldObject;
        }

        $this->selectSmth()->execute(array($id));
        $array = $this->selectSmth()->fetch();
        $this->selectSmth()->closeCursor();
        if (!is_array($array)) {
            return null;
        }
        if (!isset($array['id'])) {return null;}
        $object = $this->createObject($array);
        return $object;
    }

    /**
     * Create an object.
     *
     * @param $array
     * @return mixed
     */
    public function createObject($array)
    {
        $old = $this->getFromMap($array['id']);
        if (!is_null($old)) {
            return $old;
        }
        /** @var DomainObject $obj */
        $obj = $this->doCreateObject($array);
        $this->addToMap($obj);
        $obj->markClean();
        return $obj;
    }

    /**
     * Insert domain object
     *
     * @param \Core\Domain\DomainObject $object
     */
    public function insert(\Core\Domain\DomainObject $object)
    {
        $this->doInsert($object);
    }

    /**
     * Update element
     *
     * @param \Core\Domain\DomainObject $obj
     * @return mixed
     */
    abstract function update(\Core\Domain\DomainObject $obj);
    protected abstract function doCreateObject(array $data);
    abstract function delete(\Core\Domain\DomainObject $obj);
    protected abstract function doInsert(\Core\Domain\DomainObject $object);
    protected abstract function selectStmt();
    protected abstract function targetClass();
}
