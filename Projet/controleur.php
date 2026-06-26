<?php

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
                header('Content-Type: application/json');
                echo json_encode($resultats);
                die();
            break;

            case "Ajouter au panier":
                $required = ["userId", "id", "quantite", "dateDebut", "dateFin"];

                foreach ($required as $field) {
                    if (!valider($field)) {
                        die("Missing field: $field");
                    }
                }
                $userId = valider("userId");
                $itemId = valider("id");
                $qte = valider("quantite");
                $startDate = valider("dateDebut");
                $endDate = valider("dateFin");

                $cart = getUserCart($userId);

                if ($cart == false) {
                    $cart = createCart($userId, $startDate, $endDate);
                }

                addToCart($cart[0]["id"], $itemId, $qte, $startDate, $endDate);
            break;

            case "Reserver":
                // Met à jour le statut du panier à PENDING
                if ($cartId = valider("cartId", "POST")) {
                    updateEmprunt($cartId, "PENDING");
                }

                // Envoie automatiquement vers la page "Mes emprunts"
                $qs = "?view=mes_emprunts";
            break;

            case 'Sauvegarder les modifications':
                $idUser = valider("id");
                $name = valider("name");
                $contact = valider("contact");
                $flat_num = valider("flat_num");
                $score = valider("score") ? valider("score") : 0; 

                updateUser($idUser, $name, $contact, $flat_num, $score);
                echo userInfo($idUser);
                $qs = "?view=compte";
            break;
              case 'Se connecter':
                $msg="";
                if ($name = valider('name')){
                   
                   
                if ($password = valider('password')){
                   
                    if (verifUser($name,$password))
                       {
                        $qs = "?view=compte&msg=";
                       }}}
                else {$qs = "?view=login&msg=". urlencode('Identifiant ou mot de passe incorrect, veuillez réessayer');   }
            break; 

             case 'Ajouter l objet' :
                if ($name = valider('name'))
                if ($description = valider('description'))
                if($bail = valider('bail'))
                if ($categoryId = valider('categoryId'))
                if ($stock = valider('stock'))
                if ($photoUrl = valider('photoUrl'))
                    {
                       ajouterProduitEtPhoto($name, $description, $categoryId, $bail, $stock, $photoUrl);
                    }
                $qs = "?view=admin_gestion";
            break;


			case 'Logout': 
				
				session_destroy();
				$qs = "?view=inventaire";

			break;

			
			
			case 'Créer mon compte' :
				
				if ($name = valider('name'))
					
				if ($contact = valider('contact'))
					
                if ($password = valider('password'))
					{
						
					
					ajouterUtilisateur($name,$contact, $password);
					
					}
				$qs = "?view=inventaire"; 



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
    
                 $listeEmprunts = TriEmprunt($filtre);
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

            case 'Appliquer les filtres':
            $categorie = isset($_POST["categorie"]) ? $_POST["categorie"] : null;
            $Date      = valider("Date", "POST");
            $favoris   = valider("favoris", "POST");

            $qs = "?view=inventaire";
            if ($categorie) {
                foreach ($categorie as $cat) {
                    $qs .= "&categorie[]=" . $cat;
                }
            }
            if ($Date)    $qs .= "&Date=" . $Date;
            if ($favoris) $qs .= "&favoris=" .$favoris;
            break;



    }

}
    // Redirection après traitement de la requete
    $urlBase = dirname($_SERVER["PHP_SELF"]) . "/index.php";

	header("Location:" . $urlBase . $qs);

	ob_end_flush();

?>
