<?php
namespace Core\Domain\Collection;

use Core\Domain\DomainObject;

interface VenueCollectionInterface extends \Iterator
{
    public function add(DomainObject $object);
}