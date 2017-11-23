<?php
    $infosVisiteur = $pdo->getNomPrenomVisiteur($visiteur);
    ?> <h2><?php echo "Fiche de : " . $infosVisiteur['nom'] . " " . $infosVisiteur["prenom"]; ?></h2>

    <br/>
    
    <table class="listeLegere">
        <caption>Frais forfait</caption>
        <tr>
            <th class="libelle">Frais forfait</th>
            <th class='montant'>Quantite</th>
            <th class='montant'>Montant unitaire</th>
            <th class='montant'>Total</th>
        </tr>
        
        <?php 
        /**
         * Génération des frais forfait d'un visiteur
         */
        $total = 0;
            foreach ($lesFraisForfait as $unFraisForfait) {
                $quantite = $unFraisForfait['quantite'];
                $libelle = $unFraisForfait['libelle'];
                $idFrais = $unFraisForfait['idfrais'];
                $montant = $unFraisForfait['montant'];
                $totalUnit = ($quantite * $montant);
                $total = $total + ($quantite * $montant);
                ?>
        <tr>
            <td><?php echo $libelle; ?></td>
            <td><?php echo $quantite; ?></td>
            <td><?php echo $montant; ?></td>
            <td><?php echo $totalUnit; ?></td>
        </tr> <?php
            }
        ?>
    </table>
    <br/>
    <br/>
    
    <table class="listeLegere">
        <caption>Frais hors forfait</caption>
        <tr>
            <th class="date">Date</th>
            <th class="libelle">Libellé</th>
            <th class='montant'>Montant</th>
            <th class="montant">Total</th>
        </tr>
        <?php
        /**
         * Génération des frais hors forfait d'un visiteur
         */
        foreach ($lesFraisHorsForfait as $unFraisHorsForfait) {
            $idFraisHorsF = $unFraisHorsForfait['id'];
            $date = $unFraisHorsForfait['date'];    
            $libelle = $unFraisHorsForfait['libelle'];
            $montant = $unFraisHorsForfait['montant'];
            $total = $total + $montant;
            ?>
            <tr>
                <td><?php echo $date; ?></td>
                <td><?php echo $libelle; ?></td>
                <td><?php echo $montant; ?></td>
                <td><?php echo $total; ?></td>
                <?php
            }
            ?>
        </tr>
    </table>
    <br/>
    <br/>
    
    <table class="listeLegere">
        <caption>Etat de la fiche de frais</caption>
        <tr>
            <th>Etat</th>
            <th>Montant validé</th>
            <th>Justificatifs</th>
            <th>PDF</th>
        </tr>
        <tr>
            <td>
                <?php
                //Initialisation de l'état de la fiche à afficher
                $etatFiche = $lesInfosFicheFrais['libEtat'];
                echo $etatFiche;
                ?>
                <input type='hidden' name='etat_defaut' value='<?php echo $etatFiche; ?>' />
            </td>
            <td>
                <?php echo $montantValide ?>
            </td>
            <td>
                <?php echo $nbJustificatifs ?>
            </td>
            <td>
                <a href="index.php?uc=fraisAValider&action=generationPDF&id=<?php echo $visiteur ?>&date=<?php echo $dateValide; ?>" target="_blank"><img id=supp src="images/pdf.png" alt="pdf" /></a>
            </td>
        </tr>
    </table>
