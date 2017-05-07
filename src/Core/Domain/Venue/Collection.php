<?php
namespace Core\Domain\Venue;
use \Core\Mapper\Collection as AbstractCollection;

class Collection
    extends AbstractCollection
    implements \Core\Domain\Collection\Interfaces\VenueCollectionInterface
{
    /**
     * @return string
     */
    public function targetClass()
    {
        return "\\Core\\Domain\\Venue";
    }
}