<?php
namespace Core\Mapper;
class VenueMapper extends MapperAbstract {
    public function __construct()
    {
        parent::__construct();
        $this->selectSmth = self::$PDO->prepare("SELECT * FROM venue WHERE id=?");
        $this->updateSmth = self::$PDO->prepare("UPDATE venue SET name=?, id=? WHERE id=?");
        $this->insertSmth = self::$PDO->prepare("INSERT into venue (name) values (?)");

    }

    /**
     * Retrieve a space collection
     *
     * @return Domain\Space\Collection
     */
    public function getCollection()
    {
        return new Domain\Space\Collection();
    }

    /**
     * @param array $data
     * @return \Core\Domain\Venue
     */
    protected function doCreateObject(array $data)
    {
        $obj = new \Core\Domain\Venue($data['id']);
        $obj->setName($data['name']);
        return $obj;
    }

    /**
     * @param \Core\Domain\DomainObject $object
     */
    protected function doInsert(\Core\Domain\DomainObject $object)
    {
        $values = array($object->getName());
        $this->insertSmth->execute($values);
        $id = self::$PDO->lastInsertId();
        $object->setId($id);
    }

    /**
     * Update
     *
     * @param \Core\Domain\DomainObject $obj
     * @return null;
     */
    function update(\Core\Domain\DomainObject $obj)
    {
        $values = array($obj->getName(), $obj->getId(), $obj->getId());
        $this->updateSmth->execute($values);
    }

    /**
     *
     *
     * @return mixed
     */
    protected function selectStmt()
    {
        return $this->selectSmth->execute();
    }

    /**
     * Get mapper target class
     *
     * @return mixed
     */
    protected function targetClass()
    {
        return \Core\Domain\Venue::class;
    }      
}