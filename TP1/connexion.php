
  <?php
      require("site/liens_css_js_etc/liens_css.php");
      require("site/liens_css_js_etc/liens_js.php");
  ?>

<div class="container">
  <h2>Modal Example</h2>
  <!-- Trigger the modal with a button -->
  <button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModal">Open Modal</button>

  <!-- Modal -->
  <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h1 class="text-primary">INSCRIPTION</h1>
        </div>
        <div class="modal-body">
          <form method="POST" action="">
              <div class="form-group">
                  <label>Matricule <b style="color:red;"><?php echo $message; ?></b> </label>
                  <input type="text" class="form-control" name="Matricule" autocomplete="off" required><br>
              </div>
              <div class="form-group">
                  <label>Nom</label>
                  <input type="text" class="form-control" name="Nom" autocomplete="off" required><br>
              </div>
              <div class="form-group">
                  <label>Prenom</label>
                  <input type="text" class="form-control" name="Prenom" autocomplete="off" required><br>
              </div>
              <div class="form-group">
                  <label>Sexe</label>
                  <select name="Sexe" class="form-control">
                      <option value="M">HOMME</option>
                      <option value="F">FEMME</option>
                  </select>
              </div><br>
              <div class="form-group">
                  <label>Classe</label>

                  <select name="Classe" class="form-control">
                      <?php
                        $select = $connect->prepare('SELECT * FROM Classe'); 
                        $select->execute();
                        while($res1 = $select->fetch()){
                      ?>
                      <option value="<?php echo $res1['Intitule']; ?>"><?php echo $res1['Intitule']; ?></option>
                      <?php } ?>
                  </select><br>
              </div>
              <button type="submit" class="btn btn-primary btn-lg" name="submit"><i class="fa fa-check-square"
                      style="font-size: 0.6cm;"></i> S'inscrire</button>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>
  
</div>
