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
?>