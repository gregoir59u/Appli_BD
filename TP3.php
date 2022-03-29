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

use TP1\bd\Eloquent;

Eloquent::start('src/conf/conf.ini');
session_start();

echo "<h2>Vérifier les temps d'execution de certaine requetes</h2>";

$time_start1 = microtime(true);
$games1 = \games\model\Game::select('name')->get();
$time_end1 = microtime(true);
$time1 = $time_end1 - $time_start1;
echo '<h3>* Lister tous le jeux : </h3>';
echo "<p>Temps d'execution : $time1</p>";

$time_start2 = microtime(true);
$games2 = \games\model\Game::select('name')->where( 'name', 'like', '%Mario%')->get();
$time_end2 = microtime(true);
$time2 = $time_end2 - $time_start2;
echo '<h3>* Lister tous les jeux contenant Mario: </h3>';
echo "<p>Temps d'execution : $time2</p>";

$time_start3 = microtime(true);
$games3 = \games\model\Game::select('id')->where( 'name', 'like', 'Mario%')->get();
foreach ($games3 as $game) {
    $characters = \games\model\Game2character::select('character_id')->where('game_id','=',$game->id)->get();
    foreach ($characters as $character) {
        $gamecharacters = \games\model\Character::select('name')->where('id','=',$character->character_id)->get();
    }
}
$time_end3 = microtime(true);
$time3 = $time_end3 - $time_start3;
echo "<h3>* Afficher les personnages des jeux dont le nom débute par 'Mario': </h3>";
echo "<p>Temps d'execution : $time3</p>";

$time_start4 = microtime(true);
foreach((\games\model\Game::where('name', 'like', 'Mario%')
    ->whereHas('original_game_ratings', function($q) {
        $q->where('name', 'like', '%3+%');
    })
    ->get()) as $f2){
}
$time_end4 = microtime(true);
$time4 = $time_end4 - $time_start4;
echo "<h3>* les jeux dont le nom débute par 'Mario' et dont le rating initial contient '3+': </h3>";
echo "<p>Temps d'execution : $time4</p>";


