<?php

session_start();
require_once("fonctions.php");

echo '<!DOCTYPE HTML><html>';

html_head("Accueil");
html_body_begin();

if(!estCo()) {
  redirige('co.php');
}

// Menu
html_nav(PAGE_ACCUEIL);

// Contenu ici
echo '<div class="container">
<h1 class="center ', getColor(), '-text text-darken-2">Demande de logo</h1>';

$error = false;
$erreurs = array();
// Récupération des données en POST
if(isset($_POST['demande'])) {
  if(!isset($_POST['monstre']) || $_POST['monstre'] == '') {
    $error = true;
    $erreurs[] = 'Vous n\'avez pas précisé de monstre';
  }
  if(!isset($_POST['attribut']) || $_POST['attribut'] == 0) {
    $error = true;
    $erreurs[] = 'Vous n\'avez pas précisé d\'attribut';
  }

  if(!$error) {
    bd_connexion();
    $monstre = mysqli_real_escape_string($GLOBALS['bd'], $_POST['monstre']);
    $attribut = $_POST['attribut'];
    $precisions = isset($_POST['plus']) ? mysqli_real_escape_string($GLOBALS['bd'], $_POST['plus']) : 'Vide';

    $sql = "INSERT INTO demandes (demUser, demMonstre, demAttribut, demPrecision)
            VALUES ({$_SESSION['id']}, '$monstre', $attribut, '$precisions')";
    $R = mysqli_query($GLOBALS['bd'], $sql) or bd_error($sql);

    echo '<p class="card green lighten-5">Succès</p>';
  }
}

if($error) {
  echo '<p class="card orange lighten-5">';
  foreach($erreurs as $e) {
    echo $e, '<br>';
  }
  echo '</p>';
}

echo '<form method="POST" class="col s12">
  <div class="row">
    <div class="input-field col s6">
      <i class="material-icons prefix">account_circle</i>
      <input required name="monstre" type="text" id="monstre">
      <label for="monstre">Monstre - par ex Amazone</label>
    </div>
    <div class="input-field col s6">
      <select name="attribut">
        <option disabled selected value="0">Attribut</option>
        <option value=1>Vent</option>
        <option value=2>Eau</option>
        <option value=3>Feu</option>
        <option value=4>Dark</option>
        <option value=5>Light</option>
      </select>
    </div>
    <div class="input-field col s12">
      <i class="material-icons prefix">mode_edit</i>
      <textarea id="plus" class="materialize-textarea" name="plus"></textarea>
      <label for="plus">Précisions - couleur de fond</label>
    </div>

    <div class="input-field col s12 center">
      <button class="btn ', getColor(), ' waves-effect waves-light" type="submit" name="demande">Demander
        <i class="material-icons right">send</i>
      </button>
    </div>

  </div>

</form>
</div>';

html_body_end();
echo '</html>';

?>
