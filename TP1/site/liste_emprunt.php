<?php
session_start();
ob_start();
require("includes/nav.php");
require("includes/fonctions/fonctions.php");
require("includes/conn.php");

?>

<!DOCTYPE html>
<html>

<head>
    <title></title>
    <script type="text/javascript" src="includes/date_heure.js"></script>
</head>

<body>
    <?php 
		$select_emprunt = $connect->query("SELECT * FROM emprunt");
		$data_emprunt = $select_emprunt->fetchAll(PDO::FETCH_OBJ);
		

	?>
    <div class="container"><br>
        <h2 class="text-primary">
            <i class="glyphicon glyphicon-list" style="font-size: 0.8cm;"></i> LISTE DES EMPRUNTS
            <span class="text-primary" style="color: black; font-size: 0.8cm; float: right;" id="date_heure"></span>
        </h2><br><br>
        <table class="table">
            <tr>
                <th>Nom</th>
                <th>Prenom</th>
                <th>Livre</th>
                <th>Date d'emprunt</th>
                <th>Date de retour</th>
            </tr>
            <?php  


        foreach ($data_emprunt as $key => $value) {
        	$mat = $value->mat_etu;
        	$id_livre = $value->code_livre;

			$select_etu=$connect->query("SELECT * FROM emprunt INNER JOIN etudiant ON emprunt.mat_etu = etudiant.Matricule WHERE mat_etu = \"$mat\" ");
			$data_etu=$select_etu->fetch(PDO::FETCH_OBJ);

			$select_livre=$connect->query("SELECT * FROM emprunt INNER JOIN livre ON emprunt.code_livre = livre.Code_Liv WHERE code_livre = \"$id_livre\" ");
			$data_livre=$select_livre->fetch(PDO::FETCH_OBJ);


        	?>

            <tr>
                <td><?php echo decrypter($data_etu->Nom); ?></td>
                <td><?php echo decrypter($data_etu->Prenom); ?></td>
                <td><?php echo decrypter($data_livre->Titre); ?></td>
                <td><?php echo decrypter($value->jour_emprunt); ?></td>
                <td><?php echo decrypter($value->retour); ?></td>
            </tr>

            <?php
        }

		?>
        </table>
    </div>
</body>


<script type="text/javascript">
window.onload = date_heure('date_heure');
</script>

</html>