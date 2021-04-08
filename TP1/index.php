<?php
session_start();
ob_start();
require("site/includes/conn.php");
require("site/includes/fonctions/fonctions.php");
$alerte_inscription = false; 
if (isset($_POST["submit_inscription"])) {
    $Matricule =crypter($_POST['Matricule']);

    $Nom = crypter($_POST['Nom']);
    $Prenom = crypter($_POST['Prenom']);
    $Sexe = crypter($_POST['Sexe']);
    $Classe = crypter($_POST['Classe']);
    $user = $Nom;
    $pass = hash('sha256', $Matricule);
    $img_mat = $Matricule;

    $reponse = $connect->query("SELECT * FROM etudiant");
        $data=$reponse->fetchAll(PDO::FETCH_OBJ);
        $trouve = false;
        foreach($data as $element => $cle_T) {
          if($cle_T->Matricule == $Matricule) {
            $trouve = true;
            break;          
          }else{
            $trouve = false;
          }
        }
        if ($trouve) {
          $alerte_inscription = true;
        }else{
          $date_inscription = crypter(date("d/m/Y"));
      $heure =crypter(date("H:i:s"));
      # Insertion des etudiants
      $inserer = $connect->prepare("INSERT INTO etudiant (Matricule, Nom, Prenom, Sexe, date_inscription, heure, img) VALUES (?, ?, ?, ?, ?, ?, ?) ");
      $inserer->execute(array($Matricule, $Nom, $Prenom, $Sexe, $date_inscription, $heure, $img_mat));

      # Insertion d'utilisateur
      $user_insere = $connect->prepare("INSERT INTO user (username, password) VALUES (?, ?) ");
      $user_insere->execute(array($user, $pass));

      #Mise à jour de l'éffectif
      $select_effectif = $connect->query("SELECT * FROM classe WHERE Intitule=\"$Classe\" ");
      $data_effectif = $select_effectif->fetch(PDO::FETCH_OBJ);

      $set_effectif = decrypter($data_effectif->Effectif) + 1;
      $set_effectif = crypter($set_effectif);

      $rem_mifie = $connect->query("UPDATE classe SET Effectif=\"$set_effectif\" WHERE Intitule=\"$Classe\" ");

      # Insertion appartient
      $inserer = $connect->prepare("INSERT INTO appartenir (mat_etu, id_classe) VALUES (?, ?) ");
      $inserer->execute(array($Matricule, $data_effectif->Code_cl));

      // Enregitrer l'image*******************************
            $img=$_FILES["img"]["name"];
            $img_tmp=$_FILES["img"]["tmp_name"];

            if (!empty($img_tmp)) {
              
              $image=explode('.',$img);

              $image_ext=end($image);

              if (in_array(strtolower($image_ext), array('png','jpg','jpeg'))===false) {
                echo "Veuiller entrer une image ayant pour extention png, jpg ou jpeg";
              }else{
                $image_size = getimagesize($img_tmp);
                if ($image_size["mime"]=='image/jpeg') {
                  $image_src=imagecreatefromjpeg($img_tmp);
                }else if ($image_size["mime"]=='image/png') {
                  $image_src=imagecreatefrompng($img_tmp);
                }else{
                  $image_src=false;
                  echo "veuiller entrer une image valide ";
                }

                if ($image_src!==false) {
                  $image_width=200;
                  if ($image_size[0]==$image_width) {
                    $image_finale=$image_src;
                  }else{
                    $new_width[0]=$image_width;
                    $new_height[1] = 200;
                    $image_finale = imagecreatetruecolor($new_width[0], $new_height[1]);
                    imagecopyresampled($image_finale, $image_src, 0,0,0,0, $new_width[0], $new_height[1], $image_size[0], $image_size[1]);
                  }
                  imagejpeg($image_finale,'site/images/etudiant/'.decrypter($img_mat).'.jpg');
                }

              }

            }else{
              echo "Veuiller rentrer une image.";
            }
            //**************************************************
      $_SESSION['img'] = decrypter($img_mat);
      $_SESSION['type'] = "etud";
      $_SESSION['matricule'] = decrypter($Matricule);
      $_SESSION['nom'] = decrypter($Nom);
      $_SESSION['prenom'] = decrypter($Prenom);
      $_SESSION['sexe'] = decrypter($Sexe);
      $_SESSION['classe'] = decrypter($Classe);
      header("Location: site/acceuil.php");
    }

}

?>


<?php
$alerte_connexion = false;
if (isset($_POST["submit_connexion"])) {
  if ($_POST['Type'] == "etud") {
    $Matricule = crypter($_POST['Matricule']);
    $Nom = crypter($_POST['Nom']);
    $u = $Nom;
    $p = hash('sha256', $Matricule);

    $rec = $connect->prepare("SELECT * FROM user WHERE username = :u and password = :p ");
    $rec->execute(array("u"=>$u, "p"=>$p));
    $donne = $rec->fetch();
    if (!$donne) {
      $alerte_connexion = true;
    }else{

      $select_u = $connect->query("SELECT * FROM etudiant WHERE Matricule=\"$Matricule\" ");
      $data_u=$select_u->fetch();
      $_SESSION['img'] = decrypter($data_u["img"]);
      $_SESSION['type'] = $_POST['Type'];
      $_SESSION['matricule'] = decrypter($data_u["Matricule"]);
      $_SESSION["nom"] = decrypter($data_u["Nom"]);
      $_SESSION["prenom"] = decrypter($data_u["Prenom"]);
      $_SESSION['sexe'] = decrypter($data_u["Sexe"]);
      $_SESSION['classe'] = decrypter($data_u["Classe"]);
      header("Location: site/acceuil.php");
    }
  }

  elseif ($_POST['Type']=="admin") {
    $User = "admin";
    $Pass = "admin";

    $user = $_POST['Nom'];
    $pass = $_POST['Matricule'];

    if ($User == $user && $Pass == $pass) {
      $_SESSION['type'] = $_POST['Type'];
      header("Location: site/acceuil.php");
    }else{

    }
  }

}

?>



<!DOCTYPE html>
<html lang="fr">
<header>
    <meta charset="utf-8">
    <title>Emprunt de livre</title>
    <link rel="stylesheet" type="text/css" href="site/liens_css_js_etc/index.css">
</header>

<body>
  <?php
      require("site/liens_css_js_etc/liens_css.php");
      require("site/liens_css_js_etc/liens_js.php");
  ?>

  <nav class="navbar navbar-inverse navbar-fixed-top">
      <div class=" container-fluid">
          <div class="navbar-header">
              <a class="navbar-brand" href="index.php">E-Bib_tech</a>
          </div>
          <ul class="nav navbar-nav">
              <li class="active"><a href="index.php"><i class="glyphicon glyphicon-home"></i> Acceuil</a></li>
              <!--<li><a href="liste_inscrit.php"><i class="fa fa-group"></i> Liste des inscrits</a></li>-->
              <!--<li><a href="liste_emprunt.php"><i class="glyphicon glyphicon-list"></i> Liste des emprunts</a></li>-->
          </ul>
          <ul class="nav navbar-nav navbar-right">
            <li><a href="" data-toggle="modal" data-target="#connexion"><span class="glyphicon glyphicon-log-in"></span> Se connecter</a></li>
              <li><a href="" data-toggle="modal" data-target="#inscription"><span class="glyphicon glyphicon-user"></span> S'inscrire</a></li>
          </ul>
      </div>
  </nav>
  <br><br>
<div class="parent">
  <div class="corp">
      <div class="container">
        <div>
            <?php if ($alerte_connexion == true){?>
          <div class="alert alert-danger" style="font-size: 0.8cm;">
            <strong><i class="fa fa-warning"></i> Erreur!</strong> Identifiant et/ou mot de passe incorrect.
          </div>
        <?php } ?>
            <?php if ($alerte_inscription == true){?>
          <div class="alert alert-danger" style="font-size: 0.8cm;">
            <strong><i class="fa fa-warning"></i> Erreur!</strong> Le matricule existe déjà.
          </div>
        <?php } ?>
        <div style="text-align: center;margin-top: 5cm;">
              <h1 style="color: white; font-size: 1.5cm; font-weight: 5px;">
                BIENVENUE SUR LE SITE D'EMPRUNT DE LIVRES DE L'ESATIC
              </h1><br>
              <b style="color:orange; font-size: 2cm; text-align: center; font-family: Comic Sans MS;">
                E-Bib_tech
              </b>
            </div>
          </div>
      </div>
  </div>
</div>
  <!-- Popup d'inscription -->
  <div class="modal fade" id="inscription" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h1 class="text-primary">INSCRIPTION <span class="glyphicon glyphicon-user" style="font-size: 1cm; float: right;"></span></h1>
        </div>
        <div class="modal-body">
          <form method="POST" action="" enctype="multipart/form-data">
              <div class="form-group">
                  <label>Matricule</label>
                  <input type="text" class="form-control" name="Matricule" autocomplete="off" placeholder="***e---***" required><br>
              </div>
              <div class="form-group">
                  <label>Nom</label>
                  <input type="text" class="form-control" name="Nom" autocomplete="off" required><br>
              </div>
              <div class="form-group">
                  <label>Prenom</label>
                  <input type="text" class="form-control" name="Prenom" autocomplete="off" required><br>
              </div>
              <div class="form-group">
                  <label>Sexe</label>
                  <select name="Sexe" class="form-control">
                      <option value="M">HOMME</option>
                      <option value="F">FEMME</option>
                  </select>
              </div><br>
              <div class="form-group">
                  <label>Classe</label>

                  <select name="Classe" class="form-control">
                      <?php
                        $select = $connect->prepare('SELECT * FROM Classe'); 
                        $select->execute();
                        while($res1 = $select->fetch()){
                      ?>
                      <option value="<?php echo decrypter($res1['Intitule']); ?>"><?php echo decrypter($res1['Intitule']); ?></option>
                      <?php } ?>
                  </select><br>
              </div>
              <div class="form-group">
                  <label>Photo de profile</label>
                  <input type="file" class="form-group" name="img">
              </div><br>
              <button type="submit" class="btn btn-primary btn-lg" name="submit_inscription"><i class="fa fa-check-square"
                      style="font-size: 0.6cm;"></i> S'inscrire</button>
          </form>
        </div>
      </div>
      
    </div>
  </div>


  <!-- Popup de connexion -->
  <div class="modal fade" id="connexion" role="dialog" style="background-color: #00000055;">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h1 class="text-primary">CONNEXION <span class="glyphicon glyphicon-log-in" style="font-size: 1cm; float: right;"></span> </h1>
        </div>
        <div class="modal-body">
          <form method="POST" action="">
              <div class="form-group">
                  <label>Type</label>
                  <select name="Type" class="form-control">
                      <option value="etud">Etudiant</option>
                      <option value="admin">Administrateur</option>
                  </select>
              </div><br>
              <div class="form-group">
                  <label>Identifiant</label>
                  <input type="text" class="form-control" name="Nom" autocomplete="off" required><br>
              </div>
              <div class="form-group">
                  <label>Mot de passe</label>
                  <input type="password" class="form-control" name="Matricule" autocomplete="off" required><br>
              </div>
              <button type="submit" class="btn btn-info btn-lg" name="submit_connexion"><i class="fa fa-check-square"
                      style="font-size: 0.6cm;"></i> Se connecter</button>
          </form>
        </div>
      </div>
      
    </div>
  </div>


</body>

</html>