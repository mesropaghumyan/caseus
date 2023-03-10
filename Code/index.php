<?php
/*
 * MODULE DE PHP
 * Index du site
 *
 * Copyright 2016, Eric Dufour
 * http://techfacile.fr
 *
 * Licensed under the MIT license:
 * http://www.opensource.org/licenses/MIT
 */

// Initialisation des paramètres du site
require_once('./config/configuration.php');
require_once('./lib/foncBase.php');
require_once(PATH_TEXTES.LANG.'.php');



//vérification de la page demandée 

if(isset($_GET['page']))
{
  $page = htmlspecialchars($_GET['page']); // http://.../index.php?page=toto
  if(!is_file(PATH_CONTROLLERS.$_GET['page'].".php"))
  { 
    $page = '404'; //page demandée inexistante
  }
}
else
	$page='accueil'; //page d'accueil du site - http://.../index.php

require_once(PATH_ENTITY.'Employe.php');
require_once(PATH_ENTITY.'Etat.php');
require_once(PATH_ENTITY.'Planning.php');
require_once(PATH_ENTITY.'Jour.php');
require_once(PATH_ENTITY.'Absence.php');
require_once(PATH_ENTITY.'Unite.php');
require_once(PATH_ENTITY.'Produit.php');
require_once(PATH_ENTITY.'Conge.php');
require_once(PATH_ENTITY.'Echange.php');
require_once(PATH_ENTITY.'Service.php');
setlocale(LC_TIME, "fr_FR");

session_start();
if (!isset($_SESSION['logged']) || ($_SESSION['logged'] == false)) {
  $page = 'login';
}

//appel du controller
require_once(PATH_CONTROLLERS.$page.'.php'); 

?>
