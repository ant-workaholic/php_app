<?php

namespace Core\Command;
use \Core\Controller\Request;

/**
 * Class Command
 */
abstract class Command
{
    private static $STATUS_STRINGS = [
        'CMD_DEFAULT'            => 0,
        'CMD_OK'                 => 1,
        'CMD_ERROR'              => 2,
        'CMD_INSUFFICIENT_DATA'  => 3
    ];

    final public function __construct() {}

    /**
     * @param Request $request
     */
    public function execute(Request $request)
    {
        $this->doExecute($request);
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