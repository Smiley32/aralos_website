<?php

require_once("fonctions.php");
session_start();

echo '<!DOCTYPE HTML><html>';

html_head("Accueil");
html_body_begin();

$erreur = false;
$erreursTab = array();

// Récupération des informations d'inscription
if(isset($_POST['pseudo'])) {
  // Vérification des informations reçues
  if(strlen($_POST['pass']) < 3) {
    // Le mot de passe doit être plus long que 3
    $erreur = true;
    $erreursTab[] = 'Le mot de passe doit être plus long que 3';
  }
  if($_POST['pass'] !== $_POST['passAgain']) {
    // Les mots de passes sont différents
    $erreur = true;
    $erreursTab[] = 'Les mots de passes sont différents';
  }
  if(!isset($_POST['pseudo']) || $_POST['pseudo'] == '') {
    // Le pseudo ne doit pas être vide
    $erreur = true;
    $erreursTab[] = 'Le pseudo ne doit pas être vide';
  }

  if(!$erreur) {
    // Connexion à la base de données
    bd_connexion();

    $pseudo = mysqli_real_escape_string($GLOBALS['bd'], $_POST['pseudo']);

    // On ne garde pas le mot de passe, seulement son hachage
    $mdp = mysqli_real_escape_string($GLOBALS['bd'], md5($_POST['pass']));

    // Vérification de l'existence de cet utilisateur
    $sql = "SELECT *
            FROM utilisateurs
            WHERE utiPseudo='$pseudo'";
    $R = mysqli_query($GLOBALS['bd'], $sql) or bd_error($sql);
    $D = mysqli_fetch_assoc($R);
    mysqli_free_result($R);
    if($D) {
      // Un utilisateur existe déjà avec ce nom --> erreur
      $erreur = true;
      $erreursTab[] = 'Un utilisateur existe déjà avec ce nom';
    } else {
      // Récupération de la guilde
      $guilde = $_POST['guilde'] == 'aralos2' ? 2 : 1;
      // La couleur
      $color = mysqli_real_escape_string($GLOBALS['bd'], $_POST['color']);

      // Tout va bien, on va pouvoir entrer le nouvel utilisateur dans la base
      $sql = "INSERT INTO utilisateurs (utiPseudo, utiPass, utiGuilde, utiColor)
              VALUES ('$pseudo', '$mdp', $guilde, '$color')";

      $R = mysqli_query($GLOBALS['bd'], $sql) or bd_error($sql);

      // Connection de ce nouvel utilisateur
      $_SESSION['pseudo'] = $pseudo;
      $_SESSION['id'] = mysqli_insert_id($GLOBALS['bd']);
      $_SESSION['color'] = $color;
    }
  }
}

// Menu
html_nav(PAGE_INSCRIPTION);

echo '<div class="container">
  <h1 class="center blue-text text-darken-2">Connexion</h1>';

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
          <option value="aralos2">Aralos 2</option>
          <option value="aralos">Aralos</option>
        </select>
      </div>
      <div class="input-field col s12">
        <select name="color">
          <option value="blue" selected>Choisissez la couleur du site</option>
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
        <button class="btn waves-effect waves-light" type="submit" name="co">S\'inscrire
          <i class="material-icons right">send</i>
        </button>
      </div>

    </div>

  </form>

</div>';

html_body_end();
echo '</html>';

?>
