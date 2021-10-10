<?php

use repository\NotifyRepo as NotifyRepo;
use lib\simple_orm\Database as Database;
use model\Notify;
use repository\AcceptorRepo;

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


        $acceptor_repo = new AcceptorRepo();
        // GET RECORD BY USER ID AND ACCEPTOR ID
        $acceptor = $acceptor_repo->get_acceptor(1, 5);

        //  CREATE AND FILL MODEL
        $notify_model = new Notify([
            'user_id' => 1,
            'name' => 'Some Notify Name',
            'text' => 'Some Notify text',
            'periodic' => 'everyday',
            'time' => '10:00',
            'category_id' => 0,
            'acceptorsList' => [$acceptor]
        ]);

        //  CREATE AND FILL MODEL
        $data_for_update = array(
            'name' => 'Some Updated Name',
            'text' => 'Some Updated text',
            'time' => '20:00',
            'acceptorsList' => null
        );

        // CREATE INSTANCE OF REPO AND CREATE RECORD IN DATABASE
        $notify_repo = new NotifyRepo();

        //  CREATE RECORD IN DATABASE
        $notify_model = $notify_repo->create($notify_model);

        // UPDATE RECORD IN DATABASE
        $notify_repo->update($notify_model, $data_for_update);

        var_dump($notify_model);

        // DELETE RECORD FROM DATABASE
        // $notify_repo->delete($notify_model);

        // GET RECORD BY USER ID AND NOTIFY ID
        // $first = $notify_repo->get_notify(1, 47);
        // $first = $notify_repo->update($first, $data_for_update);
        // var_dump($first);

        // GET ALL RECORDS BY USER ID
        // $notify_list = $notify_repo->get_by_user_id(1);
        // var_dump($notify_list);
    }
}


Main::run();
