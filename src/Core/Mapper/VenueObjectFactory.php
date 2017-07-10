<?php
namespace Core\Mapper;

/**
 * Class VenueObjectFactory
 * @package Core\Mapper
 */
class VenueObjectFactory extends DomainObjectFactory
{
    /**
     * Create a concrete venue object.
     *
     * @param array $array
     * @return \Core\Domain\Venue
     */
    function createObject(array $array)
    {
        $obj = new \Core\Domain\Venue($array['id']);
        $obj->setName($array['name']);
        return $obj;
    }
}
