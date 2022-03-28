<?php

namespace TP1\bd;

use Illuminate\Database\Capsule\Manager as DB;


class Eloquent {

    public static function start($file){
        $db = new DB();
        $db->addConnection(parse_ini_file($file));
        $db->setAsGlobal();
        $db->bootEloquent();
    }

}
