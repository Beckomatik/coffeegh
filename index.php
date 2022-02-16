<?php

// echo ('Hello World ! Ca va? ok ok ')

function bdd()
{
    try {
        $bdd = new PDO("mysql:dbname=blog;host=localhost", "root", "");
    } catch (PDOException $e) {
        echo "Connexion impossible: " . $e->getMessage();
    }

    return $bdd;
}
