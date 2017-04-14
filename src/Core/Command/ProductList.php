<?php
namespace Core\Command;
use Core\Http\Request;

class ProductList extends Command
{
    /**
     * @param Request $request
     * @return bool|mixed
     */
    public function doExecute(Request $request)
    {
        $request->addFeedback("You are on product list page!!!");
        return self::statuses('CMD_OK');
    }
}
