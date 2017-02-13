<?php
namespace Core\App;

use Core\App\Helper as App;

/**
 * Class Controller
 */
class Controller {

    private $applicationHelper;

    private function __construct(){}

    /**
     * Run Front Controller
     */
    static public function run()
    {
        $instance = new Controller();
        $instance->init();
        $instance->handleRequest();
    }

    /**
     * Init an application helper
     */
    public function init()
    {
        $this->applicationHelper = App\ApplicationHelper::instance();
        $this->applicationHelper->init();
    }

    public function handleRequest()
    {
        echo "Request handling";
    }
}
