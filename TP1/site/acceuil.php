<?php
session_start();
ob_start();
require("includes/conn.php");
require("includes/fonctions/fonctions.php");
	require("includes/nav.php");
if (isset($_SESSION["type"])) {
    if ($_SESSION["type"] == "etud") {

	$m = crypter($_SESSION['matricule']);
	$select = $connect->query("SELECT * FROM appartenir WHERE mat_etu=\"$m\" ");
	$data=$select->fetch(PDO::FETCH_OBJ);

	$c = $data->id_classe;
	$select_classe = $connect->query("SELECT * FROM classe WHERE Code_cl=\"$c\" ");
	$data_classe=$select_classe->fetch(PDO::FETCH_OBJ);
	$_SESSION['c'] = decrypter($data_classe->Intitule);

	if ($_SESSION['sexe'] == "M") {
		$s = "Masculin";
	}else{
		$s = "Feminin";
	}
?>
<!DOCTYPE html>
<html lang="fr">
<header>
    <meta charset="utf-8">
    <title>Emprunt de livre</title>
    <link rel="stylesheet" type="text/css" href="liens_css_js_etc/acceuil.css">
</header>

<body>
    <div class="container">
		<h1>Bienvenue <?php if($_SESSION["sexe"] == "M"){ echo "Mr";}else{ echo "Mlle";} ?> <?php echo strtoupper($_SESSION["nom"])." ".strtoupper($_SESSION["prenom"]); ?></h1>  
    	<div class="row">
    		<div class="col-md-3">
    			<img class="img_etud" src="images/etudiant/<?php echo $_SESSION['img']; ?>.jpg">
    		</div>
    		<div class="col-md-9">  	
    			<h2 class="text-primary titre_profile">PROFILE</h2><br><br>
               
    			<label class="label_info">Matricule: </label> <span class="span_info"><?php echo $_SESSION['matricule']; ?></span><br><br>
    			<label class="label_info">Nom: </label> <span class="span_info"><?php echo $_SESSION['nom']; ?></span><br><br>
    			<label class="label_info">Prenom: </label> <span class="span_info"><?php echo $_SESSION['prenom']; ?></span><br><br>
    			<label class="label_info">Sexe: </label> <span class="span_info"><?php echo $s; ?></span><br><br>
    			<label class="label_info">Classe: </label> <span class="span_info"><?php echo $_SESSION['c']; ?></span><br><br>

    		</div>
    	</div>
    	

    </div>
</body>

</html>
<?php 

    }elseif ($_SESSION["type"]=="admin") {

?>
<!DOCTYPE html>
<html lang="fr">
<header>
    <meta charset="utf-8">
    <title>Administrateur</title>
    <link rel="stylesheet" type="text/css" href="liens_css_js_etc/acceuil.css">
</header>

<body>
    <div class="container">
        <h1>Bienvenue Mr l'Administrateur</h1>  
        <div class="row">
            <div class="col-md-3">
                <img class="img_etud" src="images/admin/admin.jpg">
            </div>
            <div class="col-md-9">      
                <h2 class="text-primary titre_profile">PROFILE</h2><br><br>
                <label class="label_info">Nom: </label> <span class="span_info">DUPONT</span><br><br>
                <label class="label_info">Prenom: </label> <span class="span_info">XAVIER</span><br><br>
                <label class="label_info">Sexe: </label> <span class="span_info">Masculin</span><br><br>

            </div>
        </div>
        

    </div>
</body>

</html>

<?php
    }
}
 ?>