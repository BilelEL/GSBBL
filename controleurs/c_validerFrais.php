<?php

//affichage du sommaire sur la page
include("vues/v_sommaireComptable.php");

$action = $_REQUEST['action'];
switch ($action) {
    //choisir mois
    case 'choisirMois':
        //affichage selection du mois
        $lesMois = $pdo->getLesMoisEnAttente();
        include 'vues/v_listeMoisComptable.php';
        break;
    
    //choisir visiteur (affichage select mois)
    case 'voirVisiteurFrais':
        //recuperation leMois
        $moisAChoisir = $_REQUEST['lstMois'];
        
        //affichage selection du mois
        $lesMois = $pdo->getLesMoisEnAttente();
        include("vues/v_listeMoisComptable.php");
        
        //affichage selection de l'utilisateur
        $lesVisiteurs = $pdo->getLesVisiteursAValider($moisAChoisir);
        include 'vues/v_listeVisiteur.php';
        break;
    
    //affichage fiche de frais (affichage select mois/visiteur)
    case 'voirFicheFrais':
        //recuperation leMois et leVisiteur
        $moisAChoisir = $_REQUEST["mois"];
        $visiteurAChoisir = $_REQUEST['lstVisiteur'];
        
        //affichage selection du mois
        $lesMois = $pdo->getLesMoisEnAttente();
        include("vues/v_listeMoisComptable.php");
        
        //affichage selection du visiteur
        $lesVisiteurs = $pdo->getLesVisiteursAValider($moisAChoisir);
        include 'vues/v_listeVisiteur.php';

        //affichage de la fiche de frais
        $lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($visiteurAChoisir, $moisAChoisir);
        $lesFraisForfait = $pdo->getLesFraisForfait($visiteurAChoisir, $moisAChoisir);
        $lesInfosFicheFrais = $pdo->getLesInfosFicheFrais($visiteurAChoisir, $moisAChoisir);
        $numAnnee = substr($moisAChoisir, 0, 4);
        $numMois = substr($moisAChoisir, 4, 2);
        $libEtat = $lesInfosFicheFrais['libEtat'];
        $montantValide = $lesInfosFicheFrais['montantValide'];
        $nbJustificatifs = $lesInfosFicheFrais['nbJustificatifs'];
        $dateModif = $lesInfosFicheFrais['dateModif'];
        $dateModif = dateAnglaisVersFrancais($dateModif);
        
        include 'vues/v_validation.php';
        break;
    
    //afficahge modification des frais forfait
    case 'modifier':
        
        //recuperation leMois et leVisiteur
        $moisAChoisir = $_REQUEST["mois"];
        $visiteurAChoisir = $_REQUEST['idVisiteur'];
        
        //recuperation du nombre de justificatifs
        $nbJustificatifs = $pdo->getNbJustificatifs($visiteurAChoisir, $moisAChoisir);
        //recuperation des frais forfait
        $lesFraisForfait = $pdo->getLesFraisForfait($visiteurAChoisir, $moisAChoisir);

        include 'vues/v_modificationFraisForfait.php';
        break;
    
    //modifie les quantites de frais forfait et retourne sur l'affichage des fiches NOUVEAU CONTROLEUR
    case 'appliquerModification':
        
        //recuperation des variables post
        $moisAChoisir = $_REQUEST['leMois'];
        $visiteurAChoisir = $_REQUEST['leVisiteur'];
        $lesFrais = $_REQUEST['lesFrais'];
        
        //verification de valeur valide puis ajout
        if(lesQteFraisValides($lesFrais)){
            $pdo->majFraisForfait($visiteurAChoisir,$moisAChoisir,$lesFrais);
        }
        
        //redirection
        header('Location: index.php?uc=validerFrais&action=voirFicheFrais&lstVisiteur='.$visiteurAChoisir.'&mois='.$moisAChoisir);
        break;
        
    //reporte le frais hors forfait au mois suivant
    case 'reporter':
        
        //recuperation des variables post
        $idFraisHorsForfait = $_REQUEST['idFraisHorsForfait'];
        $moisAChoisir = $_REQUEST['mois'];
        $visiteurAChoisir = $_REQUEST['idVisiteur'];
        
        //recuperation date du dernier mois saisi
        $dernierMois = $pdo->dernierMoisSaisi($visiteurAChoisir);
        
        //verification que le frais est dans le dernier mois de saisi
        if($moisAChoisir == $dernierMois)
        {
            $dernierMois = incrementerMois($moisAChoisir);
            $pdo->creeNouvellesLignesFrais($visiteurAChoisir, $dernierMois);
            $pdo->reportDFraisHorsForfait($idFraisHorsForfait,$dernierMois);
        }
        else
        {
            $pdo->reportDFraisHorsForfait($idFraisHorsForfait,$dernierMois);
        }
        
        //redirection
        header('Location: index.php?uc=validerFrais&action=voirFicheFrais&lstVisiteur='.$visiteurAChoisir.'&mois='.$moisAChoisir);
        break;
        
    //validation de la fiche de frais
    case 'validerFiche' :
        
        //recuperation des variables post
        $moisAChoisir = $_REQUEST['mois'];
        $visiteurAChoisir = $_REQUEST['idVisiteur'];
        $numAnnee = substr($moisAChoisir, 0, 4);
        $numMois = substr($moisAChoisir, 4, 2);
        
        //TEST
        echo "Page de validation";
        
        //validation
        // $pdo->majEtatFicheFrais($moisAChoisir,$visiteurAChoisir,"VA"); A DECOMMENTER
        
        //vue :
        include 'vues/v_confirmationValidation.php';
        
        break;
    
    //refuser un frais
    case 'refuser':
        //récuperation des variables
        $idFraisHorsForfait=$_REQUEST['idFraisHorsForfait'];
        $moisAChoisir = $_REQUEST['mois'];
        $visiteurAChoisir = $_REQUEST['idVisiteur'];
        
        //fonction refuserFraisHorsForfait
        $pdo->refuserFraisHorsForfait($idFraisHorsForfait);
        
         //redirection
        header('Location: index.php?uc=validerFrais&action=voirFicheFrais&lstVisiteur='.$visiteurAChoisir.'&mois='.$moisAChoisir);
        break;
        
        
    
}

