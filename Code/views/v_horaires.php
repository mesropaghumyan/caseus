<?php
//  En tête de page
?>
<?php require_once(PATH_VIEWS.'header.php');?>

<!--  Zone message d'alerte -->
<?php require_once(PATH_VIEWS.'alert.php');?>

<!--  Début de la page -->

<div class="row">
	<form action="index.php?page=horaires" method="post">
		<div class="four columns">
			<label for="semaine">Semaine</label>
			<input class="u-full-width" type="text" value="<?php echo $semaine ?>" id="semaine" name="semaine">
		</div>
		<div class="four columns">
			<label for="annee">Année</label>
			<input class="u-full-width" type="text" value="<?php echo $annee ?>" id="annee" name="annee">
		</div>
		<div class="four columns">
			<input class="button-primary u-full-width" type="submit" value="Valider">
		</div>
	</form>
</div>
<?php if (!isset($alert)) {?>
<table class="u-full-width">
	<thead>
		<tr>
			<th></th>
			<?php
			foreach($datesSemaine as $dateJour) {
				echo "<th>".date('l', strtotime($dateJour))."<br>".$dateJour."</th>";
			}
			?>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td>Début :</td>
			<?php for ($i=1; $i <= 7; $i++) { 
				if (isset($listeJours[$i])) echo '<td>'.$listeJours[$i]->getDebutJournee().'</td>';
				else echo '<td></td>';
			}?>
		</tr>
		<tr>
			<td>Fin :</td>
			<?php for ($i=1; $i <= 7; $i++) { 
				if (isset($listeJours[$i])) echo '<td>'.$listeJours[$i]->getFinJournee().'</td>';
				else echo '<td></td>';
			}?>
		</tr>
	</tbody>
</table>
<?php }?>
<!--  Fin de la page -->

<!--  Pied de page -->
<?php require_once(PATH_VIEWS.'footer.php'); 