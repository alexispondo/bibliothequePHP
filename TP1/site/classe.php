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
    <link rel="stylesheet" type="text/css" href="liens_css_js_etc/classe.css">
</header>

<body>
    <div class="container">
        <div class="row">
            <?php if(isset($_GET["choix"])){ ?>
                <?php if($_GET["choix"]=="ajout_classe"){ ?>
                <div><br><br>
                    <center><div style="text-align: left; display: inline-block;">
                        <h2 class="text-primary titre_profile"><i class="glyphicon glyphicon-plus" style="font-size: 0.6cm;"></i> AJOUTER UNE CLASSE</h2><br><br>
                        <form method="POST" action="">
                            <label class="label_info">Code classe:</label>
                            <input type="text" name="code" placeholder="c**" class="input_info"><br><br>
                            <label class="label_info">Nom classe:</label>
                            <input type="text" name="classe" class="input_info">
                            <br>
                            <button type="submit" class="btn btn-primary" style="display: inline-block; width: 16cm;" name="ajout_classe">Ajouter</button>
                        </form>
                    </div></center>
                </div>
                <?php }elseif($_GET["choix"]=="liste_classe"){ ?>
                <div>      
                    <?php

                        $select_classe = $connect->query("SELECT * FROM classe");
                        $data_classe = $select_classe->fetchAll(PDO::FETCH_OBJ);
                    ?><br><br>
                    <h2 class="text-primary titre_profile"><i class="glyphicon glyphicon-list" style="font-size: 0.8cm;"></i> LISTES DES CLASSES</h2><br><br>
                    <table class="table">
                        <tr>
                            <th>Code de la Classe</th>
                            <th>Classes</th>
                            <th>Effectif</th>
                        </tr>
                        <?php  


                    foreach ($data_classe as $key => $value) {


                        ?>

                        <tr>
                            <td><?php echo decrypter($value->Code_cl); ?></td>
                            <td classe="click_classe">
                                <a href="classe.php?codeClasse=<?php echo decrypter($value->Code_cl); ?>">
                                    <?php echo decrypter($value->Intitule); ?> <i class="glyphicon glyphicon-new-window"></i>
                                </a>
                            </td>
                            <td><?php echo decrypter($value->Effectif); ?></td>
                        </tr>

                        <?php
                    }

                    ?>
                    </table>
                </div>
                <?php } ?>
        </div>
            <?php } ?>

            <?php if(isset($_GET["codeClasse"])){ 
                $codeClasse =crypter($_GET['codeClasse']);
                $select_classe1 = $connect->query("SELECT * FROM classe WHERE Code_cl = \"$codeClasse\"");
                $data_classe1 = $select_classe1->fetch(PDO::FETCH_OBJ);
            ?>
            <div>
                <h2 class="text-primary"><i class="fa fa-group" style="font-size: 0.8cm;"></i> LISTE DE LA CLASSE DE <?php echo decrypter($data_classe1->Intitule); ?></h2>
                <table class="table">
                    <tr>
                        <th>Photo</th>
                        <th>Matricule</th>
                        <th>Nom</th>
                        <th>Prenom</th>
                        <th>Sexe</th>
                    </tr>
                    <?php  
                $select_appartenir = $connect->query("SELECT * FROM appartenir WHERE id_classe=\"$codeClasse\"");
                $data_appartenir = $select_appartenir->fetchAll(PDO::FETCH_OBJ);


                foreach ($data_appartenir as $key => $value) {
                    $mat = $value->mat_etu;
                    $classe = $codeClasse;

                    $select_etu=$connect->query("SELECT * FROM appartenir INNER JOIN etudiant ON appartenir.mat_etu = etudiant.Matricule WHERE mat_etu = \"$mat\" ORDER BY etudiant.Nom ");
                    $data_etu=$select_etu->fetch(PDO::FETCH_OBJ);


                    ?>

                    <tr>
                        <td><img class="img" src="images/etudiant/<?php echo decrypter($data_etu->img); ?>.jpg"></td>
                        <td><br><?php echo decrypter($data_etu->Matricule); ?></td>
                        <td><br><?php echo decrypter($data_etu->Nom); ?></td>
                        <td><br><?php echo decrypter($data_etu->Prenom); ?></td>
                        <td><br><?php echo decrypter($data_etu->Sexe); ?></td>
                    </tr>

                    <?php
                }

                ?>
                </table>
            </div>
            <?php } ?>

        

    </div>
</body>

</html>

<?php
        if (isset($_POST["ajout_classe"])) {
            $insert_classe = $connect->prepare("INSERT INTO classe (Code_cl, Intitule, Effectif) VALUES (?, ?, ?) ");
            $insert_classe->execute(array(crypter($_POST['code']), crypter($_POST['classe']), crypter("0")));
        }
    }
}
 ?>