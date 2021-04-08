<?php
session_start();
ob_start();
require("includes/conn.php");
require("includes/fonctions/fonctions.php");
	require("includes/nav.php");
if (isset($_SESSION["type"])) {
    if ($_SESSION["type"]=="admin") {

?>
<!DOCTYPE html>
<html lang="fr">
<header>
    <meta charset="utf-8">
    <title>Administrateur</title>
    <link rel="stylesheet" type="text/css" href="liens_css_js_etc/livre.css">
</header>

<body>
    <div class="container">
        <div class="row">
            <?php if(isset($_GET["choix_livre"])){ ?>
                <?php if($_GET["choix_livre"]=="ajout_livre"){ ?>
                <div><br><br>
                    <center><div style="text-align: left; display: inline-block;">
                        <h2 class="text-primary titre_profile"><i class="glyphicon glyphicon-plus" style="font-size: 0.6cm;"></i> AJOUTER UN LIVRE</h2><br><br>
                        <form method="POST" action="" enctype="multipart/form-data">
                            <label class="label_info">Code Livre:</label>
                            <input type="text" name="code_livre" placeholder="l**" class="input_info"><br><br>

                            <label class="label_info">Auteur du livre:</label>
                            <input type="text" name="auteur_livre" class="input_info"><br><br>

                            <label class="label_info">Genre du livre:</label>
                            <input type="text" name="genre_livre" class="input_info"><br><br>

                            <label class="label_info">Prix du livre:</label>
                            <input type="number" name="prix_livre" class="input_info"><br><br>

                            <label class="label_info">Titre du livre:</label>
                            <input type="text" name="titre_livre" class="input_info"><br><br>
                            
                            <label class="label_info">Charger l'image du livre:</label>
                            <input type="file" name="img_livre"><br><br>
                            
                            <label class="label_info">Charger le livre:</label>
                            <input type="file" name="contenu_livre">
                            <br>
                            <button type="submit" class="btn btn-primary" style="display: inline-block; width: 16cm;" name="ajout_livre">Ajouter</button>
                        </form>
                    </div></center>
                </div>
                <?php }elseif($_GET["choix_livre"]=="liste_livre"){ ?>
                <div>      
                    <?php

                        $select_livre = $connect->query("SELECT * FROM livre");
                        $data_livre = $select_livre->fetchAll(PDO::FETCH_OBJ);
                    ?><br><br>
                    <h2 class="text-primary titre_profile"><i class="glyphicon glyphicon-list" style="font-size: 0.8cm;"></i> LISTE DES LIVRES</h2><br><br>
                    <table class="table">
                        <tr>
                            <th>Code du livre</th>
                            <th>Image</th>
                            <th>Titre</th>
                        </tr>
                        <?php  


                    foreach ($data_livre as $key => $value) {


                        ?>

                        <tr>
                            <td><br><br><?php echo decrypter($value->Code_Liv); ?></td>
                            <td><a href="lire_pdf.php?pdf=images/livres/<?php echo decrypter($value->Code_Liv); ?>"><img class="livre_image" src="images/livres/<?php echo decrypter($value->Code_Liv); ?>.jpg"></a></td>
                            <td><br><br><?php echo decrypter($value->Titre); ?></td>
                        </tr>

                        <?php
                    }

                    ?>
                    </table>
                </div>
                <?php } ?>
        </div>
            <?php } ?>


        

    </div>
</body>

</html>

<?php
        if (isset($_POST["ajout_livre"])) {
            $insert_classe = $connect->prepare("INSERT INTO livre (Code_Liv, Titre, Auteur, Genre, Prix) VALUES (?, ?, ?, ?, ?) ");
            $insert_classe->execute(array(crypter($_POST['code_livre']), crypter($_POST['titre_livre']), crypter($_POST['auteur_livre']), crypter($_POST['genre_livre']), crypter($_POST['prix_livre'])));

            // Enregitrer l'image*******************************
            $img=$_FILES["img_livre"]["name"];
            $img_tmp=$_FILES["img_livre"]["tmp_name"];

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
                  imagejpeg($image_finale,'images/livres/'.$_POST["code_livre"].'.jpg');
                }

              }

            }else{
              echo "Veuiller rentrer une image.";
            }
            //**************************************************

            //pour insere le livre pdf
            if (isset($_FILES['contenu_livre']) AND $_FILES['contenu_livre']['error'] == 0)
            {
                $infosfichier = pathinfo($_FILES['contenu_livre']['name']);
                $extension_upload = $infosfichier['extension'];
                $extension_upload = strtolower($extension_upload);
                $extensions_autorisees = array('pdf');
                if (in_array($extension_upload, $extensions_autorisees))
                {
                    move_uploaded_file($_FILES['contenu_livre']['tmp_name'], 'images/livres/' . $_POST["code_livre"]. '.pdf');
                }else{
                    echo "envoyer un document pdf";
                }
            }else{
                echo "une erreures c'est produite";
            }
        }
    }
}
 ?>