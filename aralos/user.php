<?php

session_start();
require_once("fonctions.php");

if(!estCo()) {
  redirige('co.php');
}

$erreur = false;
$erreursTab = array();

// Récupération des informations d'inscription
if(isset($_POST['pseudo'])) {
  // Vérification des informations reçues
  if(strlen($_POST['pass']) < 3 && strlen($_POST['pass']) > 0) {
    // Le mot de passe doit être plus long que 3
    $erreur = true;
    $erreursTab[] = 'Le mot de passe doit être plus long que 3';
  }
  if($_POST['pass'] !== $_POST['passAgain']) {
    // Les mots de passes sont différents
    $erreur = true;
    $erreursTab[] = 'Les mots de passes sont différents';
  }

  if(!$erreur) {
    // Connexion à la base de données
    bd_connexion();


    if(isset($_POST['pseudo']) && $_POST['pseudo'] != '') {
      $pseudo = mysqli_real_escape_string($GLOBALS['bd'], $_POST['pseudo']);
    } else {
      $pseudo = $_SESSION['pseudo'];
    }

    $sql = "UPDATE utilisateurs SET utiPseudo='$pseudo'";
    if(isset($_POST['pass']) && $_POST['pass'] != '') {
      $mdp = mysqli_real_escape_string($GLOBALS['bd'], md5($_POST['pass']));
      $sql .= ", utiPass='$mdp' ";
    }

    if(isset($_POST['guilde']) && $_POST['guilde'] != '' && ($_POST['guilde'] == 'aralos' || $_POST['guilde'] == 'aralos2')) {
      $guilde = $_POST['guilde'] == 'aralos2' ? 2 : 1;
      $sql .= ", utiGuilde=$guilde ";
    }

    if(isset($_POST['color']) && $_POST['color'] != '' && $_POST['color'] != 'aucun') {
      $color = mysqli_real_escape_string($GLOBALS['bd'], $_POST['color']);
      $sql .= ", utiColor='$color' ";
    } else {
      $color = $_SESSION['color'];
    }

    $sql .= 'WHERE utiId=' . $_SESSION['id'];

    $R = mysqli_query($GLOBALS['bd'], $sql) or bd_error($sql);

    if(!$R) {
      $erreur = true;
      $erreursTab[] = 'Une erreur est survenue';
    } else {
      $_SESSION['pseudo'] = $pseudo;
      $_SESSION['color'] = $color;
    }
  }
}

echo '<!DOCTYPE HTML><html>';

html_head("Accueil");
html_body_begin();

// Menu
html_nav(PAGE_ACCUEIL);

// Contenu ici
echo '<div class="container">
  <h1 class="center ', getColor(), '-text text-darken-2">Ma page</h1>';

$file = "icones/{$_SESSION['id']}.png";
if(file_exists($file)) {
  echo '<div class="row"><img class="materialboxed col l4 offset-l4 m6 offset-m3 s12" src="', $file, '"></div>';
}

echo '<h3>Mes informations</h3>
      <p>Laissez les champs que vous ne souhaitez pas modifier vide</p>';

if($erreur) {
  echo '<p class="card orange lighten-5">Erreur(s) :<br>';
  foreach($erreursTab as $e) {
    echo $e, '<br>';
  }
  echo '</p>';
}

echo '<form class="col s12" method="POST">
    <div class="row">
      <div class="input-field col s12">
        <i class="material-icons prefix">account_circle</i>
        <input type="text" id="pseudo" name="pseudo">
        <label for="pseudo">Pseudo</label>
      </div>
      <div class="input-field col s12 m6">
         <i class="material-icons prefix">vpn_key</i>
        <input type="password" id="pass" name="pass">
        <label for="pass">Password</label>
      </div>
      <div class="input-field col s12 m6">
        <input type="password" id="passAgain" name="passAgain">
        <label for="passAgain">Répétez votre mot de passe</label>
      </div>
      <div class="input-field col s12">
        <select name="guilde">
          <option value="guilde">Votre guilde</option>
          <option value="aralos2">Aralos 2</option>
          <option value="aralos">Aralos</option>
        </select>
      </div>
      <div class="input-field col s12">
        <select name="color">
          <option value="aucun" selected>Choisissez la couleur du site</option>
          <option value="red">Rouge</option>
          <option value="pink">Rose</option>
          <option value="purple">Violet</option>
          <option value="blue">Bleu</option>
          <option value="green">Vert</option>
          <option value="yellow">Jaune</option>
          <option value="orange">Orange</option>
          <option value="grey">Gris</option>
        </select>
      </div>

      <div class="input-field col s12 center">
        <button class="btn ', getColor(), ' waves-effect waves-light" type="submit" name="co">Modifier
          <i class="material-icons right">send</i>
        </button>
      </div>

    </div>

  </form>
</div>';

html_body_end();
echo '</html>';

?>
