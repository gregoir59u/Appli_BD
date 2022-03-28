<?php
class Rating_board extends \Illuminate\Database\Eloquent\Model {

    protected $table = 'rating_board';
    protected $primaryKey = 'id';
    protected $fillable = [ 'name', 'deck', 'description'];
    public $timestamps = false ;

    public function ratings() {
        return $this->hasMany('\games\model\GameRating', 'rating_board_id');
    }

}