<?php
namespace Core\Domain;

/**
 * Class DomainObject
 */
abstract class DomainObject
{
    private $id;

    /**
     * DomainObject constructor.
     * @param null $id
     */
    public function __construct($id=null)
    {
        $this->id = $id;
    }

    /**
     * @return null
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return array
     */
    static function getCollection()
    {
        return array();
    }

    /**
     * @return array
     */
    public function collection()
    {
        return self::getCollection(get_class($this));
    }
}