<?php
namespace Core\Domain;

/**
 * Class Venue
 * @package Core\Domain
 */
class Venue extends DomainObject
{
    /**
     * @var null
     */
    private $name;

    /**
     * @var array
     */
    private $spaces;

    /**
     * Venue constructor.
     * @param null $id
     * @param null $name
     */
    public function __construct($id = null, $name = null)
    {
        $this->name = $name;
        $this->spaces = self::getCollection("Core\\Domain\\Space");
        parent::__construct($id);
    }

    /**
     * Specify venue name
     *
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
        $this->markDirty();
    }

    /**
     * Set spaces collection
     *
     * @param mixed $spaces
     */
    public function setSpaces(SpaceCollection $spaces)
    {
        $this->spaces = $spaces;
    }

    /**
     * Retrieve a venue name
     *
     * @return null
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Add a space to collection
     *
     * @param Space $space
     */
    public function addSpace(Space $space)
    {
        $this->spaces->add($space);
        $space->setVenue($this);
    }
}