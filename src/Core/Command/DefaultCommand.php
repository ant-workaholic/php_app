<?php
namespace Core\Command;
use Core\Http\Request;

/**
 * Class DefaultCommand
 * @package Core\Command
 */
class DefaultCommand extends Command
{
    /**
     * @param Request $request
     */
    public function doExecute(Request $request)
    {
        $baseDir = \Core\Registry\ApplicationRegistry::getBaseDir();
        $rootDir = $baseDir . DS . "src" . DS . "Core" . DS . "View" . DS . "main.phtml";
        $request->addFeedback("<a href='test.php'>Test page!! You are welcome!!!</a>");
        include ($rootDir);
    }
}