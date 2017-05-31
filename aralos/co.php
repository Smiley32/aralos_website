<?php

/*
CREATE TABLE utilisateurs (
    utiId INT PRIMARY KEY AUTO_INCREMENT,
    utiPseudo VARCHAR(255),
    utiPass VARCHAR(32),
    utiGuilde INT,
    utiColor VARCHAR(6)
);

CREATE TABLE news (
    newId INT PRIMARY KEY,
    newText VARCHAR(1000)
);

CREATE TABLE message (
    mesId INT PRIMARY KEY,
    mesDestId INT,
    mesOrigId INT,
    mesText VARCHAR(1000)
);

CREATE TABLE demandes (
    demId INT PRIMARY KEY AUTO_INCREMENT,
    demUser INT,
    demMonstre VARCHAR(100),
    demAttribut INT,
    demPrecision VARCHAR(1000)
);
*/

require_once("fonctions.php");
session_start();

/*if(isset($_POST["pseudo"])) {
  echo $_POST["pseudo"];
}*/

echo '<!DOCTYPE HTML><html>';

html_head("Accueil");
html_body_begin();

// Menu
html_nav(PAGE_CONNEXION);

/* Vérification de la connexion si nécessaire */
$erreur = false;
if(isset($_POST['pseudo'])) {
  bd_connexion();

  $pseudo = mysqli_real_escape_string($GLOBALS['bd'], $_POST['pseudo']);
  $password = mysqli_real_escape_string($GLOBALS['bd'], md5($_POST['pass']));

  $sql = "SELECT *
          FROM utilisateurs
          WHERE utiPseudo='$pseudo'
          AND utiPass='$password'";

  $R = mysqli_query($GLOBALS['bd'], $sql) or bd_error($sql);

  $D = mysqli_fetch_assoc($R);
  mysqli_free_result($R);

  if($D === NULL) {
    $erreur = true;
  } else {
    $_SESSION['pseudo'] = $D['utiPseudo'];
    $_SESSION['id'] = $D['utiId'];
    $_SESSION['color'] = $D['utiColor'];

    mysqli_close(GLOBALS['bd']);

    redirige('accueil.php');
  }
}

// Contenu ici
echo '<div class="container">
  <h1 class="center blue-text text-darken-2">Connexion</h1>';

if($erreur) {
  echo '<p class="card orange lighten-5">Impossible de vous connecter, réessayez</p>';
}

  if(!estCo()) {
    echo '<form class="col s12" method="POST" action="co.php">
      <div class="row">
        <div class="input-field col s12 m4">
          <i class="material-icons prefix">account_circle</i>
          <input type="text" id="pseudo" name="pseudo">
          <label for="pseudo">Pseudo</label>
        </div>
        <div class="input-field col s12 m4">
           <i class="material-icons prefix">vpn_key</i>
          <input type="password" id="pass" name="pass">
          <label for="pass">Password</label>
        </div>

        <div class="input-field col s12 m4 center">
          <button class="btn waves-effect waves-light" type="submit" name="co">Se connecter
            <i class="material-icons right">send</i>
          </button>
        </div>

      </div>

    </form>';
  } else {
    echo '<p class="card blue lighten-5">Vous êtes déjà connecté</p>';
  }

echo '</div>';

html_body_end();
echo '</html>';

?>
