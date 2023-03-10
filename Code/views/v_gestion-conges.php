<?php
//  En tête de page
?>
<?php require_once(PATH_VIEWS.'header.php');?>
<h1>Gestion des congés</h1>
<!--  Zone message d'alerte -->
<?php require_once(PATH_VIEWS.'alert.php');?>

<!--  Début de la page -->
<div class="row">
    <h5>Demandes en attente</h5>
    <?php if(!empty($listeCongesEnAttente)) {?>
    <form method="post">
        <div class="six columns">
            <label for="idDemande">Demandes à traiter</label>
            <select name="idDemande" class="u-full-width">
                <?php 
                    foreach ($listeCongesEnAttente as $elem) {
                        $emp = $listeEmployesIndex[$elem->getIdEmploye()];
                        $debut = jourFrancais(date("N", strtotime($elem->getDebut())), false).date(" d/m/Y", strtotime($elem->getDebut()));
                        $fin = jourFrancais(date("N", strtotime($elem->getFin())), false).date(" d/m/Y", strtotime($elem->getFin()));
                        echo '<option value="'.$elem->getIdDemande().'">'.$emp->getPrenom().' '.$emp->getNom().' : '.$debut.' - '.$fin.'</option>';
                    }
                ?>
            </select>
        </div>
        <div class="three columns">
            <input class="button-success u-full-width" type="submit" value="Accepter" name="accepter">
        </div>
        <div class="three columns">
            <input class="button-danger u-full-width" type="submit" value="Refuser" name="refuser">
        </div>
    </form>
</div>
<?php } ?>
<hr>
<div class="row">
    <h5>Futurs congés déjà acceptés</h5>
    <?php if (!empty($listeCongesFuturs)) { ?>
    <div class="table-container">
        <table class="u-full-width">
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Début</th>
                    <th>Fin</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                    foreach ($listeCongesFuturs as $elem) {
                        $emp = $listeEmployesIndex[$elem->getIdEmploye()];
                        $debut = jourFrancais(date("N", strtotime($elem->getDebut())), false).date(" d/m/Y", strtotime($elem->getDebut()));
                        $fin = jourFrancais(date("N", strtotime($elem->getFin())), false).date(" d/m/Y", strtotime($elem->getFin()));
                        echo '<tr>';
                        echo '<td>'.$emp->getNom().'</td>';
                        echo '<td>'.$emp->getPrenom().'</td>';
                        echo '<td>'.$debut.'</td>';
                        echo '<td>'.$fin.'</td>';
                        echo '</tr>';
                    }
                ?>
            </tbody>
        </table>
    </div>
    <?php } ?>
</div>
<!--  Fin de la page -->

<!--  Pied de page -->
<?php require_once(PATH_VIEWS.'footer.php'); 
