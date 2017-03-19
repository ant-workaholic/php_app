<?php
namespace Core\Front;

use Core\Helper as App;

/**
 * Class Controller
 * @package Core\Front
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
        $appController = \Core\Registry\ApplicationRegistry::appController();

        /** @var \Core\Controller\Request $cmd */
        while ($cmd = $appController->getCommand($request)) {
            $cmd->execute($request);
        }
        $this->invokeView($appController->getView($request), $request);
    }

    /**
     *
     * @param $target
     * @param $request
     */
    public function invokeView($target, $request)
    {
        include(DIR_BASE . DS ."src" . DS . "Core" . DS . "View" . DS . "$target.phtml");
        exit;
    }
}
