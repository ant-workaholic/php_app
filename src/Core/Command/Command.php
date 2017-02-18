<?php

namespace Core\Command;
use \Core\Controller\Request;
/**
 * Class Command
 */
abstract class Command
{
    final public function __construct() {}

    public function execute(Request $request)
    {
        $this->doExecute($request);
    }

    /**
     * @return mixed
     */
    abstract public function doExecute(Request $request);
}