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
require 'src/models/Game_publishers.php';

use TP1\bd\Eloquent;

Eloquent::start('src/conf/conf.ini');
session_start();

/*Question 1)*/
echo '<h2>TP2 : Q1) Afficher (name , deck) les personnages du jeu 12342</h2>';
$games = Game2character::select('character_id')->where( 'game_id', '=', '12342')->get();
foreach ($games as $game) {
    $characters = Character::select('name', 'deck')->where( 'id','=',$game->character_id)->get();
    foreach ($characters as $character) {
        echo '<br>Name : ' . $character->name . '<br>Deck : ' . $character->deck . '<br>';
    }
}

/*Question 2)*/
$numberq2 = 0;
echo '<h2>TP2 : Q2)Les personnages des jeux dont le nom (du jeu) débute par Mario</h2>';
$games = Game::select('id')->where( 'name', 'like', 'Mario%')->get();
foreach ($games as $game) {
    $characters = Game2character::select('character_id')->where('game_id','=',$game->id)->get();
    foreach ($characters as $character) {
        $gamecharacters = Character::select('name')->where('id','=',$character->character_id)->get();
        foreach($gamecharacters as $gamecharacter) {
            echo '<br>Name : ' . $gamecharacter->name;
            $numberq2 += 1;
        }
    }
}
echo "<br><h3>Nombre de lignes : $numberq2</h3>";

/*On aurait pu utiliser les sous-requetes directement au lieu des 3 foreach
 * SELECT id,name FROM `character` WHERE id IN
 * (SELECT character_id FROM game2character WHERE game_id IN
 * (SELECT id FROM game WHERE name LIKE 'Mario%'));
 */

/*Question 3)*/
$numberq3 = 0;
echo '<h2>TP2 : Q3) Les jeux développés par une compagnie dont le nom contient Sony</h2>';
$companies = Company::select('id')->where( 'name', 'like', '%sony%')->get();
foreach ($companies as $company) {
    $developers = Game_developers::select('game_id')->where('comp_id','=',$company->id)->get();
    foreach ($developers as $developer) {
        $game_sonys = Game::select('name')->where('id','=',$developer->game_id)->get();
        foreach($game_sonys as $game_sony) {
            echo '<br>Nom du jeu : ' . $game_sony->name;
            $numberq3 += 1;
        }
    }
}
echo "<br><h3>Nombre de lignes : $numberq3</h3>";

/*On aurait pu utiliser les sous-requetes directement au lieu des 3 foreach
 * SElECT name FROM game WHERE id IN
 * (SELECT game_id FROM game_developers WHERE comp_id IN
 * (SELECT id FROM company WHERE name LIKE '%sony%'));
 */

/*Question 4)*/
$numberq4 = 0;
echo '<h2>TP2 : Q4) Le rating initial (indiquer le rating board) des jeux dont le nom contient Mario</h2>';
$games2 = Game::select('id', 'name')->where( 'name', 'like', '%Mario%')->get();
foreach ($games2 as $game2) {
    $game2ratings = Game2rating::select('rating_id')->where('game_id','=',$game2->id)->get();
    foreach ($game2ratings as $game2rating) {
        $game_ratings = Game_rating::select('rating_board_id')->where('id','=',$game2rating->rating_id)->get();
        foreach($game_ratings as $game_rating) {
            $rating_board = Rating_board::select('name')->where('id','=',$game_rating->rating_board_id)->get();
            foreach($rating_board as $rating_boards) {
                echo '<br> Jeu : ' . $game2->name . " || Rating board : " . $rating_boards->name;
                $numberq4 += 1;
            }
        }
    }
}
echo "<br><h3>Nombre de lignes : $numberq4</h3>";

/*Question 5)
$numberq5 = 0;
echo '<h2>TP2 : Q5) Les jeux dont le nom débute par Mario et ayant plus de 3 personnages</h2>';
$games3 = Game2character::select('game_id', 'count(game_id)')->groupBy('game_id')->having(Game2character::raw('count(game_id)'), '>', 3)->get();
foreach ($games3 as $game3) {
    echo '<br>' . $game3->name;
    $numberq5 += 1;
}
echo "<br><h3>Nombre de lignes : $numberq5</h3>";*/

/*Question 6)*/
$numberq6 = 0;
echo '<h2>TP2 : Q6) Les jeux dont le nom débute par Mario et dont le rating initial contient "3+"</h2>';
$gaame_ratings = Game_rating::select('id')->where('name','like','%3+%')->get();
foreach ($gaame_ratings as $gaame_rating) {
    $gaame2ratings = Game2rating::select('game_id')->where('rating_id', '=', $gaame_rating->id)->get();
    foreach ($gaame2ratings as $gaame2rating) {
        $gaames = Game::select('name')->where( 'id', '=', $gaame2rating->game_id)->where('name', 'like', 'Mario%')->get();
        foreach ($gaames as $gaaame) {
            echo '<br>Nom du jeu : ' . $gaaame->name;
            $numberq6 += 1;
        }
    }
}

/*Question 7)*/
/*1ERE OPTION
$numberq7 = 0;
echo '<h2>TP2 : Q7) les jeux dont le nom débute par Mario, publiés par une compagnie dont le nom contient "Inc." et dont le rating initial contient "3+"</h2>';
$gaame_ratings = Game_rating::select('id')->where('name','like','%3+%')->get();
foreach ($gaame_ratings as $gaame_rating) {
    $gaame2ratings = Game2rating::select('game_id')->where('rating_id', '=', $gaame_rating->id)->get();
    foreach ($gaame2ratings as $gaame2rating) {
        $gaames_publi = Game_publishers::select('comp_id')->where('game_id','=',$gaame2rating->game_id)->get();
        foreach ($gaames_publi as $gaame_publi) {
            $gaames_comp = Company::select('id')->where('name','like','%Inc.%')->get();
            foreach ($gaames_comp as $gaame_comp) {
                $gaaames_pub = Game_publishers::select('game_id')->where('comp_id','=',$gaame_comp->id)->get();
                foreach ($gaaames_pub as $gaaame_pub) {
                    $gaames = Game::select('name')->where( 'id', '=', $gaaame_pub->game_id)->where('name', 'like', 'Mario%')->get();
                    foreach ($gaames as $gaaame) {
                        echo '<br>Nom du jeu : ' . $gaaame->name;
                        $numberq7 += 1;
                    }
                }
            }
        }
    }
}*/

/*2EME OPTION
$numberq7=0;
echo '<h2>TP2 : Q7) les jeux dont le nom débute par Mario, publiés par une compagnie dont le nom contient "Inc." et dont le rating initial contient "3+"</h2>';
Game_rating::where('name','like','%3+%')
    ->whereHas('');*/