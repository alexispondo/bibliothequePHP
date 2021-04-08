<?php 
session_start();
ob_start();
require("includes/nav.php");
require("includes/fonctions/fonctions.php");
require("includes/conn.php");
$message = "";
 ?>
<!DOCTYPE html>
<html>
<head>
  <link rel="stylesheet" type="text/css" href="liens_css_js_etc/charger.css">
  <title></title>
</head>
<body>
<br><br>
<form method="POST" action="export.php">
  <button class="btn btn-primary"type="submit">Exporter</button>
</form>

<h1 class="text-primary h_csv">Import CSV</h1>
<center>
<form method="POST" action="" enctype="multipart/form-data"><br><br>
	<input type="file" name="csvfiles" class="form-control-file file_file"><br>
	<input type="submit" name="Lancer" class="submit">
</form>
</center>
<?php 
if(isset($_POST["Lancer"])){
	if($_FILES['csvfiles']['name']){
		$filename = explode(".", $_FILES['csvfiles']['name']);
		if(end($filename) == "csv"){
	 	?>
    			<?php
    			$handle = fopen($_FILES['csvfiles']['tmp_name'], "r");
    			$i = 0;
          $etu_ins = 0; # Nombres d'etudiants insérés 
    			while($data = fgetcsv($handle))
    			{
    				if($i != 0){
    					$data = preg_split('[;]', $data[0]);

    					$Matricule =crypter($data[0]);
    					$Nom = crypter($data[1]);
    					$Prenom = crypter($data[2]);
              if ($data[3] == "M"){
                $img = crypter("base_h");
              }elseif($data[3] == "F"){
                $img = crypter("base_f");
              }
    					$Sexe = crypter($data[3]);
    					$Classe = crypter($data[6]);
    					$user = $Nom;
    					$pass = hash('sha256', $Matricule);

    		 			$reponse = $connect->query("SELECT * FROM etudiant");
        			$data1=$reponse->fetchAll(PDO::FETCH_OBJ);
        			$trouve = false;
        			foreach($data1 as $element => $cle_T){
        				if($cle_T->Matricule == $Matricule){
        				 	$trouve = true;
        					break;          
        				}else{
        					$trouve = false;
        				}
        			}
        			if ($trouve) {
        			  	$alerte_inscription = true;
        			}else{
                $etu_ins = $etu_ins + 1;
      					$date_inscription = crypter($data[4]);
      					$heure =crypter($data[5]);
      					# Insertion des etudiants
      					$inserer = $connect->prepare("INSERT INTO etudiant (Matricule, Nom, Prenom, Sexe, date_inscription, heure, img) VALUES (?, ?, ?, ?, ?, ?, ?) ");
      					$inserer->execute(array($Matricule, $Nom, $Prenom, $Sexe, $date_inscription, $heure, $img));

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
					    }
				  	}
				  	$i = $i +1;
				}fclose($handle);
        if ($etu_ins == 0) {
          $message = "Désolé le fichier choisit ne contient aucune nouvelle donnée !!";
        }

		   	    ?>
			<?php
		}else{
	     	$message = 'Please Select CSV File only';
	  }
	}else{
  		$message = 'Please Select File';
 	}
}
?>


<table class="table">
  <tr>
    <th>Matricule</th><th>Nom</th><th>Prenom</th><th>Sexe</th><th>date_inscription</th><th>heure</th><th>classe</th>
  </tr>
<?php
  $select_appartenir = $connect->query("SELECT * FROM appartenir ORDER BY id DESC");
  $data_appartenir = $select_appartenir->fetchAll(PDO::FETCH_OBJ);
  foreach ($data_appartenir as $key => $value1) {
    $mat = $value1->mat_etu;
    $classe1 = $value1->id_classe;

    $select_etu=$connect->query("SELECT * FROM appartenir INNER JOIN etudiant ON appartenir.mat_etu = etudiant.Matricule WHERE mat_etu = \"$mat\" ");
    $data_etu=$select_etu->fetch(PDO::FETCH_OBJ);

    $select_classe=$connect->query("SELECT * FROM appartenir INNER JOIN classe ON appartenir.id_classe = classe.Code_cl WHERE id_classe = \"$classe1\" ");
    $data_classe=$select_classe->fetch(PDO::FETCH_OBJ);


  ?>
    <tr>
      <td><?php echo decrypter($data_etu->Matricule); ?></td><td><?php echo decrypter($data_etu->Nom); ?></td><td><?php echo decrypter($data_etu->Prenom); ?></td><td><?php echo decrypter($data_etu->Sexe); ?></td><td><?php echo decrypter($data_etu->date_inscription); ?></td><td><?php echo decrypter($data_etu->heure); ?></td><td><?php echo decrypter($data_classe->Intitule); ?></td>
    </tr>
  <?php
    }

?>
<center style="font-size: 0.6cm; color: #f44;"><?php echo $message; ?></center>

</table>
</body>
</html>