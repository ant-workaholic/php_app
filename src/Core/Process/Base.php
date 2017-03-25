<?php
namespace Core\Process;

/**
 * Class Base
 * @package Core\Process
 */
abstract class Base
{
    static $DB;
    static $statements = [];

    /**
     * Base constructor.
     * @throws \Core\Exception\Base
     */
    public function __construct()
    {
        $dsn = \Core\Registry\ApplicationRegistry::getDSN();
        if (is_null($dsn)) {
            throw new \Core\Exception\Base('DSN is not defined');
        }
        self::$DB = new \PDO($dsn);
        self::$DB->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    }

    /**
     * @param $statement
     * @return mixed|\PDOStatement
     */
    public function prepareStatement($statement)
    {
        if (isset(self::$statements[$statement])) {
            return self::$statements[$statement];
        }
        $stm_handle = self::$DB->prepare($statement);
        self::$statements[$statement] = $stm_handle;
        return $stm_handle;
    }
}