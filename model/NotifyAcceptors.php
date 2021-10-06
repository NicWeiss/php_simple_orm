<?php

namespace model;

use lib\simple_orm\Model;

class NotifyAcceptors extends Model
{
    var $id = null;
    var $notify_id = null;
    var $acceptor_id = null;

    var $notify = null;
    var $acceptor = null;

    public function define_relations()
    {
        $this->relarions = [
            'notify' => ['one_to_one', 'model\Notify', 'notify_id', 'id'],
            'acceptor' => ['one_to_one', 'model\Acceptor', 'acceptor_id', 'id']
        ];
    }
}
