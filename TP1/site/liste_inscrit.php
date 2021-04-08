<?php
session_start();
ob_start();
require("includes/nav.php");
require("includes/fonctions/fonctions.php");
require("includes/conn.php");

?>

<!DOCTYPE html>
<html>

<head>
    <title></title>
    <link rel="stylesheet" type="text/css" href="liens_css_js_etc/liste_inscrit.css">
    <script type="text/javascript" src="includes/date_heure.js"></script>
</head>

<body>
    <?php 
		$select_appartenir = $connect->query("SELECT * FROM appartenir");
		$data_appartenir = $select_appartenir->fetchAll(PDO::FETCH_OBJ);
        
        # Nombre d'inscrit au total
        $req_t=$connect->prepare("SELECT COUNT(*) AS inscrit  FROM etudiant ");
        $req_t->execute();
        $reponse_t=$req_t->fetch();
        $nb_inscrit_t=$reponse_t['inscrit'];
        #-----------------------------------------------------------------------
        

        # inscrit par filière

        $select_appartenir = $connect->query("SELECT * FROM appartenir");
        $data_appartenir = $select_appartenir->fetchAll(PDO::FETCH_OBJ);








        # Nombre inscrits par Filières




        # Nombre inscrits par sexe
            $h = crypter("M");
            $f = crypter("F");
            # Nombre d'homme inscrit au total
                $req_h=$connect->prepare("SELECT COUNT(*) AS inscrit_h  FROM etudiant WHERE Sexe = \"$h\" ");
                $req_h->execute();
                $reponse_h=$req_h->fetch();
                $nb_inscrit_h=$reponse_h['inscrit_h'];
            #-----------------------------------------------------------------------
            
            # Nombre de femme inscrit au total
                $req_f=$connect->prepare("SELECT COUNT(*) AS inscrit_f  FROM etudiant WHERE Sexe = \"$f\" ");
                $req_f->execute();
                $reponse_f=$req_f->fetch();
                $nb_inscrit_f=$reponse_f['inscrit_f'];
            #-----------------------------------------------------------------------
        #------------------------------------------------------------------------------------------------------

	?>
    <div class="container"><br>
        <h2 class="text-primary">
            <i class="fa fa-group" style="font-size: 0.8cm;"></i> LISTE DES INSCRITS
            <span class="text-primary" style="color: black; font-size: 0.8cm; float: right;" id="date_heure"></span>
        </h2><br><br>

        <button class="btn btn-success" type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
            Statistiques <i class="fa fa-bar-chart"></i>
        </button>
        <div class="collapse" id="collapseExample">
          <div class="well" style="background: white; margin: 0px; border: 1px solid white;">
                <script type="text/javascript">
                    // Load google charts
                    google.charts.load('current', {'packages':['corechart']});
                    google.charts.setOnLoadCallback(drawChart);
                    // Draw the chart and set the chart values
                    function drawChart() {
                      var data = google.visualization.arrayToDataTable([
                      ['Task', 'Hours per Day'],
                    <?php 
                        $select_classe = $connect->query("SELECT * FROM classe");
                        $data_classe = $select_classe->fetchAll(PDO::FETCH_OBJ);
                        
                        $tableau = [];
                        foreach ($data_classe as $key => $value) {
                          $tableau[] = decrypter($value->Code_cl);
                        }

                        $classe = nbrs_classe($tableau, "appartenir", "id_classe");
                        foreach ($classe as $key) {
                            # code...
                        }
                    ?>
                      ['Femmes', <?php echo $nb_inscrit_f; ?>],
                      ['Hommes', <?php echo $nb_inscrit_h; ?>]
                    ]);

                      // Optional;  Ajouter un titre et donner une largeur et une hauteur
                      var options = {'title':'Total d\'inscrits','height':400};

                      // Display the chart inside the <div> element with id="piechart"
                      var chart = new google.visualization.PieChart(document.getElementById('piechart'));
                      chart.draw(data, options);
                    }
                </script>

                <script type="text/javascript">
                    google.charts.load("current", {packages:["corechart"]});
                    google.charts.setOnLoadCallback(drawChart);
                    function drawChart() {
                      var data = google.visualization.arrayToDataTable([
                        ["Element", "Density", { role: "style" } ],
                        ["Femmes", <?php echo $nb_inscrit_f; ?>, "#00a0ff"],
                        ["Hommes", <?php echo $nb_inscrit_h; ?>, "pink"]
                      ]);

                      var view = new google.visualization.DataView(data);
                      view.setColumns([0, 1,
                                       { calc: "stringify",
                                         sourceColumn: 1,
                                         type: "string",
                                         role: "annotation" },
                                       2]);

                      var options = {
                        title: "Total d'hommes et de Femmes",
                        height: 400,
                        bar: {groupWidth: "95%"},
                        legend: { position: "none" },
                      };
                      var chart = new google.visualization.BarChart(document.getElementById("barchart"));
                      chart.draw(view, options);
                    }
                </script>
                <div id="piechart" style="width: 520;height: 500; display: inline-block;"></div>
                <div id="barchart" style="width: 520;height: 500; display: inline-block;"></div>
          </div>
        </div>

        <table class="table">
            <tr>
                <span>Total inscrit: </span><b><?php echo $nb_inscrit_t; ?></b> | 
                <span>Hommes inscrits: </span><b><?php echo $nb_inscrit_h; ?></b> | 
                <span>Femmes inscrites: </span><b><?php echo $nb_inscrit_f; ?></b></tr>
            <tr>
                <th>Photo</th>
                <th>Nom</th>
                <th>Prenom</th>
                <th>Classe</th>
                <th>Date d'inscription</th>
                <th>Heure d'inscription</th>
            </tr>
            <?php  


        foreach ($data_appartenir as $key => $value) {
        	$mat = $value->mat_etu;
        	$classe = $value->id_classe;

			$select_etu=$connect->query("SELECT * FROM appartenir INNER JOIN etudiant ON appartenir.mat_etu = etudiant.Matricule WHERE mat_etu = \"$mat\" ");
			$data_etu=$select_etu->fetch(PDO::FETCH_OBJ);

			$select_classe=$connect->query("SELECT * FROM appartenir INNER JOIN classe ON appartenir.id_classe = classe.Code_cl WHERE id_classe = \"$classe\" ");
			$data_classe=$select_classe->fetch(PDO::FETCH_OBJ);


        	?>

            <tr>
                <td><img class="img" src="images/etudiant/<?php echo decrypter($data_etu->img); ?>.jpg"></td>
                <td><br><?php echo decrypter($data_etu->Nom); ?></td>
                <td><br><?php echo decrypter($data_etu->Prenom); ?></td>
                <td><br><?php echo decrypter($data_classe->Intitule); ?></td>
                <td><br><?php echo decrypter($data_etu->date_inscription); ?></td>
                <td><br><?php echo decrypter($data_etu->heure); ?></td>
            </tr>

            <?php
        }

		?>
        </table>
    </div>
</body>


<script type="text/javascript">
window.onload = date_heure('date_heure');
</script>

</html>