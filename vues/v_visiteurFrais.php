<?php 
//var_dump($leMois);  
//var_dump($valeur);
?>
<div name="bas">
    <table class="listeLegere">
        <caption>Eléments forfaitisés </caption>
        <tr>
            <?php
            foreach ($lesFraisForfait as $unFraisForfait) {
                $libelle = $unFraisForfait['libelle'];
                ?>	
                <th> <?php echo $libelle ?></th>
            <?php }
            ?>
        </tr>

        <tr>
            <?php
            foreach ($lesFraisForfait as $unFraisForfait) {
                $quantite = $unFraisForfait['quantite'];
                ?>
                <td class="qteForfait"><?php echo $quantite ?> </td>
                <?php
            }
            ?>
        </tr>
    </table>
    
    <table class="listeLegere">
        <h3>Descriptif des éléments hors forfait</h3>
        <tr>
            <th class="date">Date</th>
            <th class="libelle">Libellé</th>  
            <th class="montant">Montant</th> 
            <th class="libelle">Situation</th>
            <th class="action">&nbsp;</th>    
            <th class="libelle"> </th>
            <th class="action"> </th> 
        </tr>

        <?php
        foreach ($lesFraisHorsForfait as $unFraisHorsForfait) {
            $libelle = $unFraisHorsForfait['libelle'];
            $date = $unFraisHorsForfait['date'];
            $montant = $unFraisHorsForfait['montant'];
            $idFrais = $unFraisHorsForfait['id'];
            $situation = $pdo->voirFraisRefuse($idFrais);
            ?>		
            <tr>
                <td> <?php echo $date ?></td>
                <td><?php echo $libelle ?></td>
                <td><?php echo $montant ?></td>
                <td><?php echo $situation[0][0] ?></td>
                <td><a href="index.php?uc=fraisAValider&action=refuserFrais&idFrais=<?php echo $idFrais ?>"onclick="return confirm('Voulez-vous vraiment refuser ce frais?');">Refuser ce frais</a></td>
                <td><a href="index.php?uc=fraisAValider&action=reporterFrais&idFrais=<?php echo $idFrais ?>"onclick="return confirm('Voulez-vous vraiment reporter ce frais?');">Reporter ce frais</a></td>
                <td><a href="index.php?uc=fraisAValider&action=validerFraisHorsForfait&idFrais=<?php echo $idFrais ?>"onclick="return confirm('Voulez-vous vraiment valider ce frais?');">Valider ce frais</a></td>
                
                
            </tr>
            <?php
        }

        header("location:vues/v_visiteurFrais.php");

        ?>
    </table>
</div>

