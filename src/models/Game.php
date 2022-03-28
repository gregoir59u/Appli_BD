<?php
class Game extends \Illuminate\Database\Eloquent\Model {

    protected $table = 'game';
    protected $primaryKey = 'id';
    protected $fillable = [ 'name', 'alias', 'deck', 'description',
        'expected_release_day', 'expected_release_month',
        'expected_release_quarter', 'expected_release_year',
        'original_release_date'];

    public function platforms() {

        return $this->belongsToMany('\games\model\Platform', 'game2platform', 'game_id', 'platform_id');
    }

    public function original_game_ratings() {

        return $this->belongsToMany('\games\model\GameRating', 'game2rating', 'game_id', 'rating_id');
    }

    public function publishers() {
        return $this->belongsToMany('\games\model\Company', 'game_publishers', 'game_id', 'comp_id');
    }

    public function developers() {
        return $this->belongsToMany('\games\model\Company', 'game_developers', 'game_id', 'comp_id');
    }

    public function themes() {
        return $this->belongsToMany('\games\model\Theme', 'game2theme', 'game_id','theme_id');
    }

    public function genres() {
        return $this->belongsToMany('\games\model\Genre', 'game2genre', 'game_id','genre_id');
    }

    public function similar_games() {
        return $this->belongsToMany('\games\model\Game', 'similar_games', 'game1_id', 'game2_id');
    }

    public function characters() {
        return $this->belongsToMany('\games\model\Character', 'game2character', 'game_id', 'character_id');
    }

    public function first_appearance_characters() {
        return $this->hasMany('\games\model\Character', 'first_appeared_in_game_id');
    }

    public function comments() {
        return $this->hasMany('\games\model\Comment', 'game_id');
    }
}