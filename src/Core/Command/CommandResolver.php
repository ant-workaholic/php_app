<?php
namespace Core\Command;

class CommandResolver {

    /**
     * @var null|\ReflectionClass
     */
    private static $base_cmd = null;

    /**
     * @var DefaultCommand|null
     */
    private static $default_cmd = null;


    public function __construct()
    {
        if (is_null(self::$base_cmd)) {
            self::$base_cmd = new \ReflectionClass("\\Core\\Command\\Command");
            self::$default_cmd = new DefaultCommand();
        }
    }

    public function getCommand(\Core\Controller\Request $request)
    {
        $cmd = $request->getProperty('cmd');
        $sep = DIRECTORY_SEPARATOR;

        if (!$cmd) {
            return self::$default_cmd;
        }

        $cmd = str_replace(array_search('.', $sep), "", $cmd);
        $cmd = ucfirst($cmd);
        $filepath = "Core{$sep}Command{$sep}{$cmd}.php";
        $classname = "Core\\Command\\$cmd";
        if (file_exists($filepath)) {
           @require_once $filepath;
            if (class_exists($classname)) {
                $cmdClass = new \ReflectionClass($classname);
                if ($cmdClass->isSubclassOf(self::$base_cmd)) {
                    return $cmdClass->newInstance();
                } else {
                    $request->addFeedback("The object of the cmd command has not found.");
                }
            }
        }
        $request->addFeedback("Command $cmd has not found.");
        return clone self::$default_cmd;
    }
}