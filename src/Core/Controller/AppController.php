<?php
namespace Core\Controller;

/**
 * Class AppController
 * @package Core\Controller
 */
class AppController
{
    private static $base_cmd = null;
    private static $default_cmd = null;
    private $controllerMap;
    private $invoked = [];

    /**
     * AppController constructor.
     */
    public function __construct()
    {
        $this->controllerMap = \Core\Registry\ApplicationRegistry::getControllerMap();
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
    private function getForward(Request $req)
    {
        $forward = $this->getResource($req, "Forward");
        if ($forward) {
            $req->setProperty("cmd", $forward);
        }
        return $forward;
    }

    /**
     * @param Request $req
     * @return mixed
     */
    public function getView(Request $req)
    {
        $view = $this->getResource($req, "View");
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

    /**
     * @param Request $req
     * @return \Core\Command\DefaultCommand|null
     * @throws \Core\Exception\Base
     */
    public function getCommand(Request $req)
    {
        $previous = $req->getLastCommand();
        if (!$previous) {
            $cmd = $req->getProperty("cmd");
            if (is_null($cmd)) {
                $req->setProperty("cmd", "default");
                return self::$default_cmd;
            }
        } else {
            $cmd = $this->getForward($req);
            if (is_null($cmd)) {
                return null;
            }
        }
        $cmdObj = $this->resolveCommand($cmd);
        if (is_null($cmdObj)) {
           throw new \Core\Exception\Base("Command was not found.");
        }
        $cmdClass = get_class($cmdObj);
        if (isset($this->invoked[$cmdClass])) {
            throw  new \Core\Exception\Base("Cyclic call");
        }
        $this->invoked[$cmdClass] = 1;
        return $cmdObj;
    }

    /**
     * @param $cmd
     * @return null|object
     */
    public function resolveCommand($cmd)
    {
        $classroot = $this->controllerMap->getClassroot($cmd);
        $filepath = "Core/Command/$classroot";
        $classname = "\\Core\\Command\\$classroot";
        if (file_exists($filepath)) {
            require_once ("$filepath");
            if (class_exists($classname)) {
                $cmdClass = new \ReflectionClass($classname);
                if ($cmdClass->isSubclassOf(self::$base_cmd)) {
                    return $cmdClass->newInstance();
                }
            }
        }
        return null;
    }
}