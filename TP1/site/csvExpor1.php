

<?php
require("includes/conn.php");
    //exporter le fichier csv
    if(isset($_POST['export'])){// verifie le name de mon bouton input
        #$output= fopen('php://output','w');
        #fputcsv($output,array('matricule', 'nom','prenoms','email','sexe', 'classe','date_inscription','heure'));
        $data = 'matricule;nom;prenoms;email;sexe;classe;date_inscription;heure;'."\n";
        $req=$bd->prepare('SELECT * FROM etudiant ORDER BY nom');
        $req->execute();
        $data2 =""; 
        $sep = ";";
        
    
            while($row=$req->fetch()) {
                for ($i=0; $i <8 ; $i++) { 
                    $data2 .= $row[$i].$sep;
                }
                
                #fputcsv($output,$row);
          
            $data2 .= "\n";
            }
            #echo($data2);
            #fclose($output);//termine l enregistrement 

    }

        header('Content-Type:text/csv;charset=utf-8');
        header('Content-Disposition:attachment;filename=Liste_Etudiant.csv');//nom de mon fichier csv
        echo $data.$data2;
?>