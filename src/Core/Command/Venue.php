<?php
namespace Core\Command;
use Core\Http\Request;

class Venue extends Command
{
    /**
     * @param Request $request
     * @return bool|mixed
     */
    public function doExecute(Request $request)
    {
        $venue = new \Core\Domain\Venue();
        $venues = $venue->collection();

        var_dump($venues);
        exit;

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
