<?php
namespace Core\Domain\Collection\Interfaces;

use Core\Domain\DomainObject;
use Core\Domain;

interface SpaceCollectionInterface extends \Iterator
{
    public function add(Domain\Space $space);
}