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

        $sql = "SELECT nom, itemQte, dateDebutEmprunt, dateFinEmprunt
        FROM panier 
        INNER JOIN panier_item 
        ON panier.id=panier_item.panierId
        INNER JOIN produit
        ON produit.id = panier_item.productId
        WHERE customerId='$idUser'";

        return parcoursRs(SQLSelect($sql));
    }

    function listerEmprunts($idUser) {
        $sql = "SELECT nom, start_date, end_date, emprunt.status 
        FROM emprunt
        INNER JOIN emprunt_item
        ON emprunt.id = emprunt_item.emprunt_id
        INNER JOIN produit
        ON emprunt_item.product_id = produit.id
        WHERE user_id='$idUser'";

        return parcoursRs(SQLSelect($sql));
    }

    function listerUtilisateurs($tri = "tout") {
    $SQL = "SELECT name, contact, role, id, flat_num, score FROM user ORDER BY id DESC";

    
    return parcoursRS(SQLSelect($SQL));
}


function isAdmin($idUser)
{
	$SQL = "SELECT role FROM user WHERE id='$idUser' and role=1"; 
	return SQLGetChamp($SQL);
	// vérifie si l'utilisateur est un administrateur
}


function ajouterUtilisateur($name, $contact, $password ){
    $SQL= "INSERT INTO user(name, contact, password) VALUES ('$name','$contact','$password') ";
    return SQLInsert($SQL);
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


/**
 * Récupère la liste des emprunts triée selon un critère
 * @param string $critereTri Le filtre sélectionné (statut, utilisateurs, etc.)
 * @return array Tableau associatif contenant les emprunts
 */
function getEmpruntsTries($critereTri = '') {
    // Requête de base avec une jointure pour récupérer le nom de l'utilisateur
    $sql = "SELECT e.id, e.start_date, e.end_date, e.return_date, e.status, u.name AS nom_utilisateur 
            FROM emprunt e 
            LEFT JOIN user u ON e.user_id = u.id";

    // Ajout de la clause de tri en fonction du filtre reçu
    switch ($critereTri) {
        case 'statut':
            $sql .= " ORDER BY e.status ASC";
            break;
        case 'utilisateurs':
            // On trie par le nom de l'utilisateur joint
            $sql .= " ORDER BY u.name ASC";
            break;
        case 'date_emprunt':
            // Généralement, on veut les plus récents en premier (DESC)
            $sql .= " ORDER BY e.start_date DESC";
            break;
        case 'date_retour':
            $sql .= " ORDER BY e.return_date DESC";
            break;
        default:
            // Tri par défaut si aucun filtre n'est sélectionné ou filtre inconnu
            $sql .= " ORDER BY e.id DESC"; 
            break;
    }

    // Exécution de la requête via ta librairie
    $resultat = SQLSelect($sql);
    
    // Retourne le résultat sous forme de tableau associatif
    return parcoursRs($resultat);
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

?>