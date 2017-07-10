<?php

namespace Core\Mapper;

/**
 * Class DomainObjectFactory
 */
abstract class DomainObjectFactory
{
    abstract function createObject(array $array);
}
