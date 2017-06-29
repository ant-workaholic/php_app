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
    public function __construct($id = null)
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
     * Retrieve a model collection
     *
     * @return array
     */
    static function getCollection($type = null)
    {
        if (is_null($type)) {
            return HelperFactory::getCollection(get_called_class());
        }
        return HelperFactory::getCollection($type);
    }

    /**
     * @return array
     */
    public function collection()
    {
        return self::getCollection(get_class($this));
    }
}