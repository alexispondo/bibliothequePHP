<?php
session_start();
ob_start();
require("includes/nav.php");
require("includes/fonctions/fonctions.php");
require("includes/conn.php");

if ($_SESSION['sexe'] == "M") {
    $s = "Masculin";
}else{
    $s = "Feminin";
}

?>

<!DOCTYPE html>
<html>

<head>
    <title></title>
    <link rel="stylesheet" type="text/css" href="liens_css_js_etc/livre_emprunte.css">
    <script type="text/javascript" src="includes/date_heure.js"></script>
</head>

<body>
    <?php 
        $mat = crypter($_SESSION['matricule']);
		$select_emprunt = $connect->query("SELECT * FROM emprunt WHERE mat_etu=\"$mat\"");
		$data_emprunt = $select_emprunt->fetchAll(PDO::FETCH_OBJ);
		

	?>
    <div class="row">
        <div class="col-md-8" style="padding-left: 1cm;">
                <h2 class="text-primary">
                    <i class="glyphicon glyphicon-list" style="font-size: 0.8cm;"></i> LIVRES EMPRUNTÉS
                    <span class="text-primary" style="color: black; font-size: 0.8cm; float: right;" id="date_heure"></span>
                </h2><br><br>
                <table class="table">
                    <tr>
                        <th>Image</th>
                        <th>Livre</th>
                        <th>Date d'emprunt</th>
                        <th>Date de retour</th>
                    </tr>
                    <?php  


                foreach ($data_emprunt as $key => $value) {
                    $id_livre = $value->code_livre;

                    $select_livre=$connect->query("SELECT * FROM emprunt INNER JOIN livre ON emprunt.code_livre = livre.Code_Liv WHERE code_livre = \"$id_livre\" ");
                    $data_livre=$select_livre->fetch(PDO::FETCH_OBJ);


                    ?>

                    <tr>
                        <td><a href="lire_pdf.php?pdf=images/livres/<?php echo decrypter($data_livre->Code_Liv); ?>"><img class="livre_image" src="images/livres/<?php echo decrypter($data_livre->Code_Liv); ?>.jpg"></a></td>
                        <td><br><br><?php echo decrypter($data_livre->Titre); ?></td>
                        <td><br><br><?php echo decrypter($value->jour_emprunt); ?></td>
                        <td><br><br><?php echo decrypter($value->retour); ?></td>
                    </tr>

                    <?php
                }

                ?>
                </table>
            
        </div>
        <div class="col-md-4 profile">
            <div class="div_img"><img class="img_etud" src="images/etudiant/<?php echo $_SESSION['img']; ?>.jpg"><br><br><br></div>
            <div class="div_info">
                <label class="label_info">Matricule</label> <span class="b_info"><?php echo $_SESSION['matricule'];?></span><br><br>
                <label class="label_info">Nom</label> <span class="b_info"><?php echo $_SESSION['nom']." ".$_SESSION['prenom'];?></span><br><br>
                <label class="label_info">Sexe</label> <span class="b_info"><?php echo $s;?></span><br><br>
                <label class="label_info">Classe</label> <span class="b_info"><?php echo $_SESSION['c'];?></span><br><br>
            </div>
        </div>
    </div>
</body>


<script type="text/javascript">
window.onload = date_heure('date_heure');
</script>

</html>