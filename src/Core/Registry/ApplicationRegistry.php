<?php
/**
 * Core registry class.
 */
namespace Core\Registry;

/**
 * Class ApplicationRegistry
 * @package Core\Registry
 */
class ApplicationRegistry extends RegistryAbstract
{
    /**
     * Application instance
     *
     * @var ApplicationRegistry|null
     */
    private static $instance = null;

    /** @var array  */
    private $values = [];

    private $appController;

    private $request;
    /**
     * Frezze dir
     *
     * @var string
     */
    private $frezeedir = "data";

    /**
     * Cache storage array
     *
     * @var array
     */
    private $mtimes = array();

    private function __construct() {}

    /**
     * Create and appropriate registry singleton.
     *
     * ApplicationRegistry constructor.
     */
    public static function instance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new self;
        }
        return self::$instance;
    }

    /**
     * Get value from the registry
     *
     * @param $key
     * @return mixed|null
     */
    protected function get($key)
    {
        $path = $this->frezeedir . DIRECTORY_SEPARATOR . $key;
        if (file_exists($path)) {
            clearstatcache();
            $mtime = filemtime($path);
            if (!isset($this->mtimes[$key])) {
                $this->mtimes[$key] = 0;
            }
            if ($mtime > $this->mtimes[$key]) {
                $data = file_get_contents($path);
                $this->mtimes[$key] = $mtime;
                return ($this->values[$key] = unserialize($data));
            }
        }
        if (isset($this->values[$key])) {
            return $this->values[$key];
        }
        return null;
    }

    /**
     * Retrieve valid dsn
     *
     * @return null|string
     */
    public static function getDSN()
    {
        $host = self::getConfig()["host"]?:null;
        $dbName = self::getConfig()["dbname"]?:null;
        $charset = self::getConfig()["charset"]?:null;
        if ($host && $charset && $dbName) {
            return "mysql:host=$host;dbname=$dbName;charset=$charset";
        }
        return null;
    }

    /**
     * Specify appropriate data
     *
     * @param $key
     * @param $val
     */
    protected function set($key, $val)
    {
        $this->values[$key] = $val;
        $path = $this->frezeedir . DIRECTORY_SEPARATOR . $key;
        file_put_contents($path, serialize($val));
        $this->mtimes[$key] = time();
    }

    /**
     * Get controller map from cache
     */
    static function getControllerMap()
    {
        return self::instance()->get("controller_map");
    }

    /**
     * Save controller map data in cache
     *
     * @param \Core\Http\ControllerMap $controllerMap
     */
    static function setControllerMap(\Core\Http\ControllerMap $controllerMap)
    {
        self::instance()->set("controller_map", $controllerMap);
    }

    /**
     * Get DNS
     *
     * @return mixed|null
     */
    static function getConfig()
    {
        return self::instance()->get("config");
    }

    /**
     * Specify $config
     *
     * @param $config
     */
    static function setConfig($config)
    {
        self::instance()->set("config", $config);
    }

    /**
     * Get app controller instance
     */
    static function appController()
    {
        $inst = self::instance();
        if (is_null($inst->appController)) {
            $inst->appController = new \Core\Http\AppController(self::getControllerMap());
        }
        return $inst->appController;
    }

    /**
     * Get current request
     *
     * @return \Core\Http\Request
     */
    static function getRequest()
    {
        $inst = self::instance();
        if (is_null($inst->request)) {
            $inst->request = new \Core\Http\Request();
        }
        return $inst->request;
    }

    static function getBaseDir()
    {
        return $_SERVER['DOCUMENT_ROOT'];
    }
}
