<?php

use repository\NotifyRepo as NotifyRepo;
use lib\orm\Database as Database;
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
        $GLOBALS['DATABASE'] = new Database();
        print_r($GLOBALS['DATABASE']);
        $notify_model = new Notify();

        die;

        $notify_repo = new NotifyRepo();
        $notify_repo->create();
    }
}

Main::run();
