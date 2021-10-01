<?php

use repository\NotifyRepo as NotifyRepo;
use lib\simple_orm\Database as Database;
use model\Notify;

function autoload($classname)
{
    $classname = str_replace('\\', '/', $classname);
    $path = __DIR__ . '/';
    $include = $path . $classname . '.php';
    if (file_exists($include))
        require_once($include);
}
spl_autoload_register('autoload');

final class Main
{
    public static function run()
    {
        //  INIT DATABASE
        $GLOBALS['DATABASE'] = new Database();

        //  CREATE AND FILL MODEL
        $notify_model = new Notify([
            'id' => 158,
            'user_id' => 1,
            'name' => 'Some Notify Name',
            'text' => 'Some Notify text',
            'periodic' => 'everyday',
            'time' => '10:00',
            'category_id' => 0
        ]);


        //  CREATE AND FILL MODEL
        $data_for_update = array(
            'name' => 'Some Updated Name',
            'text' => 'Some Updated text',
            'time' => '20:00',
        );

        // CREATE INSTANCE OF REPO AND CREATE RECORD IN DATABASE
        $notify_repo = new NotifyRepo();
        // $notify_repo->update($notify_model);
        $notify_repo->update($notify_model, $data_for_update);
    }
}

Main::run();
