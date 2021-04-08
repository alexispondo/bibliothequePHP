<?php
require("includes/conn.php");
require("includes/fonctions/fonctions.php");

$separator=";";
 
//Extraire le nom des colonnes
$rsColumn = $connect->query("SHOW COLUMNS FROM etudiant"); # recupere les differentes colonnes
$columnLine="";
$columnCount=0;
if ($rsColumn) {
    while ($row = $rsColumn->fetch()) {
        $columnLine .= $row['Field'].$separator;# "Field" precise que c'est le non des colonnes q'on souhaite
        $columnCount++;
    }
    $columnLine .="\n";
}
$columnLine = preg_split('[;]', $columnLine); # Convertir la chaine de caractère en tableau en separant par un ;

$end = array_search(end($columnLine),$columnLine); # chercher le dernier element du tableau
$classe = "Classe"; # Créer la colonne classe
unset($columnLine[0]); # Supprimer le premier element du tableu (id)
unset($columnLine[$end]); # Suprimer le dernier element du tableau (le saut de ligne \n)
unset($columnLine[$end-1]); # Suprimer l'avant dernier element du tableau (img)
$columnLine = array_merge($columnLine); # Réindexer le tableau
$columnLine[] = $classe; # Ajouter la colonne classe à la fin du tableau
$columnLine[] = "\n"; # Ajouter un saut de ligne

$columnLine = implode(";", $columnLine); # Reconvertir le tableau en chaine de caractère



//Extraire les données
$rsData=$connect->query("SELECT * FROM etudiant");
$dataLine="";
while ($row = $rsData->fetch()) { # Parcourt toutes les lignes
    for ($i = 0; $i < $columnCount; $i++) { # Parcourt toutes les colonnes
        if ($i != 0) { # Saute la première colonne (id)
            $dataLine .=decrypter($row[$i]).$separator;
        }   
    }

    $dataLine = preg_split('[;]', $dataLine); # Convertir la chaine de caractère en tableau en separant par un ;
    $end = array_search(end($dataLine),$dataLine); # chercher le dernier element du tableau
    $mat = $row[1]; # Matricule de l'étudiant
    #unset($dataLine[0]); # Supprimer le premier element du tableu (id)
    unset($dataLine[$end]); # Suprimer le dernier element du tableau (le saut de ligne \n)
    unset($dataLine[$end-1]); # Suprimer l'avant dernier element du tableau (img)
    $dataLine = array_merge($dataLine); # Réindexer le tableau

    $select_etu=$connect->query("SELECT * FROM appartenir INNER JOIN etudiant ON appartenir.mat_etu = etudiant.Matricule WHERE mat_etu = \"$mat\" ");
    $data_etu=$select_etu->fetch(PDO::FETCH_OBJ);
    $classe1 = $data_etu->id_classe;

    $select_classe=$connect->query("SELECT * FROM appartenir INNER JOIN classe ON appartenir.id_classe = classe.Code_cl WHERE id_classe = \"$classe1\" ");
    $data_classe=$select_classe->fetch(PDO::FETCH_OBJ);
    $int_classe = $data_classe->Intitule;





    $dataLine[] = decrypter($int_classe); # Ajouter la colonne classe à la fin du tableau
    $dataLine[] = "\n"; # Ajouter un saut de ligne

    $dataLine = implode(";", $dataLine); # Convertir le tableauu en chaine de caractère
}

//Envoyer le contenu au navigateur internet
header("Content-type: text/x-csv");
header("Content-Disposition: attachment; filename=export_liste_etudiants.csv");
echo $columnLine.$dataLine;
exit;

?>