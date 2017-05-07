<?php
namespace Core\Domain\Space;
use \Core\Mapper\Collection as AbstractCollection;

class Collection
    extends AbstractCollection
    implements \Core\Domain\Collection\Interfaces\SpaceCollectionInterface
{
    /**
     * @return string
     */
    public function targetClass()
    {
        return "\\Core\\Domain\\Space";
    }
}