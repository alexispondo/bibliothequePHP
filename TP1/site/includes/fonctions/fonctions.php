<?php 
function crypter($data){
	#return openssl_encrypt($data, "AES-128-ECB" ,"pondokouakou");
	$a = base64_encode($data);
	return $a;
}

function decrypter($data){
	#return openssl_decrypt($data, "AES-128-ECB" ,"pondokouakou");
	$a = base64_decode($data);
	return $a;
}


# Faire correpondre un code à une classe
function classe_correspond($id){
	try
	{
		$connect = new PDO("mysql:host=localhost; dbname=tp1", "root", "");
	}catch(Exception $error)
	{
		die("UNE ERREURE:" .$error->getMessage());
	}
	$id = crypter($id);
	$data1 = $connect->prepare("SELECT Intitule FROM classe WHERE Code_cl = \"$id\" ");
	$data1->execute();
	$res = $data1->fetch();
	return decrypter($res[0]);
}

# Fonction qui donne le nombre d'etudiants en fonction de la classe
function nbrs_classe($liste, $table_bd, $colonne){
	try
	{
		$connect = new PDO("mysql:host=localhost; dbname=tp1", "root", "");
	}catch(Exception $error)
	{
		die("UNE ERREURE:" .$error->getMessage());
	}
	$table = array_unique($liste);
	$table = array_merge($table); # permet de réindexé
	$i = 0;
	$res = [];
	foreach ($table as $key) {
		$key = crypter($key);
		$req_h=$connect->prepare("SELECT COUNT(*) AS inscrit FROM $table_bd WHERE $colonne = \"$key\" ");
        $req_h->execute();
        $reponse_h=$req_h->fetch();
	    $nb_inscrit_h=$reponse_h['inscrit'];
	    $key = decrypter($key);
	    $key = classe_correspond($key);
	    $res[] = [$key,$nb_inscrit_h];
	}
	return $res;
}


















?>