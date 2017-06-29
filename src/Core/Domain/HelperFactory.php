<?php

namespace Core\Domain;
use PhpSpec\Exception\Exception;

/**
 * Class HelperFactory
 * @package Core\Domain
 */
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
                 return new $collectionClass(self::getFinder($class));
             }
         }
         return false;
     }

    /**
     * Find an appropriate mapper class.
     *
     * @param $class
     * @return bool
     */
    public static function getFinder($class)
    {
        if ($class) {
            $classParts = explode("\\", $class);
            $className = array_pop($classParts);
            $mapperClass = "Core\\Mapper\\$className"."Mapper";
            if (class_exists($mapperClass)) {
                return new $mapperClass();
            }
            self::generateClass($mapperClass, $className);;
        }
        return false;
    }

    public static function generateClass($mapperClass, $className)
    {
        if (!file_exists(DIR_BASE . DS . 'src' . DS .  $mapperClass .".php") && !class_exists($mapperClass)) {
            $entityName = strtolower($className);
            $codeGenerated = <<<"CODE"
<?php
namespace Core\Mapper;
class {$className}Mapper extends MapperAbstract {
    public function __construct()
    {
        parent::__construct();
        \$this->selectSmth = self::\$PDO->prepare("SELECT * FROM $entityName WHERE id=?");
        \$this->updateSmth = self::\$PDO->prepare("UPDATE $entityName SET name=?, id=? WHERE id=?");
        \$this->insertSmth = self::\$PDO->prepare("INSERT into $entityName (name) values (?)");

    }

    /**
     * Retrieve a space collection
     *
     * @return Domain\Space\Collection
     */
    public function getCollection()
    {
        return new Domain\Space\Collection();
    }

    /**
     * @param array \$data
     * @return \Core\Domain\\$className
     */
    protected function doCreateObject(array \$data)
    {
        \$obj = new \Core\Domain\\$className(\$data['id']);
        \$obj->setName(\$data['name']);
        return \$obj;
    }

    /**
     * @param \Core\Domain\DomainObject \$object
     */
    protected function doInsert(\Core\Domain\DomainObject \$object)
    {
        \$values = array(\$object->getName());
        \$this->insertSmth->execute(\$values);
        \$id = self::\$PDO->lastInsertId();
        \$object->setId(\$id);
    }

    /**
     * Update
     *
     * @param \Core\Domain\DomainObject \$obj
     * @return null;
     */
    function update(\Core\Domain\DomainObject \$obj)
    {
        \$values = array(\$obj->getName(), \$obj->getId(), \$obj->getId());
        \$this->updateSmth->execute(\$values);
    }

    /**
     *
     *
     * @return mixed
     */
    protected function selectStmt()
    {
        return \$this->selectSmth->execute();
    }

    /**
     * Get mapper target class
     *
     * @return mixed
     */
    protected function targetClass()
    {
        return \Core\Domain\\$className::class;
    }      
}
CODE;

            file_put_contents(DIR_BASE . DS . 'src' . DS .  $mapperClass .".php", $codeGenerated);
        }
    }
}