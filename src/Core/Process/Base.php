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
        try {
            // TODO: Move the db user and password to configuration. Temporary hardcoded.
            self::$DB = new \PDO($dsn, "root", "");
        } catch (\PDOException $e) {
            echo  $e->getMessage();
            exit;
        }
        self::$DB->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    }

    /**
     * Serve prepared statements
     *
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

    /**
     * Run prepared statements
     *
     * @param $statement
     * @param array $values
     * @return mixed|\PDOStatement
     */
    public function doStatement($statement, array $values)
    {
        $sth = $this->prepareStatement($statement);
        $sth->closeCursor();
        $db_result = $sth->execute($values);
        return $sth;
    }

    /**
     * Check if a table exists in the current database.
     *
     * @param string $table Table to search for.
     * @return bool TRUE if table exists, FALSE if no table found.
     */
    function tableExists($table) {

        // Try a select statement against the table
        // Run it in try/catch in case PDO is in ERRMODE_EXCEPTION.
        try {
            $result = self::$DB->query("SELECT 1 FROM $table LIMIT 1");
        } catch (\Exception $e) {
            // We got an exception == table not found
            return FALSE;
        }
        // Result is either boolean FALSE (no table found) or PDOStatement Object (table found)
        return $result !== FALSE;
    }
}
