<?php

// echo ('Hello World ! Ca va? ok ok ');


function bdd()
{
    try {
        $bdd = new PDO("mysql:dbname=abclight;host=localhost", "root", "");
    } catch (PDOException $e) {
        echo "Bravo: " . $e->getMessage();
    }

    return $bdd;
}

function waiters()
{
    global $bdd;

    $req = $bdd->query('SELECT id, name FROM waiters');

    $waiters = $req->fetchAll();

    return $waiters;
}