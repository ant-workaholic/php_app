<?php
namespace Core\Registry;

/**
 * Class RegistryAbstract
 * @package Core\App\Registry
 */
abstract class RegistryAbstract {
    abstract protected function get($key);
    abstract protected function set($key, $val);
}
