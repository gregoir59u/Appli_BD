<?php
namespace games\model;
class Genre extends \Illuminate\Database\Eloquent\Model{

    protected $table = 'genre';
    protected $primaryKey = 'id';
    public $timestamps = 'false';
}
