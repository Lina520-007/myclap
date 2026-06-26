<?php
    include_once("libs/maLibSQL.pdo.php");

    // INVENTAIRE ----------------------------------------------------------------------------------------------------------------------------------------
    function rechercherMateriel($recherche) {
        $recherche = addslashes($recherche);
        
        $sql = "SELECT p.id, p.name, p.description, p.bail, ph.url as photo_url
                FROM product AS p
                LEFT JOIN product_photo AS ph ON p.id = ph.product_id AND ph.index = 1 
                WHERE p.name LIKE '%$recherche%' OR p.description LIKE '%$recherche%'"; 
                
        return parcoursRs(SQLSelect($sql)); 
    }

    function listerArticlesDisponibles($categorie = null, $date = null, $favoris = null, $userId = null) {
    $sql = "SELECT p.id, p.name, p.description, p.bail, p.stock, ph.url AS photo_url
            FROM product AS p
            LEFT JOIN product_photo AS ph ON p.id = ph.product_id AND ph.index = 1";

    $conditions = [];

   if ($categorie) {
    $categorie = (array) $categorie;
    $in = "";
    foreach ($categorie as $cat) {
        $in .= $cat . ",";
    }
    $in = rtrim($in, ",");
    $conditions[] = "p.category_id IN ($in)";
}

    if ($date) {
        $conditions[] = "p.id NOT IN (
            SELECT product_id FROM emprunt
            WHERE '$date' BETWEEN date_debut AND date_fin
            AND statut != 'CANCELLED'
        )";
    }

    if ($favoris && $userId) {
        $conditions[] = "p.id IN (
            SELECT product_id FROM favoris
            WHERE user_id = $userId
        )";
    }

    if (!empty($conditions)) {
        $sql .= " WHERE " . implode(" AND ", $conditions);
    }

    return parcoursRs(SQLSelect($sql));
}

    function getEmpruntsWithItem($itemId, $userId) {
        $sql = "SELECT e.start_date, e.end_date, quantity 
            FROM emprunt_item as e
            JOIN emprunt
            ON emprunt.id = e.emprunt_id
            WHERE product_id = '$itemId'
            AND emprunt.status != 'CART'";

        return parcoursRs(SQLSelect($sql));
    }
    /**
     * @param array $product
     * @param array $emprunts
     * @param string $dateInterval
     */
    function createStockTable($product, $empruntItems, $dateInterval = "60") {
        // Tableau qui va stocker la quantité disponible associée à chaque jour
        $stockTab = array();
        
        // On itère sur les jours
        // Aujourd'hui
        $currentDate = new DateTime();              

        // Date maximale de réservation
        $interval = new DateInterval("P" . $dateInterval . "D");       // P = Period, 60 Days
        $maxDate = (clone $currentDate)->add($interval);

        // Période entre les deux
        $interval = new DateInterval("P1D");        // 1 jour
        $periode = new DatePeriod($currentDate, $interval, $maxDate, DatePeriod::INCLUDE_END_DATE);

        foreach ($periode as $date) {
            $stock = $product["stock"];

            foreach ($empruntItems as $emprunt) {
                $startDate = new DateTime($emprunt["start_date"]);
                $endDate = new DateTime($emprunt["end_date"]);
                if ($startDate <= $date && $date <= $endDate) $stock -= $emprunt["quantity"];
            }
            $key = $date->format("Y-m-d");
            $stockTab["$key"] = $stock;
        }

        return $stockTab;
    }

    // PANIER ET EMPRUNTS --------------------------------------------------------------------------------------------------------------------------------
    function getUserCart($userId) {
        $sql = "SELECT emprunt.id
        FROM user
        JOIN emprunt
        ON user.id = emprunt.user_id
        WHERE user.id = '$userId'
        AND emprunt.status = 'CART'";
        
        $output = SQLSelect($sql);

        return ($output == false) ? false : parcoursRs($output);
    }
    
    function createCart($userId, $startDate, $endDate) {
        $sql = "INSERT INTO emprunt (id, start_date, end_date, return_date, status, user_id) 
        VALUES (NULL, '$startDate', '$endDate', NULL, 'CART', '$userId')";

        SQLInsert($sql);

        return getUserCart($userId);
    }
    
    function addToCart($cartId, $itemId, $qte, $startDate, $endDate) {
        $sql = "INSERT INTO emprunt_item (id, product_id, quantity, start_date, emprunt_id, end_date) 
        VALUES (NULL, '$itemId', '$qte', '$startDate', '$cartId', '$endDate')";

        SQLInsert($sql);
    }
    
    /**
     * Affiche les éléments du panier d'un utilisateur
     * @param int $userId
     */
    function listerPanier($userId) {
        // 
        $sql = "SELECT product.name, quantity, emprunt_item.start_date, emprunt_item.end_date, emprunt.id
        FROM emprunt 
        INNER JOIN emprunt_item 
        ON emprunt.id=emprunt_item.emprunt_id
        INNER JOIN product
        ON product.id = emprunt_item.product_id
        WHERE user_id='$userId'
        AND status='CART'";

        return parcoursRs(SQLSelect($sql));
    }

    /**
     * Affiche les emprunts d'un utilisateur
     * @param int $userId
     */
    function listerEmprunts($userId) {
        // 
        $sql = "SELECT product.name, emprunt_item.start_date, emprunt_item.end_date, emprunt.status
        FROM emprunt
        INNER JOIN emprunt_item
        ON emprunt.id = emprunt_item.emprunt_id
        INNER JOIN product
        ON emprunt_item.product_id = product.id
        WHERE user_id='$userId'
        AND status!='CART'";

        return parcoursRs(SQLSelect($sql));
    }

    // ENZO ----------------------------------------------------------------------------------------------------------------------------------------------
    
    function listerUtilisateurs($tri = "tout") {
    $SQL = "SELECT name, contact, role, id, flat_num, score FROM user ORDER BY id DESC";

    return parcoursRS(SQLSelect($SQL));
    }
    // les infos d'un utilisateur
    function userInfo($user_id) {
        $SQL = "SELECT id, name, role, contact, flat_num, score FROM user WHERE id='$user_id'"; 
        return parcoursRs(SQLSelect($SQL));
    }


    /**
     * Met à jour le statut d'un emprunt
     * @param int $id
     * @param string $newStatus
     */
    function updateEmprunt($id, $newStatus) {
        $sql = "UPDATE emprunt
            SET status = '$newStatus'
            WHERE id = '$id'";
        
        return SQLUpdate($sql);
    }


    function isAdmin($idUser)
    {
        $SQL = "SELECT role FROM user WHERE id='$idUser' and role=1"; 
        return SQLGetChamp($SQL);
        // vérifie si l'utilisateur est un administrateur
    }


function ajouterUtilisateur($nom, $contact, $passe ){
    $SQL= "INSERT INTO user(name, contact, password,) VALUES ('$nom','$contact','$passe') ";
    return SQLInsert($SQL);
}

function updateUser($idUser, $nom, $contact, $numAppart, $score) {
    $SQL = "UPDATE user 
        SET name='$nom', contact='$contact', flat_num='$numAppart', score='$score' 
        WHERE id='$idUser'";
    return SQLUpdate($SQL);
}


function verifUserBdd($nom,$passe){
    $SQL="SELECT id from user WHERE name='$nom' AND password='$passe'";
    return SQLGetChamp($SQL); 


}

function rendreAdmin($idUser){
    $SQL="UPDATE user SET role=1 WHERE id='$idUser'";
    SQLUpdate($SQL);
}

function retirerAdmin($idUser){
    $SQL="UPDATE user SET role=0 WHERE id='$idUser'";
    SQLUpdate($SQL);


}

function gestionEmprunts($userId) {
   
    $sql = "SELECT 
                COALESCE(SUM(CASE WHEN status = 'fini' THEN 1 ELSE 0 END), 0) AS emprunts_termines,
                COALESCE(SUM(CASE WHEN status = 'en cours' THEN 1 ELSE 0 END), 0) AS emprunts_en_cours
            FROM emprunt
            WHERE user_id = $userId";
   
    if (SQLSelect($sql) !== false) {
       
        $tableauResultats = parcoursRs(SQLSelect($sql));
        
       
        if (!empty($tableauResultats)) {
            return $tableauResultats[0];
        }
    }
 return [
        'emprunts_termines' => 0, 
        'emprunts_en_cours' => 0
    ];
}

function listerUtilisateursEtEmprunts() {
    
    $utilisateurs = listerUtilisateurs();
    
    if (empty($utilisateurs)) {
        return [];
    }

    foreach ($utilisateurs as &$user) {
        
        $stats = gestionEmprunts($user['id']);
        
        $user['emprunts_termines'] = $stats['emprunts_termines'];
        $user['emprunts_en_cours'] = $stats['emprunts_en_cours'];
    }
    
    return $utilisateurs;
}

function TriEmprunt($filtre = '') {
    $sql = "SELECT e.id, e.start_date, e.end_date, e.return_date, e.status, u.name AS nom_utilisateur 
            FROM emprunt e 
            LEFT JOIN user u ON e.user_id = u.id";
    switch ($filtre) {
        case 'statut':
            $sql .= " ORDER BY e.status ASC";
            break;
        case 'utilisateurs':
            $sql .= " ORDER BY u.name ASC";
            break;
        default: 
            $sql .= " ORDER BY e.id DESC"; 
            break;
    }
    return parcoursRs(SQLSelect($sql));
}

function listerEmpruntsStatut($statut) {
    
    $statutsPossibles = ['CART', 'PENDING', 'VALIDATED', 'RETRIEVED', 'RETURNED'];
    $SQL = "SELECT e.id, e.start_date, e.end_date, e.return_date, e.status, u.name AS nom_utilisateur,p.id AS product_id, p.name AS product_name, ei.quantity
            FROM emprunt e
            LEFT JOIN user u ON e.user_id = u.id
            LEFT JOIN emprunt_item ei ON e.id = ei.emprunt_id
            LEFT JOIN product p ON ei.product_id = p.id WHERE e.status = '$statut'
            ORDER BY e.start_date DESC";
    $listeEmprunts = parcoursRs(SQLSelect($SQL));
    $empruntsGroupes = [];

    foreach ($listeEmprunts as $ligne) {
        $idEmprunt = $ligne['id'];
        if (!isset($empruntsGroupes[$idEmprunt])) {
            $empruntsGroupes[$idEmprunt] = [
                'id' => $ligne['id'],
                'start_date' => $ligne['start_date'],
                'end_date' => $ligne['end_date'],
                'return_date' => $ligne['return_date'],
                'status' => $ligne['status'],
                'nom_utilisateur' => $ligne['nom_utilisateur'],
                'produits' => [] 
            ];}

        if ($ligne['product_id'] !== null) {
            $empruntsGroupes[$idEmprunt]['produits'][] = [
                'id' => $ligne['product_id'],
                'nom' => $ligne['product_name'],
                'quantite' => $ligne['quantity']
            ];}
    }
    return array_values($empruntsGroupes);
}
function changerStatutEmprunt($idEmprunt,$nouveauStatut){
    $SQL = "UPDATE emprunt SET status = '$nouveauStatut' WHERE id = $idEmprunt";   
    return SQLUpdate($SQL);           
}
function listerEmpruntsUtilisateurs() {

    $SQL = "SELECT  u.id AS user_id, u.name AS nom_utilisateur, u.contact, e.id AS emprunt_id, e.start_date, e.end_date, e.return_date, e.status,p.id AS product_id, p.name AS product_name, ei.quantity FROM user u 
            INNER JOIN emprunt e ON u.id = e.user_id 
            LEFT JOIN emprunt_item ei ON e.id = ei.emprunt_id
            LEFT JOIN product p ON ei.product_id = p.id
            ORDER BY u.name ASC, e.start_date DESC";

    $listeEmprunts = parcoursRs(SQLSelect($SQL));
    $empruntsGroupes = [];
    foreach ( $listeEmprunts as $ligne) {
        $idEmprunt = $ligne['emprunt_id'];
        if (!isset($empruntsGroupes[$idEmprunt])) {
            $empruntsGroupes[$idEmprunt] = [
                'user_id' => $ligne['user_id'],
                'nom_utilisateur' => $ligne['nom_utilisateur'],
                'contact' => $ligne['contact'],
                'emprunt_id' => $ligne['emprunt_id'], 
                'start_date' => $ligne['start_date'],
                'end_date' => $ligne['end_date'],
                'return_date' => $ligne['return_date'],
                'status' => $ligne['status'],
                'produits' => []
            ];
        }

        if ($ligne['product_id'] !== null) {
            $empruntsGroupes[$idEmprunt]['produits'][] = [
                'id' => $ligne['product_id'],
                'nom' => $ligne['product_name'],
                'quantite' => $ligne['quantity']
            ];
        }
    }

    return array_values($empruntsGroupes);
}
function nombreEmpruntsEnAttente() {
    
    $SQL = "SELECT COUNT(*) FROM emprunt WHERE status = 'PENDING'";
    
   return SQLGetChamp($SQL);
}

function ajouterProduitEtPhoto($name, $description, $categoryId, $bail, $stock, $photoUrl) {
   
    $categoryId = (int)$categoryId;
    $bail = (float)$bail;
    $stock = (int)$stock;


    $sqlProduit = "INSERT INTO product (name, description, category_id, bail, stock) VALUES ('$name', '$description', $categoryId, $bail, $stock)";
                   
    $idProduit = SQLInsert($sqlProduit);
    if ($idProduit !== false && !empty($photoUrl)) {  
        $sqlPhoto = "INSERT INTO product_photo (url, product_id, `index`) VALUES ('$photoUrl', $idProduit, 0)";            
        SQLInsert($sqlPhoto); }  
    return $idProduit;


   
}


function listerCategories() {
    $SQL = "SELECT id, name FROM category ORDER BY name ASC";
    return parcoursRs(SQLSelect($SQL));
}

function listerCategory() {
    $sql = "SELECT * FROM category";
    return parcoursRs(SQLSelect($sql));
}

function listerFavory($userId) {
    $sql = "SELECT product_id FROM favorite WHERE user_id='$userId'";
    return parcoursRs(SQLSelect($sql));
}



function listerMateriel() {
    $SQL = "SELECT  p.name AS Nom,  p.stock AS Quantite, p.description AS Description, p.bail AS Caution, pp.url AS Photo FROM product p
            LEFT JOIN product_photo pp ON p.id = pp.product_id AND pp.`index` = 0
            ORDER BY p.name ASC";
                     
    return parcoursRs(SQLSelect($SQL));
}

?>