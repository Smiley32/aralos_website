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
  <h1 class="center ', getColor(), '-text text-darken-2">Liste des logos</h1>';

bd_connexion();
$sql = "SELECT utiPseudo, utiId
        FROM utilisateurs";

$R = mysqli_query($GLOBALS['bd'], $sql) or bd_error($sql);

echo '<div class="row">';
while($res = mysqli_fetch_assoc($R)) {
  $pic = "icones/{$res['utiId']}.png";
  if(file_exists($pic)) {
    echo '<img class="col l4 m6 s12 materialboxed" src="', $pic, '">';
  }
}
mysqli_free_result($R);

echo '</div></div>';

html_body_end();
echo '</html>';

?>
