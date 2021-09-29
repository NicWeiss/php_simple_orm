<?php

namespace model;

use lib\orm\Model;

class Notify extends Model
{
    var $id = null;
    var $user_id = null;
    var $name = null;
    var $text = null;
    var $periodic = null;
    var $day_of_week = null;
    var $date = null;
    var $time = null;
    var $category_id = null;
}
