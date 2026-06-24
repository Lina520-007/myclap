<?php
    include_once("libs/maLibSQL.pdo.php");

    function rechercherMateriel($recherche) {
        $recherche = addslashes($recherche);
        
        $sql = "SELECT p.id, p.name, p.description, p.bail, ph.url as photo_url
                FROM product AS p
                LEFT JOIN product_photo AS ph ON p.id = ph.product_id AND ph.index = 1 
                WHERE p.name LIKE '%$recherche%' OR p.description LIKE '%$recherche%'"; 
                
        return parcoursRs(SQLSelect($sql)); 
    }

    function listerArticlesDisponibles() {
        $sql = "SELECT p.id, p.name, p.description, p.bail, ph.url AS photo_url
                FROM product AS p
                LEFT JOIN product_photo AS ph ON p.id = ph.product_id AND ph.index = 1";
        return parcoursRs(SQLSelect($sql));
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
?>