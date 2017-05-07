<?php

namespace Core\Domain;

class HelperFactory
{
    /**
     * Create a collection class based on model class.
     *
     * @param $class
     * @return mixed
     */
     public static function getCollection($class)
     {
         if ($class) {
             $collectionClass = $class . "\\" . "Collection";
             if (class_exists($collectionClass)) {
                 return new $collectionClass();
             }
         }
         return false;
     }
}