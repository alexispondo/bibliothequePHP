<?php
session_start();
ob_start();
require("includes/nav.php");
require("includes/fonctions/fonctions.php");
require("includes/conn.php");

# Fonctions untiles ##############################
function date_change($date){ # Changer le format de la date
    $d = explode("-", $date);
    $d0 = $d[0];
    $d1 = $d[1];
    $d2 = $d[2];
    $dd = $d2."/".$d1."/".$d0;
    return $dd;
}
##################################################

if ($_SESSION['sexe'] == "M") {
    $s = "Masculin";
}else{
    $s = "Feminin";
}

$message = "";
if (isset($_POST["submit"])) {
		$Matricule = crypter($_POST['Matricule']);
		$Livre = crypter($_POST['Livre']);
		$date_e = crypter(date("d/m/Y"));
		$date_r = $_POST['date_r'];
        $date_r = crypter(date_change($date_r));
		
		$reponse = $connect->query("SELECT * FROM etudiant");
        $data=$reponse->fetchAll(PDO::FETCH_OBJ);

		$select_livre = $connect->query("SELECT * FROM livre WHERE Titre = \"$Livre\"");
		$data_livre = $select_livre->fetch(PDO::FETCH_OBJ);
		$code_l = $data_livre->Code_Liv;

		# Insertion des etudiants
		$inserer = $connect->prepare("INSERT INTO emprunt (mat_etu, code_livre, jour_emprunt, retour) VALUES (?, ?, ?, ?) ");
		$inserer->execute(array($Matricule, $code_l, $date_e, $date_r));

		header("Location: acceuil.php");
}

?>


<!DOCTYPE html>
<html>

<head>
    <title></title>
    <link rel="stylesheet" type="text/css" href="liens_css_js_etc/emprunt.css">
    <script type="text/javascript" src="includes/date_heure.js"></script>
</head>
<style>
</style>

<body>
    <div class="row">
        <div class="col-md-8" style="padding-left: 1cm;">
                <h1 class="text-primary">EMPRUNTER <span class="text-primary"
                        style="color: black; font-size: 0.8cm; float: right;" id="date_heure"></span></h1>


                <form method="POST" action="emprunt.php">
                    <div class="form-group">
                        <!--<label>Matricule <b style="color:red;"><?php echo $message; ?></b> </label>-->
                        <input class="form-control" type="hidden" name="Matricule" value="<?php echo $_SESSION["matricule"]; ?>" required autocomplete="off">
                    </div><br>

                    <div class="form-group">
                        <label>Livre</label>
                        <select class="form-control" name="Livre">
                            <?php
        					$select = $connect->prepare('SELECT * FROM livre'); 
        					$select->execute();
        					while($res1 = $select->fetch()){
        				?>
                            <option value="<?php echo decrypter($res1['Titre']); ?>"><?php echo decrypter($res1['Titre']); ?></option>
                            <?php 
        					} 
        				?>
                        </select>
                    </div><br>

                    <div class="form-group">
                        <label>Date probable de retour</label>
                        <input class="form-control" type="date" name="date_r" required>
                    </div><br>
                    <button class="btn btn-primary btn-lg" type="submit" name="submit"><i class="fa fa-check"
                            style="font-size: 0.6cm;"></i>
                        Valider</button>
                </form>
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