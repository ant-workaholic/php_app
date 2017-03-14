<?php
/**
 *
 */
namespace Core\Helper;

/**
 * Class ApplicationHelper
 */
class ApplicationHelper {

    /**
     * @var string
     */
    protected $_config = "conf/params.ini";
    protected $_routers = "conf/routes.xml";

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
        $data = \Core\Registry\ApplicationRegistry::getConfig();
        if (is_null($data)) {
            $this->parseData();
        }
        $routesMap = \Core\Registry\ApplicationRegistry::getControllerMap();

        if (is_null($routesMap)) {
            $this->loadRoutersConfig();
        }
        return;
    }

    /**
     * Parse data from routers config file
     */
    protected function loadRoutersConfig()
    {
        $this->ensure(file_exists($this->_routers), "The routers files is not exists!");
        $routes = @simplexml_load_file($this->_routers);

        $map = new \Core\Controller\ControllerMap();
        foreach ($routes->view as  $default_view) {
            $statusConf = trim($default_view["status"]);
            $status = \Core\Command\Command::statuses($statusConf);
            $map->addView((string) $default_view, 'default', $status);
        }
        \Core\Registry\ApplicationRegistry::setControllerMap($map);
    }

    /**
     * Parse a config file.
     */
    protected function parseData()
    {
        $this->ensure(file_exists($this->_config), "Config file isn't exist.");
        $options = parse_ini_file($this->_config);
        if (isset($options["dns"])) {
            \Core\Registry\ApplicationRegistry::setConfig($options);
        }
        $this->ensure($options, "The config file is empty!");

    }

    /**
     * @param $expr
     * @param $msg
     * @throws \Core\Exception\Base
     */
    public function ensure($expr, $msg)
    {
        if (!$expr) {
            throw new \Core\Exception\Base($msg);
        }
    }
    private function __clone(){}
    private function __wakeup(){}
}

