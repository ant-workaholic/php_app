<?php
namespace Core\Domain\Collection;
use \Core\Mapper\Collection as AbstractCollection;

class SpaceCollection
    extends AbstractCollection
    implements Interfaces\SpaceCollectionInterface
{
    /**
     * @return string
     */
    public function targetClass()
    {
        return "\\Core\\Domain\\Space";
    }
}