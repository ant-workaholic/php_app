<?php
namespace Core\Domain\Space;
use \Core\Mapper\Collection as AbstractCollection;

class Collection
    extends AbstractCollection
{
    /**
     * @return string
     */
    public function targetClass()
    {
        return "\\Core\\Domain\\Space";
    }
}
