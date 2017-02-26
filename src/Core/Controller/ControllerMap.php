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