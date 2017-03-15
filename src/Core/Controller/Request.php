<?php
/**
 * Simple a request wrapper.
 */
namespace Core\Controller;

/**
 * Init request data
 *
 * Class Request
 */
class Request
{
    private $properties;
    private $feedback = [];
    private $command;

    /**
     * Request constructor.
     */
    public function __construct()
    {
        $this->init();
    }

    /**
     * Init request data
     */
    public function init()
    {
        if (isset($_SERVER['REQUEST_METHOD'])) {
            $this->properties = $_REQUEST;
            return;
        }

        foreach ($_SERVER['argv'] as $arg) {
            if (strpos($arg, '=')) {
                list($key, $val) = explode("=", $arg);
                $this->setProperty($key, $val);
            }
        }
    }

    /**
     * Get property
     *
     * @param $key
     * @return null
     */
    public function getProperty($key)
    {
        if (isset($this->properties[$key])) {
            return $this->properties[$key];
        }
        return null;
    }

    /**
     * @param $key
     * @param $value
     */
    public function setProperty($key, $value)
    {
        $this->properties[$key] = $value;
    }

    /**
     * Add a feedback (or templates content)
     *
     * @param $msg
     */
    public function addFeedback($msg)
    {
        array_push($this->feedback, $msg);
    }

    /**
     * @return array
     */
    public function getFeedback()
    {
        return $this->feedback;
    }

    /**
     * @param \Core\Command\Command $command
     */
    public function setLastCommand(\Core\Command\Command $command)
    {
        $this->command = $command;
    }

    /**
     * @return mixed
     */
    public function getLastCommand()
    {
        return $this->command;
    }
}