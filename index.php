<?php

require('vendor/autoload.php');

if($SERVER['HTTP_POST'] != "coffee-fee.herokuapp.com"){
$dotenv = \Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();
}

echo('hello world !');
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
