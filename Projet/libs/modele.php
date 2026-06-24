<?php
    include_once("libs/maLibSQL.pdo.php");

    function rechercherMateriel($recherche) {
        $recherche = addslashes($recherche);
        
        $SQL = "SELECT p.id, p.nom, p.description, p.caution, ph.url AS photo_url
                FROM produit AS p
                LEFT JOIN photos_produit AS ph ON p.id = ph.produitId AND ph.ordre = 0 
                WHERE p.nom LIKE '%$recherche%' OR p.description LIKE '%$recherche%'"; 
                
        return parcoursRs(SQLSelect($SQL)); 
    }

    function listerPanier($idUser) {
        // nom, itemQte, dateDebutEmprunt, dateFinEmprunt, produit.id
        $sql = "SELECT *
        FROM panier 
        INNER JOIN panier_item 
        ON panier.id=panier_item.panierId
        INNER JOIN produit
        ON produit.id = panier_item.productId
        WHERE customerId='$idUser'";

        return parcoursRs(SQLSelect($sql));
    }

    function listerEmprunts($idUser) {
        // nom, start_date, end_date, emprunt.status
        $sql = "SELECT *
        FROM emprunt
        INNER JOIN emprunt_item
        ON emprunt.id = emprunt_item.emprunt_id
        INNER JOIN produit
        ON emprunt_item.product_id = produit.id
        WHERE user_id='$idUser'";

        return parcoursRs(SQLSelect($sql));
    }
?>