<?php

require('vendor/autoload.php');
use Carbon\Carbon;

if($_SERVER['HTTP_HOST'] != "coffee-fee.herokuapp.com"){
$dotenv = \Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();
}

echo('<h1>hello world !</h1>');
print '<br/>';

function dbaccess() {
  $dbConnection = "mysql:dbname=". $_ENV['DB_NAME'] ."; host=". $_ENV['DB_HOST'] .":". $_ENV['DB_PORT'] ."; charset=utf8";
  $user = $_ENV['DB_USERNAME'];
  $pwd = $_ENV['DB_PASSWORD'];
  
  $db = new PDO ($dbConnection, $user, $pwd, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

  return $db;
}
  
$db = dbaccess();



$req = $db->query('SELECT name FROM waiter')->fetchAll();
foreach ($req as $dbreq) {
  echo $dbreq['name'] . "<br>";
}


$rek = $db->query('SELECT name FROM edible')->fetchAll();
foreach ($rek as $dbrek) {
  echo ("Le " . $dbrek['name']. "<br>");
}

echo("<h2>Cafés</h2>");
echo("<p>Voici les cafés qui valent 1.3€</p>");


// "SELECT name, price  FROM edible WHERE FORMAT(price, 1) = 1.3"
$cafs = $db->query('SELECT price, name  FROM edible, orderedible WHERE FORMAT(price, 1) = 1.3')->fetchAll();
foreach ($cafs as $caf) {
  echo ($caf['name'] . "<br>");
} 



// $total = $db->query('SELECT price  FROM WHERE )->fetchAll();

// echo $total;

//CHIFFRE D'AFFAIRE PAR LE SERVEUR 2

echo("<p>Chiffre d'affaire du serveur 2</p>");

$ordersIdWaiter2 = 'SELECT FORMAT(SUM(price*quantity),2) AS turnover FROM `order` AS o INNER JOIN `orderedible` AS oe ON o.id=oe.idOrder WHERE o.idWaiter=2;';
$total=$db->query($ordersIdWaiter2)->fetch(PDO::FETCH_OBJ);
echo "Le serveur 2 a réalisé un chiffre d'affaire de : " . $total->turnover . " euros";


// SAVOIR LE NOM DES SERVEURS AYANT SERVI LA TABLE 1
echo("<p>La table 1 a été servie par</p>");

$tablets = $db->query('SELECT `idWaiter`, waiter.name AS nom FROM `order` INNER JOIN waiter ON `order`.idWaiter=waiter.id WHERE`idRestaurantTable`=1')->fetchAll();
foreach ($tablets as $tablet){

echo $tablet['nom'] . "<br/>";}

//CONNAITRE LE OU LES CAFES LES PLUS CONSOMMES
echo("<p>Café(s) le(s) plus consommé(s)</p>");

('SELECT e.name, SUM(oe.quantity) AS total FROM `OrderEdible` AS oe INNER JOIN `Edible` AS e ON e.id = oe.idEdible GROUP BY oe.idEdible HAVING total = ( SELECT SUM(oe.quantity) AS total FROM `OrderEdible` AS oe INNER JOIN `Edible` AS e ON e.id = oe.idEdible GROUP BY oe.idEdible ORDER BY total DESC LIMIT 1');

//AFFICHER LES INFORMATIONS DE COMMANDE DU SERVEUR 2 (nom/date de la commande/total de la commande)
// ('SELECT w.name AS waiter, o.createdAt AS creationDate, FORMAT(SUM(oe.price * oe.quantity), 2) AS turnover 
// FROM `Order` AS o
// INNER JOIN `Waiter` AS w ON o.idWaiter = w.id
// INNER JOIN `OrderEdible` AS oe ON oe.idOrder = o.id
// WHERE w.id = 2 GROUP BY oe.idOrder')
 
 
// Alternative :
// ('SELECT name, createdAt, FORMAT(SUM(price), 2) AS facture 
// FROM `Waiter`,`Order`, `OrderEdible` 
// WHERE `Waiter`.id=`Order`.idWaiter 
// AND `Order`.id=`OrderEdible`.idOrder AND idWaiter=2 GROUP BY `Order`.id');

echo("<p>info commande serveur 2</p>");


$keufai = 'SELECT name, createdAt, FORMAT(SUM(price), 2) AS facture FROM `Waiter`,`Order`, `OrderEdible` WHERE `Waiter`.id=`Order`.idWaiter AND `Order`.id=`OrderEdible`.idOrder AND idWaiter=2 GROUP BY `Order`.id;
';
$totalou=$db->query($keufai)->fetchAll(
);

foreach($totalou as $totalo){
$carbon = Carbon::parse($totalo["createdAt"]);

echo $totalo['name']. " / " . $carbon->locale('fr')->diffForHumans() . " / " . $totalo['facture'] . "<br/>";}

//ou 

// echo "<h3>Toutes les informations sur le serveur 2</h3>";

// $everythingTwos = $db->query(
//     "SELECT name, `order`.createdAt, FORMAT(SUM(orderedible.price * orderedible.quantity), 2) AS turnover
//     FROM `order`
//     INNER JOIN waiter ON `order`.idWaiter = waiter.id
//     INNER JOIN orderedible ON orderEdible.idOrder = `order`.id
//     WHERE waiter.id = 2 GROUP BY orderEdible.idOrder;
// ")->fetchAll();

// echo "Voici les commandes qu'a honorées le serveur 2 : " ;
// foreach ($everythingTwos as $everythingTwo) { 
//     echo "<br> - Serveur : " . $everythingTwo['name'] . " Date : " . $everythingTwo['createdAt'] . " Montant de la commande : " . $everythingTwo['turnover'] . "€" ;
// }