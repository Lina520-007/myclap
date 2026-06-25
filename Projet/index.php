<?php

    if (basename($_SERVER["PHP_SELF"]) != "index.php")
    {
        header("Location:../index.php");
        die("");
    }

	include_once("libs/maLibUtils.php");
    include_once("libs/modele.php"); // listes

	$view = valider("view"); 
?>



<!doctype html>
<html lang="fr">
  
<!-- **** H E A D **** -->
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>myCLAP - Emprunts</title>
    
    <link href="jqueryUI/jquery-ui.min.css" rel="stylesheet">
    <link href="css/styleInv.css" rel="stylesheet">
</head>
<!-- **** F I N **** H E A D **** -->


<!-- **** B O D Y **** -->
<body>

    <!-- HEADER : LOGO ET BARRE DE RECHERCHE -->
    <header class="mainHeader">
        <div class="logo">
            <a href="index.php?view=inventaire">
                <img src="ressources/myclap.png" alt="Logo myCLAP">
            </a>
        </div>

        <div class="searchZone" id="searchContainer">
            <input type="text" placeholder="Rechercher du matériel..." class="searchInput">
            <button class="searchBtn" id="searchToggle">
                <span class="ui-icon ui-icon-search"></span>
            </button>
        </div>

        <div class="headerAct">
            <a href="index.php?view=panier" class="actionBtn">
                <span class="ui-icon ui-icon-cart"></span>
                <span class="nbEmprunts">0</span>
            </a>
            
            <a href="index.php?view=login" class="actionBtn">
                <span class="ui-icon ui-icon-person"></span>
            </a>
            
            <a href="controleur.php?action=Logout" class="actionBtn logoutBtn">
                <span class="ui-icon ui-icon-power"></span>
            </a>
        </div>
    </header>

    <!-- CONTENU DE LA PAGE -->
    <main class="mainZone">

        <!-- SIDEBAR -->
        <aside class="sideBar" id="sideBar">
            <nav class="sideBarMenu">
                <a href="index.php?view=inventaire" class="menuItem <?php if ($view == 'inventaire' || !$view) echo 'active'; ?>">
                    <span class="ui-icon ui-icon-home"></span> Inventaire
                </a>
                <a href="index.php?view=panier" class="menuItem <?php if ($view == 'panier') echo 'active'; ?>">
                    <span class="ui-icon ui-icon-cart"></span> Mon Panier
                </a>
                <a href="index.php?view=mes_emprunts" class="menuItem <?php if ($view == 'mes_emprunts') echo 'active'; ?>">
                    <span class="ui-icon ui-icon-script"></span> Mes Emprunts
                </a>
<?php 
if (isset($_SESSION["isAdmin"]) && $_SESSION["isAdmin"] == true) :
    $nbAttente = nombreEmpruntsEnAttente();   ?>
    <div class="adminSection">
                    <span class="menuSeparator"> Administration </span>
                    
                    <a href="index.php?view=admin_gestion" class="menuItem <?php if ($view == "admin_gestion") echo "active"; ?>">
                        <span class="ui-icon ui-icon-wrench"></span> Gestion
                    </a>
                    
                    <a href="index.php?view=admin_emprunts" class="menuItem <?php if ($view == "admin_emprunts") echo "active"; ?>">
                        <span class="ui-icon ui-icon-document"></span> Emprunts global
                        
                        <?php if ($nbAttente > 0): ?>
                            <span id="nbAttente"><?= $nbAttente ?></span>
                        <?php endif; ?>
                    </a>
                    
                    <a href="index.php?view=analytics" class="menuItem <?php if ($view == "analytics") echo "active"; ?>">
                        <span class="ui-icon ui-icon-signal"></span> Analytics
                    </a>
                </div>
                <?php endif; ?>
                
            </nav>
        </aside>

        <!-- CONTENU PRINCIPAL -->
        <section class="mainContent">
			<?php
				// S'il est $view est vide, on charge la vue accueil par défaut
				if (!$view) $view = "inventaire"; 

				switch($view)
				{		

					case "inventaire" : 
						include("templates/inventaire.php");
					break;

					case "login" : 
						include("templates/login.php");
					break; 

					case "mes_emprunts" : 
						include("templates/mes_emprunts.php");
					break;

					case "panier" : 
						include("templates/panier.php");
					break;
					
					case "admin_gestion" : 
						include("templates/admin_gestion.php");
					break;

					case "admin_emprunts" : 
						include("templates/admin_emprunts.php");
					break;
					default :
						if (file_exists("templates/$view.php"))
							include("templates/$view.php");

				}
			?>
        </section>

    </main>
    
</body>
<!-- **** F I N **** B O D Y **** -->


<?php 
	include("templates/footer.php");
?>
