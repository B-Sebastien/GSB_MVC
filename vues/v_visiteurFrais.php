<?php // var_dump($leMois) ?>
<div name="bas">
        <table class="listeLegere">
            <h3>Descriptif des éléments hors forfait</h3>
            <tr>
                <th class="date">Date</th>
                <th class="libelle">Libellé</th>  
                <th class="montant">Montant</th>
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
                
                ?>		
                <tr>
                    <td> <?php echo $date ?></td>
                    <td><?php echo $libelle ?></td>
                    <td><?php echo $montant ?></td>
                    <td><a href="index.php?uc=fraisAValider&action=supprimerFrais&idFrais=<?php echo $idFrais ?>"onclick="return confirm('Voulez-vous vraiment supprimer ce frais?');">Supprimer ce frais</a></td>
                </tr>
                <?php
            }
            ?>
</table>
</div>

