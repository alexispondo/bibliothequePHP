<?php
    require("liens_css_js_etc/liens_css.php");
    require("liens_css_js_etc/liens_js.php");
?>

<nav class="navbar navbar-inverse navbar-fixed-top">
    <div class=" container-fluid">
        <div class="navbar-header">
            <a class="navbar-brand" href="acceuil.php">E-Bib_tech</a>
        </div>
        <ul class="nav navbar-nav">
            <li class="active"><a href="acceuil.php"><i class="glyphicon glyphicon-home"></i> Acceuil</a></li>
            
            <?php if ($_SESSION["type"]=="admin") { ?>
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#"><i class="fa fa-group"></i> Étudiants
                    <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="liste_inscrit.php"><i class="fa fa-group"></i> Liste des inscrits</a></li>
                        <li><a href="liste_emprunt.php"><i class="glyphicon glyphicon-list"></i> Liste des emprunts</a></li>
                        <li><a href="charger.php"><i class="glyphicon glyphicon-upload"></i> Charger des données</a></li>
                    </ul>
                </li>
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#"><i class="glyphicon glyphicon-blackboard"></i> Classe
                    <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                      <li><a href="classe.php?choix=ajout_classe">Ajout de classe</a></li>
                      <li><a href="classe.php?choix=liste_classe">Liste des classes</a></li>
                    </ul>
                </li>
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#"><span class="fa fa-book" style="font-size:0.45cm;"></span> Livre
                    <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                      <li><a href="livre.php?choix_livre=ajout_livre">Ajout de livre</a></li>
                      <li><a href="livre.php?choix_livre=liste_livre">Liste des livres</a></li>
                    </ul>
                </li>
            <?php } ?>            

            <?php if ($_SESSION["type"]=="etud") { ?>
                <li><a href="livre_emprunte.php"><i class="glyphicon glyphicon-list"></i> Livres empruntés</a></li>
            <?php } ?>
        </ul>
        <ul class="nav navbar-nav navbar-right">            

            <?php if ($_SESSION["type"]=="etud") { ?>
                <li><a href="emprunt.php"><span class="fa fa-book" style="font-size:0.45cm;"></span> Emprunter un livre</a></li>
            <?php } ?>
            
            <li><a href="deconnexion.php"><span class="glyphicon glyphicon-log-out"></span> Se deconnecter</a></li>
        </ul>
    </div>
</nav>
<br><br><br>