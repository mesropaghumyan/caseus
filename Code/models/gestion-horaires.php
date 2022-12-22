<?php
$para = array($mois, $annee);
require_once(PATH_MODELS . 'AbsenceDAO.php');
$absenceDAO = new AbsenceDAO(true);
$listeAbsences = $absenceDAO->getAbsenceParDate($para);
require_once(PATH_MODELS . 'EmployeDAO.php');
$employeDAO = new EmployeDAO(true);
require_once(PATH_MODELS . 'ServiceDAO.php');
$serviceDAO = new ServiceDAO(true);



// Partie principale : création des emplois du temps
$semTest = 49;
$anTest = 2022;
//                                                      Gestion des employés polyvalents
$srvPoly = array();
$affectation = array();

function affecterService($jour, $idEmp, $srv) {
    $GLOBALS['nbSrvPoly'][$idEmp][$jour] = 1;
    $GLOBALS['affectation'][$jour][$idEmp] = $srv;
    if(($key = array_search($srv, $GLOBALS['srvPoly'][$jour], TRUE)) !== FALSE) {
        unset($GLOBALS['srvPoly'][$jour][$key]);
    }
}

for($i = 0; $i<7; $i++) {
    $srvPoly[$i] = array();
    // on crée un tableau avec les services pour chaque jour de la semaine
    $listeServices = $serviceDAO->getListeServices();
    foreach ($listeServices as $service) {
        if (date('h:i:s', strtotime($service->getFin()) - strtotime($service->getDebut())) == '04:30:00') {
            // alors c'est un service de polyvalent
            for($j = 0; $j<$service->getNombre(); $j++) {
                array_push($srvPoly[$i], $service);
            }
        }
    }
}



$listePoly = $employeDAO->getEmployesParRang('POLY');
$nbSrvPoly = array();
foreach ($listePoly as $elem) {
    $nbSrvPoly[$elem->getId()] = array(0, 0, 0, 0, 0, 0, 0);
    $affectation[$elem->getId()] = array();
}
// on trouve le premier jour de la semaine et le dernier jour de la semaine
$jourCourant = new DateTime(date('Y-m-d',strtotime($anTest.'W'.$semTest)));
// on boucle sur tous les jours de la semaine
for($i = 0; $i<7; $i++) {
    $dateJourCourant = $jourCourant->format('Y-m-d');
    // pour chaque polyvalent on récupère ses absences pour le jour courant
    $listeAbsPoly = array();
    // $listeAbsPoly à l'indice de l'id de l'employé, c'est un tableau avec les absences de l'employé pour le jour courant
    foreach ($listePoly as $poly) {
        $listeAbsPoly[$poly->getId()] = $absenceDAO->getAbsenceParJourEtEmp(array($poly->getId(), $dateJourCourant));
        // on supprime les polyvalents qui n'ont pas d'absence du tableau
        if($listeAbsPoly[$poly->getId()] == null) {
            unset($listeAbsPoly[$poly->getId()]);
        }
    }

    if (!empty($listeAbsPoly)) {
        // on traite les absences en priorité, et on regarde si on peut affecter un service avec les conditions de l'absence
        foreach ($listeAbsPoly as $idEmp => $tabAbs) {
            // dans abs, on a un tableau d'absences pour le polyvalent qu'on regarde
            foreach ($tabAbs as $nbAbs => $abs) {
                // le formattage ci-dessous permet d'enlever la date et de ne garder que l'heure
                $da = date("H:i:s", strtotime($abs->getDebut()));
                $fa = date("H:i:s", strtotime($abs->getFin()));
                foreach ($srvPoly[$i] as $srv) {
                    $ds = date("H:i:s", strtotime($srv->getDebut()));
                    $fs = date("H:i:s", strtotime($srv->getFin()));
                    if (strtotime($da) < strtotime($ds) && strtotime($fa) < strtotime($ds) && $nbSrvPoly[$abs->getIdEmploye()][$i] == 0) {
                        affecterService($i, $abs->getIdEmploye(), $srv);
                    }
                    if (strtotime($ds) < strtotime($da) && strtotime($fs) < strtotime($da) && $nbSrvPoly[$abs->getIdEmploye()][$i] == 0) {
                        affecterService($i, $abs->getIdEmploye(), $srv);
                    }
                }
            }
        }
    }
    $jourCourant->modify('+1 day');
}
echo '<pre>';
print_r($affectation);
echo '</pre>';