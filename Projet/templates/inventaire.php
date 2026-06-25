<?php
    include_once("libs/maLibUtils.php");
    include_once("libs/modele.php");
    include_once("libs/maLibForms.php");

    
    redirigerParIndexVers("inventaire");

    $articles = listerArticlesDisponibles();

    $userId = 2;
?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

<<<<<<< Updated upstream
<<<<<<< Updated upstream
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
=======
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script> 
>>>>>>> Stashed changes
=======
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script> 
>>>>>>> Stashed changes

<style>

    .produit-details {
        display: none;
    }

    .produit-details.open {
        display: block;
    }

</style>

<section class="inventorySection">

    <h2 class="sectionTitle">Matériels disponibles</h2>

    <div class="inventoryContainer" id="inventoryContainer">

        <?php foreach ($articles as $article): ?>

            <div class="produit-card">

                <!----------------------------------------------- CARD HEAD ----------------------------------------------->
                <div class="produit-header">

                    <img src="<?= $article['photo_url'] ?? 'ressources/myclap.png' ?>" alt="<?= htmlspecialchars($article['name']) ?>"/>

                    <h3><?= htmlspecialchars($article['name']) ?></h3>

                    <button type="button" class="toggleBtn">+</button>

                </div>

                <!----------------------------------------------- CARD BODY ----------------------------------------------->
                <form action="controleur.php" method="POST">

                    <?php mkInput("hidden", "userId",$userId) ?>
                    <?php mkInput("hidden", "id",$article["id"]) ?>

                    <div class="produit-details">

                        <p class="description">Description : <?= htmlspecialchars($article['description'] ?? 'Pas de description disponible') ?></p>

                        <span class="caution">Caution : <?= $article['bail'] ?>€</span>

                        <div>
                            Quantité : 
                            <select class="selection" name="quantite">
                                <?php 
                                    $qte = $article['stock'];
                                    for ($i=1 ; $i<$qte+1 ; $i++) {
                                        echo "<option value=\"$i\">\n";
                                        echo "$i";
                                        echo "</option>\n";
                                    }
                                ?>
                            </select>
                        </div>
                        
                        <!-- Trouver les dates disponibles à partir de la quantité souhaitée -->
                        <?php 
<<<<<<< Updated upstream
<<<<<<< Updated upstream
                            $minDate = "";

                            $itemId = $article["id"];
                            $emprunts = getEmpruntWithItem($itemId);
                            $available = 0;

                            while ($available < $qte) {
                                foreach ($emprunts as $emprunt) {

                                }
                            }
                            
                        ?>
=======
=======
>>>>>>> Stashed changes
                            // On liste les emprunts qui concerne l'article
                            $itemId = $article["id"];
                            $emprunts = getEmpruntsWithItem($itemId, 0);
                            echo "$itemId\n";
                            $availableDatesTab = array();

                            if ($emprunts !== []) {
                                $availableDatesTab = null;
                                echo "pas d'emprunts\n";
                            }
                            else {
                                echo "emprunts détéctés\n";
                                $stockTab = createStockTable($article, $emprunts);

                                // On détermine les date disponibles en fonction de la quantité souhaitée
                                foreach ($stockTab as $date => $stock) {
                                    if ($stock >= $qte) $availableDatesTab[] = $date;
                                }
                            }
                        ?>

                        <script> 
                            const availableDates = <?= json_encode($availableDatesTab) ?>; 
                            flatpickr("#dateDebut", {
                                dateFormat: "Y-m-d",
                                enable: availableDates
                            });
                        </script>
<<<<<<< Updated upstream
>>>>>>> Stashed changes
=======
>>>>>>> Stashed changes

                        <div class="datesPicker">
                            <label>
                                Début : <input type="date" name="dateDebut">
                            </label>
                            <label>
                                Fin : <input type="date" name="dateFin">
                            </label>
                        </div>

                        <input class="ajouterPanierBtn" type="submit" name="action" value="Ajouter au panier"/>

                    </div>

                </form>

            </div>

        <?php endforeach; ?>

    </div>

</section>

<script>

    document.querySelectorAll('.toggleBtn').forEach(btn => {
        btn.addEventListener('click', function () {

            const card = this.closest('.produit-card');
            const details = card.querySelector('.produit-details');
            const isOpen = details.classList.contains('open');

            // fermer tous les autres
            document.querySelectorAll('.produit-details').forEach(d => {
                d.classList.remove('open');
            });

            document.querySelectorAll('.toggleBtn').forEach(b => {
                b.textContent = '+';
            });

            // ouvrir celui cliqué
            if (!isOpen) {
                details.classList.add('open');
                this.textContent = '−';
            }
        });
    });

    $("#")
    
</script>