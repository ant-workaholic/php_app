<?php
/**
 *
 */
namespace Core\Controller;

/**
 * Class ControllerMap
 * @package Core\Controller
 */
class ControllerMap
{
    /**
     * @var array
     */
    private $viewMap = array();
    private $forwardMap = array();
    private $classrootMap = array();

    public function addForward($command, $status=0, $newCommand)
    {
        $this->forwardMap[$command][$status] = $newCommand;
    }

    /**
     * @param $command
     * @param $status
     * @return null
     */
    public function getForward($command, $status)
    {
        if (isset($this->forwardMap[$command][$status])) {
            return $this->forwardMap[$command][$status];
        }
        return null;
    }

    /**
     * @param $command
     * @param $classroot
     */
    public function addClassroot($command, $classroot)
    {
        $this->classrootMap[$command] = $classroot;
    }

    /**
     * @param $command
     * @return mixed
     */
    public function getClassroot($command)
    {
        if (isset($this->classrootMap[$command])) {
            return $this->classrootMap[$command];
        }
        return null;
    }

    /**
     * Add a view to the mapper
     *
     * @param $view
     * @param string $command
     * @param int $status
     */
    public function addView($view, $command = 'default', $status=0)
    {
        $this->viewMap[$command][$status] = $view;
    }

    /**
     * Get a specific view
     *
     * @param $command
     * @param $status
     * @return null
     */
    public function getView($command, $status)
    {
        if (isset($this->viewMap[$command][$status])) {
            return $this->viewMap[$command][$status];
        }
        return null;
    }
}