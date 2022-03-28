<?php

require_once __DIR__ . '/vendor/autoload.php';
require 'src/bd/Eloquent.php';
require 'src/models/Game.php';
require 'src/models/Company.php';
require 'src/models/Platform.php';

use TP1\bd\Eloquent;

Eloquent::start('src/conf/conf.ini');
session_start();

/*Question 1)*/
$numberq1 = 0;
$games = Game::select('name')->where( 'name', 'like', '%Mario%')->get();
echo '<h2>Q1) Lister les jeux dont le nom contient Mario</h2>';
foreach ($games as $game) {
    echo '<br>' . $game->name;
    $numberq1 += 1;
}
echo "<br><h3>Nombre de lignes : $numberq1</h3>";

/*Question 2)*/
$numberq2 = 0;
$companies = Company::select('name')->where( 'location_country', '=', 'Japan')->get();
echo '<h2>Q2) Lister les compagnies installées au Japon</h2>';
foreach ($companies as $company) {
    echo '<br>' . $company->name;
    $numberq2 += 1;
}
echo "<br><h3>Nombre de lignes : $numberq2</h3>";

/*Question 3)*/
$numberq3 = 0;
$platforms = Platform::select('name')->where( 'install_base', '>=', 10000000)->get();
echo '<h2>Q3) Lister les plateformes dont la base installée est >= 10 000 000</h2>';
foreach ($platforms as $platform) {
    echo '<br>' . $platform->name;
    $numberq3 += 1;
}
echo "<br><h3>Nombre de lignes : $numberq3</h3>";

/*Question 4)*/
$numberq4 = 0;
$games = Game::select('id', 'name')->limit(442)->offset(21172)->get();
echo '<h2>Q4) Lister 442 jeux à partir du 21173ème</h2>';
foreach ($games as $game) {
    echo '<br>' . $game->id . " " . $game->name;
    $numberq4 += 1;
}
echo "<br><h3>Nombre de lignes : $numberq4</h3>";

//Question 5)
/*echo '<h2>Q5) Lister les jeux, afficher leur nom et deck, en paginant (taille des pages : 500)</h2>';
$game=App\Models\Game::paginate(500);
echo $game;
foreach ($game as $game) {
    echo '<br>JEU : ' . $game->name . "<br>DECK : " . $game->deck . "<br>";
    $numberq5 += 1;
}
echo "<br><h3>Nombre de lignes : $numberq5</h3>";*/