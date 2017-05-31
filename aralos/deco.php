<?php

require_once('fonctions.php');

session_start();
session_unset();
session_destroy();

redirige('accueil.php');

?>
