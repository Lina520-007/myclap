<?php
    include_once("libs/maLibSQL.pdo.php");

    /*
    function rechercherMateriel($recherche) {
        $recherche = addslashes($recherche);
        
        $SQL = "SELECT p.id, p.nom, p.description, p.caution, ph.url AS photo_url
                FROM produit AS p
                LEFT JOIN photos_produit AS ph ON p.id = ph.produitId AND ph.ordre = 0 
                WHERE p.nom LIKE '%$recherche%' OR p.description LIKE '%$recherche%'"; 
                
        return parcoursRs(SQLSelect($SQL)); 
    }
        */

    function listerPanier($idUser) {
        // 
        $sql = "SELECT product.name, quantity, emprunt_item.start_date, emprunt_item.end_date, emprunt.id
        FROM emprunt 
        INNER JOIN emprunt_item 
        ON emprunt.id=emprunt_item.emprunt_id
        INNER JOIN product
        ON product.id = emprunt_item.product_id
        WHERE user_id='$idUser'
        AND status='CART'";

        return parcoursRs(SQLSelect($sql));
    }

    function listerEmprunts($idUser) {
        // 
        $sql = "SELECT product.name, emprunt_item.start_date, emprunt_item.end_date, emprunt.status
        FROM emprunt
        INNER JOIN emprunt_item
        ON emprunt.id = emprunt_item.emprunt_id
        INNER JOIN product
        ON emprunt_item.product_id = product.id
        WHERE user_id='$idUser'
        AND status!='CART'";

        return parcoursRs(SQLSelect($sql));
    }
    
    function updateEmprunt($id, $newStatus) {
        $sql = "UPDATE emprunt
            SET status = '$newStatus'
            WHERE id = '$id'";
        
        return SQLUpdate($sql);
    }
?>