<?php

namespace model;

use generic\Model as Model;

class Notify extends Model
{
    var $id;
    var $user_id;
    var $name;
    var $text;
    var $periodic;
    var $day_of_week;
    var $date;
    var $time;
    var $category_id;
}
