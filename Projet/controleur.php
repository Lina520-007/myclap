<?php
    session_start();

    include_once "libs/maLibUtils.php";
    // include_once "libs/maLibSQL.pdo.php";
    include_once "libs/modele.php"; 

    $qs = "?view=inventaire";
    
    if ($action = valider("action")) {

        ob_start();

        switch($action) {

            case "Recherche AJAX":
                $search = valider("search"); 
                // $resultats = rechercherMateriel($search);
                // echo json_encode($resultats);
                die(); 
            break;

            case "Reserver":
                if ($cartId = valider("cartId", "POST")) {
                    updateEmprunt($cartId, "PENDING");
                }
                $qs = "?view=mes_emprunts";
            break;
            
        }
    }

    // Redirection après traitement de la requete
    $urlBase = dirname($_SERVER["PHP_SELF"]) . "/index.php";

	header("Location:" . $urlBase . $qs);

	ob_end_flush();

?>
