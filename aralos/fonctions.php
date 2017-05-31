<?php

define('BD_URL', 'localhost');
define('BD_USER', 'root');
define('BD_PASS', '');
define('BD_NOM', 'aralos');

function estCo() {
  return isset($_SESSION['id']);
}

function redirige($page) {
  header("Location: $page");
	exit();
}

function bd_error($sql) {
  $errNum = mysqli_errno($GLOBALS['bd']);
	$errTxt = mysqli_error($GLOBALS['bd']);

	// Collecte des informations facilitant le debugage
	$msg = '<h4>Erreur de requ&ecirc;te</h4>'
			."<pre><b>Erreur mysql :</b> $errNum"
			."<br> $errTxt"
			."<br><br><b>Requ&ecirc;te :</b><br> $sql"
			.'<br><br><b>Pile des appels de fonction</b>';

	// Récupération de la pile des appels de fonction
	$msg .= '<table border="1" cellspacing="0" cellpadding="2">'
			.'<tr><td>Fonction</td><td>Appel&eacute;e ligne</td>'
			.'<td>Fichier</td></tr>';

	// http://www.php.net/manual/fr/function.debug-backtrace.php
	$appels = debug_backtrace();
	for ($i = 0, $iMax = count($appels); $i < $iMax; $i++) {
		$msg .= '<tr align="center"><td>'
				.$appels[$i]['function'].'</td><td>'
				.$appels[$i]['line'].'</td><td>'
				.$appels[$i]['file'].'</td></tr>';
	}

	$msg .= '</table></pre>';

  echo $msg;
}

function bd_connexion() {
  $bd = mysqli_connect(BD_URL, BD_USER, BD_PASS, BD_NOM);

  if($bd !== FALSE) {
    mysqli_set_charset($bd, 'utf8') or errorExit('Erreur lors du chargement du jeu de caractères utf8');
    $GLOBALS['bd'] = $bd;
    return;
  }

  $msg = '<h4>Erreur de connexion base MySQL</h4>'
          .'<div style="margin: 20px auto; width: 350px;">'
              .'APP_BD_URL : '.APP_BD_URL
              .'<br>APP_BD_USER : '.APP_BD_USER
              .'<br>APP_BD_PASS : '.APP_BD_PASS
              .'<br>APP_BD_NOM : '.APP_BD_NOM
              .'<p>Erreur MySQL num&eacute;ro : '.mysqli_connect_errno($bd)
              .'<br>'.mysqli_connect_error($bd)
          .'</div>';
  errorExit($msg);
}

function errorExit($msg) {
  ob_end_clean();

  $buffer = date('d/m/Y H:i:s')."\n$msg\n";
	error_log($buffer, 3, 'erreurs_bd.txt');

  echo 'erreur...';
  exit();
}

function html_head($titre) {
  echo '<head>
    <link href="http://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link type="text/css" rel="stylesheet" href="css/materialize.css"  media="screen,projection"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>', $titre, '</title>
  </head>';
}

function html_body_begin() {
  echo '<body>
    <!--Import jQuery before materialize.js-->
    <script type="text/javascript" src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
    <script type="text/javascript" src="js/materialize.js"></script>';
}

function html_body_end() {
  echo '<script src="js/init.js"></script>
</body>';
}

define('PAGE_ACCUEIL', 0);
define('PAGE_CONNEXION', 1);
define('PAGE_INSCRIPTION', 2);
define('PAGE_USER', 3);
define('PAGE_LOGOS', 4);
define('PAGE_DEMANDE', 5);
define('PAGE_DECONNEXION', 6);
define('PAGE_ADMIN', 7);

function getColor() {
  return isset($_SESSION['color']) ? $_SESSION['color'] : 'blue';
}

function html_nav($current = PAGE_ACCUEIL) {
  echo '<nav class="', getColor() , ' lighten-1" role="navigation">
    <div class="nav-wrapper container"><a id="logo-container" href="accueil.php" class="brand-logo">Aralos</a>
      <ul class="right hide-on-med-and-down">';
  html_nav_liste($current);
  echo '</ul>
      <ul id="nav-mobile" class="side-nav">';
  html_nav_liste($current);
  echo '</ul>
      <a href="#" data-activates="nav-mobile" class="button-collapse"><i class="material-icons">menu</i></a>
    </div>
  </nav>';
}

function html_nav_liste($current = PAGE_ACCUEIL) {
  $liens    = array("accueil.php", "co.php",       "inscription.php", "user.php", "logos.php",  "ask_logo.php",    "deco.php",       "admin.php");
  $libelles = array("Accueil",     "Se connecter", "Inscription",     "Ma page",  "Logos",      "Demande de logo", "Se deconnecter", "Admin");

  $co = estCo();
  for($i = 0; $i <= 7; $i++) {
    if($co && ($i == 1 || $i == 2)) {
      continue;
    }
    if(!$co && ($i == 3 || $i == 5 || $i == 6 ||$i == 7)) {
      continue;
    }
    echo '<li><a href="', $liens[$i], '">', $libelles[$i], '</a></li>';
  }
}

?>
