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
        </tr>
        
        <?php 
            foreach ($lesFraisForfait as $unFraisForfait) {
                $quantite = $unFraisForfait['quantite'];
                $libelle = $unFraisForfait['libelle'];
                $idF = $unFraisForfait['idFrais'];
                ?>
        <tr>
            <td><?php echo $libelle; ?></td>
            <td><?php echo $quantite; ?></td>
            <td><?php echo $montant; ?></td>
        </tr> <?php
            }
        ?>
    </table>
    
<!--    <table class="listeLegere">
        <caption>Hors Forfait</caption>
        <tr>
            <th class="libelle">Date</th>
            <th class='montant'>Libellé</th>
            <th class='montant'>Montant</th>
            <th class='montant'>Situation</th>
            <th class='montant'>Date opération</th>
        </tr>
    </table>
    
    <table class="listeLegere">
        <caption>Hors Classification</caption>
        <tr>
            <th class="libelle">Nb justificatifs</th>
            <th class='montant'>Montant</th>
            <th class='montant'>Situation</th>
            <th class='montant'>Date opération</th>
        </tr>
    </table>-->