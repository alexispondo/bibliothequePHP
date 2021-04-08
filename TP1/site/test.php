<?php
require("includes/conn.php");
require("includes/fonctions/fonctions.php");

$separator=";";
 
//Extraire le nom des colonnes
$rsColumn = $connect->query("SHOW COLUMNS FROM etudiant");
$columnLine="";
$columnCount=0;
if ($rsColumn) {
    while ($row = $rsColumn->fetch()) {
        $columnLine .= $row['Field'].$separator;
        $columnCount++;
    }
    $columnLine .="\n";
}

//Extraire les données
$rsData=$connect->query("SELECT * FROM etudiant");
$dataLine="";
while ($row = $rsData->fetch()) {
    for ($i = 0; $i < $columnCount; $i++) {
        if ($i != 0) {
            $dataLine .=decrypter($row[$i]).$separator;
        }else{
            $dataLine .=$row[$i].$separator;
        }
    }
    $dataLine.="\n";
}

//Envoyer le contenu au navigateur internet
header("Content-type: text/x-csv");
header("Content-Disposition: attachment; filename=search_results.csv");
echo $columnLine.$dataLine;
exit;

?>