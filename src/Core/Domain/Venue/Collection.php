<?php
namespace Core\Domain\Venue;
use \Core\Mapper\Collection as AbstractCollection;
use Core\Domain\DomainObject;

/**
 * Class VenueCollection
 * @package Core\Mapper
 */
class Collection
    extends AbstractCollection
{

    /**
     * @return string
     */
    public function targetClass()
    {
        return "\\Core\\Domain\\Venue";
    }
}