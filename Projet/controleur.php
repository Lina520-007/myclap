<?php
    session_start();

    include_once "libs/maLibUtils.php";
    include_once "libs/maLibSQL.pdo.php";
    include_once "libs/modele.php"; 

    $qs = "?view=inventaire";
    
    if ($action = valider("action")) {

        ob_start();

        switch($action) {

            case "Recherche AJAX":
                $search = valider("search");
                $resultats = rechercherMateriel($search);
                header('Content-Type: application/json');
                echo json_encode($resultats);
                die(); 
            break;

            case "Reserver":
                // Met à jour le statut du panier à PENDING
                if ($cartId = valider("cartId", "POST")) {
                    updateEmprunt($cartId, "PENDING");
                }

                // Envoie automatiquement vers la page "Mes emprunts"
                $qs = "?view=mes_emprunts";
            break;

            case 'Se connecter':
				
                if ($nom = valider('nom'))
					
					
                if ($passe = valider('passe'))
					
                    if (verifUser($nom,$passe))
                       {
						$qs = "?view=accueil";
					   
					    	
					   }
            break;   

			case 'Logout': 
				
				session_destroy();
				$qs = "?view=accueil";

			break;

			
			
			case 'Créer mon compte' :
				
				if ($Nom = valider('nom'))
					
				if ($contact = valider('contact'))
					
                if ($passe = valider('passe'))
					{
						
					
					ajouterUtilisateur($nom,$contact, $passe);
					
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
            
        }
    }

    // Redirection après traitement de la requete
    $urlBase = dirname($_SERVER["PHP_SELF"]) . "/index.php";

	header("Location:" . $urlBase . $qs);

	ob_end_flush();

?>
