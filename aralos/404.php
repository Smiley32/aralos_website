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
  <h1 class="center ', getColor(), '-text text-darken-2">Erreur 404</h1>

  <p class="card ', getColor(), ' lighten-5">Page introuvable</p>
</div>';

html_body_end();
echo '</html>';

?>
