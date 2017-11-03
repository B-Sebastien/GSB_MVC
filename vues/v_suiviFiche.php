<div id="contenu">
    <h1> Suivi de paiement </h1>
    <form action="index.php?uc=fraisAValider&action=validerChoixFiche" method="post">
        <div class="corpsForm">
            <h3>Choisir une fiche : </h3>
            <p>
                <span style='color:#123e6a'>Fiche <em>(Etat validé et mise en paiement)</em>:</span><br/>
                <select id="lstVisiteur" style="width:480" name="lstVisiteur"> 
                    <?php
                    foreach ($listeFichesFrais as $data) {
                        $id = $data['id'];
                        $nom = $data['nom'];
                        $prenom = $data['prenom'];
                        $mois = $data['mois'];
                        $montant = $data['montant'];

                        //l'id de l'option est : mois + id
                        //ce format sera ensuite découpé par la suite.
                        //afin d'obtenir le mois et l'id séparément
                        ?> 
                        <option value="<?php echo $mois . $id; ?>"> 
                            <?php echo "Fiche du visiteur: " . $nom . " " . $prenom . " | Mois: " . $mois . " | Montant " . $montant . "€"; ?> 
                        </option>
                        <?php
                    }
                    ?>
                </select>
            </p>
            <p>
                <input id="ok" type="submit" value="Valider" size="20" />
            </p> 

        </div>
    </form>