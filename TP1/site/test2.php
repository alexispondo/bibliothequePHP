<?php 

$txt = "pondo;kouakou;alexis";
$l = explode(";", $txt);
foreach ($l as $key) {
	echo $key."<br>";
}

echo "<br>";

// Les délimiteurs peuvent être des tirets, points ou slash
$data = "04;30;1973";
var_dump($data);
$data = preg_split('[;]', $data);
var_dump($data);
echo "Mois : $data[0]; Jour : $data[1]; Année : $data[2]<br />\n";

 ?>