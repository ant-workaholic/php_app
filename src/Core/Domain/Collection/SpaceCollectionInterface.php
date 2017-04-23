<?php
namespace Core\Domain\Collection;

use Core\Domain\DomainObject;

interface SpaceCollectionInterface extends \Iterator
{
    public function add(DomainObject $space);
}