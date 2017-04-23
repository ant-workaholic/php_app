<?php
namespace Core\Mapper;

/**
 * The base abstract mapper class
 * @package Core\Mapper
 */
abstract class MapperAbstract
{
    protected static $PDO;

    protected $selectSmth;
    protected $updateSmth;
    protected $insertSmth;

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
     * @param $id
     * @return null
     */
    public function find($id)
    {
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
     * @param $array
     * @return mixed
     */
    public function createObject($array)
    {
        $obj = $this->doCreateObject($array);
        return $obj;
    }

    /**
     * @param \Core\Domain\DomainObject $object
     */
    public function insert(\Core\Domain\DomainObject $object)
    {
        $this->doInsert($object);
    }

    /**
     * @param \Core\Domain\DomainObject $obj
     * @return mixed
     */
    abstract function update(\Core\Domain\DomainObject $obj);
    protected abstract function doCreateObject(array $data);
    protected abstract function doInsert(\Core\Domain\DomainObject $object);
    protected abstract function selectSmth();
}