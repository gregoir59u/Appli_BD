<?php

require_once __DIR__ . '/vendor/autoload.php';
require 'src/bd/Eloquent.php';
require 'src/models/Game2character.php';
require 'src/models/Character.php';
require 'src/models/Game.php';
require 'src/models/Company.php';
require 'src/models/Game_developers.php';

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