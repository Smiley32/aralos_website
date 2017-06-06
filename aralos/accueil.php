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

  <h3 class="', getColor(), '-text text-darken-2">Messages</h3>';

bd_connexion();
$sql = "SELECT * FROM news";
$R = mysqli_query($GLOBALS['bd'], $sql) or bd_error($sql);
while($res = mysqli_fetch_assoc($R)) {
  echo '<p class="card ', getColor(), ' lighten-5">', $res['newText'], '</p>';
}

echo '</div>';

html_body_end();
echo '</html>';

?>
