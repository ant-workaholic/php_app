<?php
namespace Core\Domain\Collection;
use \Core\Mapper\Collection as AbstractCollection;
use Core\Domain\DomainObject;

/**
 * Class VenueCollection
 * @package Core\Mapper
 */
class VenueCollection
    extends AbstractCollection
    implements Interfaces\VenueCollectionInterface
{

    /**
     * @return string
     */
    public function targetClass()
    {
        return "\\Core\\Domain\\Venue";
    }
}