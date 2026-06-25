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

    function listerArticlesDisponibles() {
        $sql = "SELECT p.id, p.name, p.description, p.bail, p.stock, ph.url AS photo_url
                FROM product AS p
                LEFT JOIN product_photo AS ph ON p.id = ph.product_id AND ph.index = 1";
        return parcoursRs(SQLSelect($sql));
    }

    // PANIER ET EMPRUNTS --------------------------------------------------------------------------------------------------------------------------------
    function getUserCart($userId) {
        $sql = "SELECT emprunt.id
        FROM user
        JOIN emprunt
        ON user.id = emprunt.user_id
        WHERE user.id = '$userId'";
        
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

function filtrerEmprunts($filtre) {

}




?>