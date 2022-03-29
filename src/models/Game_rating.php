<?php
namespace games\model;
class Game_Rating extends \Illuminate\Database\Eloquent\Model {

    protected $table = 'game_rating';
    protected $primaryKey = 'id';
    protected $fillable = [ 'name', 'rating_board_id'];
    public $timestamps = false ;


    public function games() {

        return $this->belongsToMany('\games\model\Game', 'game2rating', 'rating_id', 'game_id');
    }

    public function rating_board() {
        return $this->belongsTo('\games\model\RatingBoard', 'rating_board_id');
    }

}