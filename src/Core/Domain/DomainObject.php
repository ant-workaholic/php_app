<?php
namespace Core\Domain;

/**
 * Class DomainObject
 *
 * @package Core\Domain
 */
abstract class DomainObject
{
    /**
     * @var null
     */
    private $id = -1;

    /**
     * DomainObject constructor.
     * @param null $id
     */
    public function __construct($id = null)
    {
        if (is_null($id)) {
            $this->markNew();
        }
        $this->id = $id;
    }

    /**
     * Get object's id
     *
     * @return null
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Mark concrete object as deleted.
     */
    public function delete()
    {
        // Delete current object
        $this->markDeleted();
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
     * Get appropriate collection.
     *
     * @return array
     */
    public function collection()
    {
        return self::getCollection(get_class($this));
    }

    /**
     * Mark deleted item
     */
    public function markDeleted()
    {
        ObjectWatcher::addDelete($this);
    }

    /**
     * Mark object as new
     */
    public function markNew()
    {
        ObjectWatcher::addDirty($this);
    }

    /**
     * Mark object as changed
     */
    public function markDirty()
    {
        ObjectWatcher::addDirty($this);
    }

    /**
     * Mark clean
     */
    public function markClean()
    {
        ObjectWatcher::addClean($this);
    }

    /**
     * Get finder.
     *
     * @return \Core\Mapper\MapperAbstract
     */
    public function finder()
    {
        return self::getFinder(get_class($this));
    }

    /**
     * Get finder.
     *
     * @param null $type
     * @return \Core\Mapper\MapperAbstract
     */
    public static function getFinder($type = null)
    {
        if (is_null($type)) {
            return HelperFactory::getFinder(get_called_class());
        }
        return HelperFactory::getFinder($type);
    }

    /**
     * Perform operations under a model.
     * DELETE, UPDATE, MARK AS NEW.
     */
    public function persist()
    {
        ObjectWatcher::instance()->performOperations();
    }
}
