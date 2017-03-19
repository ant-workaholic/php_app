<?php
namespace Core\Command;
use Core\Controller\Request;

class AddVenue extends Command
{
    /**
     * @param Request $request
     */
    public function doExecute(Request $request)
    {
        $name = $request->getProperty("venue_name");
        if (is_null($name)) {
            $request->addFeedback("Name was not defined");
            return self::statuses('CMD_INSUFFICIENT_DATA');
        } else {
            $request->addFeedback("This is an add venue page!!!");
            return self::statuses('CMD_OK');
        }
    }
}
