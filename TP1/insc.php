<?php
session_start();
ob_start();
require("site/includes/conn.php");
$message = "";
if (isset($_POST["submit"])) {
		$Matricule = $_POST['Matricule'];
		$Nom = $_POST['Nom'];
		$Prenom = $_POST['Prenom'];
		$Sexe = $_POST['Sexe'];
		$Classe = $_POST['Classe'];

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
          $message =  "Désolé vous êtes déjà inscrit avec ce matricule !";
        }else{
        	$date_inscription = date("d/m/Y");
			$heure =date("H:i:s");
			# Insertion des etudiants
			$inserer = $connect->prepare("INSERT INTO etudiant (Matricule, Nom, Prenom, Sexe, date_inscription, heure) VALUES (?, ?, ?, ?, ?, ?) ");
			$inserer->execute(array($Matricule, $Nom, $Prenom, $Sexe, $date_inscription, $heure));

			#Mise à jour de l'éffectif
			$select_effectif = $connect->query("SELECT * FROM classe WHERE Intitule=\"$Classe\" ");
			$data_effectif = $select_effectif->fetch(PDO::FETCH_OBJ);

			$set_effectif = $data_effectif->Effectif + 1;

			$rem_mifie = $connect->query("UPDATE classe SET Effectif=\"$set_effectif\" WHERE Intitule=\"$Classe\" ");

			# Insertion appartient
			$inserer = $connect->prepare("INSERT INTO appartenir (mat_etu, id_classe) VALUES (?, ?) ");
			$inserer->execute(array($Matricule, $data_effectif->Code_cl));

			header("Location: acceuil.php");
		}

}
?>


<!DOCTYPE html>
<html>

<head>
    <title></title>
</head>
    <?php
        require("site/liens_css_js_etc/liens_css.php");
        require("site/liens_css_js_etc/liens_js.php");
    ?>
<body>
    <div class="container">
        <h1 class="text-primary">INSCRIPTION</h1>
        <form method="POST" action="">
            <div class="form-group">
                <label>Matricule <b style="color:red;"><?php echo $message; ?></b> </label>
                <input type="text" class="form-control" name="Matricule" autocomplete="off" required><br>
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
                    <option value="<?php echo $res1['Intitule']; ?>"><?php echo $res1['Intitule']; ?></option>
                    <?php } ?>
                </select><br>
            </div>
            <button type="submit" class="btn btn-primary btn-lg" name="submit"><i class="fa fa-check-square"
                    style="font-size: 0.6cm;"></i> S'inscrire</button>
        </form>
    </div>
</body>
</html>