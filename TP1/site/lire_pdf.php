<?php 
  // Le chemin du fichier (path) 
  $file = $_GET['pdf'].".pdf"; 
    
  // Type de contenu d'en-tête
  header("Content-type: application/pdf"); 
    
  header("Content-Length: " . filesize($file));
    
  // Envoyez le fichier au navigateur.
  readfile($file); 
?>