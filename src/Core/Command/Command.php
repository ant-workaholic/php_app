<?php

namespace Core\Command;
use \Core\Controller\Request;

/**
 * Class Command
 */
abstract class Command
{
    /**
     * @var array
     */
    private static $STATUS_STRINGS = [
        'CMD_DEFAULT'            => 0,
        'CMD_OK'                 => 1,
        'CMD_ERROR'              => 2,
        'CMD_INSUFFICIENT_DATA'  => 3
    ];

    private $status = 0;

    final public function __construct() {}

    /**
     * @param Request $request
     */
    public function execute(Request $request)
    {
        $this->status = $this->doExecute($request);
        $request->setLastCommand($this);
    }

    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Get status code by string
     *
     * @param $statusStr
     * @return bool|mixed
     */
    public static function statuses($statusStr)
    {
        if (isset(self::$STATUS_STRINGS[$statusStr])) {
            return self::$STATUS_STRINGS[$statusStr];
        }
        return false;
    }

    /**
     * @return mixed
     */
    abstract public function doExecute(Request $request);
}