<?php
    include_once("libs/maLibUtils.php");
    include_once("libs/modele.php");
    include_once("libs/maLibForms.php");

    redirigerParIndexVers("inventaire");

    $articles = listerArticlesDisponibles();
    $userId = 2;
?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

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

            <?php
                $itemId = $article["id"];

                $empruntItems = getEmpruntsWithItem($itemId, $userId) ?? [];
                $stockTab = createStockTable($article, $empruntItems);

                $availableDatesTab = [];
                foreach ($stockTab as $date => $stock) {
                    if ($stock >= 1) {
                        $availableDatesTab[] = $date;
                    }
                }
            ?>

            <div class="produit-card">

                <div class="produit-header">

                    <img src="<?= $article['photo_url'] ?? 'ressources/myclap.png' ?>" alt="<?= htmlspecialchars($article['name']) ?>"/>

                    <h3><?= htmlspecialchars($article['name']) ?></h3>

                    <button type="button" class="toggleBtn">+</button>

                </div>

                <form action="controleur.php" method="POST">

                    <?php mkInput("hidden", "userId",$userId) ?>
                    <?php mkInput("hidden", "id",$article["id"]) ?>

                    <div class="produit-details">

                        <p class="description">
                            Description : <?= htmlspecialchars($article['description'] ?? 'Pas de description disponible') ?>
                        </p>

                        <span class="caution">
                            Caution : <?= $article['bail'] ?>€
                        </span>

                        <div>
                            Quantité :
                            <select class="selection"
                                    id="quantite<?= $itemId ?>"
                                    name="quantite">

                                <?php
                                    $qte = $article['stock'];
                                    for ($i = 1; $i <= $qte; $i++) {
                                        echo "<option value=\"$i\">$i</option>";
                                    }
                                ?>

                            </select>
                        </div>

                        <div class="datesPicker">

                            <label>
                                Début :<br>
                                <input type="text"
                                       id="dateDebut<?= $itemId ?>"
                                       name="dateDebut">
                            </label>

                            <label>
                                Fin :<br>
                                <input type="text"
                                       id="dateFin<?= $itemId ?>"
                                       name="dateFin">
                            </label>

                        </div>

                        <script>
                            (function () {

                                const stockTab = <?= json_encode($stockTab) ?>;

                                const quantiteSelect =
                                    document.getElementById(
                                        "quantite<?= $itemId ?>"
                                    );

                                const debutInput =
                                    "#dateDebut<?= $itemId ?>";

                                const finInput =
                                    "#dateFin<?= $itemId ?>";

                                function calculerDates(qte)
                                {
                                    const dates = [];

                                    for (const [date, stock] of Object.entries(stockTab))
                                    {
                                        if (stock >= qte)
                                        {
                                            dates.push(date);
                                        }
                                    }

                                    return dates;
                                }

                                let availableDates =
                                    calculerDates(
                                        parseInt(quantiteSelect.value)
                                    );

                                const finPicker = flatpickr(finInput, {
                                    dateFormat: "Y-m-d"
                                });

                                const debutPicker = flatpickr(debutInput, {
                                    dateFormat: "Y-m-d",
                                    enable: availableDates,

                                    onChange: function(selectedDates, dateStr)
                                    {
                                        const datesFin = [];

                                        let d = new Date(dateStr);

                                        while (true)
                                        {
                                            const y = d.getFullYear();
                                            const m = String(d.getMonth() + 1).padStart(2, '0');
                                            const day = String(d.getDate()).padStart(2, '0');

                                            const date = `${y}-${m}-${day}`;

                                            if (!availableDates.includes(date))
                                            {
                                                break;
                                            }

                                            datesFin.push(date);
                                            d.setDate(d.getDate() + 1);
                                        }

                                        finPicker.clear();
                                        finPicker.set('enable', datesFin);
                                    }
                                });

                                quantiteSelect.addEventListener('change', function ()
                                {
                                    const qte = parseInt(this.value);

                                    availableDates = calculerDates(qte);

                                    debutPicker.clear();
                                    finPicker.clear();

                                    debutPicker.set('enable', availableDates);
                                    finPicker.set('enable', []);
                                });

                            })();
                        </script>

                        <input class="ajouterPanierBtn"
                               type="submit"
                               name="action"
                               value="Ajouter au panier"/>

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

            document.querySelectorAll('.produit-details')
                .forEach(d => d.classList.remove('open'));

            document.querySelectorAll('.toggleBtn')
                .forEach(b => b.textContent = '+');

            if (!isOpen) {
                details.classList.add('open');
                this.textContent = '−';
            }
        });
    });

</script>