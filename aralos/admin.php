<?php

require_once('fonctions.php');
session_start();

echo '<!DOCTYPE HTML><html>';

html_head("Accueil");
html_body_begin();

html_nav(PAGE_ADMIN);

$co = estCo();

bd_connexion();

// Contenu de la page
if(!$co) {
  redirige('accueil.php');
  exit(); // Au cas où
}

if($_SESSION['id'] != 1) {
  redirige('accueil.php');
  exit();
}

if(isset($_POST['user'])) {
  include('php/class.uploader.php');

  $uploader = new Uploader();
  $data = $uploader->upload($_FILES['image'], array(
      'limit' => 10, //Maximum Limit of files. {null, Number}
      'maxSize' => 1, //Maximum Size of files {null, Number(in MB's)}
      'extensions' => array('png'), //Whitelist for file extension. {null, Array(ex: array('jpg', 'png'))}
      'required' => true, //Minimum one file is required for upload {Boolean}
      'uploadDir' => 'tmp/', //Upload directory {String}
      'title' => array('auto', 10), //New file name {null, String, Array} *please read documentation in README.md
      'removeFiles' => true, //Enable file exclusion {Boolean(extra for jQuery.filer), String($_POST field name containing json data with file names)}
      'replace' => false, //Replace the file if it already exists {Boolean}
      'perms' => null, //Uploaded file permisions {null, Number}
      'onCheck' => null, //A callback function name to be called by checking a file for errors (must return an array) | ($file) | Callback
      'onError' => null, //A callback function name to be called if an error occured (must return an array) | ($errors, $file) | Callback
      'onSuccess' => null, //A callback function name to be called if all files were successfully uploaded | ($files, $metas) | Callback
      'onUpload' => null, //A callback function name to be called if all files were successfully uploaded (must return an array) | ($file) | Callback
      'onComplete' => null, //A callback function name to be called when upload is complete | ($file) | Callback
      'onRemove' => 'onFilesRemoveCallback' //A callback function name to be called by removing files (must return an array) | ($removed_files) | Callback
  ));

  if($data['isComplete']) {
      $files = $data['data'];

      if(isset($files['files'][0])) {
        // On a un fichier png/jpg --> l'image (c'est l'admin qui upload donc pas plus de verif)
        // On a juste à déplacer cette image dans icones/{$id}.png
        $id = $_POST['user'];
        rename($files['files'][0], "icones/$id.png");

        $sql = "DELETE FROM demandes
                WHERE demUser=$id";
        $R = mysqli_query($GLOBALS['bd'], $sql) or bd_error($sql);
      }
  }

  /*if($data['hasErrors']) {
      $errors = $data['errors'];
      print_r($errors);
  }*/
}

echo '<div class="container">
<h1 class="center ', getColor(), '-text text-darken-2">Admin</h1>
<h3>Ajouter un logo</h3>';

if(isset($_POST['user']) && $data['hasErrors']) {
  echo 'Erreur(s) :<br>';
  foreach($data['errors'] as $key => $error) {
    echo '<p class="card orange lighten-5">[', $key, ']';
    foreach($error as $e) {
      echo '<br>', $e;
    }
    echo '</p>';
  }
}

echo '<div class="row">
 <form class="col s12" method="POST" enctype="multipart/form-data">
    <div class="row">';

// Création de la liste des utilisateurs
echo '<div class="input-field">
  <select name="user">';

$sql = "SELECT utiId, utiPseudo
        FROM utilisateurs";

$R = mysqli_query($GLOBALS['bd'], $sql) or bd_error($sql);
while($res = mysqli_fetch_assoc($R)) {
  echo '<option value="', $res['utiId'], '">', $res['utiPseudo'], '</option>';
}
mysqli_free_result($R);

echo '</select></div>';

echo '<div class="file-field input-field">
          <div class="btn ', getColor(), '">
             <span>Parcourir</span>
             <input type="file" name="image" id="image">
          </div>
          <div class="file-path-wrapper">
             <input class="file-path validate" type="text" placeholder="Ajouter un fichier">
          </div>
       </div>
       <div class="input-field col s12 center">
        <button class="btn waves-effect waves-light ', getColor(), '" type="submit" name="demande">Ajouter
          <i class="material-icons right">send</i>
        </button>
      </div>
    </div>
  </form>
</div>';
// Affichage des demandes
$sql = "SELECT * FROM demandes, utilisateurs
        WHERE demUser=utiId";

$R = mysqli_query($GLOBALS['bd'], $sql) or bd_error($sql);
while($res = mysqli_fetch_assoc($R)) {
  echo '<p class="card blue lighten-5">', $res['utiPseudo'], ' - ', $res['demMonstre'], ' ';
  switch($res['demAttribut']) {
    case 1:
      echo 'Vent';
      break;
    case 2:
      echo 'Eau';
      break;
    case 3:
      echo 'Feu';
      break;
    case 4:
      echo 'Dark';
      break;
    case 5:
      echo 'Light';
      break;
  }
  echo '<br>', $res['demPrecision'], '</p>';
}
mysqli_free_result($R);

echo '<h3>Ajouter une information</h3>';

if(isset($_POST['info']) && $_POST['news'] != '') {
  $txt = mysqli_real_escape_string($GLOBALS['bd'], $_POST['news']);
  $sql = "INSERT INTO news (newText) VALUES ('$txt')";
  $R = mysqli_query($GLOBALS['bd'], $sql) or bd_error($sql);
}

echo '<form method="POST">
<div class="row">
  <div class="input-field col s12">
    <textarea id="news" name="news" class="materialize-textarea"></textarea>
    <label for="news">Information</label>
  </div>
  <div class="input-field col s12 center">
   <button class="btn waves-effect waves-light ', getColor(), '" type="submit" name="info">Ajouter l\'info
     <i class="material-icons right">send</i>
   </button>
  </div>
</div>
</form>';

html_body_end();
echo '</html>';

?>
