<?php

require_once __DIR__ . '/vendor/autoload.php';
require 'src/bd/Eloquent.php';
require 'src/models/Game2character.php';
require 'src/models/Character.php';
require 'src/models/Game.php';
require 'src/models/Company.php';
require 'src/models/Game_developers.php';
require 'src/models/Game_rating.php';
require 'src/models/Game2rating.php';
require 'src/models/Rating_board.php';
require 'src/models/Genre.php';
require 'src/models/Game2genre.php';

use TP1\bd\Eloquent;

Eloquent::start('src/conf/conf.ini');
session_start();

/*Question 1)*/
echo '<h2>TP2 : Q1) Afficher (name , deck) les personnages du jeu 12342</h2>';
$games = \games\model\Game2character::select('character_id')->where( 'game_id', '=', '12342')->get();
foreach ($games as $game) {
    $characters = \games\model\Character::select('name', 'deck')->where( 'id','=',$game->character_id)->get();
    foreach ($characters as $character) {
        echo '<br>Name : ' . $character->name . '<br>Deck : ' . $character->deck . '<br>';
    }
}

/*Question 2)*/
$numberq2 = 0;
echo '<h2>TP2 : Q2)Les personnages des jeux dont le nom (du jeu) débute par Mario</h2>';
$games = \games\model\Game::select('id')->where( 'name', 'like', 'Mario%')->get();
foreach ($games as $game) {
    $characters = \games\model\Game2character::select('character_id')->where('game_id','=',$game->id)->get();
    foreach ($characters as $character) {
        $gamecharacters = \games\model\Character::select('name')->where('id','=',$character->character_id)->get();
        foreach($gamecharacters as $gamecharacter) {
            echo '<br>Name : ' . $gamecharacter->name;
            $numberq2 += 1;
        }
    }
}
echo "<br><h3>Nombre de lignes : $numberq2</h3>";

/*Question 3)*/
$numberq3 = 0;
echo '<h2>TP2 : Q3) Les jeux développés par une compagnie dont le nom contient Sony</h2>';
$companies = \games\model\Company::select('id')->where( 'name', 'like', '%sony%')->get();
foreach ($companies as $company) {
    $developers = \games\model\Game_developers::select('game_id')->where('comp_id','=',$company->id)->get();
    foreach ($developers as $developer) {
        $game_sonys = \games\model\Game::select('name')->where('id','=',$developer->game_id)->get();
        foreach($game_sonys as $game_sony) {
            echo '<br>Nom du jeu : ' . $game_sony->name;
            $numberq3 += 1;
        }
    }
}
echo "<br><h3>Nombre de lignes : $numberq3</h3>";

/*Question 4)*/
$numberq4 = 0;
echo '<h2>TP2 : Q4) Le rating initial (indiquer le rating board) des jeux dont le nom contient Mario</h2>';
$games2 = \games\model\Game::select('id', 'name')->where( 'name', 'like', '%Mario%')->get();
foreach ($games2 as $game2) {
    $game2ratings = \games\model\Game2rating::select('rating_id')->where('game_id','=',$game2->id)->get();
    foreach ($game2ratings as $game2rating) {
        $game_ratings = \games\model\Game_Rating::select('rating_board_id')->where('id','=',$game2rating->rating_id)->get();
        foreach($game_ratings as $game_rating) {
            $rating_board = \games\model\Rating_board::select('name')->where('id','=',$game_rating->rating_board_id)->get();
            foreach($rating_board as $rating_boards) {
                echo '<br> Jeu : ' . $game2->name . " || Rating board : " . $rating_boards->name;
                $numberq4 += 1;
            }
        }
    }
}
echo "<br><h3>Nombre de lignes : $numberq4</h3>";

/*Question 5)*/
$numberq5 = 0;
echo '<h2>TP2 : Q5) Les jeux dont le nom débute par Mario et ayant plus de 3 personnages</h2>';
foreach((\games\model\Game::where('name', 'like', 'Mario%')
    ->has('characters', '>', 3)
    ->get()) as $f1){
    echo '<br> ID : '. $f1->id . ' || Nom : ' . $f1->name;
    $numberq5 += 1;
}
echo "<br><h3>Nombre de lignes : $numberq5</h3>";

/* VERIFICATION SQL Q5)
 * select id, name from game where name like 'Mario%' AND id in
 * (select game_id from gamepedia.game2character group by game_id having count(game_id) > 3);
 */

/*Question 6)*/
$numberq6 = 0;
echo '<h2>TP2 : Q6) Les jeux dont le nom débute par Mario et dont le rating initial contient "3+"</h2>';
foreach((\games\model\Game::where('name', 'like', 'Mario%')
    ->whereHas('original_game_ratings', function($q) {
        $q->where('name', 'like', '%3+%');
    })
    ->get()) as $f2){
    echo '<br> ID : '. $f2->id . ' || Nom : ' . $f2->name;
    $numberq6 += 1;
}
echo "<br><h3>Nombre de lignes : $numberq6</h3>";

/* VERIFICATION SQL Q6)
 * select id, name from game where name like 'Mario%' AND id in
 * (select game_id from game2rating where rating_id in
 * (select id from game_rating where name like '%3+%'));
 */


/*Question 7)*/
$numberq7 = 0;
echo '<h2>TP2 : Q7) Les jeux dont le nom débute par Mario, publiés par une compagnie dont le nom contient "Inc." et dont le rating initial contient "3+"</h2>';
foreach((\games\model\Game::where('name', 'like', 'Mario%')
    ->whereHas('original_game_ratings', function($q) {
        $q->where('name', 'like', '%3+%');
    })
    ->whereHas('publishers', function($q2) {
        $q2->where('name', 'like', '%Inc.%');
    })
    ->get()) as $f3){
    echo '<br> ID : '. $f3->id . ' || Nom : ' . $f3->name;
    $numberq7 += 1;
}
echo "<br><h3>Nombre de lignes : $numberq7</h3>";

/*Question 8)*/
$numberq8 = 0;
echo '<h2>TP2 : Q8) les jeux dont le nom débute Mario, publiés par une compagnie dont le nom contient "Inc",dont le rating initial contient "3+" et ayant reçu un avis de la part du rating board nommé "CERO"</h2>';
foreach((\games\model\Game::where('name', 'like', 'Mario%')
    ->whereHas('original_game_ratings', function($q) {
        $q->where('name', 'like', '%3+%');
    })
    ->whereHas('original_game_ratings', function($q) {
        $q->where('name', 'like', '%CERO%');
    })
    ->whereHas('publishers', function($q2) {
        $q2->where('name', 'like', '%Inc.%');
    })
    ->get()) as $f4){
    echo '<br> ID : '. $f4->id . ' || Nom : ' . $f4->name;
    $numberq8 += 1;
}
echo "<br><h3>Nombre de lignes : $numberq8</h3>";

/*Question 9)
Fonctionne mais en commentaire pour éviter d'insérer à chaque rechargement de la page
$numberq9 = 0;
echo '<h2>TP2 : Q9) Ajouter un nouveau genre de jeu, et lassocier aux jeux 12, 56, 12, 345</h2>';
\games\model\Genre::insert(['id'=> 51, 'name' => 'Maximegenre', 'deck' => 'Maximedeck']);
\games\model\Game2genre::insert(['game_id' => 12, 'genre_id' => 51]);
\games\model\Game2genre::insert(['game_id' => 56, 'genre_id' => 51]);
\games\model\Game2genre::insert(['game_id' => 345, 'genre_id' => 51]);
*/