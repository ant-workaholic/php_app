<?php
namespace Core\Domain\Collection\Interfaces;

use Core\Domain\DomainObject;
use Core\Domain;

interface VenueCollectionInterface extends \Iterator
{
    public function add(Domain\Venue $object);
}
