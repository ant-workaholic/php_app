<?php
namespace Core\Controller;

class AppController
{
    private static $base_cmd = null;
    private static $default_cmd = null;
    private $controllerMap;
    private $invoked = [];

    /**
     * AppController constructor.
     * @param ControllerMap $map
     */
    public function __construct(ControllerMap $map)
    {
        $this->controllerMap = $map;
        if (is_null(self::$base_cmd)) {
            self::$base_cmd = new \ReflectionClass("Core\\Command\\Command");
            self::$default_cmd = new \Core\Command\DefaultCommand();
        }
    }

    /**
     *
     */
    public function reset()
    {
        $this->invoked = [];
    }

    /**
     * @param Request $req
     * @return mixed
     */
    public function getView(Request $req)
    {
        $view =  $this->getResource($req, "View");
        return $view;
    }

    /**
     * Get appropriate resource
     *
     * @param Request $req
     * @param $res
     * @return mixed
     */
    private function getResource(Request $req, $res)
    {
        $cmd_str = $req->getProperty("cmd");
        $previous = $req->getLastCommand();
        $status = $previous->getStatus();

        if (!isset($status) || !is_int($status)) { $status = 0; }
        $aquire = "get$res";
        $resource = $this->controllerMap->$aquire($cmd_str, $status);

        if (is_null($resource)) {
            $resource = $this->controllerMap->$aquire($cmd_str, 0);
        }

        if (is_null($resource)) {
            $resource = $this->controllerMap->$aquire("default", $status);
        }

        if (is_null($resource)) {
            $resource = $this->controllerMap->$aquire("default", 0);
        }
        return $resource;
    }


}