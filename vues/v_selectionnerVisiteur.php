<?php //var_dump($lesVisiteurs)?>
    <h2>Fiche de frais a valider</h2>
    <h3>Visiteur à sélectionner : </h3>
    <form action="index.php?uc=fraisAValider&action=voirFraisAValider" method="post">
        <div class="corpsForm">
            <p>
                <label for="lstVisiteurs" accesskey="n">Visiteur : </label>
                <select id="lstVisiteurs" name="lstVisiteurs">
                <?php
                    foreach ($lesVisiteurs as $unVisiteur) {
                        $nom = $unVisiteur['nom'];
                        $prenom =  $unVisiteur['prenom'];
                        $idVisiteur = $unVisiteur['idVisiteur'];
                        
                        /**
                         * Si la variable lstVisiteur est null et que l'id du visiteur est strictement égale à lstVisiteur
                         * Affiche la liste selected value pour sélectionner l'état initial
                         * Affiche une liste par défaut
                         */
                        if (isset($lstVisiteur) && $idVisiteur == $lstVisiteur) { ?>
                            <option selected value="<?php echo $idVisiteur; ?>"> <?php echo $nom." ".$prenom; ?></option> <?php 
                        } else { ?>
                           <option value="<?php echo $idVisiteur; ?>"> <?php echo $nom." ".$prenom; ?></option>
                        <?php 
                        }
                    }
                    ?>    
                </select>
            </p>
        <div class="piedForm">
            <p>
                <input type="hidden" value="<?php echo $leMois?>" name="hdMois"/>
                
                <input id="ok" type="submit" value="Valider" size="20" />
                <input id="annuler" type="reset" value="Effacer" size="20" />
            </p>  
        </div> 
    </form>