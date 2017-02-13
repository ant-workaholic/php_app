<?php
namespace Core\App\Helper;

/**
 * Class ApplicationHelper
 */
class ApplicationHelper {

    /**
     * @var string
     */
    protected $_config = "conf/params.ini";

    /**
     * @var null
     */
    private static $instance = null;

    /**
     * ApplicationHelper constructor.
     */
    private function __construct(){}

    /**
     * Get application helper instance
     *
     * @return ApplicationHelper
     */
    public static function instance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self;
        }
        return self::$instance;
    }

    /**
     * Init application helper
     */
    public function init()
    {
        $dns = \Core\App\Registry\ApplicationRegistry::getConfig();
        if (!is_null($dns)) {
            return;
        }
        $this->parseIni();
    }

    /**
     *
     */
    protected function parseIni()
    {
        $this->ensure(file_exists($this->_config), "Config file isn't exist.");
        $options = parse_ini_file($this->_config);
        if (isset($options["dns"])) {
            \Core\App\Registry\ApplicationRegistry::setConfig($options);
        }
        $this->ensure($options, "The config file is empty!");
    }

    /**
     * @param $expr
     * @param $msg
     * @throws \Core\App\Exception\Base
     */
    public function ensure($expr, $msg)
    {
        if (!$expr) {
            throw new \Core\App\Exception\Base($msg);
        }
    }
    private function __clone(){}
    private function __wakeup(){}
}

