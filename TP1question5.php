<?php


require_once __DIR__ . '/vendor/autoload.php';
require 'src/bd/Eloquent.php';
require 'src/models/Game.php';

use TP1\bd\Eloquent;

Eloquent::start('src/conf/conf.ini');
session_start();

/*Question 5)
$numberq5 = 0;
$games = Game::select('name', 'deck')->paginate(15)->get();
echo '<h2>Q5) Lister les jeux, afficher leur nom et deck, en paginant (taille des pages : 500)</h2>';
$games = Game::simplePaginate(15);
echo $games;
foreach ($games as $game) {
    echo '<br>JEU : ' . $game->name . "<br>DECK : " . $game->deck . "<br>";
    $numberq5 += 1;
}
echo "<br><h3>Nombre de lignes : $numberq5</h3>";*/