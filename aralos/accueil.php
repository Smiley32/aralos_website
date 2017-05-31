<?php

session_start();
require_once("fonctions.php");

echo '<!DOCTYPE HTML><html>';

html_head("Accueil");
html_body_begin();

// Menu
html_nav(PAGE_ACCUEIL);

// Contenu ici
echo '<div class="container">
  <h1 class="center ', getColor(), '-text text-darken-2">Aralos</h1>

  <p class="card ', getColor(), ' lighten-5">Aralos est une guilde de Summoners War: Sky Arena : un jeu mobile sur Android et iOS.</p>
</div>';

html_body_end();
echo '</html>';

?>
