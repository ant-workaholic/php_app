<?php
namespace Core\App\Registry;

class ApplicationRegistry extends RegistryAbstract
{
    /**
     * @var ApplicationRegistry|null
     */
    private static $instance = null;

    /** @var array  */
    private $values = [];

    /**
     * Frezze dir
     *
     * @var string
     */
    private $frezeedir = "data";

    /**
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
}
