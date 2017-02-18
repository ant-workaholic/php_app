<?php
namespace Core\Front;

use Core\Helper as App;

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

    /**
     * Handle a request
     */
    public function handleRequest()
    {
        $request = \Core\Registry\ApplicationRegistry::getRequest();
        $cmd_r = new \Core\Command\CommandResolver();
        $cmd = $cmd_r->getCommand($request);
        $cmd->execute($request);
    }
}
