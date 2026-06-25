<?php
    session_start();

    include_once "libs/maLibUtils.php";
     include_once "libs/maLibSQL.pdo.php";
     include_once "libs/modele.php";
     include_once("libs/maLibSecurisation.php"); 

    $qs = "?view=inventaire";
    
    if ($action = valider("action")) {

        ob_start();

        switch($action) {

            case "Recherche AJAX":
                $search = valider("search"); 
                $resultats = rechercherMateriel($search);
                echo json_encode($resultats);
                die(); 
            break;

            case "Reserver":
                echo "blablabla";
                $qs = "?view=mes_emprunts";
            break;

            case 'Se connecter':
				
                if ($name = valider('name'))
					
					
                if ($password = valider('password'))
					
                    if (verifUser($name,$password))
                       {
						$qs = "?view=accueil";
					   
					    	
					   }
            break;   

			case 'Logout': 
				
				session_destroy();
				$qs = "?view=accueil";

			break;

			
			
			case 'Créer mon compte' :
				
				if ($name = valider('name'))
					
				if ($contact = valider('contact'))
					
                if ($password = valider('password'))
					{
						
					
					ajouterUtilisateur($name,$contact, $password);
					
					}
				$qs = "?view=accueil"; 



			break;


			case 'Rendre administrateur' :
				
				if ($idUser = valider('idUser'))
					{ rendreAdmin($idUser);

					}
				$qs = "?view=admin_gestion"; 
			break;

			case 'Retirer rôle administrateur' :
				
				if ($idUser = valider('idUser'))
					{ retirerAdmin($idUser);

					}
				$qs = "?view=admin_gestion"; 
			break;

            case 'afficher les emprunts' :
                $filtre = isset($_GET['filtre']) ? $_GET['filtre'] : '';
    
    // Appel à la couche modèle pour récupérer les données triées
                 $listeEmprunts = getEmpruntsTries($filtre);
                $qs = "?view=admin_emprunts";
            break;

            case 'Modifier le statut' : 
                if ($filtre = valider('filtre')) 

                if ($idEmprunt = valider('idEmprunt'))
					
				if ($statutSelectionne = valider('statutSelectionne')){
                    
                    changerStatutEmprunt($idEmprunt,$statutSelectionne);
                }

                $qs = "?view=admin_emprunts&filtre=$filtre&action=afficher+les+emprunts";
                break;

            
        }
    }

    // Redirection après traitement de la requete
    $urlBase = dirname($_SERVER["PHP_SELF"]) . "/index.php";

	header("Location:" . $urlBase . $qs);

	ob_end_flush();

?>
